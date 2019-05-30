<?php
define('NET_ROOT', realpath(dirname(__FILE__) . '/..'));
define('HOST_NAME','127.0.0.1:2345');
require_once NET_ROOT . '/Lib/Store.php';

use Workerman\Protocols\Http;


function _header($content, $replace = true, $http_response_code = 0)
{
    Http::header($content, $replace, $http_response_code);
    return;
}


function ly($status,$msg)
{
    $result =  [
        'status'=> $status,
        'msg'   => $msg
    ];
    echo json_encode($result);
}

function auth()
{
    \WorkerMan\Lib\Store::config(NET_ROOT . '/Data/data.php');
    $users =  \WorkerMan\Lib\Store::get('user');
    if(!isset($_SESSION['username'])) return false;
    if(!isset($users[$_SESSION['username']])) return false;
    \WorkerMan\Lib\Store::config(NET_ROOT . '/Data/session.php');
    $session_id = Http::sessionId();
    $session = \WorkerMan\Lib\Store::get($session_id);
    if($session == null || !isset($session['username'])  ) return false;
    return true;
}

function set_session($name,$value)
{
    $_SESSION[$name] = $value;
    $session_id = Http::sessionId();
    \WorkerMan\Lib\Store::config(NET_ROOT . '/Data/session.php');
    \WorkerMan\Lib\Store::set($session_id,[$name=>$value]);

}

function destroy_session()
{
    $session_id = Http::sessionId();
    \WorkerMan\Lib\Store::config(NET_ROOT . '/Data/session.php');
    \WorkerMan\Lib\Store::delete($session_id);

}
