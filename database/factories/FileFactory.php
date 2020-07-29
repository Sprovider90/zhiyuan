<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use \App\Models\Files;

$factory->define(Files::class, function (Faker $faker) {
    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth();
    // 传参为生成最大时间不超过，因为创建时间需永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);
    $name = rand(1,99999999).'.jpg';
    $imgArr = [
        'https://ss2.bdstatic.com/70cFvnSh_Q1YnxGkpoWK1HF6hhy/it/u=3087705303,1740657087&fm=26&gp=0.jpg',
        'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=3922290090,3177876335&fm=26&gp=0.jpg',
        'https://ss2.bdstatic.com/70cFvnSh_Q1YnxGkpoWK1HF6hhy/it/u=3522674777,2853281407&fm=26&gp=0.jpg',
        'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=1462833467,3467912640&fm=26&gp=0.jpg'
    ];
    $img = $imgArr[rand(0,3)];
    return [
        'name'          => $name,
        'upload_name'   => $name,
        'size'          => rand(100,99999),
        'ext'           => substr($img,-3),
        'path'          => $img,
        'mime'          => 'image/jpeg',
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
