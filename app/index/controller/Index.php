<?php
namespace app\index\controller;
use app\common\controller\Frontend;

class Index extends Frontend
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function index()
    {
		return time();
        //return $this->view->fetch();
    }

    /*
    public function mongo()
    {
        $a = \think\facade\Db::connect('mongo')
            ->table('users')
            ->find();
        var_dump($a);
    }
    */
}
