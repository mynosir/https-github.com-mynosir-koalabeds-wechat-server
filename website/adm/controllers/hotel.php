<?php
/**
 * 酒店管理控制器
 *
 * @author jiang <qoohj@qq.com>
 *
 */
class Hotel extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->checkLogin();
        // $this->load->model('menu_model');
        $data['resource_url'] = $this->resource_url;
        $data['admin_info'] = isset($_SESSION['loginInfo']) ? $_SESSION['loginInfo'] : '';
        // $data['admin_info'] = $this->session->userdata('loginInfo');
        $data['base_url'] = $this->config->item('base_url');
        $data['current_menu'] = 'property_config';
        $data['current_menu_text'] = 'Property';
        $data['sub_menu'] = array();
        $data['menu_list'] = $this->getMenuList();



        $this->load->model('hotel_model');



        $this->data = $data;
    }


    public function index() {
        $this->data['propertyList'] = $this->hotel_model->getPropertyList();

        $this->showPage('hotel_index', $this->data);
    }

    public function edit($id) {
        $this->data['id'] = $id;
        // 更新
        $this->data['info'] = $this->hotel_model->getHotelDetail($id);
        $info2 = $this->hotel_model->getHotelDetailCn($id);
        if($info2){
            $this->data['info2'] = $info2;
        }
        $this->showPage('hotel_update', $this->data);

    }


    public function get() {
        $actionxm = $this->get_request('actionxm');
        $result = array();
        switch($actionxm) {
            case 'search':
                $page = $this->get_request('page');
                $size = $this->get_request('size');
                $keyword = $this->get_request('keyword');
                $result = $this->hotel_model->getHotelList($page, $size, $keyword);
                break;
            case 'detail':
                $id = $this->get_request('id');
                $result = $this->menu_model->getMenuById($id);
                break;
        }
        echo json_encode($result);
    }


    public function post() {
        $actionxm = $this->get_request('actionxm');
        $result = array();
        switch($actionxm) {
          case 'update':
              $id = $this->get_request('id');
              $params = $this->get_request('params');
              $result = $this->hotel_model->update($params, $id);
              break;
          case 'updateStatus':
              $id = $this->get_request('id');
              $params = $this->get_request('params');
              $result = $this->hotel_model->updateStatus($id,$params);
              break;
          case 'updateRecommend':
              $id = $this->get_request('id');
              $params = $this->get_request('params');
              $result = $this->hotel_model->updateRecommend($id,$params);
              break;
          // case 'updateCh':
          //     $id = $this->get_request('id');
          //     $params = $this->get_request('params');
          //     $result = $this->hotel_model->updateCh($params, $id);
          //     break;
          case 'delete':
              $id = $this->get_request('id');
              $result = $this->menu_model->delete($id);
              break;
          case 'upload_photo':
              if(!empty($_FILES)) {
                  $fileParts = pathinfo($_FILES['uploadfile']['name']);
                  $tempFile = $_FILES['uploadfile']['tmp_name'];
                  $targetFolder = '/public/hotel/image/';
                  $targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
                  if(!is_dir($targetPath)) mkdir($targetPath, 0777, true);
                  $now = time();
                  $fileName = $now . '_org.' . $fileParts['extension'];
                  $compressFileName = $now . '.' . $fileParts['extension'];
                  $targetFile = rtrim($targetPath, '/') . '/' . $fileName;
                  $compressTargetFile = rtrim($targetPath, '/') . '/' . $compressFileName;
                  $fileTypes = array('jpg', 'jpeg', 'gif', 'png');
                  if(in_array(strtolower($fileParts['extension']), $fileTypes)) {
                      move_uploaded_file($tempFile, $targetFile);
                      // 开始压缩图片
                      $this->compressImage($targetFile, $compressTargetFile, 1920);
                      $result = array('status'=> 0, 'thumbName'=> 'http://' . $_SERVER['HTTP_HOST'] . $targetFolder . $compressFileName, 'name'=> 'http://' . $_SERVER['HTTP_HOST'] . $targetFolder . $fileName);
                  } else {
                      $result = array('status'=> -1, 'msg'=> '文件格式不正确');
                  }
              }
              break;

        }
        echo json_encode($result);
    }
}
