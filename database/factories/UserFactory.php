<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Models\User::class, function (Faker $faker) {
    $date_time = $faker->date . ' ' . $faker->time;
    return [
        'name' => $faker->userName,
        'phone'=>$faker->phoneNumber,
        'remember_token' => Str::random(10),     
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'truename'=>$faker->name,
        'type'=>rand(1,2),
        'customer_id'=>1,
        'created_at' => $date_time,
        'updated_at' => $date_time,
    ];
});
