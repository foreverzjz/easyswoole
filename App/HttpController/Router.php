<?php
/**
 * Created by PhpStorm.
 * User: foreverzjz
 * Date: 2018/8/29
 * Time: 下午4:47
 */

namespace App\HttpController;

use EasySwoole\Core\Http\Request;
use EasySwoole\Core\Http\Response;
use FastRoute\RouteCollector;

class Router extends \EasySwoole\Core\Http\AbstractInterface\Router
{

    function register(RouteCollector $routeCollector)
    {
        $routeCollector->get('/',function (Request $request ,Response $response){
            $response->write('this router index');
            $response->end();
        });

        $routeCollector->get('/test',function (Request $request ,Response $response){
            $response->write('this router test');
            $response->end();
        });

        $routeCollector->get( '/user/{id:\d+}',function (Request $request ,Response $response,$id){
            $response->write("this is router user ,your id is {$id}");
            $response->end();
        });

        $routeCollector->get( '/user2/{id:\d+}','/index/test');
        $routeCollector->post( '/user2/{id:\d+}','/index/test2');

        $routeCollector->addRoute('GET', '/common/{id:\d+}', function (Request $request ,Response $response, $id){
            $request->withQueryParams(['id'=>$id]);
            $response->write('this router test3');
            $response->end();
        });

//        $routeCollector->get('/api/{id:\d+}/userinfo',  function (Request $request ,Response $response, $id){
//            $request->withQueryParams(['id'=>$id]);
//        });
    }
}