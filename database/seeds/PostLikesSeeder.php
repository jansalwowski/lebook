<?php

use Illuminate\Database\Seeder;

class PostLikesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Like::where('likable_type', 'App\Models\Post')->delete();

        $posts = \App\Models\Post::get(['id'])->pluck('id');

        $users = \App\Models\User::get(['id'])->pluck('id');

        $all = $users->count();
        $max = (int) ($all / 2);
        $max = $max > 70 ? 70 : $max;

        $postLikes = collect();

        foreach ($posts as $post) {
            $amount = rand(0, $max);
            if($amount == 0){
                continue;
            }


            $likedBy = $users->random($amount);

            if( !$likedBy ){
                continue;
            }

            if( is_numeric($likedBy) ){
                $likedBy = [$likedBy];
            }

            foreach ($likedBy as $user) {
                $postLikes->push(['user_id' => $user, 'likable_id' => $post, 'likable_type' => 'App\Models\Post']);
            }
        }

        foreach ($postLikes->chunk(1000) as $chunk) {
            \App\Models\Like::insert($chunk->toArray());
        }
    }
}
