# 酒店需修改或新增接口列表

## 1. `api/post?actionxm=getPay`-酒店下订单获取支付参数
参数：在原有参数中增加
```javascript
{
  ...
  couponid: xxx //优惠券id，用于更新优惠券状态
  id: xxx //酒店订单id，当用户重新支付的时候可以直接根据该id进行获取支付参数
  name: item.rooms_roomTypeName, // 房间类型名称
  type: item.rooms_roomTypeDesc, // 房间类型描述
  img: item.rooms_roomTypeImg  //房间类型图片
}
```
返回值： 将支付参数返回

## 2. `api/get?actionxm=getHotelOrderById`-获取订单id获取订单详情
参数:
```javascript
{
  id: xxx //酒店订单id，
  openid: xxx //用户信息id
}
```
返回订单表所有字段

## 3. `api/get?actionxm=getRoomTypeById`-根据房间id获取房间信息
参数:
```javascript
{
   propertyID: xxx //酒店id
   roomTypeID: xxx //房间类型id
}
```
返回值 与接口api/get?actionxm=getRoomsByHotelId中的房间一致

## 4. `api/post?actionxm=cancelHotelOrder`-取消订单
参数:
```javascript
{
   id: xxx, //订单id
   openid: xxx //用户id
}
```

## 5. `api/post?actionxm=postReviews`-对酒店订单进行评价
参数:
```javascript
{
   id: xxx, //订单id
   openid: xxx //用户id
   propteryID: xxxx, //酒店id
   rateNum: xxx, // 评价星星个数
   content: xxx, // 评价内容
}
```

# 门票需修改或新增接口列表

## 1. 获取门票订单列表
## 2. 根据id获取门票订单详情
