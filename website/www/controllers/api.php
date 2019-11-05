<?php
/**
 * 接口控制器
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class api extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $data['resource_url'] = $this->resource_url;
        $data['base_url'] = $this->config->item('base_url');
        $this->data = $data;
    }


    public function index() {
        echo 'hello api';
    }


    public function get() {
        $actionxm = $this->get_request('actionxm');
        $result = array();
        switch($actionxm) {
            // 获取首页横幅推荐
            case 'getRecommend':
                $this->load->model('cloudbeds_hotel_model');
                $type = $this->get_request('type');
                $num = $this->get_request('num');
                $result = $this->cloudbeds_hotel_model->getRecommend($type, $num);
                break;
            // 获取首页推荐酒店瀑布流
            case 'getRecommendFlow':
                $this->load->model('cloudbeds_hotel_model');
                $page = $this->get_request('page', 1);
                $num = $this->get_request('num', 10);
                $result = $this->cloudbeds_hotel_model->getRecommendFlow($page, $num);
                break;
            // 获取酒店类型
            case 'getRoomTypes':
                $this->load->model('cloudbeds_hotel_model');
                $propertyIDs = $this->get_request('propertyIDs');
                $result = $this->cloudbeds_hotel_model->getRoomTypes($propertyIDs);
                break;
        }
        echo json_encode($result);
    }


    public function post() {
        $actionxm = $this->get_request('actionxm');
        $result = array();
        switch($actionxm) {
        }
        echo json_encode($result);
    }

}
