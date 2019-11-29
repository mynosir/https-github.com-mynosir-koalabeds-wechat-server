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
                $openid = $this->get_request('openid');
                $result = $this->cloudbeds_hotel_model->getRecommend($type, $num, $openid);
                break;
            // 获取首页推荐酒店瀑布流
            case 'getRecommendFlow':
                $this->load->model('cloudbeds_hotel_model');
                $page = $this->get_request('page', 1);
                $num = $this->get_request('num', 10);
                $openid = $this->get_request('openid');
                $result = $this->cloudbeds_hotel_model->getRecommendFlow($page, $num, $openid);
                break;
            // 获取房间类型
            case 'getRoomTypes':
                $this->load->model('cloudbeds_hotel_model');
                $propertyIDs = $this->get_request('propertyIDs');
                $openid = $this->get_request('openid');
                $result = $this->cloudbeds_hotel_model->getRoomTypes($propertyIDs, $openid);
                break;
            // 获取轮播图
            case 'getBanners':
                $this->load->model('banner_model');
                $result = $this->banner_model->getList();
                break;
            // 获取用户语言配置
            case 'getLang':
                $openid = $this->get_request('openid');
                $this->load->model('user_model');
                $userinfo = $this->user_model->getUserinfoByOpenid($openid);
                if(!!$userinfo) {
                    $result = array(
                        'status'    => 0,
                        'msg'       => '查询成功',
                        'data'      => array(
                            'lang'  => $userinfo['lang']
                        )
                    );
                } else {
                    $result = array(
                        'status'    => -1,
                        'msg'       => '未找到该用户'
                    );
                }
                break;
            // 获取优惠券配置信息
            case 'getCoupons':
                $openid = $this->get_request('openid');
                $this->load->model('coupon_model');
                $result = $this->coupon_model->getList($openid);
                break;
            // 获取用户优惠券列表
            case 'getCouponByOpenid':
                $openid = $this->get_request('openid');
                $this->load->model('coupon_model');
                $list = $this->coupon_model->getUserCouponRecordForFront($openid);
                $result = array(
                    'status'    => 0,
                    'msg'       => '查询成功',
                    'data'      => $list
                );
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
                $openid = $this->get_request('openid');
                $result = $this->cloudbeds_hotel_model->getHotelDetailsInDB($propertyID, $openid);
                break;
            // 获取房间列表
            case 'getRoomsByHotelId':
                $this->load->model('cloudbeds_hotel_model');
                $propertyID = $this->get_request('propertyID', 0);
                $checkInDate = $this->get_request('checkInDate', '');       // 入住日期
                $checkOutDate = $this->get_request('checkOutDate', '');     // 离店日期
                $adults = $this->get_request('adults', 1);                  // 大人数量
                $children = $this->get_request('children', 0);              // 小孩数量
                $openid = $this->get_request('openid');
                $result = $this->cloudbeds_hotel_model->getAvailableRoomTypes($propertyID, $checkInDate, $checkOutDate, $adults, $children, $openid);
                break;
            // 获取评论列表
            case 'getReviews':
                $propertyID = $this->get_request('propertyID', 0);
                $page = $this->get_request('page', 1);
                $num = $this->get_request('num', 10);
                $this->load->model('reviews_model');
                $result = $this->reviews_model->getReviewsList($propertyID, $page, $num);
                break;
            // 根据订单id和openid获取评论列表
            case 'getReviewsByOrderIdAndOpenid':
                $orderId = $this->get_request('orderId');
                $openid = $this->get_request('openid');
                $this->load->model('reviews_model');
                $result = $this->reviews_model->getReviewsByOrderIdAndOpenid($orderId, $openid);
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
                $language = $this->get_request('language', 'en');
                $type = $this->get_request('type', '');
                $productId = $this->get_request('productId');
                $result = $this->grayline_ticket_model->getProductDetails($type, $productId, $language);
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
            // 获取短信验证码
            case 'sendSmscode':
                $phone = $this->get_request('phone');
                $this->load->model('smscode_model');
                $code = $this->smscode_model->generateCode(4);
                $url = 'https://rest.nexmo.com/sms/json';
                $data = array(
                    'api_key'   => '9ba1d919',
                    'api_secret'=> 'w11QEE6KgFq1xHYW',
                    'to'        => $phone,
                    'from'      => 'koalabeds',
                    'text'      => '"code: ' . $code . '"'
                );
                $sendStatus = $this->curlPost($url, $data);
                $sendStatusObj = json_decode($sendStatus, true);
                if($sendStatusObj['messages'][0]['status'] != 0) {
                    $result = array(
                        'status'    => -1,
                        'msg'       => '短信验证码发送失败',
                        'ext'       => $sendStatus
                    );
                } else {
                    $saveStatus = $this->smscode_model->save($phone, $code, $sendStatus);
                    if($saveStatus['status'] != 0) {
                        $result = array(
                            'status'    => -1,
                            'msg'       => '获取短信验证码失败',
                            'ext'       => $saveStatus
                        );
                    } else {
                        $result = array(
                            'status'    => 0,
                            'msg'       => '获取短信验证码成功'
                        );
                    }
                }
                break;
            // 根据订单id获取订单详情
            case 'getHotelOrderById':
                $id = $this->get_request('id');
                $openid = $this->get_request('openid');
                if(!isset($openid)) {
                    $result = array(
                        'status'    => -2,
                        'msg'       => '登录态异常'
                    );
                } else {
                    $this->load->model('hotel_order_model');
                    $result = $this->hotel_order_model->getDetailById($id);
                }
                break;
            // 根据房间id获取房间信息
            case 'getRoomTypeById':
                $propertyID = $this->get_request('propertyID');
                $roomTypeID = $this->get_request('roomTypeID');
                $this->load->model('cloudbeds_hotel_model');
                $result = $this->cloudbeds_hotel_model->getRoomTypesByRoomTypeIDs($propertyID, $roomTypeID);
                break;
            // 获取酒店订单列表
            case 'getHotelOrders':
                $openid = $this->get_request('openid');
                $status = $this->get_request('status', -2);
                $this->load->model('hotel_order_model');
                $result = $this->hotel_order_model->getListByOpenid($openid, $status);
                break;
            // 获取门票订单列表
            case 'getTicketOrders':
                $openid = $this->get_request('openid');
                $this->load->model('grayline_ticket_model');
                $result = $this->grayline_ticket_model->getOrderList($openid);
                break;
            // 通过门票订单id获取订单详情
            case 'getTicketOrderById':
                $openid = $this->get_request('openid');
                $id = $this->get_request('id');
                $this->load->model('grayline_ticket_model');
                $result = $this->grayline_ticket_model->getOrderById($openid, $id);
                break;
            // 从数据库中获取酒店列表
            case 'getHotelListInDB':
                $this->load->model('cloudbeds_hotel_model');
                $result = $this->cloudbeds_hotel_model->getHotelListInDB();
                break;
            // 通过门票订单id和openid获取订单信息
            case 'getTicketByOrderIdAndOpenid':
                $orderId = $this->get_request('orderId');
                $openid = $this->get_request('openid');
                $this->load->model('grayline_ticket_model');
                $result = $this->grayline_ticket_model->getTicketByOrderIdAndOpenid($orderId, $openid);
                break;
            // 获取房间费率和总价
            case 'getRoomsFeesAndTaxes':
                $startDate = $this->get_request('startDate');
                $endDate = $this->get_request('endDate');
                $frontend_total = $this->get_request('frontend_total');
                $rooms_quantity = $this->get_request('rooms_quantity');
                $propertyID = $this->get_request('propertyID');
                $this->load->model('cloudbeds_hotel_model');
                $result = $this->cloudbeds_hotel_model->getRoomsFeesAndTaxes($startDate, $endDate, $frontend_total, $rooms_quantity, $propertyID);
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
            // 保存语言
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
            // 取消订单
            case 'cancelOrder':
                $id = $this->get_request('id');
                $this->load->model('hotel_order_model');
                $result = $this->hotel_order_model->updateStatusById($id, -1);
                // // 查询订单信息，进行退款
                // $orderDetail = $this->hotel_order_model->getDetailById($id);
                // $refund = $this->refund($result['data']);
                // if($refund['status'] == 0) {
                //     $result = $this->hotel_order_model->updateStatusById($id, -1);
                // } else {
                //     $result = array(
                //         'status'    => -1,
                //         'msg'       => '退款失败',
                //         'ext'       => $refund
                //     );
                // }
                break;
            // 下订单
            case 'saveOrder':
                $openid = $this->get_request('openid');
                $id = $this->get_request('id');
                $this->load->model('hotel_order_model');
                $result = $this->hotel_order_model->saveOrder($openid, $id);
                // 发起退款
                if($result['status'] != 0) {
                    $result['refund'] = $this->refund($result['data']);
                }
                break;
            // 获取支付参数
            case 'getPay':
                // $params = '{"openid":"oLq-f4hAXvgLDKvkfzslJaLIqs6A","propertyID":"171327","startDate":"2019-11-18","endDate":"2019-11-19","guestFirstName":"mp","guestLastName":"mp","guestCountry":"CN","guestZip":"000000","guestEmail":"mptest","guestPhone":"mptest","rooms":{"roomTypeID":205417,"quantity":1,"roomTypeName":"Deluxe Double Room","rate":190},"rooms_roomTypeName":"Deluxe Double Room","rooms_roomTypeImg":"https:\/\/h-img3.cloudbeds.com\/uploads\/171327\/l1000190_thumb~~5db2b05ac9259.jpg","rooms_roomTypeDesc":"Beautiful deluxe double room in H36 Guesthouse right in the centre of Jordan. <br>","adults":{"roomTypeID":205417,"quantity":10,"rate":190},"children":{"roomTypeID":205417,"quantity":1,"rate":190},"frontend_total":190,"coupon_id":"6","extinfo":""}';
                // $params = json_decode($params, true);
                $params = json_decode($this->get_request('params'), true);
                @file_put_contents('/pub/logs/getPayParams', '[' . date('Y-m-d H:i:s', time()) . '](' . json_encode($params) . PHP_EOL, FILE_APPEND);
                $propertyID = isset($params['propertyID']) ? $params['propertyID'] : 0;
                if(!isset($params['openid'])) {
                    $result = array(
                        'status'    => -2,
                        'msg'       => '登录态异常'
                    );
                } else {
                    // 通过酒店id获取酒店名称
                    $this->load->model('cloudbeds_hotel_model');
                    $hotelInfo = $this->cloudbeds_hotel_model->getHotelDetailsInDB($propertyID, $params['openid']);
                    if($hotelInfo['status'] != 0) {
                        $result = array(
                            'status'    => -1,
                            'msg'       => '酒店信息查询异常'
                        );
                    } else {
                        $hotelDetail = $hotelInfo['data'];
                        // 生成订单号
                        $outTradeNo = substr('cloudbeds' . date('YmdHis', time()) . uniqid(), 0, 32); // 商品订单号
                        $total_fee = $params['frontend_total'];
                        $source_prize = $params['frontend_total'];
                        // 判断是否使用优惠券
                        if(isset($params['coupon_id']) && $params['coupon_id'] > 0) {
                            // 判断优惠券是否可用
                            $this->load->model('coupon_model');
                            $couponStatus = $this->coupon_model->validCoupon($params['openid'], $params['coupon_id']);
                            // 优惠券是否有效
                            if($couponStatus['status'] != 0) {
                                return array(
                                    'status'    => -4,
                                    'msg'       => $couponStatus['msg']
                                );
                            } else {
                                // 优惠券是否满足使用条件
                                if((float)$couponStatus['data']['totalAmount'] > (float)$source_prize) {
                                    return array(
                                        'status'    => -5,
                                        'msg'       => '优惠券不满足使用条件'
                                    );
                                }
                                $total_fee = (float)$source_prize - (float)$couponStatus['data']['discountAmount'];
                            }
                        }
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
                            'notify_url'    => 'https://koalabeds-server.kakaday.com/api/paycallback',
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
                            'outTradeNo'    => $outTradeNo,
                            'total_fee'     => $total_fee,
                            'source_prize'  => $source_prize,
                            'coupon_id'     => isset($params['coupon_id']) ? $params['coupon_id'] : 0,
                            'rooms_roomTypeName'    => $params['rooms_roomTypeName'],
                            'rooms_roomTypeDesc'    => $params['rooms_roomTypeDesc'],
                            'rooms_roomTypeImg'     => $params['rooms_roomTypeImg'],
                            'extinfo'       => $params['extinfo'],
                            'unRoomRate'    => isset($params['unRoomRate']) ? $params['unRoomRate'] : $params['frontend_total']
                        );
                        $orderSaveResult = $this->hotel_order_model->generateOrder($orderParams);
                        if($orderSaveResult['status'] != 0) {
                            $result = array(
                                'status'    => -3,
                                'msg'       => '保存订单异常',
                                'ext'       => $orderSaveResult
                            );
                        } else {
                            $result = array(
                                'status'    => 0,
                                'msg'       => '获取成功',
                                'data'      => array(
                                    'id'    => $orderSaveResult['data']['id'],
                                    'payParams' => $unifiedOrder
                                )
                            );
                        }
                    }
                }
                break;
            // 再次获取支付参数
            case 'getPayAgain':
                $id = $this->get_request('id');
                $openid = $this->get_request('openid');
                if(!isset($openid)) {
                    $result = array(
                        'status'    => -1,
                        'msg'       => '登录态异常'
                    );
                } else {
                    // 通过订单id获取订单信息
                    $this->load->model('hotel_order_model');
                    $orderInfo = $this->hotel_order_model->getDetailById($id);
                    if($orderInfo['status'] != 0) {
                        $result = array(
                            'status'    => -2,
                            'msg'       => '订单信息查询异常'
                        );
                    } else {
                        $orderDetail = $orderInfo['data'];
                        // 通过酒店id获取酒店信息
                        $this->load->model('cloudbeds_hotel_model');
                        $hotelInfo = $this->cloudbeds_hotel_model->getHotelDetailsInDB($orderDetail['propertyID'], $openid);
                        if($hotelInfo['status'] != 0) {
                            $result = array(
                                'status'    => -3,
                                'msg'       => '酒店信息查询异常'
                            );
                        } else {
                            $hotelDetail = $hotelInfo['data'];
                            // 获取支付参数
                            $data = array(
                                'service'       => 'pay.weixin.jspay',
                                'body'          => $hotelDetail['propertyName'],
                                'mch_id'        => '104530000126',
                                'is_raw'        => '1',
                                'out_trade_no'  => $orderDetail['outTradeNo'],
                                'sub_openid'    => $openid,
                                'sub_appid'     => 'wx18cda3bfbb701cb7',
                                'total_fee'     => '1',
                                'mch_create_ip' => '127.0.0.1',
                                'notify_url'    => 'https://koalabeds-server.kakaday.com/api/paycallback',
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
                            $result = array(
                                'status'    => 0,
                                'msg'       => '获取成功',
                                'data'      => array(
                                    'id'    => $id,
                                    'payParams' => $unifiedOrder
                                )
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
                    $total_fee = $params['frontend_total'];
                    $source_prize = $params['frontend_total'];
                    // 判断是否使用优惠券
                    if(isset($params['coupon_id']) && $params['coupon_id'] > 0) {
                        // 判断优惠券是否可用
                        $this->load->model('coupon_model');
                        $couponStatus = $this->coupon_model->validCoupon($params['openid'], $params['coupon_id']);
                        // 优惠券是否有效
                        if($couponStatus['status'] != 0) {
                            return array(
                                'status'    => -4,
                                'msg'       => $couponStatus['msg']
                            );
                        } else {
                            // 优惠券是否满足使用条件
                            if((float)$couponStatus['data']['totalAmount'] > (float)$source_prize) {
                                return array(
                                    'status'    => -5,
                                    'msg'       => '优惠券不满足使用条件'
                                );
                            }
                            $total_fee = (float)$source_prize - (float)$couponStatus['data']['discountAmount'];
                        }
                    }
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
                        'notify_url'    => 'https://koalabeds-server.kakaday.com/api/paycallbackGrayline',
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
                        // 'totalPrice'    => isset($params['totalPrice']) ? $params['totalPrice'] : '',
                        'totalPrice'    => $total_fee,
                        'sourcePrice'   => $source_prize,
                        'info'          => isset($params['info']) ? $params['info'] : '',
                        'orderParamsDetail'     => json_encode($params),
                        'outTradeNo'    => $outTradeNo,
                        'subQty'        => isset($params['subQty']) ? json_encode($params['subQty']) : '',
                        'extinfo'       => isset($params['extinfo']) ? $params['extinfo'] : '',
                        'create_time'   => time(),
                        'coupon_id'     => isset($params['coupon_id']) ? $params['coupon_id'] : 0
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
                            'data'      => array(
                                'id'    => $orderSaveResult['data']['id'],
                                'payParams' => $unifiedOrder
                            )
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
            // 发表评价
            case 'saveReviews':
                $params['userid'] = $this->get_request('userid');
                $params['openid'] = $this->get_request('openid');
                $params['orderId'] = $this->get_request('orderId');
                $params['content'] = $this->get_request('content');
                $params['propertyID'] = $this->get_request('propertyID');
                $params['rate'] = $this->get_request('rate');
                $this->load->model('reviews_model');
                $result = $this->reviews_model->add($params);
                break;
            // 取消订单
            // case 'cancelHotelOrder':
            //     $id = $this->get_request('id');
            //     $openid = $this->get_request('openid');
            //     $this->load->model('hotel_order_model');
            //     $result =
            //     break;
        }
        echo json_encode($result);
    }


    /**
     * 发起退款
     */
    public function refund($orderInfo) {
    // public function refund() {
        // $this->load->model('hotel_order_model');
        // $orderInfo = $this->hotel_order_model->getDetailById(26)['data'];
        $transaction_info = json_decode($orderInfo['transaction_info'], true);
        // 退款单号
        $outRefundNo = substr('refundNo' . date('YmdHis', time()) . uniqid(), 0, 32); // 商品订单号
        // {"bank_type":"CFT","cash_fee":"1","cash_fee_type":"CNY","charset":"UTF-8","fee_type":"CNY","is_subscribe":"N","local_fee_type":"CNY","local_total_fee":"1","mch_id":"104530000126","nonce_str":"1573649465205","openid":"onekpwliGdQq4_Z9aH3WSASbF8Wg","order_fee":"1","out_trade_no":"cloudbeds201911132050545dcbfc2ec","out_transaction_id":"4200000462201911135850457975","pay_result":"0","rate":"89717221","result_code":"0","sign_type":"MD5","status":"0","sub_appid":"wx18cda3bfbb701cb7","sub_is_subscribe":"N","sub_op
        $data = array(
            'service'       => 'unified.trade.refund',
            'version'       => '2.0',
            'charset'       => 'UTF-8',
            'sign_type'     => 'MD5',
            'mch_id'        => '104530000126',
            'out_trade_no'  => $transaction_info['out_trade_no'],
            'transaction_id'=> $orderInfo['transaction_id'],
            'out_refund_no' => $outRefundNo,
            'total_fee'     => $transaction_info['order_fee'],
            'refund_fee'    => $transaction_info['order_fee'],
            'nonce_str'     => '1409196838',
            'op_user_id'    => '104530000126'
        );
        $sign = $this->getSign($data, '97a36c5b28ecb6dbe194c45ebc00f46f');
        $data['sign'] = $sign;
        $xml = $this->arrayToXml($data);
        $url = 'https://gateway.wepayez.com/pay/gateway';
        $responseXml = $this->curlPost($url, $xml);
        // 判断退款结果
        libxml_disable_entity_loader(true);
        $responseObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
        @file_put_contents('/pub/logs/refund', '[' . date('Y-m-d H:i:s', time()) . ']' . json_encode($responseObj) . PHP_EOL, FILE_APPEND);
        $responseArr = json_decode(json_encode($responseObj), true);
        $this->load->model('refund_model');
        $this->refund_model->add(array(
            'status'    => $responseArr['result_code'],
            'info'      => json_encode($responseObj),
            'create_time'   => time()
        ));
        if($responseArr['result_code'] == 0) {
            return array(
                'status'    => 0,
                'msg'       => '退款成功',
                'data'      => $responseArr
            );
        } else {
            return array(
                'status'    => -1,
                'msg'       => '退款失败',
                'data'      => $responseArr
            );
        }
    }


    public function paycallbackGrayline() {
        $result = $this->notify();
        @file_put_contents('/pub/logs/paycallbackGrayline', '[' . date('Y-m-d H:i:s', time()) . ']' . json_encode($result) . PHP_EOL, FILE_APPEND);
        // 收到支付回调，判断支付成功的话，将订单状态置为1
        if($result) {
            $this->load->model('grayline_ticket_model');
            $this->grayline_ticket_model->update_transaction_info($result['out_trade_no'], json_encode($result));
            if($result['result_code'] == 0) {
                $this->grayline_ticket_model->updateOrderStatusByOutTradeNo($result['out_trade_no'], 1);
                // 主动调起预约门票流程
                $this->load->model('grayline_ticket_model');
                // 通过交易单号查询订单id
                $orderInfo = $this->grayline_ticket_model->getOrderByOutTradeNo($result['out_trade_no']);
                @file_put_contents('/pub/logs/paycallbackGraylineOrderInfo', '[' . date('Y-m-d H:i:s', time()) . ']' . json_encode($orderInfo) . PHP_EOL, FILE_APPEND);
                if($orderInfo['status'] == 0) {
                    $id = $orderInfo['data']['id'];
                    $orderResult = $this->grayline_ticket_model->orderProduct($orderInfo['data']['openid'], $id);
                    // 发起退款
                    if($orderResult['status'] != 0) {
                        $this->refund($orderResult['data']);
                    }
                }
            } else {
                $this->grayline_ticket_model->updateOrderStatusByOutTradeNo($result['out_trade_no'], 0);
            }
        }
    }


    public function paycallback() {
        $result = $this->notify();
        // {"bank_type":"CFT","cash_fee":"1","cash_fee_type":"CNY","charset":"UTF-8","fee_type":"CNY","is_subscribe":"N","local_fee_type":"CNY","local_total_fee":"1","mch_id":"104530000126","nonce_str":"1573647089880","openid":"onekpwliGdQq4_Z9aH3WSASbF8Wg","order_fee":"1","out_trade_no":"cloudbeds201911132011175dcbf2e50","out_transaction_id":"4200000462201911137229645182","pay_result":"0","rate":"89717221","result_code":"0","sign_type":"MD5","status":"0","sub_appid":"wx18cda3bfbb701cb7","sub_is_subscribe":"N","sub_openid":"oLq-f4pzzkedinC8EKDfG86HFQdg","time_end":"20191113201129","total_fee":"1","trade_type":"pay.weixin.jspay","transaction_id":"104530000126201911132187482892","version":"2.0"}
        @file_put_contents('/pub/logs/paycallback', '[' . date('Y-m-d H:i:s', time()) . ']' . json_encode($result) . PHP_EOL, FILE_APPEND);
        // 收到支付回调，判断支付成功的话，将订单状态置为1
        if($result) {
            $this->load->model('hotel_order_model');
            $this->hotel_order_model->update_transaction_info($result['out_trade_no'], json_encode($result));
            if($result['pay_result'] == 0) {
                $this->hotel_order_model->update_status($result['out_trade_no'], 1);
                // 主动调起预约cloudbeds流程
                $this->load->model('hotel_order_model');
                // 通过交易单号查询订单id
                $orderInfo = $this->hotel_order_model->getDetailByOutTradeNo($result['out_trade_no']);
                if($orderInfo['status'] == 0) {
                    $id = $orderInfo['data']['id'];
                    $orderResult = $this->hotel_order_model->saveOrder($result['openid'], $id);
                    // 发起退款
                    if($orderResult['status'] != 0) {
                        $this->refund($orderResult['data']);
                    } else {
                        // 发起销账流程
                        // $paymentResult = $this->hotel_order_model->postPayment($orderInfo['data']['propertyID'], $orderResult['data']['reservationID'], $orderResult['data']['grandTotal']);
                    }
                }
            } else {
                $this->hotel_order_model->update_status($result['out_trade_no'], 0);
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
        @file_put_contents('/pub/logs/paynotifyXML', $postStr);
        // <xml><bank_type><![CDATA[CFT]]></bank_type>
        // <cash_fee><![CDATA[1]]></cash_fee>
        // <cash_fee_type><![CDATA[CNY]]></cash_fee_type>
        // <charset><![CDATA[UTF-8]]></charset>
        // <fee_type><![CDATA[CNY]]></fee_type>
        // <is_subscribe><![CDATA[N]]></is_subscribe>
        // <local_fee_type><![CDATA[CNY]]></local_fee_type>
        // <local_total_fee><![CDATA[1]]></local_total_fee>
        // <mch_id><![CDATA[104530000126]]></mch_id>
        // <nonce_str><![CDATA[1573648705055]]></nonce_str>
        // <openid><![CDATA[onekpwliGdQq4_Z9aH3WSASbF8Wg]]></openid>
        // <order_fee><![CDATA[1]]></order_fee>
        // <out_trade_no><![CDATA[cloudbeds201911132038135dcbf9355]]></out_trade_no>
        // <out_transaction_id><![CDATA[4200000462201911136447260336]]></out_transaction_id>
        // <pay_result><![CDATA[0]]></pay_result>
        // <rate><![CDATA[89717221]]></rate>
        // <result_code><![CDATA[0]]></result_code>
        // <sign><![CDATA[BEF84DE46F64D5447D89578531C90801]]></sign>
        // <sign_type><![CDATA[MD5]]></sign_type>
        // <status><![CDATA[0]]></status>
        // <sub_appid><![CDATA[wx18cda3bfbb701cb7]]></sub_appid>
        // <sub_is_subscribe><![CDATA[N]]></sub_is_subscribe>
        // <sub_openid><![CDATA[oLq-f4pzzkedinC8EKDfG86HFQdg]]></sub_openid>
        // <time_end><![CDATA[20191113203824]]></time_end>
        // <total_fee><![CDATA[1]]></total_fee>
        // <trade_type><![CDATA[pay.weixin.jspay]]></trade_type>
        // <transaction_id><![CDATA[104530000126201911131187127081]]></transaction_id>
        // <version><![CDATA[2.0]]></version>
        // </xml>
        // 禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        // $postStr = '{"bank_type":"CFT","cash_fee":"1","cash_fee_type":"CNY","charset":"UTF-8","fee_type":"CNY","is_subscribe":"N","local_fee_type":"CNY","local_total_fee":"1","mch_id":"104530000126","nonce_str":"1573647089880","openid":"onekpwliGdQq4_Z9aH3WSASbF8Wg","order_fee":"1","out_trade_no":"cloudbeds201911132011175dcbf2e50","out_transaction_id":"4200000462201911137229645182","pay_result":"0","rate":"89717221","result_code":"0","sign":"4B35434063F6316B8D927DEB424A63CE","sign_type":"MD5","status":"0","sub_appid":"wx18cda3bfbb701cb7","sub_is_subscribe":"N","sub_openid":"oLq-f4pzzkedinC8EKDfG86HFQdg","time_end":"20191113201129","total_fee":"1","trade_type":"pay.weixin.jspay","transaction_id":"104530000126201911132187482892","version":"2.0"}';
        // $postObj = json_decode($postStr, true);
        // var_dump($postObj);
        @file_put_contents('/pub/logs/paynotify', '[' . date('Y-m-d H:i:s', time()) . ']' . json_encode($postObj) . PHP_EOL, FILE_APPEND);
        if($postObj === false) {
            $this->load->model('errorlog_model');
            $params = array(
                'content'       => 'xml解析异常(parse xml error)',
                'create_time'   => time()
            );
            $this->errorlog_model->add($params);
            return false;
        }
        $postArr = (array)$postObj;
        // 事务结果判断
        if($postArr['result_code'] != 0) {
            $this->load->model('errorlog_model');
            $params = array(
                'content'       => json_encode($postArr),
                'create_time'   => time()
            );
            $this->errorlog_model->add($params);
            return false;
        }
        // 支付结果判断
        if($postArr['pay_result'] != 0) {
            $this->load->model('errorlog_model');
            $params = array(
                'content'       => json_encode($postArr),
                'create_time'   => time()
            );
            $this->errorlog_model->add($params);
            return false;
        }
        $arr = (array)$postObj;
        unset($arr['sign']);
        @file_put_contents('/pub/logs/tmp', '[' . date('Y-m-d H:i:s', time()) . ']' . (self::getSign($arr, $config['key']) == $postArr['sign']) . PHP_EOL, FILE_APPEND);
        if(self::getSign($arr, $config['key']) == $postArr['sign']) {
            // echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
            echo 'success';
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
