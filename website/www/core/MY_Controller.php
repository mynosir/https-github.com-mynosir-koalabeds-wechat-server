<?php
/**
 * 应用基础控制器
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->resource_url = $this->config->item('base_url') . 'www/views/';
        $this->load->config('customer');
        $this->wechat = $this->config->item('wechat');
    }


    /**
     * 生成网页分享参数
     * @param  string $protocol [description]
     * @return [type]           [description]
     */
    public function generate_wxshare($protocol='http') {
        if($_SERVER['HTTP_HOST']!='dev.koalabeds-server.com') {
            $nonceStr = 'koalabeds-server';
            $timestamp = time();
            $url = $protocol . '://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            $jsapi_ticket = $this->get_share_jsapi_ticket($this->wechat['appId'], $this->wechat['appSecret']);
            $signatureArray = array(
                'jsapi_ticket'  => $jsapi_ticket,
                'noncestr'      => $nonceStr,
                'timestamp'     => $timestamp,
                'url'           => $url
            );
            ksort($signatureArray, SORT_STRING);
            $new_arr = array();
            foreach($signatureArray as $k=>$v) {
                $new_arr[] = $k . '=' . $v;
            }
            $signature = sha1(implode($new_arr, '&'));

            $result = array(
                        'signature' => $signature,
                        'nonceStr'  => $nonceStr,
                        'timestamp' => $timestamp,
                        'appId'     => $this->wechat['appId']
                    );

            return $result;
        }
    }


    public function get_share_jsapi_ticket($appid, $appSecret) {
        $mem = new Memcache();
        $mem->connect('127.0.0.1', 11211);
        $jsapi_ticket = $mem->get('koalabeds_jsapi_ticket');
        if(!$jsapi_ticket == false) {
            $ticket_arr = explode('|', $jsapi_ticket);
            if(time() < $ticket_arr[0]) {
                $mem->close();
                return $ticket_arr[1];
            }
        }
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $access_token . '&type=jsapi';
        $data = json_decode(curl_file_get_contents($url), true);
        // $data = json_decode(file_get_contents($url), true);
        if($data['ticket']) {
            $mem->set('koalabeds_jsapi_ticket', json_encode(time() + $data['expires_in']) . '|' . $data['ticket']);
            $mem->close();
            return $data['ticket'];
        } else {
            echo '获取jsapi_ticket错误';
            exit;
        }

    }


    /**
     * 显示页面，自动加上头部和尾部
     * @param  [type] $page [description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function showPage($page, $data) {
        $this->load->view('include/_header', $data);
        $this->load->view($page, $data);
        $this->load->view('include/_footer', $data);
    }


    /**
     * 发起https请求
     * @param  [type] $url  [description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function https_request($url, $data=null) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }


    /**
     * 发起http请求
     * @param  [type] $url  [description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function http_post($url, $data=null) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        if(!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }


    /**
     * 获取用户请求ip地址
     * @return [type] [description]
     */
    public function getIP() {
        $ip = '0.0.0.0';
        if(isset($_SERVER['HTTP_X_REAL_IP'])) {
            // nginx 代理模式下，获取客户端真实ip
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        } elseif(isset($_SERVER['HTTP_CLIENT_IP'])) {
            // 客户端的ip
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // 浏览当前页面的用户计算机的网关
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if(false!==$pos) unset($arr[$pos]);
            $ip = trim($arr[0]);
        } elseif(isset($_SERVER['REMOTE_ADDR'])) {
            // 浏览当前页面的用户计算机的ip地址
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }


    /**
     * 标准化请求输出
     *
     * @param boolean $success 操作是否成功
     * @param integer $error 操作错误编码
     * @param mixed $data 操作错误提示或结果数据输出
     * @return string {success:false, error:0, data:''}
     */
    public function output_result($success=false, $error=0, $data='') {
        if(is_array($success)==true) {
            echo json_encode($success);
        } else {
            echo json_encode(array('success'=>$success, 'error'=>$error, 'data'=>$data));
        }
        exit;
    }


    public function get_request($key, $default='') {
        return get_value($_REQUEST, $key, $default);
    }


    /**
     * 检查签名
     * @return [type] [description]
     */
    public function checkSignature() {
        // 获得几个参数
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
     * 接收消息
     * @return [type] [description]
     */
    public function responseMsg() {
        // 接收微信发来的xml数据
        // $postStr = $GLOBALS['HTTP_RAW_POST_DATA'];
        $postStr = file_get_contents("php://input");
        // file_put_contents('D:/work/cache/postdata', $postStr . "\r\n", FILE_APPEND);
        if(!empty($postStr)) {
            // 解析post的xml为一个对象
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            // 消息类型
            $msgType = $postObj->MsgType;
            // 消息ID
            $msgId = $postObj->MsgId;

            // 根据不同事件类型进行处理
            switch($msgType) {
                case 'text':
                    // 文本消息
                    $this->replyBlank();
                    break;
                case 'image':
                    // 图片消息
                    $this->replyBlank();
                    break;
                case 'voice':
                    // 语音消息
                    $this->replyBlank();
                    break;
                case 'video':
                    // 视频消息
                    $this->replyBlank();
                    break;
                case 'shortvideo':
                    // 小视频消息
                    $this->replyBlank();
                    break;
                case 'location':
                    // 地理位置消息
                    $this->replyBlank();
                    break;
                case 'link':
                    // 链接消息
                    $this->replyBlank();
                    break;
                case 'event':
                    // 事件类型
                    switch($postObj->Event) {
                        case 'subscribe':
                            // 关注事件
                            $toUserName = $postObj->ToUserName;         // 开发者微信号
                            $fromUserName = $postObj->FromUserName;     // 发送方帐号（一个OpenID）
                            $content = '欢迎关注~';
                            $this->replyText($fromUserName, $toUserName, time(), $content);
                            break;
                        case 'unsubscribe':
                            // 取消关注事件
                            $toUserName = $postObj->ToUserName;         // 开发者微信号
                            $fromUserName = $postObj->FromUserName;     // 发送方帐号（一个OpenID）
                            break;
                        case 'LOCATION':
                            // 上报地理位置事件
                            $toUserName = $postObj->ToUserName;         // 开发者微信号
                            $fromUserName = $postObj->FromUserName;     // 发送方帐号（一个OpenID）

                            $this->replyBlank();
                            break;
                        case 'CLICK':
                            // 点击菜单拉取消息时的事件推送
                            $toUserName = $postObj->ToUserName;         // 开发者微信号
                            $fromUserName = $postObj->FromUserName;     // 发送方帐号（一个OpenID）
                            $eventKey = $postObj->EventKey;             // 事件KEY值，与自定义菜单接口中KEY值对应
                            $this->replyBlank();
                            break;
                        case 'VIEW':
                            // 点击菜单跳转链接时的事件推送
                            $toUserName = $postObj->ToUserName;         // 开发者微信号
                            $fromUserName = $postObj->FromUserName;     // 发送方帐号（一个OpenID）

                            $this->replyBlank();
                            break;
                        default:
                            // 立即返回
                            ob_clean();
                            echo '';
                            break;
                    }
                    break;
                default:
                    // 默认立即返回
                    ob_clean();
                    echo '';
                    exit();
                    break;
            }
        }
    }


    /**
     * 回复空串
     * @return [type] [description]
     */
    public function replyBlank() {
        // 默认立即返回
        ob_clean();
        echo '';
    }


    /**
     * 回复文本消息
     * @param  [type] $toUserName   接收方帐号（收到的OpenID）
     * @param  [type] $fromUserName 开发者微信号
     * @param  [type] $createTime   消息创建时间 （整型）
     * @param  [type] $content      回复的消息内容（换行：在content中能够换行，微信客户端就支持换行显示）
     * @return [type]               [description]
     */
    public function replyText($toUserName, $fromUserName, $createTime, $content) {
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[text]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                    </xml>";
        $resultStr = sprintf($textTpl, $toUserName, $fromUserName, $createTime, $content);
        echo $resultStr;
        return;
    }


    /**
     * 回复图片消息
     * @param  [type] $toUserName   接收方帐号（收到的OpenID）
     * @param  [type] $fromUserName 开发者微信号
     * @param  [type] $createTime   消息创建时间 （整型）
     * @param  [type] $mediaId      通过素材管理中的接口上传多媒体文件，得到的id。
     * @return [type]               [description]
     */
    public function replyImage($toUserName, $fromUserName, $createTime, $mediaId) {
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[image]]></MsgType>
                        <Image>
                            <MediaId><![CDATA[%s]]></MediaId>
                        </Image>
                    </xml>";
        $resultStr = sprintf($textTpl, $toUserName, $fromUserName, $createTime, $mediaId);
        echo $resultStr;
        return;
    }


    /**
     * 回复语音消息
     * @param  [type] $toUserName   接收方帐号（收到的OpenID）
     * @param  [type] $fromUserName 开发者微信号
     * @param  [type] $createTime   消息创建时间戳 （整型）
     * @param  [type] $mediaId      通过素材管理中的接口上传多媒体文件，得到的id
     * @return [type]               [description]
     */
    public function replyVoice($toUserName, $fromUserName, $createTime, $mediaId) {
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[voice]]></MsgType>
                        <Voice>
                            <MediaId><![CDATA[%s]]></MediaId>
                        </Voice>
                    </xml>";
        $resultStr = sprintf($textTpl, $toUserName, $fromUserName, $createTime, $mediaId);
        echo $resultStr;
        return;
    }


    /**
     * 回复视频消息
     * @param  [type] $toUserName   接收方帐号（收到的OpenID）
     * @param  [type] $fromUserName 开发者微信号
     * @param  [type] $createTime   消息创建时间 （整型）
     * @param  [type] $mediaId      通过素材管理中的接口上传多媒体文件，得到的id
     * @param  [type] $title        视频消息的标题
     * @param  [type] $description  视频消息的描述
     * @return [type]               [description]
     */
    public function replyVideo($toUserName, $fromUserName, $createTime, $mediaId, $title, $description) {
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[video]]></MsgType>
                        <Video>
                            <MediaId><![CDATA[%s]]></MediaId>
                            <Title><![CDATA[%s]]></Title>
                            <Description><![CDATA[%s]]></Description>
                        </Video>
                    </xml>";
        $resultStr = sprintf($textTpl, $toUserName, $fromUserName, $createTime, $mediaId, $title, $description);
        echo $resultStr;
        return;
    }


    /**
     * 回复音乐消息
     * @param  [type] $toUserName   接收方帐号（收到的OpenID）
     * @param  [type] $fromUserName 开发者微信号
     * @param  [type] $createTime   消息创建时间 （整型）
     * @param  [type] $title        音乐标题
     * @param  [type] $description  音乐描述
     * @param  [type] $musicURL     音乐链接
     * @param  [type] $HQMusicUrl   高质量音乐链接，WIFI环境优先使用该链接播放音乐
     * @param  [type] $thumbMediaId 缩略图的媒体id，通过素材管理中的接口上传多媒体文件，得到的id
     * @return [type]               [description]
     */
    public function replyMusic($toUserName, $fromUserName, $createTime, $title, $description, $musicURL, $HQMusicUrl, $thumbMediaId) {
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[music]]></MsgType>
                        <Music>
                            <Title><![CDATA[%s]]></Title>
                            <Description><![CDATA[%s]]></Description>
                            <MusicUrl><![CDATA[%s]]></MusicUrl>
                            <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                            <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
                        </Music>
                    </xml>";
        $resultStr = sprintf($textTpl, $toUserName, $fromUserName, $createTime, $title, $description, $musicURL, $HQMusicUrl, $thumbMediaId);
        echo $resultStr;
        return;
    }


    /**
     * 回复图文消息
     * @param  [type] $toUserName   接收方帐号（收到的OpenID）
     * @param  [type] $fromUserName 开发者微信号
     * @param  [type] $createTime   消息创建时间 （整型）
     * @param  [type] $articleCount 图文消息个数，限制为8条以内
     * @param  [type] $articles     多条图文消息信息，默认第一个item为大图,注意，如果图文数超过8，则将会无响应
     *                              title 图文消息标题
     *                              descript 图文消息描述
     *                              picUrl 图片链接，支持JPG、PNG格式，较好的效果为大图360*200，小图200*200
     *                              url 点击图文消息跳转链接
     * @return [type]               [description]
     */
    public function replyNews($toUserName, $fromUserName, $createTime, $articleCount, $articles) {
        $itemTpl = '';
        foreach($articles as $item) {
            $itemTpl .= '<item>
                            <Title><![CDATA['. $item['title'] .']]></Title>
                            <Description><![CDATA[' . $item['description'] . ']]></Description>
                            <PicUrl><![CDATA[' . $item['picUrl'] . ']]></PicUrl>
                            <Url><![CDATA[' . $item['url'] . ']]></Url>
                        </item>';
        }
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[news]]></MsgType>
                        <ArticleCount>%s</ArticleCount>
                        <Articles>" . $itemTpl . "</Articles>
                    </xml>";
        $resultStr = sprintf($textTpl, $toUserName, $fromUserName, $createTime, $articleCount);
        echo $resultStr;
        return;
    }


    /**
     * 获取微信用户信息
     * @param  [type] $openid [description]
     * @return [type]         [description]
     */
    public function getWxuserInfo($openid) {
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $access_token . '&openid=' . $openid . '&lang=zh_CN';
        // $userinfo = file_get_contents($url);
        $userinfo = curl_file_get_contents($url);
        return $userinfo;
    }


    /**
     * 保存用户信息
     * @param  [type] $openid [description]
     * @return [type]         [description]
     */
    public function saveUserinfo($openid) {
        // file_put_contents('D:/work/cache/saveUserinfo_inside', $openid . '>>');
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $access_token . '&openid=' . $openid . '&lang=zh_CN&from=saveUserinfo';
        $userinfo = curl_file_get_contents($url);
        // $userinfo = file_get_contents($url);
        $myuserinfo = json_decode($userinfo, true);
        if(isset($myuserinfo['errcode']) && $myuserinfo['errcode'] > 0) {
            return false;
        } else {
            $this->load->model('user_model');
            $this->user_model->save($userinfo);
            return true;
        }
    }


    /**
     * 获取access_token
     * @return [type] [description]
     */
    public function getAccessToken() {
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
            echo '获取access_token错误';
            exit;
        }
    }


    public function base_check_auth($jumpurl) {
        // $this->session->sess_destroy();
        $wxurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->wechat['appId'] . '&redirect_uri=' . urlencode($this->config->item('base_url').'/base_server_auth?jumpurl='.urlencode($jumpurl)) . '&response_type=code&scope=snsapi_userinfo&state=889#wechat_redirect';
        header('Location: ' . $wxurl);
        exit;
    }


    public function base_server_auth($code, $jumpurl) {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->wechat['appId'] . '&secret=' . $this->wechat['appSecret'] . '&code=' . $code . '&grant_type=authorization_code';
        $data = json_decode(curl_file_get_contents($url), true);
        // $data = json_decode(file_get_contents($url), true);
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $access_token . '&openid=' . $data['openid'] . '&lang=zh_CN&from=base_server_auth';
        $data['userinfo'] = curl_file_get_contents($url);
        // $data['userinfo'] = file_get_contents($url);
        $this->session->set_userdata('koalabedsUserinfo', $data);
        $_SESSION['sewUserinfo'] = $data;
        header('Location: ' . $jumpurl);
        return true;
    }
}
