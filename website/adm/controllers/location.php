<?php
/**
 * 地区管理
 *
 * @author jiang <qoohj@qoohj.com>
 *
 */
class Location extends MY_Controller {


    public function __construct() {
        parent::__construct();
        $this->checkLogin();
        // $this->load->model('coupon_config_model');
        $data['resource_url'] = $this->resource_url;
        $data['admin_info'] = isset($_SESSION['loginInfo']) ? $_SESSION['loginInfo'] : '';
        $data['base_url'] = $this->config->item('base_url');
        $data['current_menu'] = 'location';
        $data['sub_menu'] = array();
        $data['current_menu_text'] = 'Location';
        $data['menu_list'] = $this->getMenuList();
        $this->load->model('location_model');
        $this->data = $data;
    }


    public function index() {
        $this->showPage('location_index', $this->data);
    }

    public function get() {
        $actionxm = $this->get_request('actionxm');
        $result = array();
        switch($actionxm) {
            case 'getLocationList':
                $page = $this->get_request('page');
                $size = $this->get_request('size');
                $classify = $this->get_request('classify');
                $result = $this->location_model->getLocationList($page, $size, $classify);
                break;
            case 'getDetail':
                $id = $this->get_request('id');
                $result = $this->location_model->getDetail($id);
                break;
        }
        echo json_encode($result);
    }

    public function post() {
        $actionxm = $this->get_request('actionxm');
        $result = array();
        switch($actionxm) {
            case 'updateLocation':
                $id = $this->get_request('id');
                $data = $this->get_request('params');
                $result = $this->location_model->updateLocation($id, $data);
                break;
        }
        echo json_encode($result);
    }
}
