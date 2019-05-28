<?php
namespace WorkerMan\Modules;

use Workerman\Protocols\Http;

function home()
{
    include NET_ROOT . '/Views/home.tpl.php';
}

function login()
{
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(!isset($_POST['username'])) return ly(0,'缺少用户名');
        if(!isset($_POST['password'])) return ly(0,'缺少密码');
        $username = $_POST['username'];
        $password = $_POST['password'];
        \WorkerMan\Lib\Store::config(NET_ROOT."/Data/data.php");
        $users =  \WorkerMan\Lib\Store::get('user');
        if(!isset($users[$username])) return ly(0,'用户名不存在');
        $user = $users[$username];
        if($user['password'] != md5($password)) return ly(0,'密码错误');
        $_SESSION['username']= $username;
        return ly(1,'登录成功');
    }else{
        include NET_ROOT . '/Views/login.tpl.php';
    }

}

function logout()
{
    Http::tryGcSessions();
    _header('Location: http://' . HOST_NAME.'/login' , true, 301);
    return;
}

function welcome()
{
    include NET_ROOT . '/Views/welcome.tpl.php';
}
