<?php
/**
 * cloudbeds access token保存模型
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class Cloudbeds_access_token_model extends MY_Model {

    private $table = 'ko_cloudbeds_access_token';
    private $fields = 'id, access_token, token_type, expires_in, refresh_token, update_time';

    public function __construct() {
        parent::__construct();
    }


    /**
     * 更新cloudbeds access token
     **/
    public function update_cloudbeds_access_token() {
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table);
        $result = $query->result_array();
        if(count($result) > 0) {
            // 判断更新时间
            if(($result[0]['expires_in'] + $result[0]['update_time']) - time() < 600) {
                // 更新token
                $url = 'https://hotels.cloudbeds.com/api/v1.1/access_token';
                $params = array(
                    'grant_type'    => 'refresh_token',
                    'client_id'     => 'live1_assoc163958_2Mk4KHb1qDeawdVxCzQlRmEF',
                    'client_secret' => 'te1ml5AvSdMjk0JoWFhUc8P7y4gV9bIx',
                    'refresh_token' => $result[0]['refresh_token']
                );
                $apiReturnStr = $this->https_request($url, $params);
                file_put_contents('/pub/logs/update_cloudbeds_access_token', '[' . date('Y-m-d H:i:s', time()) . ']==> ' . $apiReturnStr . PHP_EOL, FILE_APPEND);
                $apiReturn = json_decode($apiReturnStr, true);
                // {
                //     "access_token": "I1xDWLawVoUwZq7kjDDsbCpaTrIjwqZ4LSZM80Nh",
                //     "token_type": "Bearer",
                //     "expires_in": 3600,
                //     "refresh_token": "wnowH0N7RnCaGY58qoef8d3Kt6oKwZtVTjWQHfph"
                // }
                if(isset($apiReturn['access_token']) && isset($apiReturn['refresh_token'])) {
                    $data = array(
                        'access_token'  => $apiReturn['access_token'],
                        'token_type'    => $apiReturn['token_type'],
                        'expires_in'    => $apiReturn['expires_in'],
                        'refresh_token' => $apiReturn['refresh_token'],
                        'update_time'   => time()
                    );
                    $this->db->where('id', $result[0]['id'])->update($this->table, $data);
                    $rtn = array(
                        'status'    => 1,
                        'msg'       => '有效期小于10分钟，已更新access token',
                        'data'      => $data
                    );
                    return $rtn;
                }
            } else {
                // 有效期还比较长，无需更新
                $rtn = array(
                    'status'    => 0,
                    'msg'       => '有效期较长，无需更新',
                    'data'      => $result[0]
                );
                return $rtn;
            }
        } else {
            // 系统异常
            $rtn = array(
                'status'    => -1,
                'msg'       => '未查找到access_token记录'
            );
            return $rtn;
        }
    }


    /**
     * 获取cloudbeds access token参数
     **/
    public function getAccessToken() {
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table);
        $result = $query->result_array();
        if(count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

}
