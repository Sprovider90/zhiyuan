<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\ProjectsPositions::class, function (Faker $faker) {
    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth();

    // 传参为生成最大时间不超过，因为创建时间需永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);

    return [
        'project_id'           => 40,
        'number'           => 1,
        'name'     => "点位名称",
        'good_id'               => 1,
        'device_id'               => 1,
        "area_id"=>1,
        'created_at'        => $created_at,
        'updated_at'        => $updated_at,
    ];
});
