<?php

namespace app\admin\controller;

use think\facade\Config;
use app\common\controller\Backend;

/**
 * 控制台.
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend
{
    /**
     * 数据.
     */
    public function index()
    {
        $dashborad = [];
        $dashboard_data = \app\admin\model\Dashboard::dashboard_stats();
        $dashboard_chart = \app\admin\model\Dashboard::dashoard_chart();
        $dashboard_subdata = \app\admin\model\Dashboard::dashoard_substats();
        $dashboard_lastnews = \app\admin\model\Dashboard::dashoard_lastnews();
        $params = array_merge($dashborad,$dashboard_data);
        $params = array_merge($params,$dashboard_subdata);
        $params = array_merge($params,$dashboard_chart);
        $params = array_merge($params,$dashboard_lastnews);
        $this->view->assign(
            $params
        );
        return $this->view->fetch();
    }
}
