<?php
/**
 * 优惠券领取模型
 *
 * @author jiang <qoohj@qq.com>
 *
 */
class Coupon_record_model extends MY_Model {

    private $table = 'ko_coupon_record';
    private $fields = 'id, openid, cid, status, create_time';
    private $table_wx = 'ko_user';
    private $fields_wx = 'openid, wx_nickname';
    private $table_coupon = 'ko_coupon';
    private $fields_coupon = 'id, totalAmount, discountAmount';

    public function __construct() {
        parent::__construct();
    }


    /**
     * 获取广告
     * @param  integer $page [description]
     * @param  integer $size [description]
     * @return [type]        [description]
     */
    public function getCouponRecord($page=1, $size=20, $nickname) {
        $limitStart = ($page - 1) * $size;
        $where = ' where 1=1 ';
        if($nickname){
          $resQueryNickname = $this->db->query('select ' . $this->fields_wx . ' from ' . $this->table_wx . ' where wx_nickname = "'.$nickname.'"')->row();
          // var_dump($resQueryNickname);

          if($resQueryNickname){
            $queryOpenid2 = $resQueryNickname->openid;
            $where = ' where openid = "'.$queryOpenid2.'"';

          }else{
            $rtn = array(
                'total' => 0,
                'size'  => 0,
                'page'  => 0,
                'list'  => []
            );
            return $rtn;

          }

        }
        // var_dump($nickname);
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . $where . 'order by create_time desc limit ' . $limitStart . ', ' . $size);
        $result = $query->result_array();
        foreach ($result as $k => $v) {
          // code...
          $queryOpenid = $v['openid'];
          $queryCouponid = $v['cid'];
          $query2 = $this->db->query('select ' . $this->fields_wx . ' from ' . $this->table_wx . ' where openid = "'.$queryOpenid.'"')->row();
          if($query2){
              $result[$k]['wx_nickname'] = $query2->wx_nickname;
          }
          $query3 = $this->db->query('select ' . $this->fields_coupon . ' from ' . $this->table_coupon . ' where id = "'.$queryCouponid.'"')->row();
          if($query3){
              $result[$k]['totalAmount'] = $query3->totalAmount;
              $result[$k]['discountAmount'] = $query3->discountAmount;
          }
          if($result[$k]['create_time']) {
              $result[$k]['create_time'] = date('Y-m-d H:i:s', $result[$k]['create_time']);
          } else {
              $result[$k]['create_time'] = '';
          }

        }
        $pageQuery = $this->db->query('select count(1) as num from ' . $this->table);
        $pageResult = $pageQuery->result_array();
        $num = $pageResult[0]['num'];
        $rtn = array(
            'total' => $num,
            'size'  => $size,
            'page'  => $page,
            'list'  => $result
        );
        return $rtn;
    }


    /**
     * 更新广告
     * @param  [type] $id   [description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function updateCouponRecord($id, $data) {
        $this->db->where('id', $id)->update($this->table, $data);
        $result['status'] = 0;
        $result['msg'] = '更新数据成功';
        return $result;
    }


    /**
     * 删除
     * @param  [type] $id   [description]
     * @return [type]       [description]
     */
    public function deleteItem($id) {
        $this->db->where('id', $id)->delete($this->table);
        $result['status'] = 0;
        $result['msg'] = '删除成功';
        return $result;
    }


    /**
     * 新增广告
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function addCouponRecord($data) {
        $msg = '';
        if($data['totalAmount']=='') $msg = '达成优惠金额不可为空！';
        if($data['discountAmount']=='') $msg = '优惠金额不可为空！';
        if($data['validateDate']=='') $msg = '有效期不可为空！';

        if($msg != '') {
            return array(
                'status'    => -1,
                'msg'       => $msg
            );
        }

        $data['zorder'] = (int)$data['zorder'];
        $this->db->insert($this->table, $data);
        $result['status'] = 0;
        $result['msg'] = '新增数据成功';
        return $result;
    }


    /**
     * 获取广告详情
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getDetail($id) {
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . ' where id="' . $id . '"');
        $result = $query->result_array();
        return $result[0];
    }
}
