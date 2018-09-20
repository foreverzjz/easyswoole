<?php
/**
 * Created by PhpStorm.
 * User: foreverzjz
 * Date: 2018/8/24
 * Time: 下午4:52
 */

namespace App;

use EasySwoole\Config;
use EasySwoole\Core\AbstractInterface\LoggerWriterInterface;

class LoggerHandler implements LoggerWriterInterface
{
    public function writeLog($obj, $logCategory, $timeStamp)
    {
        $logPath = Config::getInstance()->getConf('LOG_DIR');
        $str = date("y-m-d H:i:s").":{$obj}\n";
        $logCategory = str_replace('::', '/' , $logCategory);
        $logCategory = str_replace('\\', '/' , $logCategory);
        switch ($logCategory){
            case 'exception':
                $filePrefix = "error_".date('ymdH');
                break;
            default:
                $filePrefix = "info_".date('ymdH');
                break;
        }
        $filePath = $logPath . "/" . $logCategory;
        $fileFullPath = $filePath. "/{$filePrefix}.log";
        is_dir($filePath) OR mkdir($filePath, 0777, true);
        file_put_contents($fileFullPath, $str,FILE_APPEND|LOCK_EX);
    }
}