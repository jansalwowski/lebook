<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\User::class, 100)->create();
        \App\Models\User::create([
            'name' => 'root',
            'username' => 'root',
            'email' => 'root@root.root',
            'password' => bcrypt('root123'),
            'verified' => true
        ]);

    }
}
