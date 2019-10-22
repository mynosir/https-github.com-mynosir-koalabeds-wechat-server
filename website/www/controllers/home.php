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


    public function test() {
        $redirect_uri = urlencode('http://dev.koalabeds-server.com/home/test');
        $url = 'https://hotels.cloudbeds.com/api/v1.1/oauth?client_id=live1_assoc163958_2Mk4KHb1qDeawdVxCzQlRmEF&redirect_uri=' . $redirect_uri . '&response_type=code';
        header('Location: ' . $url);
        // $data = curl_file_get_contents($url);
        // var_dump($data);
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
