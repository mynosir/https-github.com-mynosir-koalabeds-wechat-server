# Koalabeds接口文档

## 1. 搜索酒店

* 请求URL

> /frontend/hotel/get?actionxm=search

* 请求方式

> get

* 请求参数

| 请求参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| keyword | String，可为空 | 搜索关键词 |
| startDate | String，不可为空 | 查询开始预订日期 |
| endDate | String，不可为空 | 查询结束预订日期 |
| startPrice | Float，可为空 | 酒店价格开始区间 |
| endPrice | Float，可为空 | 酒店价格结束区间 |
| rating | Integer，可为空 | 评分。0为所有等级，1-5代表1-5星 |
| zsort | Integer，可为空 | 排序。0不排序，1价格倒序，2价格升序 |

* 返回参数

| 返回参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| status | Integer | 成功与否。0成功，非0失败 |
| msg | String | 结果信息 |
| data | Array | 搜索结果集 |
| - id | Integer | 房间id |
| - name | String | 房间名称 |
| - cover | String | 封面图片 |
| - address | String | 地址信息 |
| - star | Float | 评分 |
| - rates | Integer | 评级数 |
| - prime_cost | Float | 原价 |
| - discount_price | Float | 折扣价 |

* 返回示例

```
{
    "statu"： 0，
    "msg": "查询成功",
    "data": [{
        "id": 1,
        "name": "房间示例1",
        "cover": "http://xxx.com/1.jpg",
        "address": "广东省深圳市",
        "star": 4.6,
        "rates": 755,
        "prime_cost": 1200.00,
        "discount_price": 800.00
    }]
}
```

## 2. 查询酒店页面通用内容

* 请求URL

> /frontend/hotel/get?actionxm=general

* 请求方式

> get

* 请求参数

> 无

* 返回参数

| 返回参数 | 参数类型 | 参数说明 |
| :--- | :--- | :--- |
| status | Integer | 成功与否。0成功，非0失败 |
| msg | String | 结果信息 |
| data | Array | 查询结果集 |
| - banners | Array | 轮播图结果集 |
| -- id | Integer | id |
| -- url | String | 轮播图地址 |
| -- href | String | 跳转地址 |
| -- zsort | Integer | 排序。数字越大，越靠前 |
| - coupons | Array | 优惠券结果集 |
| -- id | Integer | id |
| --

| - id | Integer | 房间id |
| - name | String | 房间名称 |
| - cover | String | 封面图片 |
| - address | String | 地址信息 |
| - star | Float | 评分 |
| - rates | Integer | 评级数 |
| - prime_cost | Float | 原价 |
| - discount_price | Float | 折扣价 |

* 返回示例

```
{
    "statu"： 0，
    "msg": "查询成功",
    "data": [{
        "id": 1,
        "name": "房间示例1",
        "cover": "http://xxx.com/1.jpg",
        "address": "广东省深圳市",
        "star": 4.6,
        "rates": 755,
        "prime_cost": 1200.00,
        "discount_price": 800.00
    }]
}
```