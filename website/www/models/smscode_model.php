<?php
/**
 * 短信验证码模型
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class smscode_model extends MY_Model {

    private $table = 'ko_smscode';
    private $fields = 'id, phone, code, is_check, ip, sendlog, create_time';

    public function __construct() {
        parent::__construct();
    }


    /**
     * 保存短信验证码
     */
    public function save($phone, $code, $sendlog) {
        $data = array(
            'phone' => $phone,
            'code'  => $code,
            'sendlog'   => $sendlog,
            'ip'    => $this->getIP(),
            'create_time'  => time()
        );
        $this->db->insert($this->table, $data);
        $result['status'] = 0;
        $result['msg'] = '保存成功！';
        return $result;
    }


    /**
     * 生成随机数字二维码
     * @return [type] [description]
     */
    public function generateCode($length=4) {
        return generate_code($length);
    }


    /**
     * 检查短信验证码
     */
    public function checkSmsCode($phone, $smsCode) {
        // return true;
        if(!$phone || !$smsCode) {
            return false;
        }
        $where = array(
            'phone' => $phone
        );
        $query = $this->db->select($this->smscode_fields)->where($where)->order_by('create_time', 'desc')->get($this->smscode_table);
        $result = $query->result_array();
        if(count($result)==0) {
            return false;
        } else {
            $info = $result[0];
            // 判断验证码是否正确
            if($info['code']!=$smsCode) {
                return false;
            }
            // 判断验证码是否过期。5分钟内有效
            if((time() - $info['create_time']) > 60 * 5) {
                return false;
            }
            return true;
        }
    }

}
