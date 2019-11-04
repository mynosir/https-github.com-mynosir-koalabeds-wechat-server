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