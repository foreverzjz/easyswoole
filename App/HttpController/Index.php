<?php
namespace App\HttpController;

use App\Core\Base\Controller;
use App\Model\User;
use think\Db;

class Index extends Controller
{
    function index()
    {
        // TODO: Implement index() method.
//        $this->response()->write('hello world!');
        $this->writeErrorJson("错误的请求", -2);
    }

    public function test()
    {
//        $this->response()->write('test hello world!');
        $this->writeDataJson(["a1"], 2);
    }

    public function test2()
    {

//        $this->response()->write('test hello world!');
        $this->writeErrorJson("错误的请求", -2);
    }

    public function test3()
    {
        $id = $this->request()->getQueryParam('id');
        $this->writeDataJson($id, 100);
    }

    public function userInfo()
    {
        $id = $this->request()->getQueryParam('uid');
        $info = Db::table('user')
            ->where('id', $id)
            ->select();
        $this->writeDataJson($info);
    }

    public function userInfoByModel()
    {
        $id = $this->request()->getQueryParam('uid');
        $info = User::get($id);
        $this->writeDataJson($info);
    }
}