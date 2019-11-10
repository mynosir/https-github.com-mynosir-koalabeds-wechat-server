<?php
/**
 * 酒店订单模型
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class hotel_order_model extends MY_Model {

    private $table = 'ko_hotel_order';
    private $fields = 'id, openid, propertyID, startDate, endDate, guestFirstName, guestLastName, guestCountry, guestZip, guestEmail, guestPhone, rooms, rooms_roomTypeID, rooms_quantity, adults, adults_roomTypeID, adults_quantity, children, children_roomTypeID, children_quantity, status, total, frontend_total, balance, balanceDetailed, assigned, unassigned, cardsOnFile, reservationID, estimatedArrivalTime, outTradeNo, transaction_id, transaction_infos';

    public function __construct() {
        parent::__construct();
    }


    /**
     * 生成订单
     */
    public function generateOrder($params) {
        $data = array(
            'openid'        => $params['openid'],
            'propertyID'    => $params['propertyID'],
            'startDate'     => $params['startDate'],
            'endDate'       => $params['endDate'],
            'guestFirstName'    => $params['guestFirstName'],
            'guestLastName' => $params['guestLastName'],
            'guestCountry'  => $params['guestCountry'],
            'guestZip'      => $params['guestZip'],
            'guestEmail'    => $params['guestEmail'],
            'guestPhone'    => $params['guestPhone'],
            'rooms'         => json_encode($params['rooms']),
            'rooms_roomTypeID'  => $params['rooms']['roomTypeID'],
            'rooms_quantity'    => $params['rooms']['quantity'],
            'adults'        => json_encode($params['adults']),
            'adults_roomTypeID' => $params['adults']['roomTypeID'],
            'adults_quantity'   => $params['adults']['quantity'],
            'children'      => json_encode($params['children']),
            'children_roomTypeID'   => $params['children']['roomTypeID'],
            'children_quantity' => $params['children']['quantity'],
            'status'        => 0,
            'frontend_total'=> $params['frontend_total'],
            // 'total'         => $params['frontend_total'],
            'outTradeNo'    => $params['outTradeNo']
        );
        // 查询房间对应金额
        $this->load->model('cloudbeds_hotel_model');
        $CI = &get_instance();
        $rate = $CI->cloudbeds_hotel_model->getRoomsFeesAndTaxes($data['startDate'], $data['endDate'], $data['frontend_total'], $data['rooms_quantity'], $data['propertyID']);
        if($rate['status'] != 0) {
            return array(
                'status'    => -1,
                'msg'       => $rate['msg']
            );
        }
        $data['total'] = $rate['data']['grandTotal'];
        $this->db->insert($this->table, $data);
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
     * 保存酒店订单
     */
    public function saveOrder() {
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


    /**
     * 更新订单状态
     */
    public function update_status($out_trade_no, $status) {
        $where = array(
            'out_trade_no'  => $out_trade_no
        );
        $data = array(
            'status'    => $status
        );
        $this->db->where($where)->update($this->table, $data);
    }

}
