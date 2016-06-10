<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'username' => $faker->userName,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'verified' => true,
        'email_token' => null,
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Post::class, function (Faker\Generator $faker) {
    return [
        'user_id' => \App\Models\User::orderByRaw('RAND()')->first()->id,
        'body' => $faker->paragraph(rand(1,6)),
    ];
});


$factory->define(App\Models\Comment::class, function (Faker\Generator $faker) {
    return [
        'user_id' => \App\Models\User::orderByRaw('RAND()')->first()->id,
        'post_id' => \App\Models\Post::orderByRaw('RAND()')->first()->id,
        'body' => $faker->paragraph(rand(1,2)),
    ];
});
