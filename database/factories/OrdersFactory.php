<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Orders::class, function (Faker $faker) {
    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth();

    // 传参为生成最大时间不超过，因为创建时间需永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);
    $send_status  = rand(1,2);
    return [
        'order_number'      => date('Ymd',strtotime('- '.rand(0,100).' day')).'-'.sprintf("%04d", rand(0,1000)),
        'good_id'           => 1,
        'num'               => rand(1,10),
        'money'             => sprintf("%.2f",rand(1000,5000)),
        'order_status'      => rand(1,5),
        'send_goods_status' => $send_status,
        'express_name'      => $send_status ==2 ? ['顺丰快递','韵达快递','中通快递','EMS','圆通快递'][rand(0,4)] : '',
        'express_number'    => $send_status ==2 ? rand(100000000,999999999).rand(100000000,999999999) : '',
        'created_at'        => $created_at,
        'updated_at'        => $updated_at,

    ];
});
