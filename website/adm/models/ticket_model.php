<?php
/**
 * grayline票模型
 *
 * @author huang <qoohj@qq.com>
 *
 */
class Ticket_model extends MY_Model {

    private $ticket_table = 'ko_grayline_ticket_info';
    private $cn_ticket_table = 'ko_grayline_ticket_info_cn';
    private $hotels_table = 'ko_cloudbeds_hotels';
    private $ticket_fields = 'id, productId, title, type, introduce, clause';
    private $cn_ticket_fields = 'id, productId, title, type, introduce, clause';
    private $hotels_fields = 'propertyID, propertyName';

    public function __construct() {
        parent::__construct();
    }

    /**
     * 获取票列表
     **/
    public function getTicketsList($page=1, $size=6, $keyword='') {
      // var_dump($on);

      if($keyword!='') {
          $where = ' where title like "%'.$keyword.'%"';
      } else {
          $where = ' where 1=1 ';
      }

      $limitStart = ($page - 1) * $size;

      $sql = 'select '.$this->ticket_fields.' from '.$this->ticket_table.$where;
      $res = $this->db->query($sql)->result_array();

      $sql2 = 'select '.$this->cn_ticket_fields.' from '.$this->cn_ticket_table;
      $res2 = $this->db->query($sql2)->result_array();

      for ($i=0; $i < count($res); $i++) {
        // code...
        for ($j=0; $j < count($res2); $j++) {
          // code...
          if ($res[$i]['productId']==$res2[$j]['productId']) {
            // code...
            $res[$i]['title_cn'] = $res2[$j]['title'];
            $res[$i]['introduce_cn'] = $res2[$j]['introduce'];
            $res[$i]['clause_cn'] = $res2[$j]['clause'];
          }
        }
      }

      $pageQuery = $this->db->query('select count(1) as num from ' . $this->ticket_table . $where);
      $pageResult = $pageQuery->result_array();
      $num = $pageResult[0]['num'];
      $rtn = array(
          'total' => $num,
          'size'  => $size,
          'page'  => $page,
          'list'  => $res
      );
      return $rtn;
    }

    /**
     * 获取票详情
     **/
    public function getTicketDetail($id) {
          if($id <= 0) {
              return false;
          }
          $sql = 'select '.$this->ticket_fields.' from '.$this->ticket_table.' where id='.$id;
          $res = $this->db->query($sql)->result_array()[0];
          // var_dump($res);
          $sql2 = 'select '.$this->cn_ticket_fields.' from '.$this->cn_ticket_table.' where id='.$id;
          // var_dump($sql2);
          $res2 = $this->db->query($sql2)->result_array()[0];
          // var_dump($res2);
          $res['title_cn'] = $res2['title'];
          $res['introduce_cn'] = $res2['introduce'];
          $res['clause_cn'] = $res2['clause'];
          return $res;
    }

    /**
     * 保存房间详情
     **/
    public function save($id,$params) {
        $data1 = array(
          'title'=>$params['title'],
          'introduce'=>$params['introduce'],
          'clause'=>$params['clause']
        );
        $data2 = array(
          'title'=>$params['title_cn'],
          'introduce'=>$params['introduce_cn'],
          'clause'=>$params['clause_cn']
        );

        $res = $this->db->where('productId', $id)->update($this->ticket_table, $data1);
        $res2 = $this->db->where('productId', $id)->update($this->cn_ticket_table, $data2);
        if($res&&$res2){
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



}
