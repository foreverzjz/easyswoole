<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/1/9
 * Time: 下午1:04
 */

namespace EasySwoole;

use \EasySwoole\Core\AbstractInterface\EventInterface;
use EasySwoole\Core\Component\Di;
use EasySwoole\Core\Component\Logger;
use EasySwoole\Core\Component\SysConst;
use \EasySwoole\Core\Swoole\ServerManager;
use \EasySwoole\Core\Swoole\EventRegister;
use \EasySwoole\Core\Http\Request;
use \EasySwoole\Core\Http\Response;
use EasySwoole\Core\Utility\File;
use Predis\Client;
use think\Db;

Class EasySwooleEvent implements EventInterface {

    public static function frameInitialize(): void
    {
        // TODO: Implement frameInitialize() method.
        Di::getInstance()->set(SysConst::HTTP_CONTROLLER_MAX_DEPTH, 5);
        Di::getInstance()->set( SysConst::HTTP_EXCEPTION_HANDLER, \App\ExceptionHandler::class );
        Di::getInstance()->set(SysConst::LOGGER_WRITER, \App\LoggerHandler::class);
        self::loadConf(EASYSWOOLE_ROOT . "/Conf");
        self::initDb();
        self::initRedisCluster();
        date_default_timezone_set('Asia/Shanghai');
        register_shutdown_function(function (){
            if ($error = error_get_last()) {
                Logger::getInstance()->log($error['message'], 'downError');
            }
        });
    }

    public static function loadConf($ConfPath)
    {
        $Conf  = Config::getInstance();
        $files = File::scanDir($ConfPath);
        foreach ($files as $file) {
            $data = require_once $file;
            $Conf->setConf(strtolower(basename($file, '.php')), (array)$data);
        }
    }

    public static function mainServerCreate(ServerManager $server,EventRegister $register): void
    {
        // TODO: Implement mainServerCreate() method.
        // $server->addServer('tcp', 9502);
    }

    public static function onRequest(Request $request,Response $response): void
    {
        // TODO: Implement onRequest() method.
        $request->withAttribute('requestTime', microtime(true));
    }

    public static function afterAction(Request $request,Response $response): void
    {
        // TODO: Implement afterAction() method.
        //从请求里获取之前增加的时间戳
        $reqTime = $request->getAttribute('requestTime');
        //计算一下运行时间
        $runTime = round(microtime(true) - $reqTime, 3);
        //获取用户IP地址
        $ip = ServerManager::getInstance()->getServer()->connection_info($request->getSwooleRequest()->fd);

        //拼接一个简单的日志
        $logStr = ' | '.$ip['remote_ip'] .' | '. $runTime . '|' . $request->getUri() .' | '.
            $request->getHeader('user-agent')[0] . ' | POST : ' . json_encode($request->getAttributes()) . ' | RESULT : ' . $response->getBody();
        //判断一下当执行时间大于1秒记录到 slowlog 文件中，否则记录到 access 文件
        if($runTime > 1){
            Logger::getInstance()->log($logStr, 'access::slow');
        }else{
            Logger::getInstance()->log($logStr,'access');
        }
    }

    public static function catchFatalError()
    {
        if ($error = error_get_last()) {
            var_dump('<b>register_shutdown_function: Type:' . $error['type'] . ' Msg: ' . $error['message'] . ' in ' . $error['file'] . ' on line ' . $error['line'] . '</b>');
        }
    }

    public static function initDb()
    {
        $dbConf = Config::getInstance()->getConf('database');
        Db::setConfig($dbConf);
    }

    public static function initRedisCluster()
    {
        require_once './vendor/predis/predis/autoload.php';
        $parameters = Config::getInstance()->getConf('REDIS_CLUSTER');
        $options    = ['cluster' => 'redis'];

        $client = new Client($parameters, $options);
        Di::getInstance()->set('redis', $client);
    }
}