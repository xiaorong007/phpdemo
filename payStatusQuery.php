<?php
require_once('common.php');
require_once('config.php');
$data['out_order_id'] = isset($_REQUEST['out_order_id']) ? $_REQUEST['out_order_id'] :'';
if(empty($data['out_order_id'])){
    $list['code'] = '100000';
    $list['msg'] = '参数失败';
    echo json_encode($list);die;
}
//商户id
$data['uid'] = $common['userItem']['uid'];
$key = publicSplicing($data);
$data['key'] = md5($key.$common['token']);
//请求远程查询订单状态
$result = postCurl($common['api_url']['go_url'].'/ccpay/ach/query',json_encode($data),1);
$result = json_decode($result,1);
if($result['code'] == 1){
    $orderlist = file_get_contents("order.db");
    $orderlist = json_decode($orderlist,1);
    foreach ($orderlist as $key => $value){
        if($value['out_order_id'] == $data['out_order_id']){
            $status = 0;
            if($result['data']['result']['status'] == 2){
                //2 已支付
                $status = 1;
                $result['data']['result']['status'] = 1;
            }else{
                //未支付
                $result['data']['result']['status'] = 0;
            }
            $orderlist[$key]['status'] = $status;
            break;
        }
    }
    file_put_contents("order.db", json_encode($orderlist));
}
echo json_encode($result);die;

