<?php

//上传配置
return [
    /*
     * 引擎
     */
    'driver' => 'local',
    /*
     * 上传地址,默认是本地上传
     */
    'uploadurl' => 'ajax/upload',
    /*
     * CDN地址
     */
    'cdnurl'    => '',
    /*
     * 文件保存格式
     */
    'uploaddir' => 'uploads',
    /*
     * 最大可上传大小
     */
    'maxsize'   => '10mb',
    /*
     * 可上传的文件类型
     */
    'mimetype'  => 'jpg,png,gif,jpeg,bmp,webp',
    /*
     * 是否支持批量上传
     */
    'multiple'  => false,
];
