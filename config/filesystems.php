<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */


    /**
     * 上传图片最大限制 默认5M
     */
    'UPLOAD_IMAGE_MAX_SIZE' => env('UPLOAD_IMAGE_MAX_SIZE', 5),

    /**
     * 上传图片扩展 默认['jpg','png','jepg','gif']
     */
    'UPLOAD_IMAGE_EXT' => env('UPLOAD_IMAGE_EXT', 'jpg,png,jepg,gif'),

    /**
     * 上传视频最大限制 默认50M
     */
    'UPLOAD_VIDEOS_MAX_SIZE' => env('UPLOAD_VIDEOS_MAX_SIZE', 50),

    /**
     * 上传视频扩展 默认['jpg','png','jepg','gif']
     */
    'UPLOAD_VIDEOS_EXT' => env('UPLOAD_VIDEOS_EXT', 'avi,mp4,3gp,mov,rmvb,rm,flv'),


    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
        ],

    ],

];
