<?php

/*

    public function getStatusAttr($value)
    {
        $status = [-1=>'删除',0=>'禁用',1=>'正常',2=>'待审核'];
        return $status[$value];
    }

    // 物理删除
    public static function onAfterDelete($data)
    {
        @unlink(app()->getRootPath().'public/images/'.$data->path.$data->name);
        return true;
    }

    // 物理删除
    public static function onAfterDelete($data)
    {
        $subdir = app()->getRootPath().'public/'.$data->path.$data->name);
        @recursiveDelete($subdir);
        return true;
    }



*/
