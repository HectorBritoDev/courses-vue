<?php

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
 */

$factory->define(User::class, function (Faker $faker) {
    $name = $faker->name;
    $last_name = $faker->lastName;
    return [
        'name' => $name,
        'role_id' => \App\Role::all()->random()->id,
        'last_name' => $last_name,
        'slug' => str_slug($name . " " . $last_name, '-'),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),

        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'picture' => \Faker\Provider\Image::image(storage_path() . '\app\public\users', 200, 200, 'people', false),

        'remember_token' => Str::random(10),
    ];
});
