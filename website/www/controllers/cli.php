<?php
/**
 * 定时任务控制器
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class cli extends MY_Controller {

    public function __construct() {
        parent::__construct();
        set_time_limit(0);
        if(!$mode = $this->input->is_cli_request()) {
            echo 'authorize fail';
            exit;
        }
    }


    public function update_cloudbeds_access_token() {
        $this->load->model('cloudbeds_access_token_model');
        $result = $this->cloudbeds_access_token_model->update_cloudbeds_access_token();
        var_dump($result);
        return $result;
    }


    public function fetch_hotels() {
        $this->load->model('cloudbeds_hotel_model');
        $result = $this->cloudbeds_hotel_model->fetch_hotels();
        var_dump($result);
        return $result;
    }

}
