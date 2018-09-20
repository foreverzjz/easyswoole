<?php
/**
 * Created by PhpStorm.
 * User: foreverzjz
 * Date: 2018/9/18
 * Time: 下午5:38
 */

namespace App\Core\Base;

use App\Library\Common;
use EasySwoole\Config;

class Controller extends \EasySwoole\Core\Http\AbstractInterface\Controller
{
    const SIGN_MAX_TIME = 300;
    private $_isCheckSign = false;
    private $_isCheckNetwork = false;
    private $_isCheckOrigin = false;
    private $_limitRequestMethod = null;
    private $_setJsonResponse;

    function index(){}

    protected function setCheckOrigin()
    {
        $this->_isCheckOrigin = true;
    }

    protected function setJsonResponse()
    {
        $this->_setJsonResponse = true;
        $this->response()->withHeader('Content-type','application/json;charset=utf-8');
    }

    protected function writeErrorJson($message = 'error', $code = -1)
    {
        $code = $code > -1 ? -1 : $code;
        $message = $message ?: 'error';
        parent::writeJson($code, false, $message);
    }

    protected function writeDataJson($data = array(), $code = 1)
    {
        if(!$this->response()->isEndResponse()){
            $code = $code < 1 ? 1 : $code;
            $responseData = Array(
                "code"=>$code,
                "data"=>$data,
                "result"=>true,
                "msg"=>"success"
            );
            $this->response()->write(json_encode($responseData,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type','application/json;charset=utf-8');
            return true;
        }else{
            trigger_error("response has end");
            return false;
        }
    }

    protected function allowOrigin()
    {
        $origin = $this->request()->getUri();

        if(empty($origin)){
            $this->response()->write('No \'Access-Control-Allow-Origin\' header is present on the requested resource.Origin is therefore not allowed access.');
            $this->response()->withStatus(405);
            return false;
        }

        $allowOrigin = [
            'swoole.cn'
        ];
        $matches = [];
        preg_match('/[\w][\w-]*\.(?:com\.cn|com|cn|net)(\/|$)/isU', explode(':',$origin)[1], $matches);
        $domain = trim($matches[0], '/');

        if (!empty($domain) && in_array($domain, $allowOrigin)) {
            $this->response()->withHeader("Access-Control-Allow-Origin", $origin);
            return true;
        } else {
            $this->response()->write('No \'Access-Control-Allow-Origin\' header is present on the requested resource.Origin is therefore not allowed access.');
            $this->response()->withStatus(405);
            return false;
        }
    }

    protected function allowNetWork()
    {
        $ip = "127.0.0.1";
        if($ip == "127.0.0.1"){
            return true;
        }
        $this->response()->write('Not allowed request');
        $this->response()->withStatus(405);
        return false;
    }

    protected function limitRequestMethod(string $method)
    {
        if(strtoupper($this->request()->getMethod()) == strtoupper($method)){
            return true;
        }
        $this->response()->write('The specified HTTP method is not allowed for the requested resource (Request method \'' . $this->request()->getMethod() . '\' not supported).');
        $this->response()->withStatus(405);
        return false;
    }

    protected function checkRequestSign()
    {
        $requestData['device_id'] = $this->request()->getRequestParam('device_id');
        $requestData['client_type'] = intval($this->request()->getRequestParam('client_type'));
        $requestData['data'] = $this->request()->getRequestParam('data');
        $requestData['time'] = intval($this->request()->getRequestParam('time'));
        $sign = $this->request()->getRequestParam('sign');

        if(empty($sign)){
            $this->writeErrorJson('sign missing', -1);
            return false;
        }
        if(abs(time()-$requestData['time']) > self::SIGN_MAX_TIME){
            $this->writeErrorJson('sign past due', -1);
            return false;
        }

        $signKey = Config::getInstance()->getConf('APP_SETTING')['clientSignKey'];
        if(Common::createSign($requestData, $signKey) != $sign){
            $this->writeErrorJson('sign error', -1);
            return false;
        }
        return true;
    }

    protected function onRequest($action): ?bool
    {
        $this->dealRequestLimit();
        if($this->_isCheckOrigin && !$this->allowOrigin()){
            return false;
        }
        if($this->_isCheckNetwork && !$this->allowNetWork()){
            return false;
        }
        if($this->_limitRequestMethod && !$this->limitRequestMethod($this->_limitRequestMethod)){
            return false;
        }
        if($this->_isCheckSign && !$this->checkRequestSign()){
            return false;
        }
        return true;
    }

    protected function dealRequestLimit()
    {
        $source = trim($this->request()->getRequestTarget(), '/');
        $source = explode('/', $source);

        if(count($source) <= 2){
            return true;
        }

        switch($source[0]){
            case 'admin':
                $this->_isCheckNetwork = true;
                break;
            case 'priv':
                $this->_isCheckNetwork = true;
                break;
            case 'pub':
                $this->_isCheckSign = true;
                break;
            case 'web':
                $this->_isCheckOrigin = true;
                break;
        }
        return true;
    }
}