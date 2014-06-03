<?php
/**
 * User: brightwang
 * Date: 13-5-28
 * Time: 上午10:16
 * api接口抛出的特定异常
 */


class APIException extends Exception
{
    public $status;
    public $data;
    public function __construct($message = null,$status=0,$data=null,$code = 0)
    {
        $this->status=$status;
        $this->data=$data;
        parent::__construct($message, $code);
    }
}