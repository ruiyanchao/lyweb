<?php
require_once __DIR__.'/_init.php';
$url_info = parse_url($_SERVER['REQUEST_URI']);
if(isset($url_info['path']))
{
    $path = $url_info['path'];
}
else
{
    $path = '';
}

$host_name = HOST_NAME;

if(!empty($_GET))
{
    $url_data = parse_url($_SERVER['REQUEST_URI']);
    if(isset($url_data['path']))
    {
        _header('Location: http://' . $host_name .$url_data['path'], true, 301);
        return;
    }
}
$tmp_arr = explode('/', $path);
foreach($tmp_arr as $key=>$value)
{
    if(trim($value) == '')
    {
        unset($tmp_arr[$key]);
    }
}
$tmp_arr = array_values($tmp_arr);
if(isset($tmp_arr[0]))
{
    $func = "\\Workerman\\Modules\\{$tmp_arr[0]}";
    if(isset($tmp_arr[1]))
    {
        $func .= '_'.$tmp_arr[1];
        unset($tmp_arr[1]);
    }
    unset($tmp_arr[0]);
}else
{
    $func = "\\Workerman\\Modules\\home";
}
$func = str_replace('-', '_', $func);
if(!function_exists($func))
{
    foreach(glob(NET_ROOT . "/Modules/*") as $php_file)
    {
        require_once $php_file;
    }
}

if(!function_exists($func))
{
    _header("HTTP/1.0 404 Not Found", true, 404);
    echo "<html><head><title>404 Not Found</title></head><body><h3>404 Not Found</h3></body></html>";
}
else
{
    \Workerman\Protocols\Http::sessionStart();
    if($func != "\\Workerman\\Modules\\login"){
        $auth = auth();
        if(!$auth){
            _header('Location: http://' . $host_name.'/login' , true, 301);
            return;
        }
    }
    call_user_func_array($func, $tmp_arr);
}
