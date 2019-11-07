<?php
/**
 * 酒店订单模型
 *
 * @author linzequan <lowkey361@gmail.com>
 *
 */
class hotel_order_model extends MY_Model {

    private $table = 'ko_hotel_order';
    private $fields = 'id, openid, propertyID, guestName, guestEmail, guestList, reservationID, dateCreated, dateModified, estimatedArrivalTime, source, status, total, balance, balanceDetailed, assigned, unassigned, cardsOnFile, startDate, endDate';

    public function __construct() {
        parent::__construct();
    }


    /**
     * 保存酒店订单
     */
    public function saveOrder() {
    }

}
