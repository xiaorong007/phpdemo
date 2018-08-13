<?php
require_once('common.php');
require_once('config.php');
$orderlist = file_get_contents("order.db");
if(!$orderlist){
    echo json_encode(array('code'=>-1,'msg'=>'暂无订单'));
}
$orderlist = json_decode($orderlist,1);
//商户id
$data['uid'] = $common['userItem']['uid'];
$data['transid'] = $orderlist['transid'];
$key = publicSplicing($data);
$data['key'] = md5($key.$common['token']);
//请求远程查询订单状态
$result = postCurl($common['api_url']['go_url'].'/payapi/v2/query',json_encode($data),1);
$result = json_decode($result,1);
if($result['code'] == 1){
    $orderlist['status'] = $result['data']['status'];
    file_put_contents("order.db", json_encode($orderlist));
}
echo json_encode($result);die;