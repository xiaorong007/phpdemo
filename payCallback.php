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
    $orderlist = json_decode($orderlist,1);
    foreach ($orderlist as $key => $val){
        if($val['orderid'] == $orderid){
            $orderlist[$key]['status'] = 1;
            break;
	}
    }
    //修改完之后将订单放入本地数据文件
    $orderlist = json_encode($orderlist);
    file_put_contents("order.db", $orderlist);
    $code = '1';
    $msg = '处理成功';
}
echo json_encode(array('code'=>$code,'msg'=>$msg));die;
