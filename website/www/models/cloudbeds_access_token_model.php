<?php
/**
 * cloudbeds access token保存模型
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class Cloudbeds_access_token_model extends MY_Model {

    private $table = 'ko_cloudbeds_access_token';
    private $fields = 'id, access_token, token_type, expires_in, refresh_token, update_time';

    public function __construct() {
        parent::__construct();
    }


    /**
     * 获取cloudbeds access token参数
     **/
    public function getAccessToken() {
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table);
        $result = $query->result_array();
        if(count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }


    /**
     * 同步服务器access_token，方便开发
     */
    public function saveDevAccessToken($access_token) {
        return $this->db->query('update ' . $this->table . ' set `access_token` = "' . $access_token . '"');
    }

}
