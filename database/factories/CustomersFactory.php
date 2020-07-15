<?php
use Faker\Generator as Faker;

$factory->define(App\Models\Customers::class, function (Faker $faker) {
    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth();

    // 传参为生成最大时间不超过，因为创建时间需永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);
    $company_name = $faker->company;
    return [
        'company_name'  => $company_name,
        'company_addr'  => mb_substr($company_name,0,rand(2,5)),
        'type'          => rand(0,6),
        'email'         => $faker->unique()->safeEmail,
        'address'       => $faker->address,
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});

