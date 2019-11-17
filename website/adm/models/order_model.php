<?php
/**
 * 订单模型
 *
 * @author jiang <qoohj@qq.com>
 *
 */
class Order_model extends MY_Model {

    private $table = 'ko_hotel_order';
    private $fields = 'id, openid, propertyID, startDate, endDate, guestFirstName, guestLastName, guestCountry, guestZip, guestEmail, guestPhone, rooms, rooms_roomTypeID, rooms_quantity, adults, adults_roomTypeID, adults_quantity, children, children_roomTypeID, children_quantity, status, total, frontend_total, balance, balanceDetailed, assigned, unassigned, cardsOnFile, reservationID, estimatedArrivalTime, create_time, outTradeNo, transaction_id, transaction_info';
    private $table_wx = 'ko_user';
    private $fields_wx = 'openid, wx_nickname';
    public function __construct() {
        parent::__construct();
    }


    /**
     * 获取广告
     * @param  integer $page [description]
     * @param  integer $size [description]
     * @return [type]        [description]
     */
    public function getOrder($page=1, $size=20, $nickname, $status) {
        $limitStart = ($page - 1) * $size;
        $where = ' where 1=1 ';
        if($status == -1 || $status === '0' || $status > 0){
          $where = ' where status='.$status.' ';
        }
        if($nickname){
          $where2 = ' where wx_nickname like \'%'. $nickname .'%\' ';
          $res = $this->db->query('select ' . $this->fields_wx . ' from ' . $this->table_wx .$where2)->result_array();
          // var_dump($res);
          if($res){
            $queryOpenid = $res[0]['openid'];
            if($status){
              $where = ' where openid = "'.$queryOpenid.'" and status='.$status.' ';
            }else{
              $where = ' where openid = "'.$queryOpenid.'"';
            }
          }else{
            $rtn = array(
              'total' => 0,
              'size'  => 0,
              'page'  => 0,
              'list'  => []
            );
            return $rtn;
          }
          // var_dump($queryOpenid);
        }                
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . $where . 'order by id asc limit ' . $limitStart . ', ' . $size);
        $result = $query->result_array();

        foreach ($result as $k => $v) {
          // code...
          $queryOpenid = $v['openid'];
          $result[$k]['wx_nickname'] = $this->db->query('select ' . $this->fields_wx . ' from ' . $this->table_wx . ' where openid = "'.$queryOpenid.'"')->row()->wx_nickname;
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
    public function updateOrder($id, $data) {
        $this->db->where('id', $id)->update($this->table, $data);
        $result['status'] = 0;
        $result['msg'] = 'Update Success!';
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
        $result['msg'] = 'Delete Success!';
        return $result;
    }


    /**
     * 新增广告
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function addOrder($data) {
        $msg = '';
        if($data['link']=='') $msg = 'The link cannot be empty!';
        if($data['img']=='') $msg = 'The image cannot be empty!';

        if($msg != '') {
            return array(
                'status'    => -1,
                'msg'       => $msg
            );
        }

        $data['zorder'] = (int)$data['zorder'];
        $this->db->insert($this->table, $data);
        $result['status'] = 0;
        $result['msg'] = 'Add success!';
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
