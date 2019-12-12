<?php
/**
 * 房间模型
 *
 * @author huang <qoohj@qq.com>
 *
 */
class Rooms_model extends MY_Model {

    private $rooms_table = 'ko_cloudbeds_roomtypes';
    private $cn_rooms_table = 'ko_cloudbeds_roomtypes_cn';
    private $rooms_log_table = 'ko_cloudbeds_roomtypes_log';
    private $hotels_table = 'ko_cloudbeds_hotels';
    private $rooms_fields = 'id, propertyID, roomTypeID, roomTypeName, roomTypeNameShort, roomTypeDescription';
    private $hotels_fields = 'propertyID, propertyName';
    private $rooms_log_fields = 'roomTypeID, status';
    private $cn_rooms_fields = 'id, rid, propertyID, roomTypeID, roomTypeName, roomTypeNameShort, roomTypeDescription';

    public function __construct() {
        parent::__construct();
    }

    /**
     * 获取房间列表
     **/
    public function getRoomsList($page=1, $size=6, $keyword='') {
      $on = $this->rooms_table.'.id='.$this->cn_rooms_table.'.rid';
      // var_dump($on);

      if($keyword!='') {
          $where = ' where propertyID='.$keyword;
      } else {
          $where = ' where 1=1 ';
      }

      $limitStart = ($page - 1) * $size;
      // $sql = 'select * from ko_cloudbeds_roomtypes,ko_cloudbeds_roomtypes_cn';
      // $sql = 'select ko_cloudbeds_roomtypes.id,ko_cloudbeds_roomtypes.propertyID,ko_cloudbeds_roomtypes.roomTypeID,ko_cloudbeds_roomtypes.roomTypeName,ko_cloudbeds_roomtypes.roomTypeNameShort,ko_cloudbeds_roomtypes.roomTypeDescription,ko_cloudbeds_roomtypes_cn.roomTypeName as roomTypeName_cn,ko_cloudbeds_roomtypes_cn.roomTypeNameShort as roomTypeNameShort_cn,ko_cloudbeds_roomtypes_cn.roomTypeDescription as roomTypeDescription_cn from ko_cloudbeds_roomtypes inner join ko_cloudbeds_roomtypes_cn on ko_cloudbeds_roomtypes.id=ko_cloudbeds_roomtypes_cn.rid';

      $sql = 'select ko_cloudbeds_roomtypes.id,ko_cloudbeds_roomtypes.propertyID,ko_cloudbeds_roomtypes.roomTypeID,ko_cloudbeds_roomtypes.roomTypeName,ko_cloudbeds_roomtypes.roomTypeNameShort,ko_cloudbeds_roomtypes.roomTypeDescription,ko_cloudbeds_roomtypes_log.status from '.$this->rooms_table.' left join '.$this->rooms_log_table.' on ko_cloudbeds_roomtypes.roomTypeID=ko_cloudbeds_roomtypes_log.roomTypeID order by IF(ISNULL(ko_cloudbeds_roomtypes_log.status), 0, ko_cloudbeds_roomtypes_log.status) limit ' . $limitStart . ', ' . $size;
      // var_dump($sql);

      // $sql = 'select '.$this->rooms_fields.' from '.$this->rooms_table.$where.' order by propertyID';
      $query = $this->db->query($sql);
      $result = $query->result_array();
      // 中文信息表
      $sql2 = 'select '.$this->cn_rooms_fields.' from '.$this->cn_rooms_table;
      $query2 = $this->db->query($sql2);
      $result2 = $query2->result_array();
      for ($i=0; $i < count($result2); $i++) {
        // code...
        for ($j=0; $j < count($result); $j++) {
          // code...
          if($result2[$i]['rid']==$result[$j]['id']){
            $result[$j]['roomTypeName_cn']=$result2[$i]['roomTypeName'];
            $result[$j]['roomTypeNameShort_cn']=$result2[$i]['roomTypeNameShort'];
            $result[$j]['roomTypeDescription_cn']=$result2[$i]['roomTypeDescription'];
          }
        }
      }
      // 房间中文名
      $sql3 = 'select '.$this->hotels_fields.' from '.$this->hotels_table;
      $query3 = $this->db->query($sql3);
      $result3 = $query3->result_array();
      for ($i=0; $i < count($result); $i++) {
        // code...
          for ($j=0; $j < count($result3); $j++) {
            // code...
            if($result[$i]['propertyID']==$result3[$j]['propertyID']){
              $result[$i]['propertyName']=$result3[$j]['propertyName'];
            }
          }
      }
      // // 房间状态
      // $sql4 = 'select '.$this->rooms_log_fields.' from '.$this->rooms_log_table;
      // $query4 = $this->db->query($sql4);
      // $result4 = $query4->result_array();
      // for ($i=0; $i < count($result); $i++) {
      //   // code...
      //     for ($j=0; $j < count($result4); $j++) {
      //       // code...
      //       if($result[$i]['roomTypeID']==$result4[$j]['roomTypeID']){
      //         $result[$i]['status']=$result4[$j]['status'];
      //       }
      //     }
      // }
      // var_dump($result);
      // var_dump($result);
      // var_dump($result);
      // $query = $this->db->query('select ' . $this->rooms_fields . ' from ' . $this->rooms_table . ' inner join '.$this->cn_rooms_table .' on ' . $on . $where . ' order by id desc limit ' . $limitStart . ', ' . $size);
      // $result = $query->result_array();
      // $query = $this->db->query('select ' . $this->cn_hotel_fields . ' from ' . $this->cn_table);
      // $result2 = $query->result_array();
      // // var_dump($result2);
      // // $result['name_cn'] = $result2[0]['propertyName'];
      // for ($i=0; $i < count($result2); $i++) {
      //   // code...
      //   for ($j=0; $j < count($result); $j++) {
      //     // code...
      //     if($result2[$i]['hid']==$result[$j]['id']){
      //       $result[$j]['name_cn'] = $result2[$i]['propertyName'];
      //     }
      //   }
      // }
      //
      // // var_dump($result);
      $pageQuery = $this->db->query('select count(1) as num from ' . $this->rooms_table . $where);
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
     * 获取房间详情
     **/
    public function getRoomDetail($id) {
          if($id <= 0) {
              return false;
          }
          $query = $this->db->query('select ' . $this->cn_rooms_fields . ' from ' . $this->cn_rooms_table . ' where rid="' . $id . '"');
          $result = $query->result_array();

          $query2 = $this->db->query('select ' . $this->rooms_fields . ' from ' . $this->rooms_table . ' where id="' . $id . '"');
          $result2 = $query2->result_array();

          if(count($result)>0){
            $searchPropertyID = $result[0]['propertyID'];
          }else{
            // $searchPropertyID = '';
            $result[0]['roomTypeName'] = '';
            $result[0]['roomTypeNameShort'] = '';
            $result[0]['roomTypeDescription'] = '';
            $searchPropertyID = $result2[0]['propertyID'];

          }
          $query3 = $this->db->query('select ' . $this->hotels_fields . ' from ' . $this->hotels_table . ' where propertyID="' . $searchPropertyID . '"');
          $result3 = $query3->row();
          if(count($result3)>0){
            // var_dump($result3);
            $propertyName = $result3->propertyName;
          }else{
            $propertyName = '';
          }
          $rtn = array(
            'cn' => $result[0],
            'en' => $result2[0],
            'propertyName' => $propertyName
          );
          return $rtn;
    }

    /**
     * 保存房间详情
     **/
    public function save($id,$params) {
        // 检查中文表里是否存在数据
        $sql = 'select '.$this->cn_rooms_fields.' from '.$this->cn_rooms_table.' where rid='.$id;
        // var_dump($sql);
        $query = $this->db->query($sql);
        $result = $query->result_array();
        if(count($result)>0){
          $res = $this->db->where('rid', $id)->update($this->cn_rooms_table, $params);
        }else{
          $data = array(
            'rid'=>$id,
            'propertyID'=>$params['propertyID'],
            'roomTypeID'=>$params['roomTypeID'],
            'roomTypeName'=>$params['roomTypeName'],
            'roomTypeNameShort'=>$params['roomTypeNameShort'],
            'roomTypeDescription'=>$params['roomTypeDescription']
          );
          $res = $this->db->insert($this->cn_rooms_table, $data);

        }
        if($res){
          // $res2 = $this->db->where('roomTypeID', 11)->update($this->rooms_log_table, array('status'=>1));
          // var_dump($res2);
          // $sql = 'select * from '.$this->rooms_log_table.' where roomTypeID ='.$params['roomTypeID'];
          // $query = $this->db->query($sql);
          // $result = $query->result_array();
          // if(count($result)>0){
          //
          // }
          //
          // if($result){
            $sql2 = 'update '.$this->rooms_log_table.' set status=1 where roomTypeID='.$params['roomTypeID'];
            // var_dump($sql2);
            $res2 = $this->db->query($sql2);
          // }else{
          //
          // }
          if($res2){
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


}
