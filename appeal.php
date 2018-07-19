<?php
require_once('common.php');
require_once('config.php');
$data['out_order_id'] = isset($_REQUEST['out_order_id']) ? $_REQUEST['out_order_id'] :'';
$data['cert_img'] = isset($_REQUEST['cert_img']) ? $_REQUEST['cert_img'] :'';

if(empty($data['out_order_id']) || empty($data['cert_img'])){
    $list['code'] = '100000';
    $list['msg'] = '参数失败';
    echo json_encode($list);die;
}
//商户id
$data['uid'] = $common['userItem']['uid'];
$key = publicSplicing($data);
$data['key'] = md5($key.$common['token']);
//请求远程进行申诉
$result = postCurl($common['api_url']['go_url'].'/ccpay/ach/order/check',json_encode($data),1);
$result = json_decode($result,1);
echo json_encode($result);die;