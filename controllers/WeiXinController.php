<?php
/**
 * User: brightwang
 * Date: 14-5-14
 * Time: 下午12:29
 */

class WeiXinController extends BaseController
{
    public function actionApply($signature="",$timestamp=0,$nonce=0,$echostr="")
    {
        if($this->checkSignature())
        {
            echo $echostr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = "wl@20091022";
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}