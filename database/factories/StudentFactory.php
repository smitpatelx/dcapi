<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Student;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

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

$factory->define(Student::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'student_number' => $faker->numberBetween($min = 100000010, $max = 999999999),
        'ipaddress' => '168.167.0.1'
    ];
});
