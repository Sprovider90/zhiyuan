<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Storehouses::class, function (Faker $faker) {
    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth();

    // 传参为生成最大时间不超过，因为创建时间需永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);
    $name = $faker->unique()->address;
    return [
        'name'              => $name.'仓库',
        'address'           => $name.rand(1,1000).'栋'.rand(1,10000).'单元'.rand(1,10000).'号',
        'status'            => rand(1,2),
        'created_at'        => $created_at,
        'updated_at'        => $updated_at,
    ];
});
