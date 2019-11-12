<?php
/**
 * grayline 票务模型
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class Grayline_ticket_model extends MY_Model {

    private $table = 'ko_grayline_ticket';
    private $fields = 'id, openid, type, productId, travelDate, travelTime, turbojetDepartureDate, turbojetReturnDate, turbojetDepartureTime, turbojetReturnTime, turbojetDepartureFrom, turbojetDepartureTo, turbojetReturnFrom, turbojetReturnTo, turbojetQuantity, turbojetClass, turbojetTicketType, turbojetDepartureFlightNo, turbojetReturnFlightNo, hotel, title, firstName, lastName, passport, guestEmail, countryCode, telephone, promocode, agentReference, remark, subQty, subQtyProductPriceId, subQtyValue, totalPrice, info, orderParamsDetail, outTradeNo, transaction_id, transaction_info, status, create_time';
    private $email = 'wesley@koalabeds.com.hk';
    private $password = 'clcwesley1';

    public function __construct() {
        parent::__construct();
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
     * 获取产品详情
     */
    public function getProductDetails($type, $productId) {
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
            'totalPrice'=> $orderDetail['totalPrice'],
            'telephone' => $orderDetail['telephone']
        );
        // var_dump(http_build_query($data));exit;
        // $apiReturnStr = $this->http_request_grayline($url, 'email=wesley%40koalabeds.com.hk&password=clcwesley1&type=tour&productId=6&date=2019-11-12&travelTime=12:11&turbojetDepartureDate=&turbojetReturnDate=&turbojetDepartureTime=&turbojetReturnTime=&turbojetDepartureFrom=&turbojetDepartureTo=&turbojetReturnFrom=&turbojetReturnTo=&turbojetQuantity=&turbojetClass=&turbojetTicketType=&turbojetDepartureFlightNo=&turbojetReturnFlightNo=&hotel=testhotel&title=Mr&firstName=lin&lastName=zequan&passport=111&guestEmail=361789273@qq.com&subQty%5B51%5D=1&totalPrice=670&telephone=18665953630');
        // var_dump($apiReturnStr);exit;
        $apiReturnStr = $this->http_request_grayline($url, http_build_query($data));
        // 根据不同返回状态更新订单状态
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
            $this->updateOrderStatus($id, 6);
            return array(
                'status'    => 0,
                'msg'       => '下单成功',
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
     * 保存交易信息
     */
    public function update_transaction_info($out_trade_no, $transaction_info) {
        $transaction_info_obj = json_decode($transaction_info, true);
        $where = array(
            'out_trade_no'  => $out_trade_no
        );
        $data = array(
            'transaction_id'    => $transaction_info_obj['transaction_id'],
            'transaction_info'  => $transaction_info
        );
        $this->db->where($where)->update($this->table, $data);
    }

}
