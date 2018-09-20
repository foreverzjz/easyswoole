<?php
/**
 * Created by PhpStorm.
 * User: foreverzjz
 * Date: 2018/9/18
 * Time: 下午5:36
 */

namespace App\HttpController\Pub;

use App\Core\Base\Controller;

class PubBase extends Controller
{

    public function onRequest($action): ?bool
    {
        return parent::onRequest($action);
    }

}