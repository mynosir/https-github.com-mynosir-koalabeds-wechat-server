<?php
/**
 * 首页banner模型
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class banner_model extends MY_Model {

    private $table = 'ko_banner';
    private $fields = 'id, img, link, zorder, status';

    public function __construct() {
        parent::__construct();
    }


    /**
     * 查询banner列表
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
