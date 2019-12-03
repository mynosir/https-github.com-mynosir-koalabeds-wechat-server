<?php
/**
 * 酒店房间管理控制器
 *
 * @author jiang <qoohj@qq.com>
 *
 */
class Rooms extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->checkLogin();
        // $this->load->model('menu_model');
        $data['resource_url'] = $this->resource_url;
        $data['admin_info'] = isset($_SESSION['loginInfo']) ? $_SESSION['loginInfo'] : '';
        // $data['admin_info'] = $this->session->userdata('loginInfo');
        $data['base_url'] = $this->config->item('base_url');
        $data['current_menu'] = 'rooms';
        $data['current_menu_text'] = 'Property Room';
        $data['sub_menu'] = array();
        $data['menu_list'] = $this->getMenuList();



        $this->load->model('hotel_model');
        $this->load->model('rooms_model');



        $this->data = $data;
    }


    public function index() {
        $this->data['propertyList'] = $this->hotel_model->getPropertyList();
        // var_dump($this->data['propertyList']);
        $this->showPage('rooms_index', $this->data);
    }

    public function edit($id) {
        $this->data['id'] = $id;
        // 更新
        $this->data['info'] = $this->rooms_model->getRoomDetail($id);
        // $info2 = $this->rooms_model->getHotelDetailCn($id);
        // if($info2){
        //     $this->data['info2'] = $info2;
        // }
        $this->showPage('rooms_update', $this->data);

    }


    public function get() {
        $actionxm = $this->get_request('actionxm');
        $result = array();
        switch($actionxm) {
            case 'search':
                $page = $this->get_request('page');
                $size = $this->get_request('size');
                $keyword = $this->get_request('keyword');
                $result = $this->rooms_model->getRoomsList($page, $size, $keyword);
                break;
        }
        echo json_encode($result);
    }


    public function post() {
        $actionxm = $this->get_request('actionxm');
        $result = array();
        switch($actionxm) {
          case 'save':
              $id = $this->get_request('id');
              $params = $this->get_request('params');
              $result = $this->rooms_model->save($id,$params);
              break;

        }
        echo json_encode($result);
    }
}
