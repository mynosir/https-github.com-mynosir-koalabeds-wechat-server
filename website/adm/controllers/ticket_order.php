<?php
/**
 * 门票订单管理
 *
 * @author jiang <qoohj@qoohj.com>
 *
 */
class Ticket_order extends MY_Controller {


    public function __construct() {
        parent::__construct();
        $this->checkLogin();
        // $this->load->model('ticket_order_model');
        $data['resource_url'] = $this->resource_url;
        $data['admin_info'] = isset($_SESSION['loginInfo']) ? $_SESSION['loginInfo'] : '';
        $data['base_url'] = $this->config->item('base_url');
        $data['current_menu'] = 'ticket_order';
        $data['sub_menu'] = array();
        $data['current_menu_text'] = 'Ticket Order';
        $data['menu_list'] = $this->getMenuList();
        $this->load->model('ticket_order_model');
        $this->data = $data;
    }


    public function index() {
        $this->showPage('ticket_order_index', $this->data);
    }

    public function get() {
        $actionxm = $this->get_request('actionxm');
        $result = array();
        switch($actionxm) {
            case 'getTicket':
                $page = $this->get_request('page');
                $size = $this->get_request('size');
                $nickname = $this->get_request('nickname');
                $status = $this->get_request('status');
                $result = $this->ticket_order_model->getTicket($page, $size, $nickname, $status);
                break;
            case 'getDetail':
                $id = $this->get_request('id');
                $result = $this->ticket_order_model->getDetail($id);
                break;
            case 'export':
                $result = $this->ticket_order_model->export();
                break;
        }
        echo json_encode($result);
    }

    public function post() {
        $actionxm = $this->get_request('actionxm');
        $result = array();
        switch($actionxm) {
            case 'addTicket':
                $data = $this->get_request('params');
                $result = $this->ticket_order_model->addTicket($data);
                break;
            case 'updateTicket':
                $id = $this->get_request('id');
                $data = $this->get_request('params');
                $result = $this->ticket_order_model->updateTicket($id, $data);
                break;
            case 'delete':
                $id = $this->get_request('id');
                $result = $this->ticket_order_model->deleteItem($id);
                break;
            case 'upload_photo':
                if(!empty($_FILES)) {
                    $fileParts = pathinfo($_FILES['uploadfile']['name']);
                    $tempFile = $_FILES['uploadfile']['tmp_name'];
                    $targetFolder = '/public/ticket/index/';
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
