<?php
/**
 * User: brightwang
 * Date: 13-11-27
 * Time: 上午10:42
 */

class MethodCommentParser
{

    private $_className;
    private $_methodName;
    private $_comment;
    private $_docBlockNode;

    public function __construct($className,$methodName)
    {
        $this->_className=$className;
        $this->_methodName=$methodName;
        $method = new ReflectionMethod($className, $methodName);
        $this->_comment=$method->getDocComment();
        $parser=new \Sami\Parser\DocBlockParser($this->_comment);
        $this->_docBlockNode=$parser->parse($this->_comment);
    }

    public function GetComment($tagKey)
    {
        return $this->_docBlockNode->getTag($tagKey);
    }
}