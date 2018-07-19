<?php
//获取本地订单文件
$orderlist = file_get_contents("order.db");
//无数据
$list['code'] = '100000';
$list['msg'] = '没有数据';
$list['data']['list'] = '';
//有数据
if($orderlist){
    $orderlist = json_decode($orderlist,1);
    $list['code'] = '1';
    $list['msg'] = '成功';
    $list['data']['list'] = $orderlist;
}
echo json_encode($list);die;