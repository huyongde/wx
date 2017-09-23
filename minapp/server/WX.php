<?php 

class WX {
    protected $appid;
    protected $secret;
    protected $mch_id;
    protected $access_token;
    static $errorCode = array(
         
    );

    function __construct($appid, $secret, $mch_id) {
        $this->appid = $appid; 
        $this->secret = $secret; 
        $this->mch_id = $mch_id; 
        $this->access_token = $this->getAccessToken();
    }

    public function getUserInfoByCode($code) {
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=" . $this->appid 
            . "&secret=" . $this->secret 
            . "&js_code=" . $code 
            . "&grant_type=authorization_code"; 
		$res = Common::httpGet($url);
        /**
		//正常返回的JSON数据包
		{
			  "openid": "OPENID",
			  "session_key": "SESSIONKEY",
			  "unionid": "UNIONID"
		}
		//错误时返回JSON数据包(示例为Code无效)
		{
			"errcode": 40029,
			"errmsg": "invalid code"
		}
         */
        if (empty($res)) {
            return array();
        }
        $arr = json_decode($res, true);
        if (isset($arr['errcode'])) {
			// 添加错误处理逻辑
            var_dump("res error ". $res);
       		return array(); 
        }
		return $arr;
    }
    /**
     * 获取access_token, 并存储在本地的redis服务中
     */
    public function getAccessToken() {
        $redis_key = "access_token";
        $access_token = Redis::getInstance()->get($redis_key);
        if (!empty($access_token)) return $access_token;
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential" 
            . "&appid=".  $this->appid
            . "&secret=" . $this->secret; 
        $res = Common::httpGet($url);

        if (empty($res)) {
            return null;
        }
        $arr = json_decode($res, true);
        if (isset($arr['access_token'])) {
            Redis::getInstance()->setex($redis_key, 3600, $arr['access_token']);
       		return $arr['access_token']; 
        }
	    // 添加错误处理逻辑
        var_dump("get accesstoken res error ". $res);
         
    }
    /*
     * 特别注意request_body需要时json格式的字符串
     */
    public function getTemplateList() {
        $url = "http://api.weixin.qq.com/cgi-bin/wxopen/template/library/list?access_token=";
        $url .= $this->access_token;
        $data = array (
            'offset' => 0,
            'count' => 3,
        );
        $res = Common::httpPost($url, $data);

        if (empty($res)) {
            return null;
        }
        $arr = json_decode($res, true);
        return $arr;
          
    }

    public function getTemplateInfo() {
        $id = "5Fn7ftMgJzDKSfve0akOAgZkj2EpQoQdhEbejCEzVfg";
        $id = "AT0002";
        $access_token = $this->getAccessToken();
        echo "access_token: " . $access_token . "\n";

        $url = "https://api.weixin.qq.com/cgi-bin/wxopen/template/library/get?access_token=";
        $url .= $this->access_token;
        $data = array (
            'id' => $id,
        );
        $res = Common::httpPost($url, $data);

        if (empty($res)) {
            return null;
        }
        $arr = json_decode($res, true);
        return $arr;
    }

    public function addToMyTemplate($data = array()) {
        $url = "https://api.weixin.qq.com/cgi-bin/wxopen/template/add?access_token=";
        $url .= $this->access_token;
        if (empty($data)) {
            $data = array(
                "id" => 'AT0002',
                "keyword_id_list" => explode(',', '4,5,6,7,8,9'),
            );
        }
        $res = Common::httpPost($url, $data);
        if (empty($res)) {
            return null; 
        }
        $arr = json_decode($res, true);
        return $arr;
         
    }
    public function getMyTemplate() {
        $url = "https://api.weixin.qq.com/cgi-bin/wxopen/template/list?access_token=";
        $url .= $this->access_token;
        $data = array (
            'offset' => 0,
            'count' => 2,
        );
        $res = Common::httpPost($url, $data);

        if (empty($res)) {
            return null;
        }
        $arr = json_decode($res, true);
        return $arr;
    }

    public function delTemplate($data = array()) {

        $url = "https://api.weixin.qq.com/cgi-bin/wxopen/template/del?access_token=";
        $url .= $this->access_token;
        if (empty($data)) {
            $data = array (
                'template_id' => '5Fn7ftMgJzDKSfve0akOAgZkj2EpQoQdhEbejCEzVfg',
            );
        }
        $res = Common::httpPost($url, $data);

        if (empty($res)) {
            return null;
        }
        $arr = json_decode($res, true);
        return $arr;
    }
    public function sendTemplateNews($data = array()) {
        $url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=";
        $url .= $this->access_token;
        if (empty($data)) {
            $data = array(
                'touser' => 'opD0Y0SK0DyUcapC33uiqcPT00FA',
                'template_id' => '8XQdZazGYJ9LF41AoXglvYvzWzy0BxqvxAg6Ah_AUAU', 
                'page' => 'index',
                'form_id' => 'formid',
                'data' => array(
                    'keyword1' => '测试产品',
                    'keyword2' => 100,
                ),
                'emphasis_keyword' => 'keyword1.DATA', 
            );
        }

        $res = Common::httpPost($url, $data);

        if (empty($res)) {
            return null;
        }
        $arr = json_decode($res, true);
        return $arr;
    }
}
