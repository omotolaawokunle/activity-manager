<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\ActivityRequest;
use App\Jobs\AttachActivityToAllUsers;
use App\Http\Resources\ActivityCalendarResource;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Activity::query();
        if($request->month){
            $year = $request->year ?? date('Y');
            $activities = $query->whereMonth('activity_date', $request->month)->whereYear('activity_date', $year)->get();
            return response()->json(ActivityCalendarResource::collection($activities));
        }

        $user = $request->user_id ? User::where('id', $request->user_id)->first() : null;
        if($user){
            $activities = $user->activities()->paginate(10)->appends($request->all());
        }else{
            $activities = $query->paginate(10)->appends($request->all());
        }
        return view('activities.index', compact('activities', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('activities.create', [
            'users' => User::where('role', config('user.roles.user'))->get(),
            'user' => request()->get('user_id') ? User::where('id', request()->get('user_id'))->first() : null,
            'date' => request()->get('date') ? date('Y-m-d', strtotime(request()->get('date'))) : '',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ActivityRequest  $request
     *
     */
    public function store(ActivityRequest $request)
    {
        $data = $request->validated();
        if($this->checkDateActivities($data['activity_date'])) {
            return back()->with('error', 'You cannot add more than 4 activities for each day!');
        }

        if ($request->has('activity_image')) {
            $file = $request->file('activity_image');
            $name = Str::slug($data['title']) . time() . '.' . $file->getClientOriginalExtension();
            $data['image'] = $file->storePubliclyAs("images", $name, 'public');
        }
        $activity = Activity::create($data);
        if(isset($data['user_id']) && !is_null($data['user_id'])){
            $user = User::where('id', $data['user_id'])->first();
            if(!$user) return back()->with('error', 'User not found!');
            $user->activities()->attach($activity);
        }else{
            $this->dispatch(new AttachActivityToAllUsers($activity));
        }

        return back()->with('success', 'Activity created successfully!');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function edit(Activity $activity)
    {
        $user = request()->get('user_id') ? User::where('id', request()->get('user_id'))->first() : null;
        if (is_null($user) && !is_null($activity->global_activity_id)) {
            $user = $activity->users()->first();
        }
        return view('activities.edit', [
            'users' => User::where('role', config('user.roles.user'))->get(),
            'activity' => $activity,
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ActivityRequest  $request
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function update(ActivityRequest $request, Activity $activity)
    {
        $data = $request->validated();
        if ($this->checkDateActivities($data['activity_date'], $activity)) {
            return back()->with('error', 'You cannot add more than 4 activities for each day!');
        }

        if ($request->has('activity_image')) {
            $file = $request->file('activity_image');
            $name = Str::slug($data['title']) . time() . '.'. $file->getClientOriginalExtension();
            $data['image'] = $file->storePubliclyAs("images", $name, 'public');
        }
        if (isset($data['user_id']) && !is_null($data['user_id'])) {
            $user = User::where('id', $data['user_id'])->first();
            if (!$user) return back()->with('error', 'User not found!');
            if((!$activity->global_activity_id && $user->activities()->where('activities.id', $activity->id)->doesntExist()) || ($activity->global_activity_id && $user->activities()->where('activities.id', $activity->id)->doesntExist()) || (!$activity->global_activity_id && $user->activities()->where('activities.id', $activity->id)->exists())){
                if($user->activities()->where('activities.id', $activity->id)->exists()) $user->activities()->detach($activity);
                $this->createNewActivityForUser($user, $activity, $data);
                return back()->with('success', 'Activity updated for user successfully!');
            }
        }
        if(isset($data['image']) && $activity->image && file_exists(public_path("storage/$activity->image"))){
            unlink(public_path("storage/$activity->image"));
        }
        $activity->update($data);
        return back()->with('success', 'Activity updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Activity $activity)
    {
        if($activity->delete()){
            return back()->with('success', 'Activity deleted successfully!');
        }
    }

    private function createNewActivityForUser(User $user, Activity $activity, $data){
        $newActivity = $activity->replicate();
        $newActivity->global_activity_id = $activity->id;
        $newActivity->title = $data['title'];
        $newActivity->description = $data['description'];
        $newActivity->activity_date = $data['activity_date'];
        $newActivity->save();
        $user->activities()->attach($newActivity);
    }

    private function checkDateActivities($date, $except = null)
    {
        $query = Activity::whereDate('activity_date', $date);
        if($except) $query->where('id', '!=', $except->id);
        if($query->count() >= 4) return true;
        return false;
    }
}
