<?php

use Illuminate\Database\Seeder;

class CommentLikesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Like::where('likable_type', 'App\Models\Comment')->delete();

        $comments = \App\Models\Comment::get(['id'])->pluck('id');

        $users = \App\Models\User::get(['id'])->pluck('id');

        $all = $users->count();
        $max = (int) ($all / 2);
        $max = $max > 70 ? 70 : $max;

        $commentLikes = collect();

        foreach ($comments as $comment) {
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
                $commentLikes->push(['user_id' => $user, 'likable_id' => $comment, 'likable_type' => 'App\Models\Comment']);
            }
        }

        foreach ($commentLikes->chunk(1000) as $chunk) {
            \App\Models\Like::insert($chunk->toArray());
        }
    }
}
