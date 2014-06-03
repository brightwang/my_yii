<?php
/**
 * User: brightwang
 * Date: 13-11-27
 * Time: 上午10:28
 */

class ApidemoController extends BaseController
{

    /**
     * @desc 这是个测试api
     * @param string $name 姓名
     * @return string 返回姓名组合
     */
    public function apiDemo($name="guest")
    {

        return "$name hello";
    }

    public function apiTest()
    {
        return get_headers("http://a.shouyou.com/ddd.php");
    }

    public function actionDdd()
    {
        var_dump(1);exit;
    }

}