<?php
/**
 * 酒店评论模型
 *
 * @author jiang <qoohj@qq.com>
 *
 */
class Reviews_model extends MY_Model {

    private $table = 'ko_reviews';
    private $fields = 'id, propertyID, userid, rate, content, create_time, status';
    private $table_wx = 'ko_user';
    private $fields_wx = 'id, wx_nickname';
    private $table_hotels = 'ko_cloudbeds_hotels';
    private $fields_hotels = 'propertyID, propertyName';

    public function __construct() {
        parent::__construct();
    }


    /**
     * 获取
     * @param  integer $page [description]
     * @param  integer $size [description]
     * @return [type]        [description]
     */
    public function getReviews($page=1, $size=20, $keyword='', $propertyName) {
        if($propertyName){
            $resHotel = $this->db->query('select ' . $this->fields_hotels . ' from ' . $this->table_hotels . ' where propertyName = "'.$propertyName.'"')->row();
            if($resHotel){
              $queryPropertyId = $resHotel->propertyID;
              if($keyword==''){
                $where = ' where propertyID = "'.$queryPropertyId.'"';
              }else{
                $where = ' where content like \'%'. $keyword .'%\' and propertyID = "'.$queryPropertyId.'"';
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
        }else if($keyword!='') {
            $where = ' where content like \'%'. $keyword .'%\' ';
        } else {
            $where = ' where 1=1 ';
        }
        $limitStart = ($page - 1) * $size;
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . $where . 'order by id asc limit ' . $limitStart . ', ' . $size);
        $result = $query->result_array();
        // foreach($result as &$item) {
        // }


        foreach ($result as $k => &$v) {
          // code...
          if($v['create_time']) {
              $v['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
          } else {
              $v['create_time'] = '';
          }
          $queryid = $v['userid'];
          $result[$k]['wx_nickname'] = $this->db->query('select ' . $this->fields_wx . ' from ' . $this->table_wx . ' where id = "'.$queryid.'"')->row()->wx_nickname;
          $resHotel = $this->db->query('select ' . $this->fields_hotels . ' from ' . $this->table_hotels . ' where propertyID = "'.$v['propertyID'].'"')->row();
          if($resHotel){
            $result[$k]['propertyName'] = $resHotel->propertyName;
          }else{
            $result[$k]['propertyName'] = '';
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
     * 更新评论状态
     **/
    public function updateStatus($id,$params) {
        $res = $this->db->where('id', $id)->update($this->table, $params);
        if($res){
          $result = array(
              'status'    => 0,
              'msg'       => 'Update Success!'
          );
          return $result;

        }

    }

    /**
     * 更新
     * @param  [type] $id   [description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function update($id, $data) {
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
        $result['msg'] = '删除成功';
        return $result;
    }


    /**
     * 新增广告
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function addBasic($data) {
        $msg = '';
        if($data['link']=='') $msg = '链接不可为空！';
        if($data['img']=='') $msg = '图片不可为空！';

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
     * 获取详情
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getDetail($id) {
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . ' where id="' . $id . '"');
        $result = $query->result_array();
        return $result[0];
    }
}
