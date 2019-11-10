<?php
/**
 * 接口控制器
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class api extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $data['resource_url'] = $this->resource_url;
        $data['base_url'] = $this->config->item('base_url');
        $this->data = $data;
    }


    public function index() {
        echo 'hello api';
    }


    public function get() {
        $actionxm = $this->get_request('actionxm');
        $result = array();
        switch($actionxm) {
            // 获取首页横幅推荐
            case 'getRecommend':
                $this->load->model('cloudbeds_hotel_model');
                $type = $this->get_request('type');
                $num = $this->get_request('num', 10);
                $result = $this->cloudbeds_hotel_model->getRecommend($type, $num);
                break;
            // 获取首页推荐酒店瀑布流
            case 'getRecommendFlow':
                $this->load->model('cloudbeds_hotel_model');
                $page = $this->get_request('page', 1);
                $num = $this->get_request('num', 10);
                $result = $this->cloudbeds_hotel_model->getRecommendFlow($page, $num);
                break;
            // 获取房间类型
            case 'getRoomTypes':
                $this->load->model('cloudbeds_hotel_model');
                $propertyIDs = $this->get_request('propertyIDs');
                $result = $this->cloudbeds_hotel_model->getRoomTypes($propertyIDs);
                break;
            // 获取轮播图
            case 'getBanners':
                $this->load->model('banner_model');
                $result = $this->banner_model->getList();
                break;
            // 获取优惠券配置信息
            case 'getCoupons':
                $this->load->model('coupon_model');
                $result = $this->coupon_model->getList();
                break;
            // 获取城市列表
            case 'getCitys':
                $this->load->model('cloudbeds_hotel_model');
                $result = $this->cloudbeds_hotel_model->getCitys();
                break;
            // 酒店搜索
            case 'searchHotels':
                $this->load->model('cloudbeds_hotel_model');
                $params['city'] = $this->get_request('city', '');                     // 酒店所在城市
                $params['checkInDate'] = $this->get_request('checkInDate', '');       // 入住日期
                $params['checkOutDate'] = $this->get_request('checkOutDate', '');     // 离店日期
                $params['hotelName'] = $this->get_request('hotelName', '');           // 酒店名称
                $params['moneySort'] = $this->get_request('moneySort', 0);            // 价格排序
                $params['rankSort'] = $this->get_request('rankSort', 0);              // 评价排序
                $params['priceStart'] = $this->get_request('priceStart', 0);          // 价格区间开始
                $params['priceEnd'] = $this->get_request('priceEnd', 0);              // 价格区间结束
                $params['rank'] = $this->get_request('rank', 0);                      // 评价星数，0为全部
                $params['openid'] = $this->get_request('openid');                     // 用户微信openid
                $result = $this->cloudbeds_hotel_model->searchHotels($params);
                break;
            // 获取酒店详情
            case 'getHotel':
                $this->load->model('cloudbeds_hotel_model');
                $propertyID = $this->get_request('propertyID', 0);
                $result = $this->cloudbeds_hotel_model->getHotelDetailsInDB($propertyID);
                break;
            // 获取房间列表
            case 'getRoomsByHotelId':
                $this->load->model('cloudbeds_hotel_model');
                $propertyID = $this->get_request('propertyID', 0);
                $checkInDate = $this->get_request('checkInDate', '');       // 入住日期
                $checkOutDate = $this->get_request('checkOutDate', '');     // 离店日期
                $result = $this->cloudbeds_hotel_model->getAvailableRoomTypes($propertyID, $checkInDate, $checkOutDate);
                break;
            // 获取openid
            case 'getOpenid':
                $code = $this->get_request('code');         // 小程序传过来的code值
                $this->load->config('customer');
                $wechatConfig = $this->config->item('wechat');
                $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $wechatConfig['appId'] . '&secret=' . $wechatConfig['appSecret'] . '&js_code=' . $code . '&grant_type=authorization_code';
                $wechatResult = file_get_contents($url);
                $wechatResultObj = json_decode($wechatResult, true);
                // var_dump($resultObj);
                if(empty($wechatResultObj)) {
                    $result = array(
                        'status'    => -1,
                        'msg'       => '获取openid异常，微信内部错误'
                    );
                } else {
                    $loginFail = array_key_exists('errcode', $wechatResultObj);
                    if($loginFail) {
                        $result = array(
                            'status'    => -2,
                            'msg'       => '请求失败',
                            'ext'       => $wechatResult
                        );
                    } else {
                        $result = array(
                            'status'    => 0,
                            'msg'       => '请求成功',
                            'data'      => $wechatResultObj['openid']
                        );
                    }
                }
                break;
            // 获取grayline国家列表
            case 'getGraylineNationalityList':
                $this->load->model('grayline_ticket_model');
                $language = $this->get_request('language', 'en');
                $result = $this->grayline_ticket_model->getNationalityList($language);
                break;
            // 获取grayline产品列表
            case 'getGraylineProductList':
                $this->load->model('grayline_ticket_model');
                $language = $this->get_request('language', 'en');
                $type = $this->get_request('type', '');
                $result = $this->grayline_ticket_model->getProductList($language, $type);
                break;
            // 获取grayline产品详情
            case 'getGraylineProductDetails':
                $this->load->model('grayline_ticket_model');
                $type = $this->get_request('type', '');
                $productId = $this->get_request('productId');
                $result = $this->grayline_ticket_model->getProductDetails($type, $productId);
                break;
            // 查询grayline产品
            case 'queryGraylineProduct':
                $this->load->model('grayline_ticket_model');
                $type = $this->get_request('type', '');
                $productId = $this->get_request('productId');
                $date = $this->get_request('date');
                $travelTime = $this->get_request('travelTime');
                $turbojetDepartureDate = $this->get_request('turbojetDepartureDate');
                $turbojetReturnDate = $this->get_request('turbojetReturnDate');
                $turbojetDepartureTime = $this->get_request('turbojetDepartureTime');
                $turbojetReturnTime = $this->get_request('turbojetReturnTime');
                $turbojetDepartureFrom = $this->get_request('turbojetDepartureFrom');
                $turbojetDepartureTo = $this->get_request('turbojetDepartureTo');
                $turbojetReturnFrom = $this->get_request('turbojetReturnFrom');
                $turbojetReturnTo = $this->get_request('turbojetReturnTo');
                $turbojetQuantity = $this->get_request('turbojetQuantity');
                $turbojetClass = $this->get_request('turbojetClass');
                $subQtyProductPriceId = $this->get_request('subQtyProductPriceId');
                $result = $this->grayline_ticket_model->queryProduct($type, $productId, $date, $travelTime, $turbojetDepartureDate, $turbojetReturnDate, $turbojetDepartureTime, $turbojetReturnTime, $turbojetDepartureFrom, $turbojetDepartureTo, $turbojetReturnFrom, $turbojetReturnTo, $turbojetQuantity, $turbojetClass, $subQtyProductPriceId);
                break;
        }
        echo json_encode($result);
    }


    public function post() {
        $actionxm = $this->get_request('actionxm');
        $result = array();
        switch($actionxm) {
            // 保存用户信息
            case 'saveUserinfo':
                $openid = $this->get_request('openid');
                $userinfo = $this->get_request('userinfo', '');
                $this->load->model('user_model');
                $result = $this->user_model->saveUserinfo($openid, $userinfo);
                break;
            // 保存语音
            case 'updateLang':
                $openid = $this->get_request('openid');
                $lang = $this->get_request('lang', 'en');
                $this->load->model('user_model');
                $result = $this->user_model->updateLang($openid, $lang);
                break;
            // 用户获取优惠券
            case 'getUserCoupon':
                $openid = $this->get_request('openid');
                $ids = $this->get_request('ids');
                $this->load->model('coupon_model');
                $result = $this->coupon_model->getUserCoupon($openid, $ids);
                break;
            // 下订单
            case 'saveOrder':
                $openid = $this->get_request('openid');
                $id = $this->get_request('id');
                $this->load->model('hotel_order_model');
                $result = $this->hotel_order_model->saveOrder($openid, $id);
                break;
            // 获取支付参数
            case 'getPay':
                $params = json_decode($this->get_request('params'), true);
                $propertyID = isset($params['propertyID']) ? $params['propertyID'] : 0;
                if(!isset($params['openid'])) {
                    $result = array(
                        'status'    => -2,
                        'msg'       => '登录态异常'
                    );
                } else {
                    // 通过酒店id获取酒店名称
                    $this->load->model('cloudbeds_hotel_model');
                    $hotelInfo = $this->cloudbeds_hotel_model->getHotelDetailsInDB($propertyID);
                    if($hotelInfo['status'] != 0) {
                        $result = array(
                            'status'    => -1,
                            'msg'       => '酒店信息查询异常'
                        );
                    } else {
                        $hotelDetail = $hotelInfo['data'];
                        // 生成订单号
                        $outTradeNo = substr('cloudbeds' . date('YmdHis', time()) . uniqid(), 0, 32); // 商品订单号
                        // 保存订单
                        $data = array(
                            'service'       => 'pay.weixin.jspay',
                            'body'          => $hotelDetail['propertyName'],
                            'mch_id'        => '104530000126',
                            'is_raw'        => '1',
                            'out_trade_no'  => $outTradeNo,
                            'sub_openid'    => $params['openid'],
                            'sub_appid'     => 'wx18cda3bfbb701cb7',
                            'total_fee'     => '1',
                            'mch_create_ip' => '127.0.0.1',
                            'notify_url'    => 'https://koalabeds-server.kakaday.com/paycallback',
                            'nonce_str'     => '1409196838'
                        );
                        $sign = $this->getSign($data, '97a36c5b28ecb6dbe194c45ebc00f46f');
                        $data['sign'] = $sign;
                        $xml = $this->arrayToXml($data);
                        $url = 'https://gateway.wepayez.com/pay/gateway';
                        $responseXml = $this->curlPost($url, $xml);
                        // 禁止引用外部xml实体
                        libxml_disable_entity_loader(true);
                        $unifiedOrder = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
                        // 保存订单
                        $this->load->model('hotel_order_model');
                        $orderParams = array(
                            'openid'        => $params['openid'],
                            'propertyID'    => $params['propertyID'],
                            'startDate'     => $params['startDate'],
                            'endDate'       => $params['endDate'],
                            'guestFirstName'=> $params['guestFirstName'],
                            'guestLastName' => $params['guestLastName'],
                            'guestCountry'  => $params['guestCountry'],
                            'guestZip'      => $params['guestZip'],
                            'guestEmail'    => $params['guestEmail'],
                            'guestPhone'    => $params['guestPhone'],
                            'rooms'         => $params['rooms'],
                            'adults'        => $params['adults'],
                            'children'      => $params['children'],
                            'frontend_total'=> $params['frontend_total'],
                            'outTradeNo'    => $outTradeNo
                        );
                        $orderSaveResult = $this->hotel_order_model->generateOrder($orderParams);
                        if($orderSaveResult['status'] != 0) {
                            $result = array(
                                'status'    => -3,
                                'msg'       => '保存订单异常'
                            );
                        } else {
                            $result = array(
                                'status'    => 0,
                                'msg'       => '获取成功',
                                'data'      => $unifiedOrder
                            );
                        }
                    }
                }
                break;
            // 获取Grayline支付参数
            case 'getGraylinePay':
                $params = json_decode($this->get_request('params'), true);
                if(!isset($params['openid'])) {
                    $result = array(
                        'status'    => -2,
                        'msg'       => '登录态异常'
                    );
                } else {
                    // 生成订单号
                    $outTradeNo = substr('grayline' . date('YmdHis', time()) . uniqid(), 0, 32); // 商品订单号
                    // 保存订单
                    $data = array(
                        'service'       => 'pay.weixin.jspay',
                        'body'          => 'Ticket Order',
                        'mch_id'        => '104530000126',
                        'is_raw'        => '1',
                        'out_trade_no'  => $outTradeNo,
                        'sub_openid'    => $params['openid'],
                        'sub_appid'     => 'wx18cda3bfbb701cb7',
                        'total_fee'     => '1',
                        'mch_create_ip' => '127.0.0.1',
                        'notify_url'    => 'https://koalabeds-server.kakaday.com/paycallbackGrayline',
                        'nonce_str'     => '1409196838'
                    );
                    $sign = $this->getSign($data, '97a36c5b28ecb6dbe194c45ebc00f46f');
                    $data['sign'] = $sign;
                    $xml = $this->arrayToXml($data);
                    $url = 'https://gateway.wepayez.com/pay/gateway';
                    $responseXml = $this->curlPost($url, $xml);
                    // 禁止引用外部xml实体
                    libxml_disable_entity_loader(true);
                    $unifiedOrder = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
                    // 保存订单
                    $this->load->model('grayline_ticket_model');
                    $orderParams = array(
                        'openid'        => $params['openid'],
                        'type'          => $params['type'],
                        'productId'     => $params['productId'],
                        'travelDate'    => isset($params['date']) ? $params['date'] : '',
                        'travelTime'    => isset($params['travelTime']) ? $params['travelTime'] : '',
                        'turbojetDepartureDate' => isset($params['turbojetDepartureDate']) ? $params['turbojetDepartureDate'] : '',
                        'turbojetReturnDate'    => isset($params['turbojetReturnDate']) ? $params['turbojetReturnDate'] : '',
                        'turbojetDepartureTime' => isset($params['turbojetDepartureTime']) ? $params['turbojetDepartureTime'] : '',
                        'turbojetReturnTime'    => isset($params['turbojetReturnTime']) ? $params['turbojetReturnTime'] : '',
                        'turbojetDepartureFrom' => isset($params['turbojetDepartureFrom']) ? $params['turbojetDepartureFrom'] : '',
                        'turbojetDepartureTo'   => isset($params['turbojetDepartureTo']) ? $params['turbojetDepartureTo'] : '',
                        'turbojetReturnFrom'    => isset($params['turbojetReturnFrom']) ? $params['turbojetReturnFrom'] : '',
                        'turbojetReturnTo'      => isset($params['turbojetReturnTo']) ? $params['turbojetReturnTo'] : '',
                        'turbojetQuantity'      => isset($params['turbojetQuantity']) ? $params['turbojetQuantity'] : '',
                        'turbojetClass'         => isset($params['turbojetClass']) ? $params['turbojetClass'] : '',
                        'turbojetTicketType'    => isset($params['turbojetTicketType']) ? $params['turbojetTicketType'] : '',
                        'turbojetDepartureFlightNo' => isset($params['turbojetDepartureFlightNo']) ? $params['turbojetDepartureFlightNo'] : '',
                        'turbojetReturnFlightNo'    => isset($params['turbojetReturnFlightNo']) ? $params['turbojetReturnFlightNo'] : '',
                        'hotel'         => isset($params['hotel']) ? $params['hotel'] : '',
                        'title'         => isset($params['title']) ? $params['title'] : '',
                        'firstName'     => isset($params['firstName']) ? $params['firstName'] : '',
                        'lastName'      => isset($params['lastName']) ? $params['lastName'] : '',
                        'passport'      => isset($params['passport']) ? $params['passport'] : '',
                        'guestEmail'    => isset($params['guestEmail']) ? $params['guestEmail'] : '',
                        'countryCode'   => isset($params['countryCode']) ? $params['countryCode'] : '',
                        'telephone'     => isset($params['telephone']) ? $params['telephone'] : '',
                        'promocode'     => isset($params['promocode']) ? $params['promocode'] : '',
                        'agentReference'=> isset($params['agentReference']) ? $params['agentReference'] : '',
                        'remark'        => isset($params['remark']) ? $params['remark'] : '',
                        'subQtyProductPriceId'      => isset($params['subQtyProductPriceId']) ? $params['subQtyProductPriceId'] : '',
                        'subQtyValue'   => isset($params['subQtyValue']) ? $params['subQtyValue'] : '',
                        'totalPrice'    => isset($params['totalPrice']) ? $params['totalPrice'] : '',
                        'info'          => isset($params['info']) ? $params['info'] : '',
                        'orderParamsDetail'     => json_encode($params),
                        'outTradeNo'    => $outTradeNo
                    );
                    $orderSaveResult = $this->grayline_ticket_model->generateOrder($orderParams);
                    if($orderSaveResult['status'] != 0) {
                        $result = array(
                            'status'    => -3,
                            'msg'       => '保存订单异常'
                        );
                    } else {
                        $result = array(
                            'status'    => 0,
                            'msg'       => '获取成功',
                            'data'      => $unifiedOrder
                        );
                    }
                }
                break;
            // 预订grayline产品
            case 'orderProduct':
                $openid = $this->get_request('openid');
                $id = $this->get_request('id');
                $this->load->model('grayline_ticket_model');
                $result = $this->grayline_ticket_model->orderProduct($openid, $id);
                break;
        }
        echo json_encode($result);
    }


    public function paycallbackGrayline() {
        $result = $this->notify();
        @file_put_contents('/pub/logs/paycallbackGrayline', '[' . date('Y-m-d H:i:s', time()) . '](' . json_encode($result) . PHP_EOL, FILE_APPEND);
        // 收到支付回调，判断支付成功的话，将订单状态置为1
        if($result) {
            $this->load->model('grayline_ticket_model');
            $this->grayline_ticket_model->update_transaction_info($result['out_trade_no'], json_encode($result));
            if($result['result_code'] == 'SUCCESS') {
                $this->grayline_ticket_model->updateOrderStatus($result['out_trade_no'], 1);
            } else {
                $this->grayline_ticket_model->updateOrderStatus($result['out_trade_no'], 2);
            }
        }
    }


    public function paycallback() {
        $result = $this->notify();
        // {"appid":"wxbb38e532bce13768","attach":"pay","bank_type":"PAB_CREDIT","cash_fee":"1","fee_type":"CNY","is_subscribe":"Y","mch_id":"1558979671","nonce_str":"iV0qBhKWXmop6Orw","openid":"otVuH1JP-Aj9FStCDNzbUOuq9RKk","out_trade_no":"gkmj_20191024211023_5db1a2bf2ffa","result_code":"SUCCESS","return_code":"SUCCESS","time_end":"20191024211031","total_fee":"1","trade_type":"JSAPI","transaction_id":"4200000442201910247202565485"}
        @file_put_contents('/pub/logs/paycallback', '[' . date('Y-m-d H:i:s', time()) . '](' . json_encode($result) . PHP_EOL, FILE_APPEND);
        // 收到支付回调，判断支付成功的话，将订单状态置为1
        if($result) {
            $this->load->model('hotel_order_model');
            $this->hotel_order_model->update_transaction_info($result['out_trade_no'], json_encode($result));
            if($result['result_code'] == 'SUCCESS') {
                $this->hotel_order_model->update_status($result['out_trade_no'], 1);
            } else {
                $this->hotel_order_model->update_status($result['out_trade_no'], 2);
            }
        }
    }


    public function notify() {
        $config = array(
            'mch_id'    => '104530000126',
            'appid'     => 'wx18cda3bfbb701cb7',
            'key'       => '97a36c5b28ecb6dbe194c45ebc00f46f'
        );
        $postStr = file_get_contents('php://input');
        // 禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        @file_put_contents('/pub/logs/paynotify', '[' . date('Y-m-d H:i:s', time()) . '](' . json_encode($postObj) . PHP_EOL, FILE_APPEND);
        // if($postObj === false) {
        //     $this->load->model('errorlog_model');
        //     $params = array(
        //         'content'       => 'xml解析异常(parse xml error)',
        //         'create_time'   => time()
        //     );
        //     $this->errorlog_model->add($params);
        //     return false;
        // }
        // if($postObj->return_code != 'SUCCESS') {
        //     $this->load->model('errorlog_model');
        //     $params = array(
        //         'content'       => json_encode($postObj),
        //         'create_time'   => time()
        //     );
        //     $this->errorlog_model->add($params);
        //     return false;
        // }
        // if($postObj->result_code != 'SUCCESS') {
        //     $this->load->model('errorlog_model');
        //     $params = array(
        //         'content'       => json_encode($postObj),
        //         'create_time'   => time()
        //     );
        //     $this->errorlog_model->add($params);
        //     return false;
        // }
        $arr = (array)$postObj;
        unset($arr['sign']);
        if(self::getSign($arr, $config['key']) == $postObj->sign) {
            echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
            return $arr;
        }
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

}
