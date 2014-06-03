<?php
/**
 * User: brightwang
 * Date: 13-5-28
 * Time: 上午10:11
 */

require_once 'Sami/Parser/DocBlockParser.php';
require_once 'Sami/Parser/Node/DocBlockNode.php';

class BaseController extends Controller
{
    /**
     * 是否开启验证
     */
    protected $validSign = false;

    /**
     * 执行参数校验
     * @param $filterChain
     */
    public function filterParameterVerifier($filterChain)
    {
        if (!$this->validSign) {
            $filterChain->run();
            return;
        }
    }

    public function createAction($actionID)
    {
        $extensionRequest="";
        if(stripos($actionID,".")!=false)
        {
            $actionArray=explode(".",$actionID);
            if(is_array($actionArray)&&count($actionArray)==2)
            {
                $actionID=$actionArray[0];
                $extensionRequest=$actionArray[1];
            }
        }
        if ($actionID === '')
            $actionID = $this->defaultAction;
        if (method_exists($this, 'action' . $actionID) && strcasecmp($actionID, 's')) // we have actions method
            return new CInlineAction($this, $actionID);
        else if ((method_exists($this, 'api' . $actionID) && strcasecmp($actionID, 's'))) {
            return new CAPIInlineAction($this, $actionID,$extensionRequest);
        } else {
            return parent::createAction($actionID);
        }
    }

    /**
     * @return array
     */
    public function filters()
    {
        return array(
            'ParameterVerifier',
        );
    }
}