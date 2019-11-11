<?php
/**
 * 优惠券配置管理
 *
 * @author jiang <qoohj@qoohj.com>
 *
 */
class Coupon extends MY_Controller {


    public function __construct() {
        parent::__construct();
        $this->checkLogin();
        // $this->load->model('coupon_model');
        $data['resource_url'] = $this->resource_url;
        $data['admin_info'] = isset($_SESSION['loginInfo']) ? $_SESSION['loginInfo'] : '';
        $data['base_url'] = $this->config->item('base_url');
        $data['current_menu'] = 'coupon';
        $data['sub_menu'] = array();
        $data['current_menu_text'] = '优惠券配置管理';
        $data['menu_list'] = $this->getMenuList();
        $this->load->model('coupon_model');
        $this->data = $data;
    }


    public function index() {
        $this->showPage('coupon_index', $this->data);
    }

    public function get() {
        $actionxm = $this->get_request('actionxm');
        $result = array();
        switch($actionxm) {
            case 'getCoupon':
                $page = $this->get_request('page');
                $size = $this->get_request('size');
                $classify = $this->get_request('classify');
                $result = $this->coupon_model->getCoupon($page, $size, $classify);
                break;
            case 'getDetail':
                $id = $this->get_request('id');
                $result = $this->coupon_model->getDetail($id);
                break;
        }
        echo json_encode($result);
    }

    public function post() {
        $actionxm = $this->get_request('actionxm');
        $result = array();
        switch($actionxm) {
            case 'addCoupon':
                $data = $this->get_request('params');
                $result = $this->coupon_model->addCoupon($data);
                break;
            case 'updateCoupon':
                $id = $this->get_request('id');
                $data = $this->get_request('params');
                $result = $this->coupon_model->updateCoupon($id, $data);
                break;
            case 'delete':
                $id = $this->get_request('id');
                $result = $this->coupon_model->deleteItem($id);
                break;
            case 'upload_photo':
                if(!empty($_FILES)) {
                    $fileParts = pathinfo($_FILES['uploadfile']['name']);
                    $tempFile = $_FILES['uploadfile']['tmp_name'];
                    $targetFolder = '/public/coupon/index/';
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
                        $result = array('status'=> 0, 'name'=> 'http://' . $_SERVER['HTTP_HOST'] . $targetFolder . $compressFileName);
                    } else {
                        $result = array('status'=> -1, 'msg'=> '文件格式不正确');
                    }
                }
                break;
        }
        echo json_encode($result);
    }
}
