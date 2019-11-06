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
                $num = $this->get_request('num', 10);
                $result = $this->cloudbeds_hotel_model->getRecommend($type, $num);
                break;
            // 获取首页推荐酒店瀑布流
            case 'getRecommendFlow':
                $this->load->model('cloudbeds_hotel_model');
                $page = $this->get_request('page', 1);
                $num = $this->get_request('num', 10);
                $result = $this->cloudbeds_hotel_model->getRecommendFlow($page, $num);
                break;
            // 获取房间类型
            case 'getRoomTypes':
                $this->load->model('cloudbeds_hotel_model');
                $propertyIDs = $this->get_request('propertyIDs');
                $result = $this->cloudbeds_hotel_model->getRoomTypes($propertyIDs);
                break;
            // 获取轮播图
            case 'getBanners':
                $this->load->model('banner_model');
                $result = $this->banner_model->getList();
                break;
            // 获取优惠券配置信息
            case 'getCoupons':
                $this->load->model('coupon_model');
                $result = $this->coupon_model->getList();
                break;
            // 获取城市列表
            case 'getCitys':
                $this->load->model('cloudbeds_hotel_model');
                $result = $this->cloudbeds_hotel_model->getCitys();
                break;
            // 酒店搜索
            case 'searchHotels':
                $this->load->model('cloudbeds_hotel_model');
                $params['city'] = $this->get_request('city', '');                     // 酒店所在城市
                $params['checkInDate'] = $this->get_request('checkInDate', '');       // 入住日期
                $params['checkOutDate'] = $this->get_request('checkOutDate', '');     // 离店日期
                $params['hotelName'] = $this->get_request('hotelName', '');           // 酒店名称
                $params['moneySort'] = $this->get_request('moneySort', 0);            // 价格排序
                $params['rankSort'] = $this->get_request('rankSort', 0);              // 评价排序
                $params['priceStart'] = $this->get_request('priceStart', 0);          // 价格区间开始
                $params['priceEnd'] = $this->get_request('priceEnd', 0);              // 价格区间结束
                $params['rank'] = $this->get_request('rank', 0);                      // 评价星数，0为全部
                $result = $this->cloudbeds_hotel_model->searchHotels($params);
                break;
            // 获取酒店详情
            case 'getHotel':
                $this->load->model('cloudbeds_hotel_model');
                $propertyID = $this->get_request('propertyID', 0);
                $result = $this->cloudbeds_hotel_model->getHotelDetailsInDB($propertyID);
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
