<?php

namespace app\api\controller;

use app\common\controller\Api;

/**
 * 首页接口.
 */
class Index extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * 首页.
     */
    public function index()
    {
        return json([
            'error' => 'access error',
            'time' => time(),
        ]);
        //$this->success('请求成功');
    }
}
