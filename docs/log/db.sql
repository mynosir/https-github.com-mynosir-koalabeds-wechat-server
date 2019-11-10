-- linzequan 20191030
-- cloudbeds access token保存表
create table `ko_cloudbeds_access_token` (
    `id` int not null auto_increment comment '自增id',
    `access_token` varchar(64) not null comment 'access token',
    `token_type` varchar(32) not null comment 'token type',
    `expires_in` int not null comment '有效期',
    `refresh_token` varchar(64) not null comment 'refresh token',
    `update_time` int not null comment '更新时间',
    primary key (`id`)
) engine = myisam character set utf8 collate utf8_general_ci comment = 'cloudbeds access token保存表';


-- linzequan 20191031
-- cloudbeds酒店表
create table `ko_cloudbeds_hotels` (
    `id` int not null auto_increment comment '自增id',
    `propertyID` int not null comment 'cloudbeds酒店id',
    `propertyName` varchar(255) comment 'cloudbeds酒店名称',
    `propertyImage` varchar(255) comment 'cloudbeds酒店图片',
    `propertyImageThumb` varchar(255) comment 'cloudbeds酒店图片缩略图',
    `propertyPhone` varchar(32) comment 'cloudbeds酒店电话',
    `propertyEmail` varchar(32) comment 'cloudbeds酒店邮箱',
    `propertyAddress1` varchar(256) comment 'cloudbeds酒店地址1',
    `propertyAddress2` varchar(256) comment 'cloudbeds酒店地址2',
    `propertyCity` varchar(256) comment 'cloudbeds酒店城市',
    `propertyState` varchar(256) comment 'cloudbeds酒店所在区域',
    `propertyZip` varchar(256) comment 'cloudbeds酒店邮政编码',
    `propertyCountry` varchar(256) comment 'cloudbeds酒店所在国家',
    `propertyLatitude` varchar(256) comment 'cloudbeds酒店所在经度',
    `propertyLongitude` varchar(256) comment 'cloudbeds酒店所在纬度',
    `propertyCheckInTime` varchar(256) comment 'cloudbeds酒店入住时间',
    `propertyCheckOutTime` varchar(256) comment 'cloudbeds酒店退房时间',
    `propertyLateCheckOutAllowed` boolean comment 'cloudbeds酒店是否允许延迟退房时间',
    `propertyLateCheckOutType` varchar(256) comment 'cloudbeds酒店延迟退房单位，允许值为value数值或者percent百分比',
    `propertyLateCheckOutValue` varchar(128) comment 'cloudbeds酒店延迟退房数值',
    `propertyTermsAndConditions` text comment 'cloudbeds酒店展示给用户条款和条件',
    `propertyAmenities` varchar(512) comment 'cloudbeds酒店设施清单',
    `propertyDescription` text comment 'cloudbeds酒店描述',
    `propertyTimezone` varchar(128) comment 'cloudbeds酒店时区',
    `propertyCurrencyCode` varchar(128) comment 'cloudbeds酒店货币编码',
    `propertyCurrencySymbol` varchar(128) comment 'cloudbeds酒店货币符号',
    `propertyCurrencyPosition` varchar(128) comment 'cloudbeds酒店货币位置',
    primary key (`id`)
) engine = myisam character set utf8 collate utf8_general_ci comment = 'cloudbeds酒店表';


-- linzequan 20191031
-- cloudbeds酒店中文信息表
create table `ko_cloudbeds_hotels_cn` (
    `id` int not null auto_increment comment '自增id',
    `hid` int not null comment '酒店表id',
    `propertyID` int not null comment 'cloudbeds酒店id',
    `propertyName` varchar(255) comment 'cloudbeds酒店名称',
    `propertyDescription` text comment 'cloudbeds酒店描述',
    primary key (`id`)
) engine myisam character set utf8 collate utf8_general_ci comment = 'cloudbeds酒店中文信息表';


-- linzequan 20191031
-- 酒店订单表
create table `ko_hotel_order` (
    `id` int not null auto_increment comment '自增id',
    `openid` varchar(32) comment '订单用户微信openid',
    `propertyID` int not null comment '酒店id',
    `startDate` date comment '入住日期',
    `endDate` date comment '退房日期',
    `guestFirstName` varchar(64) default '' comment '客户名称',
    `guestLastName` varchar(64) default '' comment '客户姓',
    `guestCountry` varchar(64) default '' comment '客户所在国家编码',
    `guestZip` varchar(32) default '' comment '客户邮编',
    `guestEmail` varchar(128) default '' comment '客户邮箱',
    `guestPhone` varchar(32) default '' comment '客户手机号码',
    `rooms` text comment '预订的房间信息',
    `rooms_roomTypeID` varchar(32) default '' comment '预订的房间房型',
    `rooms_quantity` int default 0 comment '预订的房间数量',
    `adults` text comment '预订的大人房间信息',
    `adults_roomTypeID` varchar(32) default '' comment '预订的大人房间信息',
    `adults_quantity` int default 0 comment '预订的大人房间数量',
    `children` text comment '预订的儿童房间信息',
    `children_roomTypeID` varchar(32) default '' comment '预订的儿童房间信息',
    `children_quantity` int default 0 comment '预订的儿童房间数量',
    `status` int default 0 comment '订单状态。0未支付，1已支付，2支付失败，3待确认，4订单取消，5预订失败，6预订成功，7入住，8离店，9不显示',
    `total` varchar(32) comment '总价格',
    `frontend_total` varchar(32) comment '小程序计算的总价格',
    `balance` varchar(32) comment '余款',
    `balanceDetailed` text comment '余款细节',
    `assigned` text comment '分配的酒店房间详情',
    `unassigned` text comment '未分配的酒店房间详情',
    `cardsOnFile` text comment '信用卡信息',
    `reservationID` int comment '预定id',
    `estimatedArrivalTime` time comment '到店时间，24小时制',
    `create_time` int comment '订单生成时间',
    `outTradeNo` varchar(64) comment '订单编号',
    `transaction_id` varchar(64) default '' comment '交易单号',
    `transaction_info` varchar(512) default '' comment '交易详情',
    primary key (`id`)
) engine myisam character set utf8 collate utf8_general_ci comment = '酒店订单表';


-- linzequan 20191105
-- cloudbeds酒店表添加推荐字段
alter table `ko_cloudbeds_hotels` add recommend int(3) default 0 comment '是否推荐。0不推荐，1推荐到首页横幅，2推荐到首页瀑布流';


-- linzequan 20191106
-- cloudbeds酒店表添加状态字段
alter table `ko_cloudbeds_hotels` add `status` int default 0 comment '状态。0下架，1上架，-1删除。默认0';


-- linzequan 20191106
-- 添加首页轮播图表
create table `ko_banner` (
    `id` int not null auto_increment comment '自增id',
    `img` varchar(255) not null comment '图片地址',
    `link` varchar(255) default '' comment '跳转地址',
    `zorder` int default 100 comment '排序。数值越大越靠前，默认值100',
    `status` int default 0 comment '状态。0显示，1隐藏',
    primary key (`id`)
) engine myisam character set utf8 collate utf8_general_ci comment = '首页轮播图表';
-- 测试数据
-- insert into ko_banner(img, link) values('https://img1.qunarzz.com/order/comp/1805/2e/6e407f088bfb902.png', 'https://baidu.com');
-- insert into ko_banner(img, link) values('https://simg1.qunarzz.com/site/images/wap/home/recommend/20160509_banner_750x376.jpg', 'https://sina.com.cn');


-- linzequan 20191106
-- 添加优惠券配置表
create table `ko_coupon` (
    `id` int not null auto_increment comment '自增id',
    `totalAmount` decimal(20, 2) not null comment '满多少金额可以使用',
    `discountAmount` decimal(20, 2) not null comment '优惠多少金额',
    `validateDate` int not null comment '有效期多少天',
    `zorder` int default 100 comment '排序。数值越大越靠前，默认值100',
    `status` int default 0 comment '优惠券状态。0显示，1隐藏',
    primary key (`id`)
) engine myisam character set utf8 collate utf8_general_ci comment = '优惠券配置表';
-- 测试数据
-- insert into ko_coupon(totalAmount, discountAmount, validateDate) values(500.00, 50.00, 30);
-- insert into ko_coupon(totalAmount, discountAmount, validateDate) values(300.00, 30.00, 15);
-- insert into ko_coupon(totalAmount, discountAmount, validateDate) values(200.00, 20.00, 10);
-- insert into ko_coupon(totalAmount, discountAmount, validateDate) values(100.00, 10.00, 5);


-- linzequan 20191107
-- 添加酒店评论表
create table `ko_reviews` (
    `id` int not null auto_increment comment '自增id',
    `propertyID` int not null comment '酒店id',
    `userid` int not null comment '用户id',
    `rate` float(8,1) default 5.0 comment '评星，默认5星',
    `content` text comment '评论内容',
    `create_time` int comment '评论时间',
    `status` int default 0 comment '评论状态。0显示，1隐藏。默认0',
    primary key (`id`)
) engine myisam character set utf8 collate utf8_general_ci comment = '酒店评论表';
-- 测试数据
-- insert into ko_reviews(propertyID, userid, rate, content, create_time) values(173691, 1, 4.5, '我是评论内容1', 1573120221);
-- insert into ko_reviews(propertyID, userid, rate, content, create_time) values(173691, 1, 4.5, '我是评论内容2', 1573120221);
-- insert into ko_reviews(propertyID, userid, rate, content, create_time) values(173691, 1, 4.5, '我是评论内容3', 1573120221);
-- insert into ko_reviews(propertyID, userid, rate, content, create_time) values(173691, 1, 4.5, '我是评论内容4', 1573120221);


-- linzequan 20191107
-- 添加微信用户信息表
create table `ko_user` (
    `id` int not null auto_increment comment '自增id',
    `openid` varchar(32) not null comment '微信openid',
    `userinfo` varchar(512) default '' comment '微信用户详细信息',
    `wx_avatarUrl` varchar(512) default '' comment '微信头像',
    `wx_city` varchar(255) default '' comment '微信城市',
    `wx_province` varchar(255) default '' comment '微信省份',
    `wx_country` varchar(255) default '' comment '微信国家',
    `wx_sex` int(2) default 0 comment '微信性别',
    `wx_language` varchar(32) default '' comment '微信语言',
    `wx_nickname` varchar(255) default '' comment '微信昵称',
    `lang` varchar(32) default 'en' comment '用户使用小程序的预言。可选择en、cn，默认en英文',
    primary key (`id`)
) engine myisam character set utf8 collate utf8_general_ci comment = '微信用户信息表';


-- linzequan 20191107
-- 添加微信用户领取优惠券记录表
create table `ko_coupon_record` (
    `id` int not null auto_increment comment '自增id',
    `openid` varchar(32) not null comment '微信openid',
    `cid` int comment '优惠券id',
    `status` int default 0 comment '使用状态。0未使用，1已使用，默认0',
    `create_time` int comment '领取时间',
    primary key (`id`)
) engine myisam character set utf8 collate utf8_general_ci comment = '微信用户领取优惠券记录表';