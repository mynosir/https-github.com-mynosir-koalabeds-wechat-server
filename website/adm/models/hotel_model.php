<?php
/**
 * 酒店模型
 *
 * @author huang <qoohj@qq.com>
 *
 */
class Hotel_model extends MY_Model {

    private $table = 'ko_cloudbeds_hotels';
    private $cn_table = 'ko_cloudbeds_hotels_cn';
    private $fields = 'id, propertyID, propertyName, propertyImageThumb, propertyPhone, propertyEmail, propertyCity, propertyState,status';
    private $hotel_fields = 'id,propertyID,propertyName,propertyImage,propertyImageThumb,propertyPhone,propertyEmail,propertyAddress1,propertyAddress2,propertyCity,propertyState,propertyZip,propertyCountry,propertyLatitude,propertyLongitude,propertyCheckInTime,propertyCheckOutTime,propertyLateCheckOutAllowed,propertyLateCheckOutType,propertyLateCheckOutValue,propertyTermsAndConditions,propertyAmenities,propertyDescription,propertyTimezone,propertyCurrencyCode,propertyCurrencySymbol,propertyCurrencyPosition,status';
    private $cn_hotel_fields = 'id, hid, propertyID, propertyName, propertyDescription, propertyAddress';
    private $school_fields = 'id, name, type, area';

    public function __construct() {
        parent::__construct();
    }

    /**
     * 获取酒店列表
     **/
    public function getHotelList($page=1, $size=6, $keyword='') {
      if($keyword!='') {
          $where = ' where propertyName like \'%'. $keyword .'%\' ';
      } else {
          $where = ' where 1=1 ';
      }

      $limitStart = ($page - 1) * $size;

      $query = $this->db->query('select ' . $this->hotel_fields . ' from ' . $this->table . $where . ' order by id desc limit ' . $limitStart . ', ' . $size);
      $result = $query->result_array();
      $query = $this->db->query('select ' . $this->cn_hotel_fields . ' from ' . $this->cn_table);
      $result2 = $query->result_array();
      // var_dump($result2);
      // $result['name_cn'] = $result2[0]['propertyName'];
      for ($i=0; $i < count($result2); $i++) {
        // code...
        for ($j=0; $j < count($result); $j++) {
          // code...
          if($result2[$i]['hid']==$result[$j]['id']){
            $result[$j]['name_cn'] = $result2[$i]['propertyName'];
          }
        }
      }

      // var_dump($result);
      $pageQuery = $this->db->query('select count(1) as num from ' . $this->table . $where);
      $pageResult = $pageQuery->result_array();
      $num = $pageResult[0]['num'];
      $rtn = array(
          'total' => $num,
          'size'  => $size,
          'page'  => $page,
          'list'  => $result
      );
      return $rtn;
      return $rtn;
    }

    /**
     * 获取酒店详情
     **/
    public function getHotelDetail($id) {
          if($id <= 0) {
              return false;
          }
          $query = $this->db->query('select ' . $this->hotel_fields . ' from ' . $this->table . ' where id="' . $id . '"');
          $result = $query->result_array();
          return $result[0];
    }

    /**
     * 获取酒店详情（中文）
     **/
    public function getHotelDetailCn($id) {
          if($id <= 0) {
              return false;
          }
          $query = $this->db->query('select ' . $this->cn_hotel_fields . ' from ' . $this->cn_table . ' where hid="' . $id . '"');
          $result = $query->result_array();
          if(count($result)>0){
            return $result[0];
          }else{
            return $result;
          }
    }

    /**
     * 更新酒店状态
     **/
    public function updateStatus($id,$params) {
            $res = $this->db->where('id', $id)->updateStatus($this->table, $params);
            if($res){
              $result = array(
                  'status'    => 0,
                  'msg'       => 'Update Success!'
              );
              return $result;

            }

    }

    /**
     * 更新酒店信息
     **/
    public function update($params,$id) {
            $res = $this->db->where('id', $id)->update($this->table, $params['en']);
            if($res){
              $query = $this->db->query('select ' . $this->cn_hotel_fields . ' from ' . $this->cn_table . ' where hid="' . $id .'"');
              $res2 = $query->result_array();
              if (count($res2)>0) {
                // code...
                $this->db->where('hid', $id)->update($this->cn_table, $params['ch']);
              }else{
                // var_dump($params);
                $data = array(
                    'hid'   => $id,
                    'propertyID' => $params['ch']['propertyID'],
                    'propertyName' => $params['ch']['propertyName'],
                    'propertyAddress' => $params['ch']['propertyAddress'],
                    'propertyDescription' => $params['ch']['propertyDescription']
                );
                $this->db->insert($this->cn_table, $data);

              }
              $result = array(
                  'status'    => 0,
                  'msg'       => 'Update Success!'
              );
              return $result;

            }

    }


    /**
     * 更新酒店信息(中文)
     **/
    public function updateCh($params,$id) {
            $query = $this->db->query('select ' . $this->cn_hotel_fields . ' from ' . $this->$cn_table);
            var_dump($query);
            $result = $query->result_array();
            var_dump($result);
            // $this->db->where('hid', $id)->update($this->table, $params);
            $result = array(
                'status'    => 0,
                'msg'       => 'Update Success!'
            );
            return $result;
    }
    // /**
    //  * 获取捐款类别分类列表
    //  **/
    // public function getClassList() {
    //     $query = $this->db->query('select ' . $this->class_fields . ' from ' . $this->class_table . ' order by zsort desc');
    //     $result = $query->result_array();
    //     return $result;
    // }
    //
    //
    // /**
    //  * 获取捐款学校列表
    //  **/
    // public function getSchoolList() {
    //     $query = $this->db->query('select ' . $this->school_fields . ' from ' . $this->school_table);
    //     $result = $query->result_array();
    //     return $result;
    // }
    //
    //
    // /**
    //  * 新增/更新可冠名捐款项目
    //  **/
    // public function add($id = 0, $params) {
    //     $data = array(
    //         'cid'   => $params['cid'],
    //         'sid'   => $params['sid'],
    //         'money_czbk'    => $params['money_czbk'],
    //         'money_xxzc'    => $params['money_xxzc'],
    //         'money_kgmjz'   => $params['money_kgmjz'],
    //         'money_total'   => $params['money_total'],
    //         'floorage'  => $params['floorage'],
    //         'situation' => $params['situation'],
    //         'situation' => $params['situation'],
    //         'status'    => $params['status'],
    //         'create_time'   => time()
    //     );
    //     if($id == 0) {
    //         $this->db->insert($this->table, $data);
    //         $result = array(
    //             'status'    => 0,
    //             'msg'       => '保存成功！'
    //         );
    //         return $result;
    //     } else {
    //         $this->db->where('id', $id)->update($this->table, $data);
    //         $result = array(
    //             'status'    => 0,
    //             'msg'       => '更新成功！'
    //         );
    //         return $result;
    //     }
    // }
    //
    //
    // /**
    //  * 分页获取项目数据
    //  * @param  integer $page    [description]
    //  * @param  integer $size    [description]
    //  * @param  string  $keyword [description]
    //  * @return [type]           [description]
    //  */
    // public function getList($page = 1, $size = 20, $keyword = '') {
    //     $result = array();
    //     $keywordArr = array();
    //     if($keyword['status'] != -1) {
    //         $keywordArr[] = ' `status` = ' . $keyword['status'] . ' ';
    //     }
    //     if(count($keywordArr) > 0) {
    //         $keyword = implode(' and ', $keywordArr);
    //         $where = ' where (' . $keyword . ') ';
    //     } else {
    //         $where = ' where 1=1 ';
    //     }
    //     // var_dump($where);
    //     $limitStart = ($page - 1) * $size;
    //     $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . $where . ' order by create_time desc limit ' . $limitStart . ', ' . $size);
    //     $result = $query->result_array();
    //     foreach($result as $k=>&$v) {
    //         $v['classInfo'] = $this->getClassById($v['cid']);
    //         if($v['classInfo']['pid'] == 0) {
    //             $v['classInfo']['pname'] = '建筑类';
    //         } else if($v['classInfo']['pid'] == 1) {
    //             $v['classInfo']['pname'] = '运动类';
    //         } else if($v['classInfo']['pid'] == 2) {
    //             $v['classInfo']['pname'] = '功能场室类';
    //         } else if($v['classInfo']['pid'] == 3) {
    //             $v['classInfo']['pname'] = '景观类';
    //         } else {
    //             $v['classInfo']['pname'] = '';
    //         }
    //         $v['schoolInfo'] = $this->getSchoolById($v['sid']);
    //         if($v['status'] == 0) {
    //             $v['statusText'] = '待审核';
    //         } else if($v['status'] == 1) {
    //             $v['statusText'] = '有效';
    //         } else if($v['status'] == 2) {
    //             $v['statusText'] = '下架';
    //         } else {
    //             $v['statusText'] = '';
    //         }
    //         $v['create_time_show'] = date("Y-m-d H:i:s", $v['create_time']);
    //     }
    //     $pageQuery = $this->db->query('select count(1) as num from ' . $this->table . $where);
    //     $pageResult = $pageQuery->result_array();
    //     $num = $pageResult[0]['num'];
    //     $rtn = array(
    //         'total' => $num,
    //         'size'  => $size,
    //         'page'  => $page,
    //         'list'  => $result
    //     );
    //     return $rtn;
    // }
    //
    //
    // /**
    //  * 通过分类id获取分类信息
    //  **/
    // public function getClassById($id = 0) {
    //     if($id == 0) return '';
    //     $query = $this->db->query('select ' . $this->class_fields . ' from ' . $this->class_table . ' where id = "' . $id . '"');
    //     $result = $query->result_array();
    //     if(count($result) > 0) {
    //         return $result[0];
    //     } else {
    //         return false;
    //     }
    // }
    //
    //
    // /**
    //  * 通过学校id获取学校信息
    //  **/
    // public function getSchoolById($id = 0) {
    //     if($id == 0) return '';
    //     $query = $this->db->query('select ' . $this->school_fields . ' from ' . $this->school_table . ' where id = "' . $id . '"');
    //     $result = $query->result_array();
    //     if(count($result) > 0) {
    //         return $result[0];
    //     } else {
    //         return false;
    //     }
    // }
    //
    //
    // /**
    //  * 下架记录
    //  * @param  [type] $id [description]
    //  * @return [type]     [description]
    //  */
    // public function xiajia($id) {
    //     $this->db->query('update ' . $this->table . ' set status = 2 where id = ' . $id);
    //     $result['status'] = 0;
    //     $result['msg'] = '下架成功';
    //     return $result;
    // }
    //
    //
    // /**
    //  * 通过id获取记录详情
    //  * @param  [type] $nid [description]
    //  * @return [type]      [description]
    //  */
    // public function getDetail($nid) {
    //     if($nid <= 0) {
    //         return false;
    //     }
    //     $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . ' where id="' . $nid . '"');
    //     $result = $query->result_array();
    //     return $result[0];
    // }
}
