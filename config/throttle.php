<?php
// +----------------------------------------------------------------------
// | 节流设置
// +----------------------------------------------------------------------
return [
    // 缓存键前缀，防止键值与其他应用冲突
    'prefix' => 'throttle_',
    // 缓存的键，true 表示使用来源ip
    'key' => true,
    // 设置访问频率，例如 '10/m' 指的是允许每分钟请求10次。默认值 null 表示不限制， eg: 10/m  20/h  300/d 200/300
    'visit_rate' => null,
    // 访问受限时返回的http状态码
    'visit_fail_code' => 429,
    // 访问受限时访问的文本信息
    'visit_fail_text' => '访问频率受到限制，请稍等__WAIT__秒再试',
];
