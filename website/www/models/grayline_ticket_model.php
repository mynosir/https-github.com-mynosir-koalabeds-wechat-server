<?php
/**
 * grayline 票务模型
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class Grayline_ticket_model extends MY_Model {

    private $table = 'ko_grayline_ticket';
    private $info_table = 'ko_grayline_ticket_info';
    private $info_cn_table = 'ko_grayline_ticket_info_cn';
    private $info_v2_table = 'ko_grayline_ticket_info_v2';
    private $info_cn_v2_table = 'ko_grayline_ticket_info_cn_v2';
    private $fields = 'id, openid, type, productId, travelDate, travelTime, turbojetDepartureDate, turbojetReturnDate, turbojetDepartureTime, turbojetReturnTime, turbojetDepartureFrom, turbojetDepartureTo, turbojetReturnFrom, turbojetReturnTo, turbojetQuantity, turbojetClass, turbojetTicketType, turbojetDepartureFlightNo, turbojetReturnFlightNo, hotel, title, firstName, lastName, passport, guestEmail, countryCode, telephone, promocode, agentReference, remark, subQty, subQtyProductPriceId, subQtyValue, totalPrice, info, orderParamsDetail, outTradeNo, transaction_id, transaction_info, status, create_time, sourcePrice, extinfo, coupon_id';
    private $info_fields = 'id, productId, title, type, introduce, clause';
    private $info_cn_fields = 'id, productId, title, type, introduce, clause';
    private $info_v2_fields = 'id, productId, title, code, image, type, introduce, clause, status';
    private $info_cn_v2_fields = 'id, tiid, productId, title, introduce, clause';
    private $email = 'wesley@koalabeds.com.hk';
    private $password = 'clcwesley1';


    public function __construct() {
        parent::__construct();
    }


    public function fetch_tickets_v2() {
        $typeArr = array('tour', 'ticket');
        $url = 'http://grayline.com.hk/b2b/api/getProductList';
        $logArr = array();
        $data = array(
            'email'     => $this->email,
            'password'  => $this->password,
            'language'  => 'en'
        );
        foreach($typeArr as $x=>$y) {
            $data['type'] = $y;
            $apiReturnStr = $this->http_request_grayline($url, $data);
            if($apiReturnStr['meta']['code'] == 'RESP_OKAY') {
                foreach($apiReturnStr['data'] as $a=>$b) {
                    // 判断当前记录是否已经记录到数据库
                    $hasExist = $this->checkExistByProductIdV2($b['productId']);
                    if(!$hasExist) {
                        // 记录不存在，入库
                        $code = '';
                        if($y == 'tour') $code = $b['tourCode'];
                        if($y == 'ticket') $code = $b['ticketCode'];
                        $params = array(
                            'productId' => $b['productId'],
                            'title'     => $b['title'],
                            'code'      => $code,
                            'image'     => $b['image'],
                            'type'      => $y,
                            'introduce' => '',
                            'clause'    => ''
                        );
                        $this->db->insert($this->info_v2_table, $params);
                        @file_put_contents('/pub/logs/fetch_tickets_v2/' . date('Y-m-d', time()), '[' . date('Y-m-d H:i:s', time()) . '](' . $b['productId'] . ')==> ' . json_encode($params) . PHP_EOL, FILE_APPEND);
                        @file_put_contents('/pub/logs/fetch_tickets_success_v2/' . date('Y-m', time()), '[' . date('Y-m-d H:i:s', time()) . '](' . $b['productId'] . ')==> ' . json_encode($params) . PHP_EOL, FILE_APPEND);
                        $logArr[] = '[' . date('Y-m-d H:i:s', time()) . '](' . $b['productId'] . ')==> ' . json_encode($params);
                    } else {
                        // 记录存在，更新
                        $code = '';
                        if($y == 'tour') $code = $b['tourCode'];
                        if($y == 'ticket') $code = $b['ticketCode'];
                        $params = array(
                            'title' => $b['title'],
                            'type'  => $b['type'],
                            'code'  => $code,
                            'image' => $b['image']
                        );
                        $where = array(
                            'productId' => $b['productId']
                        );
                        $this->db->where($where)->update($this->info_v2_table, $params);
                        // 写入日志
                        @file_put_contents('/pub/logs/fetch_tickets_v2/' . date('Y-m-d', time()), '[' . date('Y-m-d H:i:s', time()) . '](' . $b['productId'] . ')==> 记录已经存在' . PHP_EOL, FILE_APPEND);
                            $logArr[] = '[' . date('Y-m-d H:i:s', time()) . '](' . $b['productId'] . ')==> 记录已经存在';
                    }
                }
            }
        }
        return $logArr;
    }


    public function fetch_tickets() {
        $languageArr = array(array(
            'lang'  => 'en',
            'table' => $this->info_table
        ), array(
            'lang'  => 'zh-cn',
            'table' => $this->info_cn_table
        ));
        $typeArr = array('tour', 'ticket');
        $url = 'http://grayline.com.hk/b2b/api/getProductList';
        $logArr = array();
        foreach($languageArr as $k=>$v) {
            $data = array(
                'email'     => $this->email,
                'password'  => $this->password,
                'language'  => $v['lang']
            );
            foreach($typeArr as $x=>$y) {
                $data['type'] = $y;
                $apiReturnStr = $this->http_request_grayline($url, $data);
                if($apiReturnStr['meta']['code'] == 'RESP_OKAY') {
                    foreach($apiReturnStr['data'] as $a=>$b) {
                        // 判断当前记录是否已经记录到数据库
                        $hasExist = $this->checkExistByProductId($b['productId'], $v['table']);
                        if(!$hasExist) {
                            // 记录不存在，入库
                            $params = array(
                                'productId'     => $b['productId'],
                                'title'         => $b['title'],
                                'type'          => $b['type']
                            );
                            $this->db->insert($v['table'], $params);
                            @file_put_contents('/pub/logs/fetch_tickets/' . $v['lang'] . '_' . date('Y-m-d', time()), '[' . date('Y-m-d H:i:s', time()) . '](' . $b['productId'] . ')==> ' . json_encode($params) . PHP_EOL, FILE_APPEND);
                            @file_put_contents('/pub/logs/fetch_tickets_success/' . $v['lang'] . '_' . date('Y-m', time()), '[' . date('Y-m-d H:i:s', time()) . '](' . $b['productId'] . ')==> ' . json_encode($params) . PHP_EOL, FILE_APPEND);
                            $logArr[] = '[' . date('Y-m-d H:i:s', time()) . '](' . $v['lang'] . $b['productId'] . ')==> ' . json_encode($params);
                        } else {
                            // 记录存在，更新
                            $params = array(
                                'title'     => $b['title'],
                                'type'      => $b['type']
                            );
                            $where = array(
                                'productId' => $b['productId']
                            );
                            $this->db->where($where)->update($v['table'], $params);
                            // 写入日志文件
                            @file_put_contents('/pub/logs/fetch_tickets/' . $v['lang'] . '_' . date('Y-m-d', time()), '[' . date('Y-m-d H:i:s', time()) . '](' . $b['productId'] . ')==> 记录已经存在' . PHP_EOL, FILE_APPEND);
                            $logArr[] = '[' . date('Y-m-d H:i:s', time()) . '](' . $v['lang'] . $b['productId'] . ')==> 记录已经存在';
                        }
                    }
                }
            }
        }
        return $logArr;
    }


    /**
     * 检查记录是否已经记录在数据库中
     **/
    public function checkExistByProductId($productId, $table) {
        $query = $this->db->query('select count(1) as num from ' . $table . ' where productId = ' . $productId);
        $result = $query->result_array();
        if($result[0]['num'] > 0) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * 检查记录是否已经记录在数据库中
     **/
    public function checkExistByProductIdV2($productId) {
        $query = $this->db->query('select count(1) as num from ' . $this->info_v2_table . ' where productId = ' . $productId);
        $result = $query->result_array();
        if($result[0]['num'] > 0) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * 获取国家列表
     */
    public function getNationalityList($language) {
        $url = 'http://grayline.com.hk/b2b/api/getNationalityList';
        $data = array(
            'email'     => $this->email,
            'password'  => $this->password,
            'language'  => $language
        );
        $apiReturnStr = $this->http_request_grayline($url, $data);
        if($apiReturnStr['meta']['code'] == 'RESP_OKAY') {
            return array(
                'status'    => 0,
                'msg'       => '查询成功',
                'data'      => $apiReturnStr['data']
            );
        } else {
            return array(
                'status'    => 1,
                'msg'       => '系统异常',
                'ext'       => json_encode($apiReturnStr['meta'])
            );
        }
    }


    /**
     * 获取产品列表v2
     */
    public function getProductListV2($language, $type = '') {
        $where = '';
        if($type != '') {
            $where = ' where type = "' . $type . '" ';
        }
        $query = $this->db->query('select ' . $this->info_v2_fields . ' from ' . $this->info_v2_table . $where);
        $result = $query->result_array();
        if($language == 'zh-cn') {
            foreach($result as $k=>$v) {
                $cnInfo = $this->getProductCn($v['id']);
                if(!!$cnInfo) {
                    $result[$k]['title'] = $cnInfo['title'];
                    $result[$k]['introduce'] = $cnInfo['introduce'];
                    $result[$k]['clause'] = $cnInfo['clause'];
                }
            }
        }
        return array(
            'status'    => 0,
            'msg'       => '查询成功',
            'data'      => $result
        );
    }


    public function getProductCn($tiid) {
        $query = $this->db->query('select ' . $this->info_cn_v2_fields . ' from ' . $this->info_cn_v2_table . ' where tiid = ' . $tiid);
        $result = $query->result_array();
        if(count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }


    public function getProductCnByProductId($productId) {
        $query = $this->db->query('select ' . $this->info_cn_v2_fields . ' from ' . $this->info_cn_v2_table . ' where productId = ' . $productId);
        $result = $query->result_array();
        if(count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }


    /**
     * 获取产品列表
     */
    public function getProductList($language, $type) {
        $url = 'http://grayline.com.hk/b2b/api/getProductList';
        $data = array(
            'email'     => $this->email,
            'password'  => $this->password,
            'language'  => $language,
        );
        if($type != '') $data['type'] = $type;
        $apiReturnStr = $this->http_request_grayline($url, $data);
        if($apiReturnStr['meta']['code'] == 'RESP_OKAY') {
            return array(
                'status'    => 0,
                'msg'       => '查询成功',
                'data'      => $apiReturnStr['data']
            );
        } else {
            return array(
                'status'    => 1,
                'msg'       => '系统异常',
                'ext'       => json_encode($apiReturnStr['meta'])
            );
        }
    }


    /**
     * 获取产品详情v2
     */
    public function getProductDetailsV2($type, $productId, $language = 'en') {
        if(!$type || !$productId) {
            return array(
                'status'    => -1,
                'msg'       => '参数异常'
            );
        }
        $url = 'http://grayline.com.hk/b2b/api/getProductDetails';
        $data = array(
            'email'     => $this->email,
            'password'  => $this->password,
            'type'      => $type,
            'productId' => $productId
        );
        $apiReturnStr = $this->http_request_grayline($url, $data);
        if($apiReturnStr['meta']['code'] == 'RESP_OKAY') {
            if($language == 'zh-cn') {
                $cnInfo = $this->getProductCnByProductId($productId);
                if(!!$cnInfo) {
                    $apiReturnStr['data']['title'] = $cnInfo['title'];
                    $apiReturnStr['data']['introduce'] = $cnInfo['introduce'];
                    $apiReturnStr['data']['clause'] = $cnInfo['clause'];
                }
            }
            return array(
                'status'    => 0,
                'msg'       => '查询成功',
                'data'      => $apiReturnStr['data']
            );
        } else {
            return array(
                'status'    => 1,
                'msg'       => '系统异常',
                'ext'       => json_encode($apiReturnStr['meta'])
            );
        }
    }


    /**
     * 获取产品详情
     */
    public function getProductDetails($type, $productId, $language='en') {
        if(!$type || !$productId) {
            return array(
                'status'    => -1,
                'msg'       => '参数异常'
            );
        }
        $url = 'http://grayline.com.hk/b2b/api/getProductDetails';
        $data = array(
            'email'     => $this->email,
            'password'  => $this->password,
            'type'      => $type,
            'productId' => $productId
        );
        $apiReturnStr = $this->http_request_grayline($url, $data);
        if($apiReturnStr['meta']['code'] == 'RESP_OKAY') {
            // 查询详情及条款
            $extInfo = $this->getProductDetailsExt($productId, $language);
            if($extInfo['status'] == 0) {
                $apiReturnStr['data']['introduce'] = $extInfo['data']['introduce'];
                $apiReturnStr['data']['clause'] = $extInfo['data']['clause'];
            } else {
                $apiReturnStr['data']['introduce'] = '';
                $apiReturnStr['data']['clause'] = '';
            }
            return array(
                'status'    => 0,
                'msg'       => '查询成功',
                'data'      => $apiReturnStr['data']
            );
        } else {
            return array(
                'status'    => 1,
                'msg'       => '系统异常',
                'ext'       => json_encode($apiReturnStr['meta'])
            );
        }
    }


    public function getProductDetailsExt($productId, $language) {
        if($language == 'zh-cn') {
            $table = $this->info_cn_table;
            $fields = $this->info_cn_fields;
        } else {
            $table = $this->info_table;
            $fields = $this->info_fields;
        }
        $query = $this->db->query('select ' . $fields . ' from ' . $table . ' where productId = ' . $productId);
        $result = $query->result_array();
        if(count($result) > 0) {
            return array(
                'status'    => 0,
                'msg'       => '查询成功',
                'data'      => $result[0]
            );
        } else {
            return array(
                'status'    => -1,
                'msg'       => '不存在记录'
            );
        }
    }


    /**
     * 查询产品
     */
    public function queryProduct($type, $productId, $travelDate, $travelTime, $turbojetDepartureDate, $turbojetReturnDate, $turbojetDepartureTime, $turbojetReturnTime, $turbojetDepartureFrom, $turbojetDepartureTo, $turbojetReturnFrom, $turbojetReturnTo, $turbojetQuantity, $turbojetClass, $subQtyProductPriceId) {
        if(!$type || !$productId) {
            return array(
                'status'    => -1,
                'msg'       => '参数异常'
            );
        }
        $url = 'http://grayline.com.hk/b2b/api/queryProduct';
        $data = array(
            'email'     => $this->email,
            'password'  => $this->password,
            'type'      => $type,
            'productId' => $productId
        );
        if(!!$travelDate) $data['date'] = $travelDate;
        if(!!$travelTime) $data['travelTime'] = $travelTime;
        if(!!$turbojetDepartureDate) $data['turbojetDepartureDate'] = $turbojetDepartureDate;
        if(!!$turbojetReturnDate) $data['turbojetReturnDate'] = $turbojetReturnDate;
        if(!!$turbojetDepartureTime) $data['turbojetDepartureTime'] = $turbojetDepartureTime;
        if(!!$turbojetReturnTime) $data['turbojetReturnTime'] = $turbojetReturnTime;
        if(!!$turbojetDepartureFrom) $data['turbojetDepartureFrom'] = $turbojetDepartureFrom;
        if(!!$turbojetDepartureTo) $data['turbojetDepartureTo'] = $turbojetDepartureTo;
        if(!!$turbojetReturnFrom) $data['turbojetReturnFrom'] = $turbojetReturnFrom;
        if(!!$turbojetReturnTo) $data['turbojetReturnTo'] = $turbojetReturnTo;
        if(!!$turbojetQuantity) $data['turbojetQuantity'] = $turbojetQuantity;
        if(!!$turbojetClass) $data['turbojetClass'] = $turbojetClass;
        if(!!$subQtyProductPriceId) $data['subQtyProductPriceId'] = $subQtyProductPriceId;
        $apiReturnStr = $this->http_request_grayline($url, $data);
        if($apiReturnStr['meta']['code'] == 'RESP_OKAY') {
            return array(
                'status'    => 0,
                'msg'       => '查询成功',
                'data'      => $apiReturnStr['data']
            );
        } else {
            return array(
                'status'    => 1,
                'msg'       => '系统异常',
                'ext'       => json_encode($apiReturnStr['meta'])
            );
        }
    }





    /**
     * 查询产品v2
     */
    public function queryProductV2($type, $productId, $travelDate, $travelTime, $turbojetDepartureDate, $turbojetReturnDate, $turbojetDepartureTime, $turbojetReturnTime, $turbojetDepartureFrom, $turbojetDepartureTo, $turbojetReturnFrom, $turbojetReturnTo, $turbojetQuantity, $turbojetClass, $subQtyProductPriceId) {
        if(!$type || !$productId) {
            return array(
                'status'    => -1,
                'msg'       => '参数异常'
            );
        }
        $url = 'http://grayline.com.hk/b2b/api/queryProduct';
        $data = array(
            'email'     => $this->email,
            'password'  => $this->password,
            'type'      => $type,
            'productId' => $productId
        );
        if(!!$travelDate) $data['date'] = $travelDate;
        if(!!$travelTime) $data['travelTime'] = $travelTime;
        if(!!$turbojetDepartureDate) $data['turbojetDepartureDate'] = $turbojetDepartureDate;
        if(!!$turbojetReturnDate) $data['turbojetReturnDate'] = $turbojetReturnDate;
        if(!!$turbojetDepartureTime) $data['turbojetDepartureTime'] = $turbojetDepartureTime;
        if(!!$turbojetReturnTime) $data['turbojetReturnTime'] = $turbojetReturnTime;
        if(!!$turbojetDepartureFrom) $data['turbojetDepartureFrom'] = $turbojetDepartureFrom;
        if(!!$turbojetDepartureTo) $data['turbojetDepartureTo'] = $turbojetDepartureTo;
        if(!!$turbojetReturnFrom) $data['turbojetReturnFrom'] = $turbojetReturnFrom;
        if(!!$turbojetReturnTo) $data['turbojetReturnTo'] = $turbojetReturnTo;
        if(!!$turbojetQuantity) $data['turbojetQuantity'] = $turbojetQuantity;
        if(!!$turbojetClass) $data['turbojetClass'] = $turbojetClass;
        if(!!$subQtyProductPriceId) $data['subQtyProductPriceId'] = $subQtyProductPriceId;
        $apiReturnStr = $this->http_request_grayline($url, $data);
        if($apiReturnStr['meta']['code'] == 'RESP_OKAY') {
            return array(
                'status'    => 0,
                'msg'       => '查询成功',
                'data'      => $apiReturnStr['data']
            );
        } else {
            return array(
                'status'    => 1,
                'msg'       => '系统异常',
                'ext'       => json_encode($apiReturnStr['meta'])
            );
        }
    }


    /**
     * 生成订单
     */
    public function generateOrder($params) {
        if(!isset($params['type']) || !isset($params['productId']) || !$params['type'] || !$params['productId']) {
            return array(
                'status'    => -1,
                'msg'       => '参数异常'
            );
        }
        $this->db->insert($this->table, $params);
        $insertId = $this->db->insert_id();
        // 变更优惠券状态
        if(isset($params['coupon_id']) && $params['coupon_id'] > 0) {
            $this->load->model('coupon_model');
            $CI = &get_instance();
            $CI->coupon_model->updateStatus($params['coupon_id'], 1);
        }
        return array(
            'status'    => 0,
            'msg'       => '订单生成成功',
            'data'      => array(
                'id'    => $insertId
            )
        );
    }


    /**
     * 从数据库中查询订单数据
     */
    public function getOrderDetailInDBById($id) {
        if(!$id) {
            return array(
                'status'    => -1,
                'msg'       => '查询订单参数异常'
            );
        }
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . ' where id = ' . $id);
        $result = $query->result_array();
        if(count($result) > 0) {
            return array(
                'status'    => 0,
                'msg'       => '查询成功',
                'data'      => $result[0]
            );
        } else {
            return array(
                'status'    => -2,
                'msg'       => '未找到对应订单'
            );
        }
    }


    /**
     * 预订产品
     */
    public function orderProduct($openid, $id) {
        if(!$openid || !$id) {
            return array(
                'status'    => -1,
                'msg'       => '参数异常'
            );
        }
        $url = 'http://grayline.com.hk/b2b/api/orderProduct';
        // 查询订单信息，用于支付
        $orderDetailObj = $this->getOrderDetailInDBById($id);
        if($orderDetailObj['status'] != 0) {
            return array(
                'status'    => -2,
                'msg'       => $orderDetailObj['msg']
            );
        }
        $orderDetail = $orderDetailObj['data'];
        $data = array(
            'email'     => $this->email,
            'password'  => $this->password,
            'type'      => $orderDetail['type'],
            'productId' => $orderDetail['productId'],
            'date'      => $orderDetail['travelDate'],
            'travelTime'=> $orderDetail['travelTime'],
            'turbojetDepartureDate' => $orderDetail['turbojetDepartureDate'],
            'turbojetReturnDate'    => $orderDetail['turbojetReturnDate'],
            'turbojetDepartureTime' => $orderDetail['turbojetDepartureTime'],
            'turbojetReturnTime'    => $orderDetail['turbojetReturnTime'],
            'turbojetDepartureFrom' => $orderDetail['turbojetDepartureFrom'],
            'turbojetDepartureTo'   => $orderDetail['turbojetDepartureTo'],
            'turbojetReturnFrom'    => $orderDetail['turbojetReturnFrom'],
            'turbojetReturnTo'      => $orderDetail['turbojetReturnTo'],
            'turbojetQuantity'      => $orderDetail['turbojetQuantity'],
            'turbojetClass'         => $orderDetail['turbojetClass'],
            'turbojetTicketType'    => $orderDetail['turbojetTicketType'],
            'turbojetDepartureFlightNo' => $orderDetail['turbojetDepartureFlightNo'],
            'turbojetReturnFlightNo'    => $orderDetail['turbojetReturnFlightNo'],
            'hotel'     => $orderDetail['hotel'],
            'title'     => $orderDetail['title'],
            'firstName' => $orderDetail['firstName'],
            'lastName'  => $orderDetail['lastName'],
            'passport'  => $orderDetail['passport'],
            'guestEmail'=> $orderDetail['guestEmail'],
            // 'subQty'    => array($orderDetail['subQtyProductPriceId']=> $orderDetail['subQtyValue']),
            'subQty'    => json_decode($orderDetail['subQty'], true),
            'totalPrice'=> $orderDetail['sourcePrice'],
            'telephone' => $orderDetail['telephone']
        );
        // var_dump(http_build_query($data));exit;
        // $apiReturnStr = $this->http_request_grayline($url, 'email=wesley%40koalabeds.com.hk&password=clcwesley1&type=tour&productId=6&date=2019-11-12&travelTime=12:11&turbojetDepartureDate=&turbojetReturnDate=&turbojetDepartureTime=&turbojetReturnTime=&turbojetDepartureFrom=&turbojetDepartureTo=&turbojetReturnFrom=&turbojetReturnTo=&turbojetQuantity=&turbojetClass=&turbojetTicketType=&turbojetDepartureFlightNo=&turbojetReturnFlightNo=&hotel=testhotel&title=Mr&firstName=lin&lastName=zequan&passport=111&guestEmail=361789273@qq.com&subQty%5B51%5D=1&totalPrice=670&telephone=18665953630');
        // var_dump($apiReturnStr);exit;
        $apiReturnStr = $this->http_request_grayline($url, http_build_query($data));
        // 根据不同返回状态更新订单状态
        @file_put_contents('/pub/logs/saveGraylineOrderParams', '[' . date('Y-m-d H:i:s', time()) . '](' . json_encode($data) . PHP_EOL, FILE_APPEND);
        @file_put_contents('/pub/logs/saveGraylineOrder', '[' . date('Y-m-d H:i:s', time()) . '](' . json_encode($apiReturnStr) . PHP_EOL, FILE_APPEND);
        // array(2) {
        //   ["meta"]=>
        //   array(2) {
        //     ["code"]=>
        //     string(9) "RESP_OKAY"
        //     ["message"]=>
        //     string(7) "Success"
        //   }
        //   ["data"]=>
        //   array(1) {
        //     ["orderId"]=>
        //     string(18) "GLB-20191111-31556"
        //   }
        // }
        if($apiReturnStr['meta']['code'] == 'RESP_OKAY') {
            // 更新订单信息
            $this->updateOrderStatus($id, 2);
            return array(
                'status'    => 0,
                'msg'       => '下单成功',
                'data'      => $apiReturnStr['data']
            );
        } else {
            $this->updateOrderStatus($id, -1);
            // 预订失败，发起退款
            return array(
                'status'    => 1,
                'msg'       => '系统异常',
                'ext'       => json_encode($apiReturnStr['meta'])
            );
        }
    }


    /**
     * 更新订单状态
     */
    public function updateOrderStatus($id, $status) {
        $where = array(
            'id'    => $id
        );
        $data = array(
            'status'    => $status
        );
        $this->db->where($where)->update($this->table, $data);
    }


    /**
     * 通过交易单号更新订单状态
     */
    public function updateOrderStatusByOutTradeNo($outTradeNo, $status) {
        $where = array(
            'outTradeNo'    => $outTradeNo
        );
        $data = array(
            'status'    => $status
        );
        $this->db->where($where)->update($this->table, $data);
    }


    /**
     * 保存交易信息
     */
    public function update_transaction_info($out_trade_no, $transaction_info) {
        $transaction_info_obj = json_decode($transaction_info, true);
        $where = array(
            'outTradeNo'  => $out_trade_no
        );
        $data = array(
            'transaction_id'    => $transaction_info_obj['transaction_id'],
            'transaction_info'  => $transaction_info
        );
        $this->db->where($where)->update($this->table, $data);
    }


    /**
     * 根据微信openid获取订单列表
     */
    public function getOrderList($openid) {
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . ' where openid = "' . $openid . '" order by id desc');
        $result = $query->result_array();
        if(count($result) > 0) {
            return array(
                'status'    => 0,
                'msg'       => '查询成功',
                'data'      => $result
            );
        } else {
            return array(
                'status'    => -1,
                'msg'       => '未查找到对应订单信息'
            );
        }
    }


    /**
     * 通过订单id查询订单详情
     */
    public function getOrderById($openid, $id) {
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . ' where openid = "' . $openid . '" and id = ' . $id . ' order by id desc');
        $result = $query->result_array();
        if(count($result) > 0) {
            return array(
                'status'    => 0,
                'msg'       => '查询成功',
                'data'      => $result[0]
            );
        } else {
            return array(
                'status'    => -1,
                'msg'       => '未查找到对应订单信息'
            );
        }
    }


    /**
     * 通过交易单号查询订单详情
     */
    public function getOrderByOutTradeNo($outTradeNo) {
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . ' where outTradeNo = "' . $outTradeNo . '" order by id desc');
        @file_put_contents('/pub/logs/paycallbackGraylineGetOrderByOutTradeNo', '[' . date('Y-m-d H:i:s', time()) . ']select ' . $this->fields . ' from ' . $this->table . ' where openid = "' . $openid . '" and outTradeNo = "' . $outTradeNo . '" order by id desc' . PHP_EOL, FILE_APPEND);
        $result = $query->result_array();
        if(count($result) > 0) {
            return array(
                'status'    => 0,
                'msg'       => '查询成功',
                'data'      => $result[0]
            );
        } else {
            return array(
                'status'    => -1,
                'msg'       => '未查找到对应订单信息'
            );
        }
    }


    public function getTicketByOrderIdAndOpenid($orderId, $openid) {
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . ' where id = ' . $orderId . ' and openid = "' . $openid . '"');
        $result = $query->result_array();
        if(count($result) > 0) {
            return array(
                'status'    => 0,
                'msg'       => '查询成功',
                'data'      => $result[0]
            );
        } else {
            return array(
                'status'    => -1,
                'msg'       => '未查找到对应订单信息'
            );
        }
    }

}
