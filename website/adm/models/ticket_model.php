<?php
/**
 * grayline票模型
 *
 * @author huang <qoohj@qq.com>
 *
 */
class Ticket_model extends MY_Model {

    private $ticket_table = 'ko_grayline_ticket_info_v2';
    private $cn_ticket_table = 'ko_grayline_ticket_info_cn_v2';
    private $hotels_table = 'ko_cloudbeds_hotels';
    private $ticket_fields = 'id, productId, title, code, image, type, introduce, clause, status';
    private $cn_ticket_fields = 'id, tiid, productId, title, introduce, clause';
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
          $where = ' where title like "%'.$keyword.'%" ';
      } else {
          $where = ' where 1=1 ';
      }

      $limitStart = ($page - 1) * $size;


      // $sql1 = 'select ko_grayline_ticket_info.productId,ko_grayline_ticket_info.type,ko_grayline_ticket_info.title,ko_grayline_ticket_info_cn.title as title_cn from ko_grayline_ticket_info left join ko_grayline_ticket_info_cn on ko_grayline_ticket_info.productId=ko_grayline_ticket_info_cn.productId and ko_grayline_ticket_info.type=ko_grayline_ticket_info_cn.type'.$where;
      // // var_dump($sql);
      // $res = $this->db->query($sql1)->result_array();
      // // var_dump($res);
      // $sql2 = 'select ko_grayline_ticket_info_cn.productId,ko_grayline_ticket_info_cn.type,ko_grayline_ticket_info.title,ko_grayline_ticket_info_cn.title as title_cn from ko_grayline_ticket_info right join ko_grayline_ticket_info_cn on ko_grayline_ticket_info.productId=ko_grayline_ticket_info_cn.productId and ko_grayline_ticket_info.type=ko_grayline_ticket_info_cn.type'.$where;
      // // var_dump($sql);
      // $sql = $sql1.' union '.$sql2.' limit '. $limitStart . ', '.$size;
      // $res = $this->db->query($sql)->result_array();
      // // var_dump($res);
      //
      // $sqlSum = $sql1.' union '.$sql2;
      // $resSum = $this->db->query($sqlSum)->result_array();
      // $pageQuery = $this->db->query('select count(1) as num from ' . $this->ticket_table);
      // $pageResult = $pageQuery->result_array();
      // $num = count($resSum);


      $query = $this->db->query('select ' . $this->ticket_fields . ' from ' . $this->ticket_table . $where . ' limit ' . $limitStart . ', ' . $size);
      $result = $query->result_array();
      $query2 = $this->db->query('select ' . $this->cn_ticket_fields . ' from ' . $this->cn_ticket_table . $where . ' limit ' . $limitStart . ', ' . $size);
      $result2 = $query2->result_array();
      // var_dump($result2);
      if (count($result2)>0) {
        // code...
        for ($i=0; $i < count($result); $i++) {
          // code...
          $result[$i]['title_cn'] = '';
          $result[$i]['introduce_cn'] = '';
          $result[$i]['clause_cn'] = '';
          for ($j=0; $j < count($result2); $j++) {
            // code...
            if ($result[$i]['id']==$result2[$j]['tiid']) {
              // code...
              $result[$i]['title_cn'] = $result2[$j]['title'];
              $result[$i]['introduce_cn'] = $result2[$j]['introduce'];
              $result[$i]['clause_cn'] = $result2[$j]['clause'];
            }

          }
        }
      }else{
        for ($i=0; $i < count($result); $i++) {
          // code...
          $result[$i]['title_cn'] = '';
          $result[$i]['introduce_cn'] = '';
          $result[$i]['clause_cn'] = '';
        }
      }
      $pageQuery = $this->db->query('select count(1) as num from ' . $this->ticket_table.$where);
      $pageResult = $pageQuery->result_array();
      $num = $pageResult[0]['num'];
      // var_dump($num);
      $rtn = array(
          'total' => $num,
          'size'  => $size,
          'page'  => $page,
          'list'  => $result
      );
      // var_dump($rtn);
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
          $res = $this->db->query($sql)->result_array();
          // var_dump($res);
          $sql2 = 'select '.$this->cn_ticket_fields.' from '.$this->cn_ticket_table.' where tiid='.$id;
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
     * 更新酒店房间
     **/
    public function updateStatus($id,$params) {
        $res = $this->db->where('id', $id)->update($this->ticket_table, $params);
        if($res){
          $result = array(
              'status'    => 0,
              'msg'       => 'Update Success!'
          );
          return $result;

        }

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
        // var_dump($params['title']);
        // if($params['title'])
        $where = array(
          'id'=>$id
        );
        $where2 = array(
          'tiid'=>$id
        );
        // $sql = 'select '.$this->ticket_fields.' from '.$this->ticket_table.' where id='.$id.;
        // $res = $this->db->query($sql)->result_array();
        $res = $this->db->where($where)->update($this->ticket_table, $data1);
        $sql2 = 'select '.$this->cn_ticket_fields.' from '.$this->cn_ticket_table.' where id='.$id;
        $res2 = $this->db->query($sql2)->result_array();
        if(count($res2)==0){
          $data2['tiid'] = $id;
          $res2 = $this->db->insert($this->cn_ticket_table, $data2);
        }else{
          $res2 = $this->db->where($where2)->update($this->cn_ticket_table, $data2);
        }





        // if(count($res)==0){
        //   $data1['productId'] = $productId;
        //   $data1['type'] = $type;
        //   $res = $this->db->insert($this->ticket_table, $data1);
        // }else{
        //   $res = $this->db->where($where)->update($this->ticket_table, $data1);
        // }
        // $sql2 = 'select '.$this->cn_ticket_fields.' from '.$this->cn_ticket_table.' where productId='.$productId.' and type="'.$type.'"';
        // $res2 = $this->db->query($sql2)->result_array();
        // if(count($res2)==0){
        //   $data2['productId'] = $productId;
        //   $data2['type'] = $type;
        //   $res2 = $this->db->insert($this->cn_ticket_table, $data2);
        // }else{
        //   $res2 = $this->db->where($where)->update($this->cn_ticket_table, $data2);
        // }
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
