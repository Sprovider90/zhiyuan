<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\ProjectsWaringSetting::class, function (Faker $faker) {
    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth();

    // 传参为生成最大时间不超过，因为创建时间需永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);
    return [
        'project_id'           => 42,
        'remind_time'           => 1,
        'percentage'     => "0.2",
        'notice_start_time'               => 900,
        'notice_end_time'               => 1500,
        "notice_phone"=>"1780510690",
        'created_at'        => $created_at,
        'updated_at'        => $updated_at,
    ];
});
