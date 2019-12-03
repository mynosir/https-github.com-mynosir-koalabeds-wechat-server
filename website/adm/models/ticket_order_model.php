<?php
/**
 * 门票订单模型
 *
 * @author jiang <qoohj@qq.com>
 *
 */
class Ticket_order_model extends MY_Model {

    private $table = 'ko_grayline_ticket';
    private $fields = 'id, openid, type, productId, travelDate, travelTime, turbojetDepartureDate, turbojetReturnDate, turbojetDepartureTime, turbojetReturnTime, turbojetDepartureFrom, turbojetDepartureTo, turbojetReturnFrom, turbojetReturnTo, turbojetQuantity, turbojetClass, turbojetTicketType, turbojetDepartureFlightNo, turbojetReturnFlightNo, hotel, title, firstName, lastName, passport, guestEmail, countryCode, telephone, promocode, agentReference, remark, subQtyProductPriceId, subQtyValue, totalPrice, info, orderParamsDetail, create_time, outTradeNo, transaction_id, transaction_info, status';
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
    public function getTicket($page=1, $size=20, $nickname, $status) {
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
        // var_dump($where);
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . $where . 'order by create_time desc limit ' . $limitStart . ', ' . $size);
        // var_dump('select ' . $this->fields . ' from ' . $this->table . $where . 'order by id asc limit ' . $limitStart . ', ' . $size);
        $result = $query->result_array();
        foreach ($result as $k => $v) {
          // code...
          $queryOpenid = $v['openid'];
          $result[$k]['wx_nickname'] = $this->db->query('select ' . $this->fields_wx . ' from ' . $this->table_wx . ' where openid = "'.$queryOpenid.'"')->row()->wx_nickname;
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
     * 更新广告
     * @param  [type] $id   [description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function updateTicket($id, $data) {
        $this->db->where('id', $id)->update($this->table, $data);
        $result['status'] = 0;
        $result['msg'] = '更新数据成功';
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
    public function addTicket($data) {
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
     * 获取广告详情
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getDetail($id) {
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . ' where id="' . $id . '"');
        $result = $query->result_array();
        return $result[0];
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
      for ($i=0; $i < count($res); $i++) {
        // code...
        for ($j=0; $j < count($res2); $j++) {
          // code...
          if ($res[$i]['openid']==$res2[$j]['openid']) {
            // code...
            $res[$i]['wx_nickname'] = $res2[$j]['wx_nickname'];
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
      ->setCellValue('B1', 'Wechat Nickname')
      ->setCellValue('C1', 'Total Price')
      ->setCellValue('D1', 'Type')
      ->setCellValue('E1', 'Title')
      ->setCellValue('F1', 'FirstName')
      ->setCellValue('G1', 'LastName')
      ->setCellValue('H1', 'Passport')
      ->setCellValue('I1', 'Guest Email')
      ->setCellValue('J1', 'Country Code')
      ->setCellValue('K1', 'Telephone')
      ->setCellValue('L1', 'Guest Selected Address')
      ->setCellValue('M1', 'Create Time')
      ->setCellValue('N1', 'Status');
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

      // 标签名

      $phpexcel->getActiveSheet()->setTitle('Ticket Order');

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
        $phpexcel->getActiveSheet()->setCellValue('B'.$i,$value['wx_nickname']);
        $phpexcel->getActiveSheet()->setCellValue('C'.$i,$value['totalPrice']);
        $phpexcel->getActiveSheet()->setCellValue('D'.$i,$value['type']);
        $phpexcel->getActiveSheet()->setCellValue('E'.$i,$value['title']);
        $phpexcel->getActiveSheet()->setCellValue('F'.$i,$value['firstName']);
        $phpexcel->getActiveSheet()->setCellValue('G'.$i,$value['lastName']);
        $phpexcel->getActiveSheet()->setCellValue('H'.$i,$value['passport']);
        $phpexcel->getActiveSheet()->setCellValue('I'.$i,$value['guestEmail']);
        $phpexcel->getActiveSheet()->setCellValue('J'.$i,$value['countryCode']);
        $phpexcel->getActiveSheet()->setCellValue('K'.$i,$value['telephone']);
        $phpexcel->getActiveSheet()->setCellValue('L'.$i,$value['hotel']);
        $phpexcel->getActiveSheet()->setCellValue('M'.$i,$value['create_time']);
        $phpexcel->getActiveSheet()->setCellValue('N'.$i,$value['status']);


      }


      $outputFileName = 'ticket_order_'.time().'.xls';
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

}
