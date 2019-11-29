<?php
/**
 * grayline测试控制器
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class grayline extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $data['resource_url'] = $this->resource_url;
        $data['base_url'] = $this->config->item('base_url');
        $this->data = $data;
        $this->email = 'wesley@koalabeds.com.hk';
        $this->password = 'clcwesley1';
        $this->language = 'zh-cn';
    }

    public function getNationalityList() {
        $curl = curl_init();
        $url = 'http://grayline.com.hk/b2b/api/getNationalityList';
        $data = array(
            'email'     => $this->email,
            'password'  => $this->password,
            'language'  => $this->language
        );
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($curl), true);
        curl_close($curl);
        var_dump($output);
    }

    public function getProductList() {
        $curl = curl_init();
        $url = 'http://grayline.com.hk/b2b/api/getProductList';
        $data = array(
            'email'     => $this->email,
            'password'  => $this->password,
            'language'  => $this->language,
            'type'      => 'ticket'
        );
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($curl), true);
        curl_close($curl);
        var_dump($output);
    }

    public function getProductDetails() {
        $curl = curl_init();
        $url = 'http://grayline.com.hk/b2b/api/getProductDetails';
        $data = array(
            'email'     => $this->email,
            'password'  => $this->password,
            'type'      => 'tour',
            'productId' => 46
        );
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($curl), true);
        curl_close($curl);
        var_dump($output);
    }

    public function queryProduct() {
        $curl = curl_init();
        $url = 'http://grayline.com.hk/b2b/api/queryProduct';
        $data = array(
            'email'     => $this->email,
            'password'  => $this->password,
            'type'      => 'tour',
            'productId' => 46,
            'date'      => '2019-11-15',
            'travelTime'=> '22:03'
        );
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($curl), true);
        curl_close($curl);
        var_dump($output);
    }

    public function orderProduct() {
        $curl = curl_init();
        $url = 'http://grayline.com.hk/b2b/api/orderProduct';
        $data = array(
            'email'     => $this->email,
            'password'  => $this->password,
            'type'      => 'tour',
            'productId' => 46,
            'date'      => '2019-11-15',
            'turbojetDepartureDate' => '2019-11-15',
            'turbojetReturnDate'    => '2019-11-18',
            'turbojetDepartureTime' => '16:00',
            'turbojetReturnTime'    => '12:00',
            'turbojetDepartureFrom' => 'HKG',
            'turbojetDepartureTo'   => 'MAC',
            'turbojetReturnFrom'    => 'MAC',
            'turbojetReturnTo'      => 'HKG',
            'turbojetTicketType'    => 'redemption',
            'hotel'     => 'test hotel',
            'title'     => 'Mr',
            'firstName' => 'zequan',
            'lastName'  => 'lin',
            'passport'  => '86',
            'guestEmail'=> '361789273@qq.com',
            'subQty'    => array("96"=> 1),
            'totalPrice'=> 495,
            'telephone' => '18665953630',

            'openid'    => 'oLq-f4iaG_zt3onbC8lzZ4ODht-c',
            'subQtyProductPriceId'  => '96',
            'subQtyValue'   => 1,
        );
        echo json_encode($data);exit;
        $data = http_build_query($data);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = json_decode(curl_exec($curl), true);
        curl_close($curl);
        var_dump($output);
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
