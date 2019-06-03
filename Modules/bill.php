<?php
namespace WorkerMan\Modules;

function bill()
{
    $month = date("Y-m");
    if(isset($_GET['month'])){
        $month = date("Y-m",strtotime($_GET['month']));
    }
    \WorkerMan\Lib\Store::config(NET_ROOT."/Data/bill_{$month}.php");
    $list = \WorkerMan\Lib\Store::getAll();
    $total = [];
    foreach ($list  as $row){
        $total[$row['username']][] = $row['consume_sum'];
    }
    include NET_ROOT . '/Views/bill.tpl.php';
}

function bill_add()
{
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(!isset($_POST['consume_type'])) return ly(0,'消费类型必选');
        if(!isset($_POST['consume_time'])) return ly(0,'消费时间必填');
        if(!isset($_POST['consume_sum']))  return ly(0,'消费金额必填');
        $consume_type = $_POST['consume_type'];
        $consume_time = $_POST['consume_time'];
        $consume_sum  = $_POST['consume_sum'];
        $consume_remark  = $_POST['consume_remark'] ?? "";
        $username     = $_SESSION['username'];
        $month = date("Y-m",strtotime($consume_time));
        \WorkerMan\Lib\Store::config(NET_ROOT."/Data/bill_{$month}.php");
        \WorkerMan\Lib\Store::set(time().rand(1000,9999),['consume_time'=>$consume_time,'consume_type'=>$consume_type,'consume_sum'=>$consume_sum,'username'=>$username,"consume_remark"=>$consume_remark]);
        return ly(1,'添加成功');
    }else {
        include NET_ROOT . '/Views/bill_add.tpl.php';
    }
}

function bill_delete()
{
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(!isset($_POST['id'])) return ly(0,'请选择具体条数');
        $id = $_POST['id'];
        $month = date("Y-m");
        if(isset($_POST['month'])){
            $month = date("Y-m",strtotime($_POST['month']));
        }
        \WorkerMan\Lib\Store::config(NET_ROOT."/Data/bill_{$month}.php");
        \WorkerMan\Lib\Store::delete($id);
        return ly(1,'删除成功');
    }
}
