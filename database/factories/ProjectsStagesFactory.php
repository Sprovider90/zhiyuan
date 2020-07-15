<?php

use Faker\Generator as Faker;

$factory->define(App\Models\ProjectsStages::class, function (Faker $faker) {

    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth();

    // 传参为生成最大时间不超过，因为创建时间需永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);

    return [
        'stage_name'=>"阶段一",
        'start_date'=>$created_at->format('Y-m-d'),
        'end_date'=>$updated_at->format('Y-m-d'),
        'stage'=>rand(1,3),
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});

