<?php
/**
 * cloudbeds 酒店模型
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class Cloudbeds_hotel_model extends MY_Model {

    private $table = 'ko_cloudbeds_hotels';
    private $fields = 'id, propertyID, propertyName, propertyImage, propertyImageThumb, propertyPhone, propertyEmail, propertyAddress1, propertyAddress2, propertyCity, propertyState, propertyZip, propertyCountry, propertyLatitude, propertyLongitude, propertyCheckInTime, propertyCheckOutTime, propertyLateCheckOutAllowed, propertyLateCheckOutType, propertyLateCheckOutValue, propertyTermsAndConditions, propertyAmenities, propertyDescription, propertyTimezone, propertyCurrencyCode, propertyCurrencySymbol, propertyCurrencyPosition';

    public function __construct() {
        parent::__construct();
    }


    /**
     * 抓取cloudbeds酒店
     **/
    public function fetch_hotels() {
        $pageSize = 20;
        $pageNumber = 1;
        $logArr = array();
        $hasNext = true;
        while($hasNext) {
            $access_token_result = $this->update_cloudbeds_access_token();
            if($access_token_result['status']) {
                return array(
                    'status'    => -1,
                    'msg'       => $access_token_result['msg']
                );
            }
            $url = 'https://hotels.cloudbeds.com/api/v1.1/getHotels?pageSize=' . $pageSize . '&pageNumber=' . $pageNumber;
            $apiReturnStr = $this->https_request_cloudbeds($url, $access_token_result['data']['access_token']);
            if(isset($apiReturnStr['success']) && !!$apiReturnStr['success']) {
                // 判断当前是否爬完所有分页数据，是的话则将循环标志位置为false
                if($apiReturnStr['total'] <= $pageSize * $pageNumber) {
                    $hasNext = false;
                } else {
                    $pageNumber++;
                }
                foreach($apiReturnStr['data'] as $k=>$v) {
                    // 判断当前记录是否已经被记录到数据库中
                    $hasExist = $this->checkExistByPropertyID($v['propertyID']);
                    if(!$hasExist) {
                        // 记录不存在，通过详情接口抓取更多信息
                        $hotelDetails = $this->getHotelDetails($v['propertyID']);
                        $imageArr = array();
                        $imageThumbArr = array();
                        foreach($hotelDetails['propertyImage'] as $x=>$y) {
                            $imageArr[] = $y['image'];
                            $imageThumbArr[] = $y['thumb'];
                        }
                        $image = implode(',', $imageArr);
                        $imageThumb = implode(',', $imageThumbArr);
                        $params = array(
                            'propertyID'    => $v['propertyID'],
                            'propertyName'  => $v['propertyName'],
                            'propertyImage' => $image,
                            'propertyImageThumb'    => $imageThumb,
                            'propertyPhone' => $hotelDetails['propertyPhone'],
                            'propertyEmail' => $hotelDetails['propertyEmail'],
                            'propertyAddress1'      => $hotelDetails['propertyAddress']['propertyAddress1'],
                            'propertyAddress2'      => $hotelDetails['propertyAddress']['propertyAddress2'],
                            'propertyCity'          => $hotelDetails['propertyAddress']['propertyCity'],
                            'propertyState'         => $hotelDetails['propertyAddress']['propertyState'],
                            'propertyZip'           => $hotelDetails['propertyAddress']['propertyZip'],
                            'propertyCountry'       => $hotelDetails['propertyAddress']['propertyCountry'],
                            'propertyLatitude'      => $hotelDetails['propertyAddress']['propertyLatitude'],
                            'propertyLongitude'     => $hotelDetails['propertyAddress']['propertyLongitude'],
                            'propertyCheckInTime'   => $hotelDetails['propertyPolicy']['propertyCheckInTime'],
                            'propertyCheckOutTime'  => $hotelDetails['propertyPolicy']['propertyCheckOutTime'],
                            'propertyLateCheckOutAllowed'   => $hotelDetails['propertyPolicy']['propertyLateCheckOutAllowed'],
                            'propertyLateCheckOutType'      => $hotelDetails['propertyPolicy']['propertyLateCheckOutType'],
                            'propertyLateCheckOutValue'     => $hotelDetails['propertyPolicy']['propertyLateCheckOutValue'],
                            'propertyTermsAndConditions'    => $hotelDetails['propertyPolicy']['propertyTermsAndConditions'],
                            'propertyAmenities'     => implode(',', $hotelDetails['propertyAmenities']),
                            'propertyDescription'   => $hotelDetails['propertyDescription'],
                            'propertyTimezone'      => $v['propertyTimezone'],
                            'propertyCurrencyCode'  => $v['propertyCurrency']['currencyCode'],
                            'propertyCurrencySymbol'        => $v['propertyCurrency']['currencySymbol'],
                            'propertyCurrencyPosition'      => $v['propertyCurrency']['currencyPosition']
                        );
                        // 入库前再次进行不存在确认
                        if(!$this->checkExistByPropertyID($v['propertyID'])) {
                            $this->db->insert($this->table, $params);
                            @file_put_contents('/pub/logs/fetch_hotels/' . date('Y-m-d', time()), '[' . date('Y-m-d H:i:s', time()) . '](' . $v['propertyID'] . ')==> ' . json_encode($params) . PHP_EOL, FILE_APPEND);
                            @file_put_contents('/pub/logs/fetch_hotels_success/' . date('Y-m', time()), '[' . date('Y-m-d H:i:s', time()) . '](' . $v['propertyID'] . ')==> ' . json_encode($params) . PHP_EOL, FILE_APPEND);
                            $logArr[] = '[' . date('Y-m-d H:i:s', time()) . '](' . $v['propertyID'] . ')==> ' . json_encode($params);
                        } else {
                            // 记录存在，写入日志文件
                            @file_put_contents('/pub/logs/fetch_hotels/' . date('Y-m-d', time()), '[' . date('Y-m-d H:i:s', time()) . '](' . $v['propertyID'] . ')==> 记录已经存在' . PHP_EOL, FILE_APPEND);
                            $logArr[] = '[' . date('Y-m-d H:i:s', time()) . '](' . $v['propertyID'] . ')==> 记录已经存在';
                        }
                    } else {
                        // 记录存在，写入日志文件
                        @file_put_contents('/pub/logs/fetch_hotels/' . date('Y-m-d', time()), '[' . date('Y-m-d H:i:s', time()) . '](' . $v['propertyID'] . ')==> 记录已经存在' . PHP_EOL, FILE_APPEND);
                        $logArr[] = '[' . date('Y-m-d H:i:s', time()) . '](' . $v['propertyID'] . ')==> 记录已经存在';
                    }
                }
            }
        }
        return $logArr;
    }


    /**
     * 获取酒店详情
     **/
    public function getHotelDetails($propertyID = 0) {
        $access_token_result = $this->update_cloudbeds_access_token();
        if($access_token_result['status']) {
            return false;
        }
        $url = 'https://hotels.cloudbeds.com/api/v1.1/getHotelDetails?propertyID=' . $propertyID;
        $apiReturnStr = $this->https_request_cloudbeds($url, $access_token_result['data']['access_token']);
        if(isset($apiReturnStr['success']) && !!$apiReturnStr['success']) {
            return $apiReturnStr['data'];
        } else {
            return false;
        }
    }


    /**
     * 检查酒店是否已经记录在数据库中
     **/
    public function checkExistByPropertyID($propertyID) {
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . ' where propertyID = ' . $propertyID);
        $result = $query->result_array();
        if(count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * 查询推荐列表
     **/
    public function getRecommend($type = 0, $num = 10) {
        if($type == 0) {
            return array(
                'status'    => -1,
                'msg'       => '推荐位置不可为空'
            );
        }
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . ' where `recommend` = ' . $type . ' order by id desc limit 0, ' . $num);
        $result = $query->result_array();
        return array(
            'status'    => 0,
            'msg'       => '查询成功',
            'data'      => $result
        );
    }


    /**
     * 获取首页推荐列表瀑布流
     **/
    public function getRecommendFlow($page = 1, $num = 10) {
        $query = $this->db->query('select ' . $this->fields . ' from ' . $this->table . ' where `recommend` = 1 order by id desc limit ' . ($page - 1) * $num . ' , ' . $num);
        $result = $query->result_array();
        if(count($result) > 0) {
            $rtn = array(
                'status'    => 0,
                'msg'       => '查询成功',
                'data'      => $result
            );
        } else {
            $rtn = array(
                'status'    => -1,
                'msg'       => '没有更多数据'
            );
        }
        return $rtn;
    }


    /**
     * 获取酒店房型
     **/
    public function getRoomTypes($propertyIDs) {
        $access_token_result = $this->update_cloudbeds_access_token();
        if($access_token_result['status']) {
            return array(
                'status'    => -1,
                'msg'       => $access_token_result['msg']
            );
        }
        $url = 'https://hotels.cloudbeds.com/api/v1.1/getRoomTypes?propertyIDs=' . $propertyIDs;
        $apiReturnStr = $this->https_request_cloudbeds($url, $access_token_result['data']['access_token']);
        if(isset($apiReturnStr['success']) && !!$apiReturnStr['success']) {
            return array(
                'status'    => 0,
                'msg'       => '查询成功',
                'data'      => $apiReturnStr['data']
            );
        } else {
            return array(
                'status'    => -2,
                'msg'       => $apiReturnStr['message']
            );
        }
    }


    /**
     * 查询城市列表数据
     */
    public function getCitys() {
        $query = $this->db->query('select distinct `propertyCity` from ' . $this->table);
        $result = $query->result_array();
        if(count($result) > 0) {
            return array(
                'status'    => 0,
                'msg'       => '查询成功',
                'data'      => $result
            );
        } else {
            return array(
                'status'    => -1,
                'msg'       => '没有数据'
            );
        }
    }

}
