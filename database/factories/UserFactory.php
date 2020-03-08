<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

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
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$cmnNTbd6Xsqo9iZmcNdVMexOTBJnCBtEIv4RHOWTFgBhz2ViEXoSO', // 123456
        'mobile' => '+98' . rand(11111,99999) . rand(11111,99999),
        'avatar' => null,
        'type' => User::TYPE_USER,
        'website' => $faker->url,
        'verify_code' => null,
        'verified_at' => now(),
    ];
});
