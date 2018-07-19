<?php
//读取本地数据文件
require_once("common.php");
require_once("config.php");

$orderlist = file_get_contents("order.db");
if($orderlist){
    $orderlist = json_decode($orderlist,1);
}else{
    $orderlist = array();
}
//组装数据用于请求数据
$data = !empty($_REQUEST) ? $_REQUEST : $common['userItem'] ;
//支付成功回调地址
$data['notify_url'] ='http://'. $_SERVER['HTTP_HOST'].'/payCallback.php';
//唯一订单号
$data['orderid'] = tableidrand();
//用户ip
$data['user_ip'] = getIp();
$key = publicSplicing($data);
$data['key'] = md5($key.$common['token']);
//请求远程接口生成订单
$result = postCurl($common['api_url']['go_url'].'/ccpay/ach/pay',json_encode($data),1);
//远程接口返回存入日志
file_put_contents("log.txt", $result.PHP_EOL, FILE_APPEND);
$result = json_decode($result,1);
$array['code'] = '100000';
$array['msg'] = '';
$array['data']['xingxingPayPlatform'] = $result['msg'];
if($result['code'] == 1){
    $array = array();
    $array['code'] = $result['code'];
    $array['msg'] = '成功';
    $array['data']['qrcodeUrl'] = $result['data']['result']['qr_url'];
    $array['data']['payUrl'] = $result['data']['result']['parse_url'];
    $array['data']['orderid'] = $result['data']['result']['orderid'];
    //成功到支付页生成订单到本地数据库
    $parms = array();
    $parms['price'] = $data['price'];
    $parms['pay_type'] = $data['pay_type'];
    $parms['orderid'] = $data['orderid'];
    $parms['goodsname'] = $data['goodsname'];
    $parms['orderuid'] = $data['orderuid'];
    $parms['user_ip'] = $data['user_ip'];
    $parms['created_at'] = date('Y-m-d H:i:s');
    $parms['status'] = 0;
    $parms['out_order_id'] = $result['data']['result']['out_order_id'];
    array_push($orderlist,$parms);
    $orderlist = json_encode($orderlist);
    file_put_contents("order.db", $orderlist);
}
echo json_encode($array);die;
