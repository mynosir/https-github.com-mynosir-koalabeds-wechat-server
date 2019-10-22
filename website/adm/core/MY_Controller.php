<?php
/**
 * 应用基础控制器
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class MY_Controller extends CI_Controller {

    public $ctrl_name='';
    public $ctrl_pms='';
    public $resource_url;

    public function __construct() {
        parent::__construct();
        $this->resource_url = $this->config->item('base_url') . 'adm/views/static/';
        $this->load->config('customer');
        $this->wechat = $this->config->item('wechat');
        // $this->getAccessToken();
    }


    /**
     * 判断浏览器是否IE
     * @return boolean [description]
     */
    public function isIE() {
        $userbrowser = $_SERVER['HTTP_USER_AGENT'];
        if(preg_match('/MSIE/i', $userbrowser)) {
            return 'yes';
        } else {
            return 'no';
        }
    }


    /**
     * 发送模板消息
     * @param  [type] $openid      用户openid
     * @param  [type] $template_id 模板id
     * @param  [type] $data        模板数据
     * @return [type]              [description]
     */
    public function templateSend($openid, $template_id, $data) {
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token;
        $data = '{
            "touser":"' . $openid . '",
            "template_id":"' . $template_id . '",
            "data":' . $data . '
        }';
        $result = json_decode($this->https_post($url, $data), true);
        return $result;
    }


    /**
     * 检查签名
     * @return [type] [description]
     */
    public function checkSignature() {
        // 获得参数
        $nonce = $_GET['nonce'];
        $timestamp = $_GET['timestamp'];
        $echostr = $_GET['echostr'];
        $signature = $_GET['signature'];
        // 参数字典序排序
        $array = array($nonce, $timestamp, $this->wechat['token']);
        sort($array);
        // 验证
        $str = sha1(implode($array));
        // 对比验证str与signature，若确认此次GET请求来自微信服务器，原样返回echostr参数内容，则接入生效，成为开发者成功，否则接入失败。
        if($str == $signature && $echostr){
            // 第一次接入微信api有echostr这个参数，之后就没有了
            echo $echostr;
            return true;
        } else{
            //接入成功后的其他处理
            // echo '签名失败';
            return false;
        }
    }


    /**
     * 获取access_token
     * @return [type] [description]
     */
    public function getAccessToken() {
        if($_SERVER['HTTP_HOST']=='dev.koalabeds-server.com') {
            return 'xxxxx';
        }
        $mem = new Memcache();
        $mem->connect('127.0.0.1', 11211);
        $access_token = $mem->get('koalabeds_access_token');
        if(!$access_token == false) {
            $token_arr = explode('|', $access_token);
            if(time() < $token_arr[0]) {
                $mem->close();
                return $token_arr[1];
            }
        }
        // 没存储过该值，直接从微信服务器获取，并保存起来
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $this->wechat['appId'] . '&secret=' . $this->wechat['appSecret'];
        // $data = json_decode(file_get_contents($url), true);
        $data = json_decode(curl_file_get_contents($url), true);
        if($data['access_token']) {
            $mem->set('koalabeds_access_token', json_encode(time() + $data['expires_in']) . '|' . $data['access_token']);
            $mem->close();
            return $data['access_token'];
        } else {
            echo '获取sbj_access_token错误';
            exit;
        }
    }


    /**
     * 获取模板列表
     * @return [type] [description]
     */
    public function get_all_private_tmpl() {
        $url = 'https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=' . $this->getAccessToken();
        // $data = json_decode(file_get_contents($url), true);
        $data = json_decode(curl_file_get_contents($url), true);
        if($data['template_list']) {
            return $data['template_list'];
        } else {
            return false;
        }
    }


    /**
     * 删除模板
     * @param  [type] $template_id [description]
     * @return [type]              [description]
     */
    public function del_private_template($template_id) {
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/template/del_private_template?access_token=' . $access_token;
        $data = '{
            "template_id":"' . $template_id . '"
        }';
        $result = json_decode($this->https_post($url, $data), true);
        if($result['errcode'] == 0) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * 发送https请求
     * @param  [type] $url  [description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function https_post($url, $data) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        if(curl_errno($curl)) {
           return 'Errno' . curl_error($curl);
        }
        curl_close($curl);
        return $result;
    }


    public function getMenuList() {
        $this->load->model('menu_model');
        $this->load->model('role_model');
        $list = $this->menu_model->search();
        $result = array(
            'sysMenu'   => 0,
            'appMenu'   => array()
        );
        // 获取当前角色
        // $userinfo = $this->session->userdata('loginInfo');
        $userinfo = isset($_SESSION['loginInfo']) ? $_SESSION['loginInfo'] : '';
        if($userinfo && $userinfo['is_admin']=='1') {
            // 超管
            $result['sysMenu'] = 1;
            $result['appMenu'] = $list['data'];
        } else {
            $roleInfo = $this->role_model->getRoleById($userinfo['role_id']);
            $pmsArr = explode(',', $roleInfo['data']['pms']);
            foreach($list['data'] as $v) {
                if(in_array($v['id'], $pmsArr)) {
                    $result['appMenu'][] = $v;
                }
            }
        }
        return $result;
    }


    public function showPage($page, $data) {
        $this->load->view('include/_header', $data);
        $this->load->view($page, $data);
        $this->load->view('include/_footer', $data);
    }


    public function checkLogin() {
        // $userinfo = $this->session->userdata('loginInfo');
        $userinfo = isset($_SESSION['loginInfo']) ? $_SESSION['loginInfo'] : '';
        if($userinfo == '') {
            header('Location: /adm/login/index');
            exit;
        }
        /*if(!$this->session->userdata('loginInfo')) {
            header('Location: /adm/login/index');
            exit;
        }*/
    }


    public function get_request($key='', $default='') {
        if($key!='') {
            return get_value($_REQUEST, $key, $default);
        } else {
            return $_REQUEST;
        }
    }


    /**
     * 压缩图片
     * @param  [type] $imgsrc  [description]
     * @param  [type] $imgdest [description]
     * @return [type]          [description]
     */
    public function compressImage($imgsrc, $imgdest, $widthLimit=800) {
        list($width, $height, $type) = getimagesize($imgsrc);
        $newWidth = $width > $widthLimit ? $widthLimit : $width;
        $newHeight = $height * ($newWidth / $width);
        switch($type) {
            case 1:
                $giftype = $this->checkGif($imgsrc);
                if($giftype) {
                    $image_wp = imagecreatetruecolor($newWidth, $newHeight);
                    $image = imagecreatefromgif($imgsrc);
                    imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                    imagejpeg($image_wp, $imgdest, 75);
                    imagedestroy($image_wp);
                }
                break;
            case 2:
                $image_wp = imagecreatetruecolor($newWidth, $newHeight);
                $image = imagecreatefromjpeg($imgsrc);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagejpeg($image_wp, $imgdest, 75);
                imagedestroy($image_wp);
                break;
            case 3:
                $image_wp = imagecreatetruecolor($newWidth, $newHeight);
                $image = imagecreatefrompng($imgsrc);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagejpeg($image_wp, $imgdest, 75);
                imagedestroy($image_wp);
                break;
        }
        return;
    }

}
