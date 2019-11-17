<?php
/**
 * 评论模型
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class reviews_model extends MY_Model {

    private $table = 'ko_reviews';
    private $fields = 'id, propertyID, orderId, userid, openid, rate, content, create_time, status';

    public function __construct() {
        parent::__construct();
    }


    /**
     * 保存记录
     **/
    public function add($params) {
        $this->load->model('user_model');
        $CI = &get_instance();
        $userid = $CI->user_model->getUserIdByOpenid($params['openid']);
        $data = array(
            'propertyID'    => $params['propertyID'],
            'orderId'       => $params['orderId'],
            'userid'        => $userid,
            'openid'        => $params['openid'],
            'rate'          => $params['rate'],
            'content'       => $params['content'],
            'create_time'   => time()
        );
        $this->db->insert($this->table, $data);
        $result = array(
            'status'    => 0,
            'msg'       => '保存成功！'
        );
        return $result;
    }


    /**
     * 获取酒店平均评价
     */
    public function getReviewsRate($propertyID) {
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . ' where status=0 and propertyID=' . $propertyID);
        $result = $query->result_array();
        $rate = 5;
        $sum = 0;
        $count = 0;
        foreach($result as $k=>$v) {
            $sum += $v['rate'];
            $count += 1;
        }
        if($count > 0) {
            $rate = round($sum / $count, 1);
        }
        $result = array(
            'rate'      => $rate,
            'rateNum'   => $count
        );
        return array(
            'status'    => 0,
            'msg'       => '查询成功',
            'data'      => $result
        );
    }


    /**
     * 获取评论列表
     */
    public function getReviewsList($propertyID, $page = 1, $num = 10) {
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . ' where `propertyID` = ' . $propertyID . ' and status = 0 order by id desc limit ' . ($page - 1) * $num . ' , ' . $num);
        $result = $query->result_array();
        if(count($result) > 0) {
            $rtn = array(
                'status'    => 0,
                'msg'       => '查询成功',
                'data'      => $result
            );
        } else {
            $rtn = array(
                'status'    => -1,
                'msg'       => '没有更多数据'
            );
        }
        return $rtn;
    }


    /**
     * 获取评论，专门为个人显示，不过滤状态值
     */
    public function getReviewsByOrderIdAndOpenid($orderId, $openid) {
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . ' where `orderId` = ' . $orderId . ' and openid = "' . $openid . '"');
        $result = $query->result_array();
        if(count($result) > 0) {
            $rtn = array(
                'status'    => 0,
                'msg'       => '查询成功',
                'data'      => $result[0]
            );
        } else {
            $rtn = array(
                'status'    => -1,
                'msg'       => '没有更多数据'
            );
        }
        return $rtn;
    }
}
