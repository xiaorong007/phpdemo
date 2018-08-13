<?php
//回调数据
$input = file_get_contents('php://input');
file_put_contents("payCallback",$input.PHP_EOL, FILE_APPEND);
$input = json_decode($input,1);
$orderid = $input['orderid'];
//打开数据数据文件
$orderlist = file_get_contents("order.db");
$code = '-1';
$msg = '订单失败';
if($orderlist){
    //读取本地数据进行数据修改
    $orderlist = json_decode($orderlist,1);
        if($orderlist['orderid'] == $orderid){
            $orderlist['status'] = 1;
        }
    //修改完之后将订单放入本地数据文件
    $orderlist = json_encode($orderlist);
    file_put_contents("order.db", $orderlist);
    $code = '1';
    $msg = '处理成功';
}
echo 'SUCCESS';