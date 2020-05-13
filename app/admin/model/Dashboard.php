<?php

namespace app\admin\model;

use app\common\model\BaseModel;
use think\facade\Config;

class Dashboard extends BaseModel
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    public function status($value, $data)
    {
        return __($value);
    }

    public static function dashboard_stats()
    {
        $hooks = config('addons.hooks');
        $uploadmode = isset($hooks['upload_config_init']) && $hooks['upload_config_init'] ? implode(',', $hooks['upload_config_init']) : 'local';
        $config = Config::get('composer');
        $addonVersion = isset($config['version']) ? $config['version'] : __('Unknown');
        $array = [
            'totaluser'        => 35200,
            'totalviews'       => 219390,
            'totalorder'       => 32143,
            'totalorderamount' => 174800,
            'addonversion'     => $addonVersion,
            'uploadmode'       => $uploadmode,
        ];
        return $array;
    }

    public static function dashoard_chart()
    {
        $seventtime = \fast\Date::unixtime('day', -7);
        $paylist = $createlist = [];
        for ($i = 0; $i < 7; $i++) {
            $day = date('Y-m-d', $seventtime + ($i * 86400));
            $createlist[$day] = mt_rand(20, 200);
            $paylist[$day] = mt_rand(1, mt_rand(1, $createlist[$day]));
        }
        $array = [
            'paylist'          => $paylist,
            'createlist'       => $createlist,
        ];
        return $array;
    }

    public static function dashoard_substats()
    {
        $array = [
            // 统计信息
            'totalall' => Db::table($_vtables)->cache(true)->count('id'),
            'totaltoday'    => Db::table($_vtables)->whereDay('created_at')->count('id'),
            'totalyesterday'    => Db::table($_vtables)->whereDay('created_at','yesterday')->count('id'),

            //'totalweek' => Db::table($_vtables)->whereWeek('created_at')->count('id'),
            //'totallastweek' => Db::table($_vtables)->whereMonth('created_at','last week')->count('id'),

            //'totalmonth'    => Db::table($_vtables)->whereMonth('created_at')->count('id'),
            //'totallastmonth'    => Db::table($_vtables)->whereMonth('created_at','last month')->count('id'),

            //'totalyear' => Db::table($_vtables)->whereYear('created_at')->count('id'),
            //'totallastyear' => Db::table($_vtables)->whereYear('created_at','last year')->count('id'),

            //  Db::query('SELECT count(*) FROM `app_images` WHERE TO_DAYS(created_at) = TO_DAYS(NOW())'),

            'todayuserlogin'   => 321,
            'todayusersignup'  => 430,
            'todayorder'       => 2324,
            'unsettleorder'    => 132,
            'sevendnu'         => '80%',
            'sevendau'         => '32%',
        ];
        return $array;
    }

    public static function dashoard_lastnews()
    {
        return [];
    }
}

