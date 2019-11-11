<?php
/**
 * 可冠名项目管理控制器
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class Gmmj_item extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->checkLogin();
        $data['resource_url'] = $this->resource_url;
        $data['admin_info'] = isset($_SESSION['loginInfo']) ? $_SESSION['loginInfo'] : '';
        $data['base_url'] = $this->config->item('base_url');
        $data['current_menu'] = 'gmmj_item';
        $data['current_menu_text'] = '可冠名项目';
        $data['sub_menu'] = array();
        $data['menu_list'] = $this->getMenuList();
        $data['isIE'] = $this->isIE();
        $this->data = $data;
        $this->load->model('gmmj_item_model');
    }


    public function index() {
        $this->load->view('include/_header', $this->data);
        $this->load->view('gmmj_item', $this->data);
        $this->load->view('include/_footer', $this->data);
    }


    public function add($nid=0) {
        $this->data['nid'] = $nid;
        // 查询类别分类列表
        $classList = $this->gmmj_item_model->getClassList();
        $newClassList = array(array(), array(), array(), array());
        foreach($classList as $k=>$v) {
            if($v['status'] == 0) {
                $newClassList[$v['pid']][] = $v;
            }
        }
        $this->data['classList'] = json_encode($newClassList);
        // 查询学校列表
        $this->data['schoolList'] = json_encode($this->gmmj_item_model->getSchoolList());
        if($nid > 0) {
            // 更新
            $info = $this->gmmj_item_model->getDetail($nid);
            // 查询类别分项对应的类别
            $classInfo = $this->gmmj_item_model->getClassById($info['id']);
            $info['bcid'] = $classInfo['pid'];
            $this->data['info'] = $info;
            $this->load->view('include/_header', $this->data);
            $this->load->view('gmmj_item_update', $this->data);
            $this->load->view('include/_footer', $this->data);
        } else {
            // 新增
            $this->load->view('include/_header', $this->data);
            $this->load->view('gmmj_item_add', $this->data);
            $this->load->view('include/_footer', $this->data);
        }
    }


    public function get() {
        $actionxm = $this->get_request('actionxm');
        $result = array();
        switch($actionxm) {
            case 'getList':
                $page = $this->get_request('page');
                $size = $this->get_request('size');
                $keyword = $this->get_request('keyword');
                $result = $this->gmmj_item_model->getList($page, $size, $keyword);
                break;
        }
        echo json_encode($result);
    }

    public function post() {
        $actionxm = $this->get_request('actionxm');
        $result = array();
        switch($actionxm) {
            case 'upload_contentImg':
                if(!empty($_FILES)) {
                    $fileParts = pathinfo($_FILES['file']['name']);
                    $tempFile = $_FILES['file']['tmp_name'];
                    $targetFolder = '/public/gmmj_item/img_contents/';
                    $targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
                    $now = time();
                    $fileName = $now . '_org.' . $fileParts['extension'];
                    $compressFileName = $now . '.' . $fileParts['extension'];
                    $targetFile = rtrim($targetPath, '/') . '/' . $fileName;
                    $compressTargetFile = rtrim($targetPath, '/') . '/' . $compressFileName;
                    $fileTypes = array('jpg', 'jpeg', 'gif', 'png');
                    if(in_array(strtolower($fileParts['extension']), $fileTypes)) {
                        move_uploaded_file($tempFile, $targetFile);
                        // 开始压缩图片
                        $this->compressImage($targetFile, $compressTargetFile);
                        $result = array('status'=> 0, 'name'=> 'http://' . $_SERVER['HTTP_HOST'] . $targetFolder . $compressFileName);
                    } else {
                        $result = array('status'=> -1, 'msg'=> '文件格式不正确');
                    }
                }
                break;
            case 'add':
                $params = $this->get_request('params');
                $nid = $this->get_request('nid');
                $result = $this->gmmj_item_model->add($nid, $params);
                break;
            case 'xiajia':
                $id = $this->get_request('id');
                $result = $this->gmmj_item_model->xiajia($id);
                break;
        }
        echo json_encode($result);
    }
}
