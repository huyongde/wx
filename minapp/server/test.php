<?php
include 'WeixinPay.php';  
include 'WX.php';  
include 'Common.php';  
include 'Redis.php';

$dev = true;
if ($dev) {
    $appid  = '';  
    $mch_id = '';  
    $secret = '';  

    $key    = "";
    $openid = "";

} else {

    $appid  = '';  
    $mch_id = '';  
    $secret = '';  

    $key    = "";
    $openid = "";
    
}
$wx = new WX($appid , $secret, $mch_id);


function testRedis() {
    Redis::getInstance()->setex("a", 100, 909090);
    var_dump(Redis::getInstance()->get("a"));
}

function testGetUserInfo() {
    global $wx;
    $code = "";
    $res = $wx->getUserInfoByCode($code);
    if (!empty($res)) {
        $openid = $res['openid'];
        $session_key = $res['session_key'];
        $expire_in = $res['expires_in'];
    } else {
        exit;
    }
}
echo ("openid:" . $openid . "\n");
function testTemplate() {
    global $wx;

    $arr_tmp = $wx->SendTemplateNews();
    print_r($arr_tmp);
    return;
    $arr_tmp = $wx->delTemplate();
    print_r($arr_tmp);

    $arr_tmp = $wx->addToMyTemplate();
    print_r($arr_tmp);
    $arr_tmp = $wx->getTemplateList();
    print_r($arr_tmp);
    $arr_tmp = $wx->getTemplateInfo();
    print_r($arr_tmp);
    $arr_tmp = $wx->getMyTemplate();
    print_r($arr_tmp);
}
testTemplate();
exit;

$out_trade_no = $mch_id. time();  
$body = "充值押金";  
$total_fee = floatval(99*100);  
$weixinpay = new WeixinPay($appid,$openid,$mch_id,$key,$out_trade_no,$body,$total_fee);  
$return=$weixinpay->pay();  
echo json_encode($return);  
