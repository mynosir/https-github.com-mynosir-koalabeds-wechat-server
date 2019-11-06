<?php
/**
 * 首页控制器
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class home extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $data['resource_url'] = $this->resource_url;
        $data['base_url'] = $this->config->item('base_url');
        $this->data = $data;
    }


    public function index() {
        $mydata = file_get_contents("php://input");
        if(isset($_GET['echostr']) && $_GET['echostr']) {
            // 如果发来了echostr则进行验证
            $this->checkSignature();
        } else {
            if(!!$mydata) {
                // 如果没有echostr，则返回消息
                $this->responseMsg();
            } else {
                echo 'hello';
            }
        }
    }


    public function getAccessToken() {
        $this->load->model('cloudbeds_hotel_model');
        $result = $this->cloudbeds_hotel_model->update_cloudbeds_access_token();
        echo json_encode($result);
    }


    public function fetchServerAccessToken() {
        $result = json_decode($this->https_request('https://koalabeds-server.kakaday.com/home/getAccessToken'), true);
        if($result['status'] == 0) {
            $this->load->model('cloudbeds_access_token_model');
            $this->cloudbeds_access_token_model->saveDevAccessToken($result['data']['access_token']);
            echo '同步服务器cloudbeds access token成功';
        } else {
            echo json_encode($result);
        }
    }


    public function getHotels() {
        $curl = curl_init();
        $access_token = 'fn3J6VwleQaKr5011AkkeC5uc2RRRHXmDuUreq5y';
        $url = 'https://hotels.cloudbeds.com/api/v1.1/getHotels';
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($curl), true);
        curl_close($curl);
        var_dump($output);
    }


    public function getHotelDetails() {
        $curl = curl_init();
        $access_token = 'FkqeKbMe7vZxyc9Ymoanc5YoRuFD1MC9QDk2ojvR';
        $url = 'https://hotels.cloudbeds.com/api/v1.1/getHotelDetails?propertyID=170048';
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($curl), true);
        curl_close($curl);
        var_dump($output);
    }


    public function getRoomTypes() {
        $curl = curl_init();
        $access_token = 'iWySzHNIEGlogsOm0RPaSHYgXUBkFIglUMswAYVQ';
        $url = 'https://hotels.cloudbeds.com/api/v1.1/getRoomTypes?propertyIDs=170048';
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($curl), true);
        curl_close($curl);
        var_dump($output);
    }


    public function getAvailableRoomTypes() {
        $curl = curl_init();
        $access_token = 'FkqeKbMe7vZxyc9Ymoanc5YoRuFD1MC9QDk2ojvR';
        $url = 'https://hotels.cloudbeds.com/api/v1.1/getAvailableRoomTypes?propertyIDs=170048&startDate=2019-11-02&endDate=2019-11-03&rooms=1';
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($curl), true);
        curl_close($curl);
        var_dump($output);
    }


    public function getRooms() {
        $curl = curl_init();
        $access_token = 'vd0YIi16dqLsaQHUy35VoCIN8fhSmepyhdN8wX3g';
        $url = 'https://hotels.cloudbeds.com/api/v1.1/getRooms?propertyIDs=170048';
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($curl), true);
        curl_close($curl);
        var_dump($output);
    }


    public function postReservation() {
        $curl = curl_init();
        $access_token = 'FkqeKbMe7vZxyc9Ymoanc5YoRuFD1MC9QDk2ojvR';
        $url = 'https://hotels.cloudbeds.com/api/v1.1/postReservation';
        $data = array(
            'propertyID'    => 170048,
            'startDate'     => '2019-11-02',
            'endDate'       => '2019-11-03',
            'guestFirstName'    => 'zequan',
            'guestLastName' => 'lin',
            'guestCountry'  => 'cn',
            'guestZip'      => '86',
            'guestEmail'    => '361789273@qq.com',
            'amount'        => 0.01,
            'type'          => 'hotel',
            'reservationID' => time(),
            'rooms' => array(
                'roomTypeID'=> 197686,
                'quantity'  => 1
            ),
            'adults'    => array(
                'roomTypeID'=> 197686,
                'quantity'  => 1
            ),
            'paymentMethod' => 'ebanking'
        );
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($curl), true);
        curl_close($curl);
        var_dump($output);
    }


    public function postPayment() {
        $curl = curl_init();
        $access_token = 'FkqeKbMe7vZxyc9Ymoanc5YoRuFD1MC9QDk2ojvR';
        $url = 'https://hotels.cloudbeds.com/api/v1.1/postPayment';
        $data = array(
            'propertyID'        => 170048,
            'reservationID'     => time(),
            'type'              => 'credit',
            'amount'            => 0.01,
            'cardType'          => 'visa'
        );
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($curl), true);
        curl_close($curl);
        var_dump($output);
    }


    public function test1() {
        var_dump('hello');
    }


    public function get() {
        $actionxm = $this->get_request('actionxm');
        $result = array();
        switch($actionxm) {
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
