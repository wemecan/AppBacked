<?php

/**
 * 函数扩展
 * @return mixed|string
 *
 * require_once app()->getRootPath().'app'.DIRECTORY_SEPARATOR.'function.php';
 */

//获得用户IP地址
function get_ip(){
    $ipaddress = '';
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
        $ipaddress = $_SERVER["HTTP_CF_CONNECTING_IP"];
    elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    elseif(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    elseif(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    elseif(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    elseif(isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    elseif(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = '0.0.0.0';
    return $ipaddress;
}

