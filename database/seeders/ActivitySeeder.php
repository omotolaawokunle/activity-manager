<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Activity;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dates = ['2022/11/12', '2022/10/10', '2022/12/23', '2022/12/20', '2022/11/13', '2022/10/21'];
        $title = ['Monthly Activity', 'Gym', 'Football Match', 'Concert'];
        $description = ['A monthly activity related to meetings.', 'Weekly gym workout for all users.', 'Organized football match between team of users', 'Musical concert for users.'];
        $images = [null,
            'https://images.pexels.com/photos/703016/pexels-photo-703016.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'https://images.pexels.com/photos/46798/the-ball-stadion-football-the-pitch-46798.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2',
            'https://images.pexels.com/photos/167636/pexels-photo-167636.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2'
        ];

        for($i=0; $i<10; $i++){
            $event = rand(0, count($title) - 1);
            Activity::create([
                'title' => $title[$event],
                'description' => $description[$event],
                'activity_date' => $dates[rand(0, count($dates) - 1)],
                'image' => $images[$event]
            ]);
        }
    }
}
