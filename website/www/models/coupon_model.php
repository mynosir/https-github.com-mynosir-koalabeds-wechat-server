<?php
/**
 * 优惠券模型
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class coupon_model extends MY_Model {

    private $table = 'ko_coupon';
    private $fields = 'id, totalAmount, discountAmount, validateDate, zorder, status';

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

}
