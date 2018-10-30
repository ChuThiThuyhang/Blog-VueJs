<?php

use Faker\Generator as Faker;
use App\Models\User;
use App\Models\Asset;
use App\Models\Admin;

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
    static $assetIds, $adminIds;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => 'Abc#abc1', // secret,
        'first_login' => 1,
        'birthday' => $faker->date(),
        'remember_token' => str_random(10),
        'reset_password_token' => null,
        'reset_password_time' => null,
        'asset_id' => $faker->randomElement($assetIds ?: $assetIds = Asset::pluck('id')->toArray()),
        'created_by' => $faker->randomElement($adminIds ?: $adminIds = Admin::select(['id'])->pluck('id')->toArray()),
        'representative' => $faker->boolean,
        'gender' => $faker->randomElement(['male', 'female']),
        'firebase_key' => null,
    ];
});
