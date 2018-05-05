<?php

use Faker\Generator as Faker;


/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;
    return [
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$OExhSFi9YnXROYfb6TQqAeru74fJ0NDVrDQ5cdKi/3TNpxqm50MMu', // "secret"
        'remember_token' => str_random(10),
        
    ];
});
$factory->define(App\Task::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
      
    ];
});
