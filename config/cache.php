<?php

use think\facade\Env;

// +----------------------------------------------------------------------
// | 缓存设置
// +----------------------------------------------------------------------

$cache_driver = extension_loaded('redis') ? 'redis' : 'file';

return [
    // 默认缓存驱动
    'default' => Env::get('cache.driver', $cache_driver),

    // 缓存连接方式配置
    'stores'  => [
        'file' => [
            // 驱动方式
            'type'       => 'File',
            // 缓存保存目录
            'path'       => '',
            // 缓存前缀
            'prefix'     => '',
            // 缓存有效期 0表示永久缓存
            'expire'     => 0,
            // 缓存标签前缀
            'tag_prefix' => 'tag:',
            // 序列化机制 例如 ['serialize', 'unserialize']
            'serialize'  => [],
        ],

        // 更多的缓存连接
        'redis' => [
            // 驱动方式
            'type'       => 'redis',
            // 缓存前缀
            'prefix'     => '',
            // 缓存有效期 0表示永久缓存
            'expire'     => 3600,
            // 缓存标签前缀
            'tag_prefix' => 'tag:',
            // 连接地址
            'host'       => Env::get('redis.host'),
            // 端口
            'port'       => Env::get('redis.port'),
            // serialize
            'serialize'  => ['serialize', 'unserialize'],
        ],
    ],
];
