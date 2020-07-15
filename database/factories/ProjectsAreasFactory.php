<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Models\ProjectsAreas::class, function (Faker $faker) {

    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth();

    // 传参为生成最大时间不超过，因为创建时间需永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);

    return [
        'area_name'=>$faker->name,
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
