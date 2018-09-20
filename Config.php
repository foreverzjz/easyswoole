<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2017/12/30
 * Time: 下午10:59
 */

return [
    'SERVER_NAME'=>"EasySwoole",
    'MAIN_SERVER'=>[
        'HOST'=>'0.0.0.0',
        'PORT'=>9501,
        'SERVER_TYPE'=>\EasySwoole\Core\Swoole\ServerManager::TYPE_WEB_SERVER,
        'SOCK_TYPE'=>SWOOLE_TCP,//该配置项当为SERVER_TYPE值为TYPE_SERVER时有效
        'RUN_MODEL'=>SWOOLE_PROCESS,
        'SETTING'=>[
            'task_worker_num' => 8, //异步任务进程
            'task_max_request'=>10,
            'max_request'=>5000,//强烈建议设置此配置项
            'worker_num'=>8
        ],
    ],
    'DEBUG'=>true,
    'TEMP_DIR'=>null,//若不配置，则默认框架初始化
    'LOG_DIR'=>null,//若不配置，则默认框架初始化
    'EASY_CACHE'=>[
        'PROCESS_NUM'=>1,//若不希望开启，则设置为0
        'PERSISTENT_TIME'=>0//如果需要定时数据落地，请设置对应的时间周期，单位为秒
    ],
    'POOL_MANAGER' => [
        'App\Utility\MysqlPool' => [
            'min' => 5,
            'max' => 20,
            'type' => 1
        ],
//        'App\Utility\RedisPool' => [
//            'min' => 5,
//            'max' => 20,
//            'type' => 1
//        ],
    ],
    'CLUSTER'=>[
        'enable'=>false,
        'token'=>null,
        'broadcastAddress'=>['255.255.255.255:9556'],
        'listenAddress'=>'0.0.0.0',
        'listenPort'=>'9556',
        'broadcastTTL'=>5,
        'nodeTimeout'=>10,
        'nodeName'=>'easySwoole',
        'nodeId'=>null
    ],
    'MYSQL'=>[
        'host'=>'127.0.0.1',
        'user'=>'root',
        'password'=>'',
        'db_name'=>'manage'
    ],
    'REDIS'=>[
        'host' => '127.0.0.1',
        'port' => 6379,
        'serialize' => false,
        'dbName' => 1,
        'auth' => null,
        'pool' => [
            'min' => 5,
            'max' => 100
        ],
        'errorHandler' => null
    ],
    'REDIS_CLUSTER'=>[
        '127.0.0.1:7000',
        '127.0.0.1:7001',
        '127.0.0.1:7002',
        '127.0.0.1:7003',
        '127.0.0.1:7004',
        '127.0.0.1:7005'
    ],
    'APP_SETTING'=>[
        'clientSignKey'=>'ja*whAH!7znG4TiG'
    ]
];