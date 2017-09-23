<?php

Class Common {
	  public static function httpGet($url) {
        $oCurl = curl_init();
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }

	public static function httpPost($url, $data, $header = array()) {
        var_dump($url);
        var_dump($data);
        if (!$url) {
            return false;
        }
        if(function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            if(is_array($header) && !empty($header)){
                $set_head = array();
                foreach ($header as $k => $v){
                    $set_head[] = $k.':'.$v;
                }
                curl_setopt($ch, CURLOPT_HTTPHEADER, $set_head);
            }
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));  
            // 设置或者不设置都可以。 不设置的话，content-type默认是：application/x-www-form-urlencode
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);// 25s to timeout.
            $response = curl_exec($ch);
            if(curl_errno($ch)){
                //error
                return curl_error($ch);
            }
            //$result = curl_getinfo($ch);
            curl_close($ch);
            $info = array();
            if($response){
                $info = $response;
            }
            return $info;
        } else {
            throw new Exception('Do not support CURL function.');
        }
    }
}
