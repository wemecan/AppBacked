<?php

// +----------------------------------------------------------------------
// | Trace设置 开启调试模式后有效
// +----------------------------------------------------------------------
return [
    // 内置Html 支持扩展
    'type'    => 'Html',
    // 读取的日志通道名
    'channel' => '',
    // Tab
    'tabs' => [
        'base'  => '基本',
        'file'  => '文件',
        'info'  => '流程',
        'error' => '错误',
        'sql'   => 'SQL',
        'debug' => '调试',
        'user'  => '用户',
    ],
];
