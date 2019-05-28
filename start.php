<?php
use \Workerman\Worker;
use \Workerman\WebServer;
require_once __DIR__ . '/vendor/autoload.php';

// 0.0.0.0 代表监听本机所有网卡，不需要把0.0.0.0替换成其它IP或者域名
// 这里监听8080端口，如果要监听80端口，需要root权限，并且端口没有被其它程序占用
$webserver = new WebServer('http://0.0.0.0:2345');
// 类似nginx配置中的root选项，添加域名与网站根目录的关联，可设置多个域名多个目录
$webserver->addRoot('127.0.0.1', __DIR__.'/Web/');
// 设置开启多少进程
$webserver->count = 4;


Worker::runAll();