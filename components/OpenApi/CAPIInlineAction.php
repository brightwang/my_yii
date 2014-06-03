<?php
/**
 * User: brightwang
 * Date: 13-11-27
 * Time: 上午10:21
 */


class CAPIInlineAction extends CAction
{
    private $_extensionAction;
    public function __construct($controller, $id,$extensionAction="")
    {
        parent::__construct($controller, $id);
        $this->_extensionAction=$extensionAction;
        //普通php错误转换为异常
        function PHPErrorHandler($errno, $errstr, $errfile, $errline)
        {
            throw new ErrorFromPHPException($errstr, $errno);
        }
        $oldHandler = set_error_handler("PHPErrorHandler");
    }

    public function run()
    {
        $method = 'api' . $this->getId();
        try {
            $this->getController()->$method();
        } catch (APIException $e) {
            self::json_exit($e->status, $e->getMessage(), array());
        }
        catch (ErrorFromPHPException $error_php) {
            self::json_exit(APIStatus::CODE_INTERNAL_SERVER_ERROR, $error_php->getMessage(), array());
        }
    }

    public function runWithParams($params)
    {
        $methodName = 'api' . $this->getId();
        $controller = $this->getController();

        //获取action扩展操作
        if(!empty($this->_extensionAction))
        {
            $parser=new MethodCommentParser($controller, $methodName);
            $comment=$parser->GetComment($this->_extensionAction);
            $comment=empty($comment)?"暂无描述":$comment;
            self::json_exit(APIStatus::CODE_OK,"Sucess",$comment);
        }
        $method = new ReflectionMethod($controller, $methodName);
        try {
            if ($method->getNumberOfParameters() > 0) {
                $missingParams = array();
                $params = self::generateFunctionParams($method, $missingParams);
                if (!empty($missingParams)) {
                    throw new APIException('缺少必须的参数：' . implode(', ', $missingParams), APIStatus::CODE_PARAMETER_ERROR);
                }
                //正常获取退出
                $result = $this->runWithParamsInternal($controller, $method, $params);
                if ($result instanceof APIResult)
                    self::json_exit($result->status, $result->message, $result);
                else
                    self::json_exit(APIStatus::CODE_OK, "Sucess", $result);
            } else
                self::json_exit(APIStatus::CODE_OK, "Sucess", $controller->$methodName());
        } catch (APIException $e) {
            self::json_exit($e->status, $e->getMessage(), array());
        }
        catch (ErrorFromPHPException $error_php) {
            self::json_exit(APIStatus::CODE_INTERNAL_SERVER_ERROR, $error_php->getMessage(), array());
        }
    }

    /**
     * 从当前请求上下文中自动组装方法所需要的参数
     * @param ReflectionFunction $function
     * @param array $missingParams
     * @return array
     */
    public static function generateFunctionParams(ReflectionMethod $function, array &$missingParams)
    {
        $params = array();
        foreach ($function->getParameters() as $i => $param) {
            $name = $param->getName();
            if (self::issetRequestParam($name)) {
                $value = self::getRequestParam($name);
                if ($param->isArray()) {
                    $params[] = is_array($value) ? $value : array($value);
                } else if (!is_array($value)) {
                    //如果有默认值，根据默认值进行类型转换。
                    if ($param->isDefaultValueAvailable()) {
                        $defaultVal = $param->getDefaultValue();
                        settype($value, gettype($defaultVal));
                    }
                    $params[] = $value;
                } else {
                    $missingParams[] = $name;
                    continue;
                }
            } else if ($param->isDefaultValueAvailable()) {
                $params[] = $param->getDefaultValue();
            } else {
                $missingParams[] = $name;
                continue;
            }
        }

        return $params;
    }

    /**
     * 根据给定的参数，判断当前GET或POST请求是否有传入该参数
     * @param $name
     * @return bool
     */
    private static function issetRequestParam($name)
    {
        return isset($_GET[$name]) || isset($_POST[$name]);
    }

    /**
     * 根据给定的参数名称获取GET或POST请求中的数据
     * @param $name
     * @return mixed
     */
    private static function getRequestParam($name)
    {
        return trim(Yii::app()->request->getParam($name));
    }

    /**
     * 输出json并返回
     * @param $code 状态码
     * @param $msg 消息
     * @param $data 内容
     */
    public static function json_exit($status, $message, $data = null)
    {
        $ret = CJSON::encode(array("status" => $status, "message" => $message, "data" => $data));
        exit($ret);
    }

    protected function runWithParamsInternal($object, $method, $params)
    {
        $ps = array();
        return $method->invokeArgs($object, $params);
    }
}