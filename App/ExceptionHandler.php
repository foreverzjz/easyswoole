<?php
/**
 * Created by PhpStorm.
 * User: foreverzjz
 * Date: 2018/8/24
 * Time: 下午4:52
 */

namespace App;

use EasySwoole\Core\Component\Logger;
use EasySwoole\Core\Http\AbstractInterface\ExceptionHandlerInterface;
use EasySwoole\Core\Http\Request;
use EasySwoole\Core\Http\Response;

class ExceptionHandler implements ExceptionHandlerInterface
{
    public function handle( \Throwable $exception, Request $request, Response $response )
    {
        $exceptionErr = "exception error" . PHP_EOL . " exception message: " . $exception->getMessage() . PHP_EOL . " exception line: " . $exception->getLine() . PHP_EOL . " exception file: " . $exception->getFile() . PHP_EOL . $exception->getTraceAsString();
        Logger::getInstance()->log($exceptionErr, 'exception');
    }
}