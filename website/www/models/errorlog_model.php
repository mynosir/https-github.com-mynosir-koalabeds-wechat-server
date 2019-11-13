<?php
/**
 * 系统异常日志表模型
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class errorlog_model extends MY_Model {

    private $table = 'ko_errorlog';
    private $fields = 'id, content, create_time';

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
