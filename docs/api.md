# Koalabeds接口文档

## 1. 首页酒店推荐列表

* 请求URL

> /api/get?actionxm=getRecommend

* 请求方式

> get

* 请求参数

| 请求参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| type | Integer，不可为空 | 推荐位置，首页为2 |
| num | Integer，可为空 | 查询个数，默认为10 |

* 返回参数

| 返回参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| status | Integer | 成功与否。0成功，非0失败 |
| msg | String | 结果信息 |
| data | Array | 搜索结果集 |

* 返回示例

```
{
    "statu"： 0，
    "msg": "查询成功",
    "data": [{
        "id": 1,
        "propertyID": "172068",
        "propertyName": "Zixin Motel",
        "propertyImage": "广东省深圳市",
        "propertyImageThumb": 4.6,
        "propertyPhone": "85252414566",
        "propertyEmail": "zixinmotel@gmail.com",
        ...
    }]
}
```


## 2. 首页酒店推荐瀑布流

* 请求URL

> /api/get?actionxm=getRecommendFlow

* 请求方式

> get

* 请求参数

| 请求参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| page | Integer，可为空 | 页码，默认为1 |
| num | Integer，可为空 | 查询个数，默认为10 |

* 返回参数

| 返回参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| status | Integer | 成功与否。0成功，-1无更多数据，其他失败 |
| msg | String | 结果信息 |
| data | Array | 搜索结果集 |

* 返回示例

```
{
    "statu"： 0，
    "msg": "查询成功",
    "data": [{
        "id": 1,
        "propertyID": "172068",
        "propertyName": "Zixin Motel",
        "propertyImage": "广东省深圳市",
        "propertyImageThumb": 4.6,
        "propertyPhone": "85252414566",
        "propertyEmail": "zixinmotel@gmail.com",
        ...
    }]
}
```


## 3. 获取酒店房型

* 请求URL

> /api/get?actionxm=getRoomTypes

* 请求方式

> get

* 请求参数

| 请求参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| propertyIDs | Integer，不可为空 | cloudbeds酒店id，多个以英文逗号隔开 |

* 返回参数

| 返回参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| status | Integer | 成功与否。0成功，其他失败 |
| msg | String | 结果信息 |
| data | Array | 搜索结果集 |

* 返回示例

```
{
    "statu"： 0，
    "msg": "查询成功",
    "data": [{
        "id": 1,
        "roomTypeID": "197687",
        "propertyID": "172068",
        "maxGuests": "3"
        ...
    }]
}
```


## 4. 获取轮播图

* 请求URL

> /api/get?actionxm=getBanners

* 请求方式

> get

* 请求参数

无

* 返回参数

| 返回参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| status | Integer | 成功与否。0成功，其他失败 |
| msg | String | 结果信息 |
| data | Array | 结果集 |

* 返回示例

```
{
    "statu"： 0，
    "msg": "查询成功",
    "data": [{
        "id": 1,
        "img": "https://xxxx/a.jpg",
        "link": "https://xxxx",
        "zorder": "3"
        ...
    }]
}
```


## 5. 获取优惠券配置信息

* 请求URL

> /api/get?actionxm=getCoupons

* 请求方式

> get

* 请求参数

无

* 返回参数

| 返回参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| status | Integer | 成功与否。0成功，其他失败 |
| msg | String | 结果信息 |
| data | Array | 结果集 |

* 返回示例

```
{
    "statu"： 0，
    "msg": "查询成功",
    "data": [{
        "id": 1,
        "money": "10",
        "validateDate": "2019-11-01",
        "status": "0"
        ...
    }]
}
```


## 6. 获取酒店城市列表

* 请求URL

> /api/get?actionxm=getCitys

* 请求方式

> get

* 请求参数

无

* 返回参数

| 返回参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| status | Integer | 成功与否。0成功，其他失败 |
| msg | String | 结果信息 |
| data | Array | 搜索结果集 |

* 返回示例

```
{
    "statu"： 0，
    "msg": "查询成功",
    "data": [{
        "id": 1,
        "name": "shenzhen"
        ...
    }]
}
```


## 7. 酒店搜索

* 请求URL

> /api/get?actionxm=searchHotels

* 请求方式

> get

* 请求参数

| 请求参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| city | String | 酒店所在城市 |
| checkInDate | String | 入住日期 |
| checkOutDate | String | 离店日期 |
| hotelName | String | 酒店名称 |
| moneySort | String | 价格排序。0不排序，1升序，2降序。默认0 |
| rankSort | String | 评价排序。0不排序，1升序，2降序。默认0 |
| priceStart | String | 价格区间开始 |
| priceEnd | String | 价格区间结束 |
| rank | Integer | 评价星数，0为全部。默认0 |

* 返回参数

| 返回参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| status | Integer | 成功与否。0成功，其他失败 |
| msg | String | 结果信息 |
| data | Array | 结果集 |

* 返回示例

```
{
    "statu"： 0，
    "msg": "查询成功",
    "data": [{
        "id": 1,
        "propertyID": "172068",
        "propertyImageThumb": "https://xxx/a.jpg",
        "propertyName": "酒店名称",
        "propertyAddress1": "",
        "propertyAddress2": "",
        "propertyCity": "",
        "propertyState": "",
        "grandTotal": "最低价格",
        "rank": "3"，
        "rankNum": "908"
        ...
    }]
}
```


## 8. 获取酒店详情

* 请求URL

> /api/get?actionxm=getHotel

* 请求方式

> get

* 请求参数

| 请求参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| propertyID | Integer，不可为空 | 酒店id |

* 返回参数

| 返回参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| status | Integer | 成功与否。0成功，其他失败 |
| msg | String | 结果信息 |
| data | Array | 结果集 |

* 返回示例

```
{
    "statu"： 0，
    "msg": "查询成功",
    "data": [{
        "id": 1,
        "propertyID": "172068",
        "rank": "3"，
        "rankNum": "908"
        /* hotel表所有字段 */
        ...
    }]
}
```


## 9. 获取房间列表

* 请求URL

> /api/get?actionxm=getRoomsByHotelId

* 请求方式

> get

* 请求参数

| 请求参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| propertyID | Integer，不可为空 | 酒店id |
| checkInDate | String | 入住日期 |
| checkOutDate | String | 离店日期 |

* 返回参数

| 返回参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| status | Integer | 成功与否。0成功，其他失败 |
| msg | String | 结果信息 |
| data | Array | 结果集 |

* 返回示例

```
{
    "statu"： 0，
    "msg": "查询成功",
    "data": [{
        "id": 1,
        "propertyID": "172068",
        "rank": "3"，
        "rankNum": "908"
        /* 同搜索 */
        ...
    }]
}
```


## 10. 获取评论列表

* 请求URL

> /api/get?actionxm=getReviews

* 请求方式

> get

* 请求参数

| 请求参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| propertyID | Integer，不可为空 | 酒店id |
| page | Integer，可为空 | 页码，默认为1 |
| num | Integer，可为空 | 查询个数，默认为10 |

* 返回参数

| 返回参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| status | Integer | 成功与否。0成功，其他失败 |
| msg | String | 结果信息 |
| data | Array | 结果集 |

* 返回示例

```
{
    "statu"： 0，
    "msg": "查询成功",
    "data": [{
        "id": 1,
        /* 评论列表 */
        ...
    }]
}
```


## 11. 获取openid

* 请求URL

> /api/get?actionxm=getOpenid

* 请求方式

> get

* 请求参数

| 请求参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| code | String | 微信授权code |

* 返回参数

| 返回参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| status | Integer | 成功与否。0成功，其他失败 |
| msg | String | 结果信息 |
| data | Object | 结果集 |

* 返回示例

```
{
    "statu"： 0，
    "msg": "查询成功",
    "data": "afasdfdsfsadf",
}
```


## 12. 保存用户信息

* 请求URL

> /api/post?actionxm=saveUserinfo

* 请求方式

> post

* 请求参数

| 请求参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| openid | String | 微信用户openid |
| userinfo | String | 微信用户信息 |

* 返回参数

| 返回参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| status | Integer | 成功与否。0成功，其他失败 |
| msg | String | 结果信息 |

* 返回示例

```
{
    "statu"： 0，
    "msg": "保存成功"
}
```


## 13. 下订单

* 请求URL

> /api/post?actionxm=getReviews

* 请求方式

> post

* 请求参数

> 参照http://hotels.cloudbeds.com/api/docs/#api-Reservation-postReservation

* 返回参数

> 参照http://hotels.cloudbeds.com/api/docs/#api-Reservation-postReservation

* 返回示例

```
{
    "success": true,
    "reservationID": "5386838946",
    "status": "confirmed",
    "guestID": 282,
    "guestFirstName": "John",
    "guestLastName": "Doe",
    "guestGender": "M",
    "guestEmail": "john.doe@example.com",
    "startDate": "2018-12-03",
    "endDate": "2018-12-07",
    "dateCreated": "2018-12-03 20:47:35",
    "grandTotal": 124.27,
    "unassigned": [
    {
        "subReservationID": "5386838946",
        "roomTypeName": "Double",
        "roomTypeID": 2,
        "adults": 1,
        "children": 0,
        "dailyRates": [
        {
            "date": "2018-12-03",
            "rate": 30
        },
        {
            "date": "2018-12-04",
            "rate": 30
        },
        {
            "date": "2018-12-05",
            "rate": 30
        },
        {
            "date": "2018-12-06",
            "rate": 30
        }
        ],
        "roomTotal": 120
    }
    ]
}
```


## 14. 获取订单列表

* 请求URL

> /api/get?actionxm=getHotelOrders

* 请求方式

> get

* 请求参数

| 请求参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| openid | String | 微信用户openid |

* 返回参数

| 返回参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| status | Integer | 成功与否。0成功，其他失败 |
| msg | String | 结果信息 |
| data | Array | 订单列表 |

* 返回示例

```
{
    "statu"： 0，
    "msg": "查询成功",
    "data": [{
        "id": "xxx",
        "status": 0,
        ...
    }]
}
```


## 15. 用户获取优惠券

* 请求URL

> /api/post?actionxm=getUserCoupon

* 请求方式

> post

* 请求参数

| 请求参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| ids | String | 优惠券ids，以,分隔 |
| openid | String | 微信用户openid |

* 返回参数

| 返回参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| status | Integer | 成功与否。0成功，其他失败 |
| msg | String | 结果信息 |

* 返回示例

```
{
    "statu"： 0，
    "msg": "请求成功"
}
```


## 16. 保存用户设置预言

* 请求URL

> /api/post?actionxm=updateLang

* 请求方式

> post

* 请求参数

| 请求参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| openid | String | 微信用户openid |
| lang | String | 语言，默认英文 |

* 返回参数

| 返回参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| status | Integer | 成功与否。0成功，其他失败 |
| msg | String | 结果信息 |

* 返回示例

```
{
    "statu"： 0，
    "msg": "保存成功"
}
```