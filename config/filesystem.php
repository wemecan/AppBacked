<?php

use think\facade\Env;

return [
    'default' => Env::get('filesystem.driver', 'local'),
    'disks'   => [
        'local'  => [
            'type' => 'local',
            'root' => app()->getRuntimePath().'storage',
        ],
        'runtime'  => [
            'type' => 'local',
            'root' => app()->getRootPath().'runtime',
        ],
        'public' => [
            'type'       => 'local',
            'root'       => app()->getRootPath().'public',
            'url'        => '/',
            'visibility' => 'public',
        ],
        // 更多的磁盘配置信息
        'aliyun' => [
            'type'         => 'aliyun',
            'accessId'     => '******',
            'accessSecret' => '******',
            'bucket'       => 'bucket',
            'endpoint'     => 'oss-cn-hongkong.aliyuncs.com',
            'url'          => 'http://oss-cn-hongkong.aliyuncs.com',//不要斜杠结尾，此处为URL地址域名。
        ],
        'qiniu'  => [
            'type'      => 'qiniu',
            'accessKey' => '******',
            'secretKey' => '******',
            'bucket'    => 'bucket',
            'url'       => '',//不要斜杠结尾，此处为URL地址域名。
        ],
        'qcloud' => [
            'type'       => 'qcloud',
            'region'      => '***', //bucket 所属区域 英文
            'appId'      => '***', // 域名中数字部分
            'secretId'   => '***',
            'secretKey'  => '***',
            'bucket'          => '***',
            'timeout'         => 60,
            'connect_timeout' => 60,
            'cdn'             => '您的 CDN 域名',
            'scheme'          => 'https',
            'read_from_cdn'   => false,
        ]
    ],
];
