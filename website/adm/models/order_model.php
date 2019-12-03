<?php
/**
 * 订单模型
 *
 * @author jiang <qoohj@qq.com>
 *
 */
class Order_model extends MY_Model {

    private $table = 'ko_hotel_order';
    private $fields = 'id, openid, propertyID, startDate, endDate, guestFirstName, guestLastName, guestCountry, guestZip, guestEmail, guestPhone, rooms, rooms_roomTypeID, rooms_quantity, adults, adults_roomTypeID, adults_quantity, children, children_roomTypeID, children_quantity, status, total, frontend_total, balance, balanceDetailed, assigned, unassigned, cardsOnFile, reservationID, estimatedArrivalTime, create_time, outTradeNo, transaction_id, transaction_info,coupon_id,source_prize';
    private $table_wx = 'ko_user';
    private $fields_wx = 'openid, wx_nickname';
    private $table_hotels = 'ko_cloudbeds_hotels';
    private $fields_hotels = 'propertyID, propertyName';
    private $rooms_table = 'ko_cloudbeds_roomtypes';
    private $rooms_fields = 'id, propertyID, roomTypeID, roomTypeName, roomTypeNameShort, roomTypeDescription';
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
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . $where . 'order by create_time desc limit ' . $limitStart . ', ' . $size);
        $result = $query->result_array();
        // 酒店房间
        $sql4 = 'select '.$this->rooms_fields.' from '.$this->rooms_table;
        $res4 = $this->db->query($sql4)->result_array();
        for ($i=0; $i < count($result); $i++) {
          // code...
          for ($j=0; $j < count($res4); $j++) {
            // code...
            if($result[$i]['rooms_roomTypeID']==$res4[$j]['roomTypeID']){
              $result[$i]['roomTypeName'] = $res4[$j]['roomTypeName'];
            }
          }
        }
        foreach ($result as $k => $v) {
          // code...
          $queryOpenid = $v['openid'];
          $queryPropertyID = $v['propertyID'];
          $result[$k]['wx_nickname'] = $this->db->query('select ' . $this->fields_wx . ' from ' . $this->table_wx . ' where openid = "'.$queryOpenid.'"')->row()->wx_nickname;
          $resHotel = $this->db->query('select ' . $this->fields_hotels . ' from ' . $this->table_hotels . ' where propertyID = "'.$queryPropertyID.'"')->row();
          if($resHotel){
            $result[$k]['propertyName'] = $resHotel->propertyName;
          }else{
            $result[$k]['propertyName'] = '';
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
     * 导出数据
     * @param  [type] $id   [description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function export() {
      $sql = 'select '.$this->fields.' from '.$this->table.' order by create_time desc';
      $res = $this->db->query($sql)->result_array();
      // wx
      $sql2 = 'select '.$this->fields_wx.' from '.$this->table_wx;
      $res2 = $this->db->query($sql2)->result_array();
      // 酒店名
      $sql3 = 'select '.$this->fields_hotels.' from '.$this->table_hotels;
      $res3 = $this->db->query($sql3)->result_array();
      // 酒店房间
      $sql4 = 'select '.$this->rooms_fields.' from '.$this->rooms_table;
      $res4 = $this->db->query($sql4)->result_array();
      for ($i=0; $i < count($res); $i++) {
        // code...
        for ($j=0; $j < count($res2); $j++) {
          // code...
          if ($res[$i]['openid']==$res2[$j]['openid']) {
            // code...
            $res[$i]['wx_nickname'] = $res2[$j]['wx_nickname'];
          }
        }
        for ($k=0; $k < count($res3); $k++) {
          // code...
          if ($res[$i]['propertyID']==$res3[$k]['propertyID']) {
            // code...
            $res[$i]['propertyName'] = $res3[$k]['propertyName'];
          }
        }
        for ($l=0; $l < count($res4); $l++) {
          // code...
          if ($res[$i]['rooms_roomTypeID']==$res4[$l]['roomTypeID']) {
            // code...
            $res[$i]['roomTypeName'] = $res4[$l]['roomTypeName'];
          }
        }
        if($res[$i]['create_time']) {
            $res[$i]['create_time'] = date('Y-m-d H:i:s', $res[$i]['create_time']);
        } else {
            $res[$i]['create_time'] = '';
        }

      }
      // var_dump($res);
      $this->load->library('PHPExcel');

      $this->load->library('PHPExcel/IOFactory');

      $phpexcel = new PHPExcel();

      // 设置表头

      $phpexcel->setActiveSheetIndex(0)
      ->setCellValue('A1', 'Trade No.')
      ->setCellValue('B1', 'Property Name')
      ->setCellValue('C1', 'Reservation ID')
      ->setCellValue('D1', 'Wechat Nickname')
      ->setCellValue('E1', 'Total Price')
      ->setCellValue('F1', 'Start Date')
      ->setCellValue('G1', 'End Date')
      ->setCellValue('H1', 'Guest Firstname')
      ->setCellValue('I1', 'Guest Lastname')
      ->setCellValue('J1', 'Guest Country')
      ->setCellValue('K1', 'Guest Zip')
      ->setCellValue('L1', 'Guest Email')
      ->setCellValue('M1', 'Guest Phone')
      ->setCellValue('N1', 'Room Type')
      ->setCellValue('O1', 'Quantity')
      ->setCellValue('P1', 'Adults Quantity')
      ->setCellValue('Q1', 'Children Quantity')
      ->setCellValue('R1', 'Create Time')
      ->setCellValue('S1', 'Status');
      // 设置样式

      $phpexcel->createSheet();

      $objSheet = $phpexcel->getActiveSheet();

      $objSheet->getColumnDimension('A')->setWidth(15); //设置列宽
      $objSheet->getColumnDimension('B')->setWidth(15);
      $objSheet->getColumnDimension('C')->setWidth(15);
      $objSheet->getColumnDimension('D')->setWidth(15);
      $objSheet->getColumnDimension('E')->setWidth(15);
      $objSheet->getColumnDimension('F')->setWidth(15);
      $objSheet->getColumnDimension('G')->setWidth(15);
      $objSheet->getColumnDimension('H')->setWidth(15);
      $objSheet->getColumnDimension('I')->setWidth(15);
      $objSheet->getColumnDimension('J')->setWidth(15);
      $objSheet->getColumnDimension('K')->setWidth(15);
      $objSheet->getColumnDimension('L')->setWidth(15);
      $objSheet->getColumnDimension('M')->setWidth(15);
      $objSheet->getColumnDimension('N')->setWidth(15);
      $objSheet->getColumnDimension('O')->setWidth(15);
      $objSheet->getColumnDimension('P')->setWidth(15);
      $objSheet->getColumnDimension('Q')->setWidth(15);
      $objSheet->getColumnDimension('R')->setWidth(20);
      $objSheet->getColumnDimension('S')->setWidth(15);

      // 标签名

      $phpexcel->getActiveSheet()->setTitle('Property Order');

      // 使用第一个表

      $phpexcel->setActiveSheetIndex(0);

      $objWriter = new PHPExcel_Writer_Excel5($phpexcel);

      // 查询到的数据源

      $key = 0;

      foreach ($res as $value) {

        //表格是从2开始的 因为上面还有表头

        $i=$key+2;

        $key++;
        // var_dump(isset($value['status']));
        if(isset($value['status'])){
          if ($value['status'] == 0) {

            $value['status'] = "To Be Paid";

          }else if($value['status'] == 1) {

            $value['status'] = "Paid";

          }else if($value['status'] == 2){

            $value['status'] = "Reserve Success";

          }else if($value['status'] == -1){

            $value['status'] = "Reserve Cancelled";

          }

        }

        $phpexcel->getActiveSheet()->setCellValue('A'.$i,$value['outTradeNo']);
        // var_dump($value['propertyName']);
        if(isset($value['propertyName'])){
          $phpexcel->getActiveSheet()->setCellValue('B'.$i,$value['propertyName']);
        }
        $phpexcel->getActiveSheet()->setCellValue('C'.$i,$value['reservationID']);
        $phpexcel->getActiveSheet()->setCellValue('D'.$i,$value['wx_nickname']);
        $phpexcel->getActiveSheet()->setCellValue('E'.$i,$value['total']);
        $phpexcel->getActiveSheet()->setCellValue('F'.$i,$value['startDate']);
        $phpexcel->getActiveSheet()->setCellValue('G'.$i,$value['endDate']);
        $phpexcel->getActiveSheet()->setCellValue('H'.$i,$value['guestFirstName']);
        $phpexcel->getActiveSheet()->setCellValue('I'.$i,$value['guestLastName']);
        $phpexcel->getActiveSheet()->setCellValue('J'.$i,$value['guestCountry']);
        $phpexcel->getActiveSheet()->setCellValue('K'.$i,$value['guestZip']);
        $phpexcel->getActiveSheet()->setCellValue('L'.$i,$value['guestEmail']);
        $phpexcel->getActiveSheet()->setCellValue('M'.$i,$value['guestPhone']);
        if(isset($value['roomTypeName'])){
          $phpexcel->getActiveSheet()->setCellValue('N'.$i,$value['roomTypeName']);
        }
        $phpexcel->getActiveSheet()->setCellValue('O'.$i,$value['rooms_quantity']);
        $phpexcel->getActiveSheet()->setCellValue('P'.$i,$value['adults_quantity']);
        $phpexcel->getActiveSheet()->setCellValue('Q'.$i,$value['children_quantity']);
        $phpexcel->getActiveSheet()->setCellValue('R'.$i,$value['create_time']);
        $phpexcel->getActiveSheet()->setCellValue('S'.$i,$value['status']);


      }


      $outputFileName = 'propery_order_'.time().'.xls';
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
