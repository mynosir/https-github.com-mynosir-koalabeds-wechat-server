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
    `guestName` varchar(32) not null comment '客户名称',
    `guestEmail` varchar(128) default '' comment '客户邮箱',
    `guestList` text comment '客户列表',
    `reservationID` varchar(64) comment '预定id',
    `dateCreated` datetime comment '订单生成时间',
    `dateModified` datetime comment '订单信息修改时间',
    `estimatedArrivalTime` time comment '到店时间，24小时制',
    `source` varchar(32) comment '订单来源',
    `status` varchar(32) comment '订单状态。not_confirmed：订单信息确认中，confirmed：订单已经确认；canceled：订单被取消；checked_in：入住，checked_out：离店，no_show：不显示',
    `total` varchar(32) comment '总价格',
    `balance` varchar(32) comment '余款',
    `balanceDetailed` text comment '余款细节',
    `assigned` text comment '分配的酒店房间详情',
    `unassigned` text comment '未分配的酒店房间详情',
    `cardsOnFile` text comment '信用卡信息',
    `startDate` date comment '入住日期',
    `endDate` date comment '退房日期',
    primary key (`id`)
) engine myisam character set utf8 collate utf8_general_ci comment = '订单表';


-- linzequan 20191105
-- cloudbeds酒店表添加推荐字段
alter table `ko_cloudbeds_hotels` add recommend int(3) default 0 comment '是否推荐。0不推荐，1推荐到首页横幅，2推荐到首页瀑布流';


-- linzequan 20191106
-- 添加首页轮播图
create table `ko_banner` (
    `id` int not null auto_increment comment '自增id',
    `img` varchar(255) not null comment '图片地址',
    `link` varchar(255) default '' comment '跳转地址',
    `zorder` int default 100 comment '排序。数值越大越靠前，默认值100',
    `status` int default 0 comment '状态。0显示，1隐藏',
    primary key (`id`)
) engine myisam character set utf8 collate utf8_general_ci comment = '首页轮播图';
-- 测试数据
-- insert into ko_banner(img, link) values('https://img1.qunarzz.com/order/comp/1805/2e/6e407f088bfb902.png', 'https://baidu.com');
-- insert into ko_banner(img, link) values('https://simg1.qunarzz.com/site/images/wap/home/recommend/20160509_banner_750x376.jpg', 'https://sina.com.cn');