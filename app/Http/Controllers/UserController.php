<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Jobs\AttachUserToAllActivities;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.index', ['users' => User::where('role', config('user.roles.user'))->withCount('activities')->paginate(10)]);
    }

    public function login(LoginRequest $request){
        if(!Auth::attempt($request->validated())) return response()->json(['status' => false, 'message' => 'Invalid credentials'], 401);

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'status' => true,
            'message' => 'User Logged In Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }

    public function registerUser(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        $this->dispatch(new AttachUserToAllActivities($user));

        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }

    public function getUserActivities(Request $request)
    {
        $activities = $request->user()->activities();
        try{
            $rangeArray = explode('-', $request->range);
            $from_date = null;
            $to_date = null;
            if(count($rangeArray) > 0){
                $from_date = Carbon::createFromFormat('d/n/y', trim($rangeArray[0]))->format('Y-m-d');
                if(isset($rangeArray[1])) $to_date = Carbon::createFromFormat('d/n/y', trim($rangeArray[1]))->format('Y-m-d');
            }
            if($from_date){
                $activities = $activities->where('activity_date', '>=', $from_date);
            }

            if ($to_date) {
                $activities = $activities->where('activity_date', '<=', $to_date);
            }

            return response()->json([
                'status' => true,
                'message' => 'Activities retrieved successfully!',
                'data' => $activities->get()
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving activities!',
                'error' => $e->getMessage()
            ], 400);
        }

    }
}
