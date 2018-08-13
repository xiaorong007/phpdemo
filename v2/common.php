<?php
//curl 请求数据
function postCurl($url,$data){
    $headers = array(
        "Content-type:application/json",
    );
    //初始化
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 1);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, 1);
    //设置post数据
    $post_data = $data;
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    //执行命令
    $data = curl_exec($curl);

    $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    //关闭URL请求
    curl_close($curl);
    $header = substr($data, 0, $headerSize);
    return substr($data, $headerSize);
}
//获取唯一值
function tableidrand(){
    return time().rand(1000000,9999999);
}
//用户key排序
function publicSplicing(array $post){
    $data = array();
    $autograph = '';
    foreach ($post as $key=>$val ){
        $data[$key] = $val;
    }
    ksort($data);
    foreach ($data as $key=>$value){
        $autograph .='&'.$key.'='.$value;
    }
    return substr($autograph,1);
}
//获取ip
function getIp(){
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
        $cip = $_SERVER["HTTP_CLIENT_IP"];
    }elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
        $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    }elseif(!empty($_SERVER["REMOTE_ADDR"])){
        $cip = $_SERVER["REMOTE_ADDR"];
    }else{
        $cip = '';
    }
    preg_match("/[\d\.]{7,15}/", $cip, $cips);
    $cip = isset($cips[0]) ? $cips[0] : 'unknown';
    unset($cips);
    return $cip;
}