<?php
/**
 * Created by PhpStorm.
 * User: foreverzjz
 * Date: 2018/9/19
 * Time: 下午6:31
 */

namespace App\Library;

class Common
{
    static function createSign(array $params, string $signKey)
    {
        $signString = '';
        if(!empty($params)){
            ksort($params);
            foreach($params as $key=>$value){
                $signString .= $value;
            }
        }

        $signString .= $signKey;
        return md5($signString);
    }
}