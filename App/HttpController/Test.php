<?php
/**
 * Created by PhpStorm.
 * User: foreverzjz
 * Date: 2018/9/6
 * Time: 上午10:01
 */

namespace App\HttpController;

use EasySwoole\Core\Component\Di;
use EasySwoole\Core\Component\Pool\PoolManager;

class Test extends Base
{
    public function mysql()
    {
        $pool = PoolManager::getInstance()->getPool('App\Utility\MysqlPool');
        $db = $pool->getObj();
        $this->writeDataJson($db->get('user'));
        $pool->freeObj($db);

    }

    public function redis()
    {
        $this->writeDataJson([]);
        $this->response()->end();
        $pool = PoolManager::getInstance()->getPool('App\Utility\RedisPool');
        $i = 10;
        $redis = [];
        while($i>=0){
            $redis[] = $pool->getObj();
            --$i;
        }
        $this->writeDataJson($redis);
    }

    public function set()
    {
        $redis = Di::getInstance()->get('redis');
        $ret = $redis->set('zhaijiazhen', 'love self');
        $this->writeDataJson($ret);
    }

    public function get()
    {
        $redis = Di::getInstance()->get('redis');
        $ret = $redis->get('zhaijiazhen');
        $this->writeDataJson($ret);
    }
}