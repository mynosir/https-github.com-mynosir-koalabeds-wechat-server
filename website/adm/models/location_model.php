<?php
/**
 * 地区配置模型
 *
 * @author jiang <qoohj@qq.com>
 *
 */
class Location_model extends MY_Model {

    private $table = 'ko_cloudbeds_city';
    private $fields = 'id, propertyCity, status';
    private $cn_table = 'ko_cloudbeds_city_cn';
    private $cn_fields = 'id, cid, propertyCity';

    public function __construct() {
        parent::__construct();
    }


    /**
     * 获取地区
     * @param  integer $page [description]
     * @param  integer $size [description]
     * @return [type]        [description]
     */
    public function getLocationList($page=1, $size=20) {
        $limitStart = ($page - 1) * $size;
        $where = ' where 1=1 ';
        $select = 'ko_cloudbeds_city.id,ko_cloudbeds_city.propertyCity,ko_cloudbeds_city.status,ko_cloudbeds_city_cn.propertyCity as propertyCity_cn';
        $this->db->select($select);
        $this->db->from($this->table);
        $this->db->join($this->cn_table, 'ko_cloudbeds_city.id = ko_cloudbeds_city_cn.cid','left');
        $result = $this->db->get()->result_array();
        // var_dump($res);
        // // $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . $where . 'order by zorder desc, id asc limit ' . $limitStart . ', ' . $size);
        // $result = $query->result_array();
        //
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
     * 更新地区
     * @param  [type] $id   [description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function updateLocation($id, $data) {
        $data1 = array(
          'propertyCity'=>$data['propertyCity'],
          'status'=>$data['status']
        );
        $data2 = array(
          'cid'=>$id,
          'propertyCity'=>$data['propertyCity_cn']
        );
        $sql = 'select '.$this->cn_fields.' from '.$this->cn_table.' where cid='.$id;
        $res = $this->db->query($sql)->result_array();
        if (count($res)==0) {
          // code...
          $res2 = $this->db->insert($this->cn_table, $data2);
        }else{
          $res2 = $this->db->where('cid', $id)->update($this->cn_table, $data2);
        }
        $res1 = $this->db->where('id', $id)->update($this->table, $data1);
        if($res1&&$res2){
            $result = array(
                'status'    => 0,
                'msg'       => 'Update Success!'
            );
            return $result;

        }else{
            $result = array(
                'status'    => 1,
                'msg'       => 'Update Error!'
            );
            return $result;

        }

    }



    /**
     * 获取地区详情
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getDetail($id) {
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . ' where id="' . $id . '"');
        $res = $query->result_array();
        $query2 = $this->db->query('select ' . $this->cn_fields . ' from ' . $this->cn_table . ' where cid="' . $id . '"');
        $res2 = $query2->result_array();
        // var_dump($res2);
        if(count($res2)==0){
          $res2 = array(
            'propertyCity'=>''
          );
        }else{

          $res2 = $res2[0];

        }
        $result = array(
          'res_en'=>$res[0],
          'res_cn'=>$res2
        );
        return $result;
    }
}
