<?php
/**
 * User: brightwang
 * Date: 13-11-27
 * Time: 上午10:12
 */

class APIStatus
{
    /**
     * 操作成功的状态码
     */
    const CODE_OK = 1000;
    /**
     * 参数错误
     */
    const CODE_PARAMETER_ERROR = 1001;
    /**
     * 参数为空
     */
    const CODE_PARAMETER_IS_EMPTY = 1002;
    /**
     * 内容不存在
     */
    const CODE_NOT_EXIST = 1003;
    /**
     * 参数太长
     */
    const CODE_PARAMETER_TOO_LONG = 1004;
    /**
     * 未知错误
     */
    const CODE_INTERNAL_SERVER_ERROR = 1005;
    /**
     * 不允许的请求方法
     */
    const CODE_METHOD_NOT_ALLOWED = 1006;
}