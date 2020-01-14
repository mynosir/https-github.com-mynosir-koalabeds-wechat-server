<?php
/**
 * 首页控制器
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class home extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $data['resource_url'] = $this->resource_url;
        $data['base_url'] = $this->config->item('base_url');
        $this->data = $data;
    }


    public function index() {
        $mydata = file_get_contents("php://input");
        if(isset($_GET['echostr']) && $_GET['echostr']) {
            // 如果发来了echostr则进行验证
            $this->checkSignature();
        } else {
            if(!!$mydata) {
                // 如果没有echostr，则返回消息
                $this->responseMsg();
            } else {
                echo 'hello';
            }
        }
    }


    public function getAccessToken() {
        return;
        $this->load->model('cloudbeds_hotel_model');
        $result = $this->cloudbeds_hotel_model->update_cloudbeds_access_token();
        echo json_encode($result);
    }


    public function fetchServerAccessToken() {
        return;
        $result = json_decode($this->https_request('https://koalabeds-server.kakaday.com/home/getAccessToken'), true);
        if($result['status'] == 0) {
            $this->load->model('cloudbeds_access_token_model');
            $this->cloudbeds_access_token_model->saveDevAccessToken($result['data']['access_token']);
            echo '同步服务器cloudbeds access token成功==> ' . $result['data']['access_token'];
        } else {
            echo json_encode($result);
        }
    }


    public function getHotels() {
        $curl = curl_init();
        $access_token = 'qciYx4Quogmz890bq2QK80knzeUwDcA0rQWOr3Jf';
        $url = 'https://hotels.cloudbeds.com/api/v1.1/getHotels';
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($curl), true);
        curl_close($curl);
        var_dump($output);
    }


    public function getHotelDetails() {
        $curl = curl_init();
        $access_token = 'FkqeKbMe7vZxyc9Ymoanc5YoRuFD1MC9QDk2ojvR';
        $url = 'https://hotels.cloudbeds.com/api/v1.1/getHotelDetails?propertyID=170048';
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($curl), true);
        curl_close($curl);
        var_dump($output);
    }


    public function getRoomTypes() {
        $curl = curl_init();
        $access_token = 'EVseDSeZKG3cUjeJr3Nm24u6V3AYIwNvCoMKe9Xb';
        $url = 'https://hotels.cloudbeds.com/api/v1.1/getRoomTypes?propertyIDs=173267&roomTypeID=215200';
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($curl), true);
        curl_close($curl);
        var_dump($output);
    }


    public function getAvailableRoomTypes() {
        $curl = curl_init();
        $access_token = 'U8eWgFwT5rdu7pYDrsOkRcohhYwcP3JBxvmqdJaZ';
        $url = 'https://hotels.cloudbeds.com/api/v1.1/getAvailableRoomTypes?propertyIDs=172068&startDate=2019-11-29&endDate=2019-11-30&rooms=1';
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($curl), true);
        curl_close($curl);
        var_dump($output);
    }


    public function getRooms() {
        $curl = curl_init();
        $access_token = 'vd0YIi16dqLsaQHUy35VoCIN8fhSmepyhdN8wX3g';
        $url = 'https://hotels.cloudbeds.com/api/v1.1/getRooms?propertyIDs=170048';
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($curl), true);
        curl_close($curl);
        var_dump($output);
    }


    public function getRate() {
        $curl = curl_init();
        $access_token = '2tBHitaksLZbyTseYm2qr1yRRrQRM9rUmjkUBo3Y';
        $url = 'https://hotels.cloudbeds.com/api/v1.1/getRate?roomTypeID=208676&startDate=2019-11-17&endDate=2019-11-18';
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($curl), true);
        curl_close($curl);
        var_dump($output);
    }


    public function postReservation() {
        $curl = curl_init();
        $access_token = '2tBHitaksLZbyTseYm2qr1yRRrQRM9rUmjkUBo3Y';
        $url = 'https://hotels.cloudbeds.com/api/v1.1/postReservation';
        $data = array(
            'propertyID'    =>  173267,
            'startDate'     => '2019-11-18',
            'endDate'       => '2019-11-19',
            'guestFirstName'    => '123',
            'guestLastName' => '123',
            'guestCountry'  => 'CN',
            'guestZip'      => '000000',
            'guestEmail'    => '361789273@qq.com',
            'rooms' => array(array(
                'roomTypeID'=> 208676,
                'quantity'  => 1,
                'rate'      => 100
            )),
            'adults'    => array(array(
                'roomTypeID'=> 208676,
                'quantity'  => 1,
                'rate'      => 100
            )),
            'children'  => array(array(
                'roomTypeID'=> 208676,
                'quantity'  => 0,
                'rate'      => 100
            )),
            'paymentMethod' => 'cash'
        );
        // array(13) { ["success"]=> bool(true) ["reservationID"]=> string(12) "842706099534" ["status"]=> string(9) "confirmed" ["guestID"]=> int(26820944) ["guestFirstName"]=> string(6) "zequan" ["guestLastName"]=> string(3) "lin" ["guestGender"]=> string(3) "N/A" ["guestEmail"]=> string(16) "361789273@qq.com" ["startDate"]=> string(10) "2019-11-10" ["endDate"]=> string(10) "2019-11-13" ["dateCreated"]=> string(19) "2019-11-07 15:52:29" ["grandTotal"]=> int(900) ["unassigned"]=> array(1) { [0]=> array(7) { ["subReservationID"]=> string(12) "842706099534" ["roomTypeName"]=> string(29) "4 Guests Ensuite with Windows" ["roomTypeID"]=> int(197686) ["adults"]=> int(1) ["children"]=> int(0) ["dailyRates"]=> array(3) { [0]=> array(2) { ["date"]=> string(10) "2019-11-10" ["rate"]=> int(300) } [1]=> array(2) { ["date"]=> string(10) "2019-11-11" ["rate"]=> int(300) } [2]=> array(2) { ["date"]=> string(10) "2019-11-12" ["rate"]=> int(300) } } ["roomTotal"]=> int(900) } } }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($curl), true);
        curl_close($curl);
        var_dump($output);
    }


    public function postPayment() {
        $curl = curl_init();
        $access_token = 'fytdUbjUYgCXN1z4B07dPkcDbUWDGd3EjxGULhaB';
        $url = 'https://hotels.cloudbeds.com/api/v1.1/postPayment';
        $data = array(
            'propertyID'        => 172068,
            'reservationID'     => '267985683407',
            'type'              => 'Paid at another location.',
            'amount'            => 430,
            'description'       => 'from Koalabeds mini program'
            // 'cardType'          => 'visa'
        );
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($curl), true);
        curl_close($curl);
        var_dump($output);
    }


    public function getPaymentMethods() {
        $curl = curl_init();
        $access_token = 'SnzJW8ZZy5nP2qOKP8SkdSdotqW8L4zabfYl4RoL';
        $url = 'https://hotels.cloudbeds.com/api/v1.1/getPaymentMethods?propertyID=173267';
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($curl), true);
        curl_close($curl);
        var_dump($output);
    }


    public function pay() {
        $params = array(
            'service'   => 'pay.weixin.jspay',
            'body'      => '支付测试',
            'mch_id'   => '104530000126',
            'is_raw'    => '1',
            'out_trade_no'  => '1409196838',
            'sub_openid'    => 'oLq-f4iaG_zt3onbC8lzZ4ODht-c',
            'sub_appid' => 'wx18cda3bfbb701cb7',
            'total_fee' => '1',
            'mch_create_ip' => '127.0.0.1',
            'notify_url'    => 'https://koalabeds-server.kakaday.com/',
            'nonce_str' => '1409196838'
        );
        $sign = $this->getSign($params, '97a36c5b28ecb6dbe194c45ebc00f46f');
        $params['sign'] = $sign;
        $xml = $this->arrayToXml($params);
        $url = 'https://gateway.wepayez.com/pay/gateway';

        $responseXml = $this->curlPost($url, $xml);
        // 禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $unifiedOrder = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
        var_dump($unifiedOrder);
    }


    public function sms() {
        $curl = curl_init();
        $url = 'https://rest.nexmo.com/sms/json';
        $data = array(
            'api_key'   => '9ba1d919',
            'api_secret'=> 'w11QEE6KgFq1xHYW',
            'to'        => '8618665953630',
            'from'      => 'koalabeds',
            'text'      => '【koalabeds】1234',
            'type'      => 'unicode'
        );
        curl_setopt($curl, CURLOPT_URL, $url);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, TRUE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        var_dump($output);
    }

    public function curlPost($url = '', $postData = '', $options = array()) {
        if(is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if(!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        // https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function getSign($params, $key) {
        ksort($params, SORT_STRING);
        $unSignParaString = self::formatQueryParaMap($params, false);
        $signStr = strtoupper(md5($unSignParaString . "&key=" . $key));
        return $signStr;
    }
    protected static function formatQueryParaMap($paraMap, $urlEncode = false) {
        $buff = '';
        ksort($paraMap);
        foreach($paraMap as $k => $v) {
            if(null != $v && "null" != $v) {
                if($urlEncode) {
                    $v = urlencode($v);
                }
                $buff .= $k . "=" . $v . "&";
            }
        }
        $reqPar = '';
        if(strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }


    public function arrayToXml($arr) {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if(is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }


    public function test1() {
        var_dump('hello');
    }


    public function get() {
        $actionxm = $this->get_request('actionxm');
        $result = array();
        switch($actionxm) {
        }
        echo json_encode($result);
    }


    public function post() {
        $actionxm = $this->get_request('actionxm');
        $result = array();
        switch($actionxm) {
        }
        echo json_encode($result);
    }

}
