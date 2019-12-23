<?php
/**
 * grayline票管理控制器
 *
 * @author jiang <qoohj@qq.com>
 *
 */
class Ticket extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->checkLogin();
        // $this->load->model('menu_model');
        $data['resource_url'] = $this->resource_url;
        $data['admin_info'] = isset($_SESSION['loginInfo']) ? $_SESSION['loginInfo'] : '';
        // $data['admin_info'] = $this->session->userdata('loginInfo');
        $data['base_url'] = $this->config->item('base_url');
        $data['current_menu'] = 'ticket_config';
        $data['current_menu_text'] = 'Ticket';
        $data['sub_menu'] = array();
        $data['menu_list'] = $this->getMenuList();



        $this->load->model('ticket_model');
        // $this->load->model('rooms_model');



        $this->data = $data;
    }


    public function index() {
        // $this->data['propertyList'] = $this->hotel_model->getPropertyList();
        $this->showPage('ticket_index', $this->data);
    }

    public function edit() {
        $id = $this->get_request('id');
        // $type = $this->get_request('type');

        $this->data['id'] = $id;
        // $this->data['type'] = $type;
        // 更新
        $this->data['info'] = $this->ticket_model->getTicketDetail($id);
        // $info2 = $this->rooms_model->getHotelDetailCn($id);
        // if($info2){
        //     $this->data['info2'] = $info2;
        // }
        $this->showPage('ticket_update', $this->data);

    }


    public function get() {
        $actionxm = $this->get_request('actionxm');
        $result = array();
        switch($actionxm) {
            case 'search':
                $page = $this->get_request('page');
                $size = $this->get_request('size');
                $keyword = $this->get_request('keyword');
                $result = $this->ticket_model->getTicketsList($page, $size, $keyword);
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
              $result = $this->ticket_model->save($id,$params);
              break;
          case 'getTicketId':
              $params = $this->get_request('params');
              $result = $this->ticket_model->getTicketId($params);
              break;
          case 'updateStatus':
              $id = $this->get_request('id');
              $params = $this->get_request('params');
              $result = $this->ticket_model->updateStatus($id,$params);
              // $classify = $this->get_request('classify');
              // $keyword = $this->get_request('keyword');
              // $result = $this->reviews_model->updateReviews($page, $size);

              break;

        }
        echo json_encode($result);
    }
}
