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
$data['notifyurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/payCallback.php';
//唯一订单号
$data['orderid'] = tableidrand();
//用户ip
//$data['userip'] = getIp();
$key = publicSplicing($data);
$data['key'] = md5($key.$common['token']);
//请求远程接口生成订单
$result = postCurl($common['api_url']['go_url'].'/payapi/v2/orders',json_encode($data),1);
//远程接口返回存入日志
file_put_contents("pay.log", $result.PHP_EOL, FILE_APPEND);
$result = json_decode($result,1);
if($result['code'] == 1){
    //成功到支付页生成订单到本地数据库
    $parms = array();
    $parms['price'] = $data['price'];
    $parms['paytype'] = $data['paytype'];
    $parms['orderid'] = $data['orderid'];
    $parms['orderuid'] = $data['orderuid'];
    $parms['created_at'] = date('Y-m-d H:i:s');
    $parms['status'] = 0;
    $parms['transid'] = $result['data']['result']['transid'];
    file_put_contents("order.db", json_encode($parms));
    header('Location:'.$result['data']['result']['url']);die;
}
echo json_encode($result);die;