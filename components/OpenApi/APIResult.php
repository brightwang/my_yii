<?php
/**
 * User: brightwang
 * Date: 13-11-27
 * Time: 上午10:23
 */

class APIResult
{
    public $message;
    public $status;
    public $data;

    public function __construct($message, $status, $data = array())
    {
        $this->message = $message;
        $this->status = $status;
        $this->data = $data;
    }
}