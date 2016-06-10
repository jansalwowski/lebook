<?php

use Illuminate\Database\Seeder;

class FollowersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Follower::truncate();

        $users = \App\Models\User::get(['id'])->pluck('id');

        $all = $users->count();
        $min = floor($all / 20);
        $min = $min < 2 ? 2 : $min;
        $max = floor($all / 4);
        $max = $max > 150 ? 150 : $max;

        $followers = collect();

        foreach ($users as $user) {
            $tmp = $users->reject(
                function ($value, $key) use ($user) {
                    return $value == $user;
                }
            )->random(rand($min, $max));

            foreach ($tmp as $follower) {
                $followers->push(['user_id' => $user, 'follower_id' => $follower]);
            }

        }

        \App\Models\Follower::insert($followers->toArray());
    }
}
