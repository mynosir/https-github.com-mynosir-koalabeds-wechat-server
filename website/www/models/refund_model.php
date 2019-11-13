<?php
/**
 * 退款记录模型
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class refund_model extends MY_Model {

    private $table = 'ko_refund';
    private $fields = 'id, status, info, create_time';

    public function __construct() {
        parent::__construct();
    }


    /**
     * 保存记录
     **/
    public function add($params) {
        $this->db->insert($this->table, $params);
        $result = array(
            'status'    => 0,
            'msg'       => '保存成功！'
        );
        return $result;
    }

}
