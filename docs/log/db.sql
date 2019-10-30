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