<?php
/**
 * 小程序用户模型
 *
 * @author jiang <qoohj@qq.com>
 *
 */
class User_model extends MY_Model {

    private $table = 'ko_user';
    private $fields = 'id, openid, userinfo, wx_avatarUrl, wx_city, wx_province, wx_country, wx_sex, wx_language, wx_nickname, lang';
    private $property_order_table = 'ko_hotel_order';
    private $property_order_fields = 'id, openid, propertyID, startDate, endDate, guestFirstName, guestLastName, guestCountry, guestZip, guestEmail, guestPhone, rooms, rooms_roomTypeID, rooms_quantity, adults, adults_roomTypeID, adults_quantity, children, children_roomTypeID, children_quantity, status, total, frontend_total, balance, balanceDetailed, assigned, unassigned, cardsOnFile, reservationID, estimatedArrivalTime, create_time, outTradeNo, transaction_id, transaction_info,coupon_id,source_prize';
    private $ticket_order_table = 'ko_grayline_ticket';
    private $ticket_order_fields = 'id, openid, type, productId, travelDate, travelTime, turbojetDepartureDate, turbojetReturnDate, turbojetDepartureTime, turbojetReturnTime, turbojetDepartureFrom, turbojetDepartureTo, turbojetReturnFrom, turbojetReturnTo, turbojetQuantity, turbojetClass, turbojetTicketType, turbojetDepartureFlightNo, turbojetReturnFlightNo, hotel, title, firstName, lastName, passport, guestEmail, countryCode, telephone, promocode, agentReference, remark, subQtyProductPriceId, subQtyValue, totalPrice, info, orderParamsDetail, create_time, outTradeNo, transaction_id, transaction_info, status';
    private $hotels_table = 'ko_cloudbeds_hotels';
    private $hotels_fields = 'propertyID, propertyName';
    private $coupon_record_table = 'ko_coupon_record';
    private $coupon_record_fields = 'id, openid, cid, status, create_time';
    private $coupon_table = 'ko_coupon';
    private $coupon_fields = 'id, totalAmount, discountAmount';


    public function __construct() {
        parent::__construct();
    }


    /**
     * 获取
     * @param  integer $page [description]
     * @param  integer $size [description]
     * @return [type]        [description]
     */
    public function getUser($page=1, $size=20, $keyword='') {
        if($keyword!='') {
            $where = ' where wx_nickname like \'%'. $keyword .'%\' ';
        } else {
            $where = ' where 1=1 ';
        }

        $limitStart = ($page - 1) * $size;
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . $where . 'order by id asc limit ' . $limitStart . ', ' . $size);
        $result = $query->result_array();

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
     * 获取
     * @param  integer $page [description]
     * @param  integer $size [description]
     * @return [type]        [description]
     */
    public function getPropertyList($openid) {
      $sql = 'select '.$this->property_order_fields.' from '.$this->property_order_table.' where openid="'.$openid.'" order by create_time desc';
      $res = $this->db->query($sql)->result_array();

      // 酒店表
      $sql2 = 'select '.$this->hotels_fields.' from '.$this->hotels_table;
      $res2 = $this->db->query($sql2)->result_array();
      for ($i=0; $i < count($res); $i++) {
        // code...
        for ($j=0; $j < count($res2); $j++) {
          // code...
          if($res[$i]['propertyID']==$res2[$j]['propertyID']){
            $res[$i]['propertyName'] = $res2[$j]['propertyName'];
          }
        }
      }
      // 查询微信名
      $sql3 = 'select '.$this->fields.' from '.$this->table.' where openid="'.$openid.'"';
      $res3 = $this->db->query($sql3)->row();
      for ($i=0; $i < count($res); $i++) {
        // code...
        $res[$i]['wx_nickname'] = $res3->wx_nickname;
        if($res[$i]['create_time']) {
            $res[$i]['create_time'] = date('Y-m-d H:i:s', $res[$i]['create_time']);
        } else {
            $res[$i]['create_time'] = '';
        }
      }

      return $res;

    }

      /**
       * 获取
       * @param  integer $page [description]
       * @param  integer $size [description]
       * @return [type]        [description]
       */
      public function getTicketList($openid) {
        $sql = 'select '.$this->ticket_order_fields.' from '.$this->ticket_order_table.' where openid="'.$openid.'" order by create_time desc';
        $res = $this->db->query($sql)->result_array();

        // // 酒店表
        // $sql2 = 'select '.$this->hotels_fields.' from '.$this->hotels_table;
        // $res2 = $this->db->query($sql2)->result_array();
        // for ($i=0; $i < count($res); $i++) {
        //   // code...
        //   for ($j=0; $j < count($res2); $j++) {
        //     // code...
        //     if($res[$i]['propertyID']==$res2[$j]['propertyID']){
        //       $res[$i]['propertyName'] = $res2[$j]['propertyName'];
        //     }
        //   }
        // }
        // 查询微信名
        $sql3 = 'select '.$this->fields.' from '.$this->table.' where openid="'.$openid.'"';
        $res3 = $this->db->query($sql3)->row();
        for ($i=0; $i < count($res); $i++) {
          // code...
          $res[$i]['wx_nickname'] = $res3->wx_nickname;
          if($res[$i]['create_time']) {
              $res[$i]['create_time'] = date('Y-m-d H:i:s', $res[$i]['create_time']);
          } else {
              $res[$i]['create_time'] = '';
          }
        }

        return $res;

      }

      /**
       * 获取
       * @param  integer $page [description]
       * @param  integer $size [description]
       * @return [type]        [description]
       */
      public function getCouponList($openid) {
        $sql = 'select '.$this->coupon_record_fields.' from '.$this->coupon_record_table.' where openid="'.$openid.'" order by create_time desc';
        $res = $this->db->query($sql)->result_array();

        // 优惠券表
        $sql2 = 'select '.$this->coupon_fields.' from '.$this->coupon_table;
        $res2 = $this->db->query($sql2)->result_array();
        for ($i=0; $i < count($res); $i++) {
          // code...
          for ($j=0; $j < count($res2); $j++) {
            // code...
            if($res[$i]['cid']==$res2[$j]['id']){
              $res[$i]['totalAmount'] = $res2[$j]['totalAmount'];
              $res[$i]['discountAmount'] = $res2[$j]['discountAmount'];
            }
          }
        }
        // 查询微信名
        $sql3 = 'select '.$this->fields.' from '.$this->table.' where openid="'.$openid.'"';
        $res3 = $this->db->query($sql3)->row();
        for ($i=0; $i < count($res); $i++) {
          // code...
          $res[$i]['wx_nickname'] = $res3->wx_nickname;
          if($res[$i]['create_time']) {
              $res[$i]['create_time'] = date('Y-m-d H:i:s', $res[$i]['create_time']);
          } else {
              $res[$i]['create_time'] = '';
          }
        }

        return $res;

      }



      /**
       * 导出数据
       * @param  [type] $id   [description]
       * @param  [type] $data [description]
       * @return [type]       [description]
       */
      public function export() {
        $sql = 'select '.$this->fields.' from '.$this->table;
        $res = $this->db->query($sql)->result_array();
        // var_dump($res);
        $this->load->library('PHPExcel');

        $this->load->library('PHPExcel/IOFactory');

        $phpexcel = new PHPExcel();

        // 设置表头

        $phpexcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Wechat Openid')
        ->setCellValue('B1', 'Wechat Nickname')
        ->setCellValue('C1', 'Wechat Avartar')
        ->setCellValue('D1', 'Sex')
        ->setCellValue('E1', 'Lang')
        ->setCellValue('F1', 'Country')
        ->setCellValue('G1', 'Province')
        ->setCellValue('H1', 'City');
        // 设置样式

        $phpexcel->createSheet();

        $objSheet = $phpexcel->getActiveSheet();

        $objSheet->getColumnDimension('A')->setWidth(15); //设置列宽
        $objSheet->getColumnDimension('B')->setWidth(15);
        $objSheet->getColumnDimension('C')->setWidth(25);
        $objSheet->getColumnDimension('D')->setWidth(15);
        $objSheet->getColumnDimension('E')->setWidth(15);
        $objSheet->getColumnDimension('F')->setWidth(15);
        $objSheet->getColumnDimension('G')->setWidth(15);
        $objSheet->getColumnDimension('H')->setWidth(15);

        // 标签名

        $phpexcel->getActiveSheet()->setTitle('Mini Program User');

        // 使用第一个表

        $phpexcel->setActiveSheetIndex(0);

        $objWriter = new PHPExcel_Writer_Excel5($phpexcel);

        // 查询到的数据源

        $key = 0;

        foreach ($res as $value) {

          //表格是从2开始的 因为上面还有表头

          $i=$key+2;

          if(isset($value['wx_sex'])){
            if ($value['wx_sex'] == 0) {

              $value['wx_sex'] = "Unknown";

            }else if($value['wx_sex'] == 1) {

              $value['wx_sex'] = "Male";

            }else if($value['wx_sex'] == 2){

              $value['wx_sex'] = "Female";

            }

          }

          $key++;
          $phpexcel->getActiveSheet()->setCellValue('A'.$i,$value['openid']);
          $phpexcel->getActiveSheet()->setCellValue('B'.$i,$value['wx_nickname']);
          $phpexcel->getActiveSheet()->setCellValue('C'.$i,$value['wx_avatarUrl']);
          $phpexcel->getActiveSheet()->setCellValue('D'.$i,$value['wx_sex']);
          $phpexcel->getActiveSheet()->setCellValue('E'.$i,$value['lang']);
          $phpexcel->getActiveSheet()->setCellValue('F'.$i,$value['wx_country']);
          $phpexcel->getActiveSheet()->setCellValue('G'.$i,$value['wx_province']);
          $phpexcel->getActiveSheet()->setCellValue('H'.$i,$value['wx_city']);


        }


        $outputFileName = 'user_list_'.time().'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outputFileName.'"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save("php://output");

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
