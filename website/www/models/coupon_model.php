<?php
/**
 * 优惠券模型
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class coupon_model extends MY_Model {

    private $table = 'ko_coupon';
    private $record_table = 'ko_coupon_record';
    private $fields = 'id, totalAmount, discountAmount, validateDate, zorder, status';
    private $record_fields = 'id, openid, cid, status, create_time';

    public function __construct() {
        parent::__construct();
    }


    /**
     * 查询优惠券列表
     */
    public function getList() {
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . ' where `status` = 0 order by zorder desc, id desc');
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
                'msg'       => '没有数据'
            );
        }
    }


    /**
     * 用户获取优惠券
     */
    public function getUserCoupon($openid, $ids) {
        $idArr = explode(',', $ids);
        if(count($idArr) <= 0) {
            return array(
                'status'    => -1,
                'msg'       => '请选择领取的优惠券'
            );
        }
        foreach($idArr as $k=>$v) {
            // 判断优惠券是否存在
            if(!$this->checkCouponExistByID($v)) {
                return array(
                    'status'    => -2,
                    'msg'       => '所领取的优惠券状态异常'
                );
            }
            // 判断是否已经领取对应优惠券
            if(!!$this->checkRecordExistByID($openid, $v)) {
                return array(
                    'status'    => -3,
                    'msg'       => '已经领过该优惠券'
                );
            }
            $data = array(
                'openid'    => $openid,
                'cid'       => $v,
                'status'    => 0,
                'create_time'   => time()
            );
            $this->db->insert($this->record_table, $data);
        }
        return array(
            'status'    => 0,
            'msg'       => '领取成功'
        );
    }


    /**
     * 检查是否存在对应的优惠券
     **/
    public function checkCouponExistByID($id) {
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . ' where id = ' . $id);
        $result = $query->result_array();
        if(count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * 检查是否已经领取过对应的优惠券
     */
    public function checkRecordExistByID($openid, $id) {
        $query = $this->db->query('select ' . $this->record_fields . ' from ' . $this->record_table . ' where id = ' . $id . ' and openid = "' . $openid . '"');
        $result = $query->result_array();
        if(count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * 判断优惠券是否可用
     */
    public function validCoupon($openid, $id) {
        $query = $this->db->query('select ' . $this->record_fields . ' from ' . $this->record_table . ' where id = ' . $id . ' and openid = "' . $openid . '"');
        $result = $query->result_array();
        if(count($result) > 0) {
            if($result[0]['status'] == 0) {
                return array(
                    'status'    => 0,
                    'msg'       => '优惠券有效',
                    'data'      => $result[0]
                );
            } else {
                return array(
                    'status'    => -1,
                    'msg'       => '优惠券无效'
                );
            }
        } else {
            return array(
                'status'    => -2,
                'msg'       => '不存在该优惠券'
            );
        }
    }

}
