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




    public function getTicketId($params) {

      $where = ' where productId='.$params['productId'].' and type="'.$params['type'].'"';
      $sql = 'select '.$this->ticket_fields.' from '.$this->ticket_table.$where;
      $res = $this->db->query($sql)->result_array();
      if(count($res)==0){
        $result = '';
      }else{
        $result = $res[0]['id'];
      }
      return $result;
    }


    /**
     * 获取票列表
     **/
    public function getTicketsList($page=1, $size=6, $keyword='') {
      // var_dump($on);

      if($keyword!='') {
          $where = ' where ko_grayline_ticket_info.title like "%'.$keyword.'%" or ko_grayline_ticket_info_cn.title like "%'.$keyword.'%" ';
      } else {
          $where = ' where 1=1 ';
      }

      $limitStart = ($page - 1) * $size;

      // $sql1 = 'select '.$this->ticket_fields.' from '.$this->ticket_table.$where.' limit ' . $limitStart . ', ' . $size;
      // $res1 = $this->db->query($sql)->result_array();
      //
      // $sql2 = 'select '.$this->cn_ticket_fields.' from '.$this->cn_ticket_table;
      // $res2 = $this->db->query($sql2)->result_array();
      //
      // $sql1 = 'select '.$this->ticket_fields.' from '.$this->ticket_table.$where;
      // $sql2 = 'select '.$this->cn_ticket_fields.' from '.$this->cn_ticket_table.$where;
      // $sql = $sql1.' union '.$sql2.' limit '.$limitStart.', '.$size;
      // $res = $this->db->query($sql)->result_array();
      $sql1 = 'select ko_grayline_ticket_info.productId,ko_grayline_ticket_info.type,ko_grayline_ticket_info.title,ko_grayline_ticket_info_cn.title as title_cn from ko_grayline_ticket_info left join ko_grayline_ticket_info_cn on ko_grayline_ticket_info.productId=ko_grayline_ticket_info_cn.productId and ko_grayline_ticket_info.type=ko_grayline_ticket_info_cn.type'.$where;
      // var_dump($sql);
      $res = $this->db->query($sql1)->result_array();
      // var_dump($res);
      $sql2 = 'select ko_grayline_ticket_info_cn.productId,ko_grayline_ticket_info_cn.type,ko_grayline_ticket_info.title,ko_grayline_ticket_info_cn.title as title_cn from ko_grayline_ticket_info right join ko_grayline_ticket_info_cn on ko_grayline_ticket_info.productId=ko_grayline_ticket_info_cn.productId and ko_grayline_ticket_info.type=ko_grayline_ticket_info_cn.type'.$where;
      // var_dump($sql);
      $sql = $sql1.' union '.$sql2.' limit '. $limitStart . ', '.$size;
      $res = $this->db->query($sql)->result_array();
      // var_dump($res);

      $sqlSum = $sql1.' union '.$sql2;
      $resSum = $this->db->query($sqlSum)->result_array();
      // $pageQuery = $this->db->query('select count(1) as num from ' . $this->ticket_table);
      // $pageResult = $pageQuery->result_array();
      $num = count($resSum);
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
    public function getTicketDetail($id, $type) {
          if($id <= 0) {
              return false;
          }
          $sql = 'select '.$this->ticket_fields.' from '.$this->ticket_table.' where productId='.$id.' and type="'.$type.'"';
          $res = $this->db->query($sql)->result_array();
          // var_dump($res);
          $sql2 = 'select '.$this->cn_ticket_fields.' from '.$this->cn_ticket_table.' where productId='.$id.' and type="'.$type.'"';
          // var_dump($sql2);
          $res2 = $this->db->query($sql2)->result_array();
          // var_dump($res2);

          // $sql = 'select ko_grayline_ticket_info.*,ko_grayline_ticket_info_cn.title as title_cn,ko_grayline_ticket_info_cn.introduce as introduce_cn,ko_grayline_ticket_info_cn.clause as clause_cn from ko_grayline_ticket_info join ko_grayline_ticket_info_cn on ko_grayline_ticket_info.productId=ko_grayline_ticket_info_cn.productId and ko_grayline_ticket_info.type=ko_grayline_ticket_info_cn.type where ko_grayline_ticket_info.productId='.$id;
          // var_dump($sql);
          // $res = $this->db->query($sql)->result_array();
          // var_dump($res);

          // $res['title_cn'] = $res2['title'];
          // $res['introduce_cn'] = $res2['introduce'];
          // $res['clause_cn'] = $res2['clause'];


          if(count($res)==0){
            $res = array(
              'title'=>'',
              'introduce'=>'',
              'clause'=>''
            );
          }else{
            $res = $res[0];
          }
          if(count($res2)==0){
            $res2 = array(
              'title'=>'',
              'introduce'=>'',
              'clause'=>''
            );
          }else{
            $res2 = $res2[0];
          }

          $result = array(
            'res_en'=>$res,
            'res_cn'=>$res2
          );
          return $result;
    }

    /**
     * 保存房间详情
     **/
    public function save($productId,$type,$params) {
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
        // var_dump($params['title']);
        // if($params['title'])
        $where = array(
          'productId'=>$productId,
          'type'=>$type
        );
        $sql = 'select '.$this->ticket_fields.' from '.$this->ticket_table.' where productId='.$productId.' and type="'.$type.'"';
        $res = $this->db->query($sql)->result_array();
        if(count($res)==0){
          $data1['productId'] = $productId;
          $data1['type'] = $type;
          $res = $this->db->insert($this->ticket_table, $data1);
        }else{
          $res = $this->db->where($where)->update($this->ticket_table, $data1);
        }
        $sql2 = 'select '.$this->cn_ticket_fields.' from '.$this->cn_ticket_table.' where productId='.$productId.' and type="'.$type.'"';
        $res2 = $this->db->query($sql2)->result_array();
        if(count($res2)==0){
          $data2['productId'] = $productId;
          $data2['type'] = $type;
          $res2 = $this->db->insert($this->cn_ticket_table, $data2);
        }else{
          $res2 = $this->db->where($where)->update($this->cn_ticket_table, $data2);
        }
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




        // $res = $this->db->where('productId', $id)->update($this->ticket_table, $data1);
        // $res2 = $this->db->where('productId', $id)->update($this->cn_ticket_table, $data2);
        // if($res&&$res2){
        //     $result = array(
        //         'status'    => 0,
        //         'msg'       => 'Update Success!'
        //     );
        //     return $result;
        //
        // }else{
        //     $result = array(
        //         'status'    => 1,
        //         'msg'       => 'Update Error!'
        //     );
        //     return $result;
        //
        // }

    }



}
