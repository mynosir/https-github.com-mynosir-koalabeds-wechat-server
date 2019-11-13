<?php
/**
 * 微信用户模型
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class user_model extends MY_Model {

    private $table = 'ko_user';
    private $fields = 'id, openid, userinfo, wx_nickname, wx_sex, wx_language, wx_city, wx_province, wx_country, wx_avatarUrl, lang';

    public function __construct() {
        parent::__construct();
    }


    public function saveUserinfo($openid, $userinfo) {
        if($userinfo == '') {
            return array(
                'status'    => -1,
                'msg'       => '参数异常'
            );
        }
        // {"openid":"oLq-f4iaG_zt3onbC8lzZ4ODht-c","userinfo":{"nickName":"宏","gender":1,"language":"zh_CN","city":"Shantou","province":"Guangdong","country":"China","avatarUrl":"https://wx.qlogo.cn/mmopen/vi_32/V95RibrKF21lbrbunsV0vXSAiaxVWzYgp0mbFzG0GicD4pom4BgEcLrKHAnYJgWxDjmk4jDG8cSgXSfuynjRlibfjg/132"}}
        $userinfoObj = json_decode($userinfo, true);
        $data = array(
            'userinfo'      => $userinfo,
            'wx_nickname'   => $userinfoObj['nickName'],
            'wx_sex'        => $userinfoObj['gender'],
            'wx_language'   => $userinfoObj['language'],
            'wx_city'       => $userinfoObj['city'],
            'wx_province'   => $userinfoObj['province'],
            'wx_country'    => $userinfoObj['country'],
            'wx_avatarUrl'  => $userinfoObj['avatarUrl']
        );
        if(!$this->checkExistByOpenid($openid)) {
            // 不存在记录，新增
            $data['openid'] = $openid;
            $this->db->insert($this->table, $data);
        } else {
            $where = array(
                'openid'    => $openid
            );
            $this->db->where($where)->update($this->table, $data);
        }
        $result = array(
            'status'    => 0,
            'msg'       => '保存成功'
        );
        return $result;
    }


    /**
     * 检查用户记录是否存在
     **/
    public function checkExistByOpenid($openid) {
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . ' where openid = "' . $openid . '"');
        $result = $query->result_array();
        if(count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function updateLang($openid, $lang) {
        if(!$this->checkExistByOpenid($openid)) {
            // 不存在记录
            return array(
                'status'    => -1,
                'msg'       => '用户异常'
            );
        } else {
            $data = array(
                'lang'  => $lang
            );
            $where = array(
                'openid'    => $openid
            );
            $this->db->where($where)->update($this->table, $data);
            return array(
                'status'    => 0,
                'msg'       => '保存成功'
            );
        }
    }


    /**
     * 获取用户语言
     */
    public function getLangByOpenid($openid = '') {
        if(!$openid) {
            return array(
                'status'    => -1,
                'msg'       => 'openid不可为空',
                'data'      => array(
                    'lang'  => 'en'
                )
            );
        }
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . ' where openid = "' . $openid . '"');
        $result = $query->result_array();
        if(count($result) > 0) {
            return array(
                'status'    => 0,
                'msg'       => '获取成功',
                'data'      => array(
                    'lang'  => $result[0]['lang']
                )
            );
        } else {
            return array(
                'status'    => -1,
                'msg'       => '未找到该用户信息',
                'data'      => array(
                    'lang'  => 'en'
                )
            );
        }
    }

}
