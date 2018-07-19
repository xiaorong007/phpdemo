<?php
//查询当前订单是否支付状态
$orderid = !empty($_REQUEST['orderid']) ? $_REQUEST['orderid'] : '';
if(!$orderid){
    $list['code'] = '100000';
    $list['msg'] = '参数失败';
    $list['data']['list'] = '未支付';
    echo json_encode($list);die;
}
//打开本地数据文件
$orderlist = file_get_contents("order.db");
if($orderlist){
    $orderlist = json_decode($orderlist,1);
    foreach ($orderlist as $key => $val){
        if($val['orderid'] == $orderid){
            $list['code'] = '100000';
            $list['msg'] = '未支付';
            $list['data']['list'] = '未支付';
            if( $orderlist[$key]['status'] == 1){
                $list = array();
                $list['code'] = '1';
                $list['msg'] = '成功';
                $list['data'] = $val;
            }
            echo json_encode($list);die;
            break;
        }
    }
}