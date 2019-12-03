$(function() {
    var page = {
        init: function(p) {
            var json = {
                api: config.apiServer + 'user/get',
                type: 'get',
                data: {
                    actionxm: 'getUser',
                    page: !p ? 1 : p,
                    size: 20,
                    keyword: $('#search_name').val()
                }
            };
            var callback = function(res) {
                // 处理表格数据
                var list = res['list'],
                    sex = ['Unknown','Male','Female'],

                    listTpl = '<tr><th>Serial No.</th><th>Wechat Nickname</th><th>Avartar</th><th>Sex</th><th>Lang</th><th>Country</th><th>Province</th><th>City</th><th>Record</th></tr>';
                for(var i in list) {
                    var listid = parseInt(i)+1;
                    listTpl += '<tr>';
                    listTpl += '<td>' + listid + '</td>';
                    // listTpl += '<td>' + list[i]['openid'] + '</td>';
                    listTpl += '<td>' + list[i]['wx_nickname'] + '</td>';
                    listTpl += '<td><img src="' + list[i]['wx_avatarUrl'] + '" style="width: 60px; height: 60px;"></td>';
                    listTpl += '<td>' + sex[list[i]['wx_sex']] + '</td>';
                    // listTpl += '<td>' + list[i]['wx_language'] + '</td>';
                    listTpl += '<td>' + list[i]['lang'] + '</td>';
                    listTpl += '<td>' + list[i]['wx_country'] + '</td>';
                    listTpl += '<td>' + list[i]['wx_province'] + '</td>';
                    listTpl += '<td>' + list[i]['wx_city'] + '</td>';
                    listTpl += '<td><button type="button" class="btn btn-sm btn-primary js_property" data-toggle="modal" data-target="#propertyModal" data-id="' + list[i]['openid'] + '">Property</button>&nbsp&nbsp<button type="button" class="btn btn-sm btn-primary js_ticket" data-toggle="modal" data-target="#ticketModal" data-id="' + list[i]['openid'] + '">Ticket</button>&nbsp&nbsp<button type="button" class="btn btn-sm btn-primary js_coupon" data-toggle="modal" data-target="#couponModal" data-id="' + list[i]['openid'] + '">Coupon</button></td>';
                    listTpl += '</tr>';
                }
                $('.js_table').html(listTpl);
                // 处理分页
                var pageTpl = '',
                    total = parseInt(res.total),
                    size = parseInt(res.size),
                    page = parseInt(res.page),
                    itemNum = Math.ceil(total / size),
                    itemStart = 1,
                    itemMax = 1,
                    fisrtItemCls = page==1 ? ' class="disabled"' : '',
                    lastItemCls = page==itemNum ? ' class="disabled"' : '';
                pageTpl += '<li ' + fisrtItemCls + '><a href="javascript:void(0)" aria-label="Previous" data-page="1" class="js_pageItem"><span aria-hidden="true">&laquo;</span></a></li>';
                if(page>3) {
                    itemStart = (page + 2) > itemNum ? itemNum - 4 : page - 2;
                    itemMax = (page + 2) > itemNum ? itemNum : page + 2;
                } else {
                    itemMax = itemNum>=5 ? 5 : itemNum;
                }
                for(itemStart; itemStart<=itemMax; itemStart++) {
                    var pageItemCls = itemStart==page ? ' class="active"' : '';
                    pageTpl += '<li ' + pageItemCls + '><a href="javascript:void(0)" data-page="' + itemStart + '" class="js_pageItem">' + itemStart + '</a></li>';
                }
                pageTpl += '<li ' + lastItemCls + '><a href="javascript:void(0)" aria-label="Next" data-page="' + itemNum + '" class="js_pageItem"><span aria-hidden="true">&raquo;</span></a></li>';
                $('.js_page').html(pageTpl);
            };
            json.callback = callback;
            Utils.requestData(json);
        },
        getCouponList: function(id) {
          var json = {
              api: config.apiServer + 'user/get',
              type: 'get',
              data: {
                  actionxm: 'getCouponList',
                  openid: id
              }
          };
          var callback = function(res) {
              // 处理表格数据
              var list = res,
                  show = ['show','hide'],
                  used = ['unused','used'],
                  listTpl = '<tr><th>Serial No.</th><th>Wechat Nickname</th><th>Coupon Over Amount</th><th>Coupon Discount Amount</th><th>Status</th><th>Create Time</th></tr>';
              for(var i in list) {
                  var listid = parseInt(i)+1;
                  listTpl += '<tr>';
                  listTpl += '<td>' + listid + '</td>';
                  // listTpl += '<td>' + list[i]['openid'] + '</td>';
                  listTpl += '<td>' + list[i]['wx_nickname'] + '</td>';
                  listTpl += '<td>' + list[i]['totalAmount'] + '</td>';
                  listTpl += '<td>' + list[i]['discountAmount'] + '</td>';
                  listTpl += '<td>' + used[list[i]['status']] + '</td>';
                  listTpl += '<td>' + list[i]['create_time'] + '</td>';
                  listTpl += '</tr>';
              }
              $('.js_table4').html(listTpl);
              // 处理分页
              var pageTpl = '',
                  total = parseInt(res.total),
                  size = parseInt(res.size),
                  page = parseInt(res.page),
                  itemNum = Math.ceil(total / size),
                  itemStart = 1,
                  itemMax = 1,
                  fisrtItemCls = page==1 ? ' class="disabled"' : '',
                  lastItemCls = page==itemNum ? ' class="disabled"' : '';
              pageTpl += '<li ' + fisrtItemCls + '><a href="javascript:void(0)" aria-label="Previous" data-page="1" class="js_pageItem"><span aria-hidden="true">&laquo;</span></a></li>';
              if(page>3) {
                  itemStart = (page + 2) > itemNum ? itemNum - 4 : page - 2;
                  itemMax = (page + 2) > itemNum ? itemNum : page + 2;
              } else {
                  itemMax = itemNum>=5 ? 5 : itemNum;
              }
              for(itemStart; itemStart<=itemMax; itemStart++) {
                  var pageItemCls = itemStart==page ? ' class="active"' : '';
                  pageTpl += '<li ' + pageItemCls + '><a href="javascript:void(0)" data-page="' + itemStart + '" class="js_pageItem">' + itemStart + '</a></li>';
              }
              pageTpl += '<li ' + lastItemCls + '><a href="javascript:void(0)" aria-label="Next" data-page="' + itemNum + '" class="js_pageItem"><span aria-hidden="true">&raquo;</span></a></li>';
              $('.js_page').html(pageTpl);
          };
          json.callback = callback;
          Utils.requestData(json);

        },
        getTicketList: function(id) {
            var json = {
                api: config.apiServer + 'user/get',
                type: 'get',
                data: {
                    actionxm: 'getTicketList',
                    openid: id
                }
            };
            var callback = function(res) {
              // 处理表格数据
              var list = res,
                  show = ['Show','Hide'],
                  status = ['To Be Paid','Paid','Payment Failure','Not Confirmed','Order Canceled','Reserve Fail','Reserve Success','No Show'],
                  statusArray = [
                    {
                    'status': 0,
                    'value': 'To Be Paid'
                    },
                    {
                      'status': 1,
                      'value': 'Paid'
                    },
                    {
                      'status': 2,
                      'value': 'Reserve Success'
                    },
                    {
                      'status': -1,
                      'value': 'Reserve Cancelled'
                    }
                  ],
                  listTpl = '<tr><th>Serial No.</th><th>Trade No</th><th>Wechat Nickname</th><th>Type</th><th>Title</th><th>FirstName</th><th>LastName</th><th>Passport</th><th>Guest Email</th><th>Country Code</th><th>Telephone</th><th>Guest Selected Address</th><th>Total Price</th><th>Create Time</th><th>Status</th></tr>';
                  // <th>travelDate</th><th>travelTime</th><th>turbojetDepartureDate</th><th>turbojetReturnDate</th><th>turbojetDepartureTime</th><th>turbojetReturnTime</th><th>turbojetDepartureFrom</th><th>turbojetDepartureTo</th><th>turbojetReturnFrom</th><th>turbojetReturnTo</th><th>turbojetQuantity</th><th>turbojetClass</th><th>turbojetTicketType</th><th>turbojetDepartureFlightNo</th><th>turbojetReturnFlightNo</th>
              function getStatus(status) {
                for (var i = 0; i < statusArray.length; i++) {
                  if (statusArray[i]['status']==status) {
                    return statusArray[i]['value'];
                  }
                }
              }
              for(var i in list) {
                  var listid = parseInt(i)+1;
                  listTpl += '<tr>';
                  // listTpl2 += '<tr>';
                  listTpl += '<td>' + listid + '</td>';
                  // listTpl += '<td>' + list[i]['openid'] + '</td>';
                  listTpl += '<td>' + list[i]['outTradeNo'] + '</td>';
                  listTpl += '<td>' + list[i]['wx_nickname'] + '</td>';
                  listTpl += '<td>' + list[i]['totalPrice'] + '</td>';
                  listTpl += '<td>' + list[i]['type'] + '</td>';
                  // listTpl += '<td>' + list[i]['productId'] + '</td>';
                  // listTpl += '<td>' + list[i]['travelDate'] + '</td>';
                  // listTpl += '<td>' + list[i]['travelTime'] + '</td>';
                  // listTpl += '<td>' + list[i]['turbojetDepartureDate'] + '</td>';
                  // listTpl += '<td>' + list[i]['turbojetReturnDate'] + '</td>';
                  // listTpl += '<td>' + list[i]['turbojetDepartureTime'] + '</td>';
                  // listTpl += '<td>' + list[i]['turbojetReturnTime'] + '</td>';
                  // listTpl += '<td>' + list[i]['turbojetDepartureFrom'] + '</td>';
                  // listTpl += '<td>' + list[i]['turbojetDepartureTo'] + '</td>';
                  // listTpl += '<td>' + list[i]['turbojetReturnFrom'] + '</td>';
                  // listTpl += '<td>' + list[i]['turbojetReturnTo'] + '</td>';
                  // listTpl += '<td>' + list[i]['turbojetQuantity'] + '</td>';
                  // listTpl += '<td>' + list[i]['turbojetClass'] + '</td>';
                  // listTpl += '<td>' + list[i]['turbojetTicketType'] + '</td>';
                  // listTpl += '<td>' + list[i]['turbojetDepartureFlightNo'] + '</td>';
                  // listTpl += '<td>' + list[i]['turbojetReturnFlightNo'] + '</td>';
                  listTpl += '<td>' + list[i]['title'] + '</td>';
                  listTpl += '<td>' + list[i]['firstName'] + '</td>';
                  listTpl += '<td>' + list[i]['lastName'] + '</td>';
                  listTpl += '<td>' + list[i]['passport'] + '</td>';
                  listTpl += '<td>' + list[i]['guestEmail'] + '</td>';
                  listTpl += '<td>' + list[i]['countryCode'] + '</td>';
                  listTpl += '<td>' + list[i]['telephone'] + '</td>';
                  listTpl += '<td>' + list[i]['hotel'] + '</td>';
                  // listTpl += '<td>' + list[i]['promocode'] + '</td>';
                  // listTpl += '<td>' + list[i]['agentReference'] + '</td>';
                  // listTpl += '<td>' + list[i]['remark'] + '</td>';
                  // listTpl += '<td>' + list[i]['subQtyProductPriceId'] + '</td>';
                  // listTpl += '<td>' + list[i]['subQtyValue'] + '</td>';
                  // listTpl += '<td>' + list[i]['info'] + '</td>';
                  // listTpl += '<td>' + list[i]['orderParamsDetail'] + '</td>';
                  listTpl += '<td>' + list[i]['create_time'] + '</td>';
                  listTpl += '<td>' + getStatus(list[i]['status']) + '</td>';
                  // listTpl2 += '<td>' + getStatus(list[i]['status']) + '</td>';

                  listTpl += '</tr>';
                  // listTpl2 += '</tr>';
              }
              $('.js_table3').html(listTpl);
              // $('.js_table2').html(listTpl2);
              // 处理分页
              var pageTpl = '',
                  total = parseInt(res.total),
                  size = parseInt(res.size),
                  page = parseInt(res.page),
                  itemNum = Math.ceil(total / size),
                  itemStart = 1,
                  itemMax = 1,
                  fisrtItemCls = page==1 ? ' class="disabled"' : '',
                  lastItemCls = page==itemNum ? ' class="disabled"' : '';
              pageTpl += '<li ' + fisrtItemCls + '><a href="javascript:void(0)" aria-label="Previous" data-page="1" class="js_pageItem"><span aria-hidden="true">&laquo;</span></a></li>';
              if(page>3) {
                  itemStart = (page + 2) > itemNum ? itemNum - 4 : page - 2;
                  itemMax = (page + 2) > itemNum ? itemNum : page + 2;
              } else {
                  itemMax = itemNum>=5 ? 5 : itemNum;
              }
              for(itemStart; itemStart<=itemMax; itemStart++) {
                  var pageItemCls = itemStart==page ? ' class="active"' : '';
                  pageTpl += '<li ' + pageItemCls + '><a href="javascript:void(0)" data-page="' + itemStart + '" class="js_pageItem">' + itemStart + '</a></li>';
              }
              pageTpl += '<li ' + lastItemCls + '><a href="javascript:void(0)" aria-label="Next" data-page="' + itemNum + '" class="js_pageItem"><span aria-hidden="true">&raquo;</span></a></li>';
              $('.js_page').html(pageTpl);

            };
            json.callback = callback;
            Utils.requestData(json);

        },
        getPropertyList: function(id) {
            var json = {
                api: config.apiServer + 'user/get',
                type: 'get',
                data: {
                    actionxm: 'getPropertyList',
                    openid: id
                }
            };
            var callback = function(res) {
              var list = res,
                  show = ['show','hide'],
                  // status = ['to be paid','paid','reserve success','reserve cancelled'],
                  statusArray = [
                    {
                    'status': 0,
                    'value': 'To Be Paid'
                    },
                    {
                      'status': 1,
                      'value': 'Paid'
                    },
                    {
                      'status': 2,
                      'value': 'Reserve Success'
                    },
                    {
                      'status': -1,
                      'value': 'Reserve Cancelled'
                    }
                  ],
                  listTpl = '<tr><th>Serial No.</th><th>Trade No.</th><th>Property Name</th><th>Reservation ID</th><th>Wechat Nickname</th><th>Total Price</th><th>Start Date</th><th>End Date</th><th>Guest Firstname</th><th>Guest Lastname</th><th>Guest Country</th><th>Guest Zip</th><th>Guest Email</th><th>Guest Phone</th><th>Room Type</th><th>Quantity</th><th>Adults Quantity</th><th>Children Quantity</th><th>Create Time</th><th>Status</th></tr>';

                  // console.log(list);
              function getStatus(status) {
                for (var i = 0; i < statusArray.length; i++) {
                  if (statusArray[i]['status']==status) {
                    return statusArray[i]['value'];
                  }
                }
              }
              for(var i in list) {
                  if(list[i]['total']==null){
                    list[i]['total']='';
                  }
                  if(list[i]['startDate']==null){
                    list[i]['startDate']='';
                  }
                  if(list[i]['endDate']==null){
                    list[i]['endDate']='';
                  }
                  if(list[i]['balance']==null){
                    list[i]['balance']='';
                  }
                  if(list[i]['assigned']==null){
                    list[i]['assigned']='';
                  }
                  if(list[i]['unassigned']==null){
                    list[i]['unassigned']='';
                  }
                  if(list[i]['cardsOnFile']==null){
                    list[i]['cardsOnFile']='';
                  }
                  if(list[i]['reservationID']==null){
                    list[i]['reservationID']='';
                  }
                  if(list[i]['estimatedArrivalTime']==null){
                    list[i]['estimatedArrivalTime']='';
                  }
                  if(list[i]['cardsOnFile']==null){
                    list[i]['cardsOnFile']='';
                  }
                  if(list[i]['outTradeNo']==null){
                    list[i]['outTradeNo']='';
                  }
                  var listid = parseInt(i)+1;
                  listTpl += '<tr>';
                  listTpl += '<td>' + listid + '</td>';
                  // listTpl += '<td>' + list[i]['openid'] + '</td>';
                  listTpl += '<td>' + list[i]['outTradeNo'] + '</td>';
                  // listTpl += '<td>' + list[i]['propertyID'] + '</td>';
                  listTpl += '<td>' + list[i]['propertyName'] + '</td>';
                  listTpl += '<td>' + list[i]['reservationID'] + '</td>';
                  listTpl += '<td>' + list[i]['wx_nickname'] + '</td>';
                  listTpl += '<td>' + list[i]['total'] + '</td>';
                  // listTpl += '<td>' + list[i]['balance'] + '</td>';
                  // listTpl += '<td>' + list[i]['source_prize'] + '</td>';
                  // listTpl += '<td>' + list[i]['coupon_id'] + '</td>';
                  listTpl += '<td>' + list[i]['startDate'] + '</td>';
                  listTpl += '<td>' + list[i]['endDate'] + '</td>';
                  listTpl += '<td>' + list[i]['guestFirstName'] + '</td>';
                  listTpl += '<td>' + list[i]['guestLastName'] + '</td>';
                  listTpl += '<td>' + list[i]['guestCountry'] + '</td>';
                  listTpl += '<td>' + list[i]['guestZip'] + '</td>';
                  listTpl += '<td>' + list[i]['guestEmail'] + '</td>';
                  listTpl += '<td>' + list[i]['guestPhone'] + '</td>';
                  // listTpl += '<td>' + list[i]['rooms'] + '</td>';
                  listTpl += '<td>' + list[i]['rooms_roomTypeID'] + '</td>';
                  listTpl += '<td>' + list[i]['rooms_quantity'] + '</td>';
                  // listTpl += '<td>' + list[i]['adults'] + '</td>';
                  // listTpl += '<td>' + list[i]['adults_roomTypeID'] + '</td>';
                  listTpl += '<td>' + list[i]['adults_quantity'] + '</td>';
                  // listTpl += '<td>' + list[i]['children'] + '</td>';
                  // listTpl += '<td>' + list[i]['children_roomTypeID'] + '</td>';
                  listTpl += '<td>' + list[i]['children_quantity'] + '</td>';
                  // listTpl += '<td>' + getStatus(list[i]['status']) + '</td>';
                  // listTpl += '<td>' + list[i]['frontend_total'] + '</td>';
                  // listTpl += '<td>' + list[i]['balanceDetailed'] + '</td>';
                  // listTpl += '<td>' + list[i]['assigned'] + '</td>';
                  // listTpl += '<td>' + list[i]['unassigned'] + '</td>';
                  // listTpl += '<td>' + list[i]['cardsOnFile'] + '</td>';
                  // listTpl += '<td>' + list[i]['estimatedArrivalTime'] + '</td>';
                  listTpl += '<td>' + list[i]['create_time'] + '</td>';
                  // listTpl += '<td>' + list[i]['transaction_id'] + '</td>';
                  listTpl += '<td>' + getStatus(list[i]['status']) + '</td>';
                  // listTpl += '<td>' + list[i]['transaction_info'] + '</td>';
                  listTpl += '</tr>';
              }
              $('.js_table2').html(listTpl)
            };
            json.callback = callback;
            Utils.requestData(json);
        },
        export: function() {
            window.location.href = config.apiServer+'user/get?actionxm=export';
        },
        deleteConfirmTip: function(id) {
            $('#confirmModal').find('.js_sure_delete').attr('data-id', id);
            $('#confirmModal').modal('show');
        },
        deletItem: function(id) {
            var json = {
                api: config.apiServer + 'user/post',
                type: 'post',
                data: {
                    actionxm: 'delete',
                    id: id
                }
            };
            var callback = function(res) {
                if(res.status==0) {
                    $('#confirmModal').modal('hide');
                    alert(res.msg);
                    window.location.reload();
                } else {
                    alert(res.msg);
                }
            };
            json.callback = callback;
            Utils.requestData(json);
        }
    };
    page.init();
    $('body').delegate('#export', 'click', function(e){
        e.preventDefault();
        page.export();
    });
    
    $('body').delegate('.js_pageItem', 'click', function(e) {
        var p = $(e.currentTarget).data('page');
        page.init(p);
    });
    $('body').delegate('.js_coupon', 'click', function(e) {
        var id = $(e.currentTarget).data('id');
        page.getCouponList(id);
    });
    $('body').delegate('.js_ticket', 'click', function(e) {
        var id = $(e.currentTarget).data('id');
        page.getTicketList(id);
    });
    $('body').delegate('.js_property', 'click', function(e) {
        var id = $(e.currentTarget).data('id');
        page.getPropertyList(id);
    });
    $('body').delegate('.js_edit', 'click', function(e) {
        var id = $(e.currentTarget).data('id');
        page.getDetail(id);
    });
    $('body').delegate('.js_delete', 'click', function(e){
        var id = $(e.currentTarget).data('id');
        page.deleteConfirmTip(id);
    });
    $('body').delegate('.js_sure_delete', 'click', function(e){
        var id = $(e.currentTarget).data('id');
        page.deletItem(id);
    });
    $('body').delegate('.js_addPhoto', 'click', function(e) {
        if ($('#addModal').hasClass('in')) {
            $('.js_add_photo').val($('#prevArea').attr('src'));
            $('.js_add_photo_prev').attr('src', $('#prevArea').attr('src'));
        } else {
            $('.js_photo').val($('#prevArea').attr('src'));
            $('.js_photo_prev').attr('src', $('#prevArea').attr('src'));
        }
        $('#uploadModal').modal('hide');
    });
    $('body').delegate('.js_add_saveBtn', 'click', function() {
        var photo = $('.js_add_photo').val()=='' ? $('.js_add_photo_prev').attr('src') : $('.js_add_photo').val(),
            status = $('.js_add_select').val(),
            link = $('.js_add_link').val(),
            sort = $('.js_add_sort').val();
        var json = {
                api: config.apiServer + 'user/post',
                type: 'post',
                data: {
                    actionxm: 'addUser',
                    params: {
                        img: photo,
                        link: link,
                        status: status,
                        zorder: sort
                    }
                }
            };
        var callback = function(res) {
            if(res.status==0) {
                alert(res.msg);
                window.location.reload();
            } else {
                alert(res.msg);
            }
        };
        json.callback = callback;
        Utils.requestData(json);
    });
    $('body').delegate('.js_saveBtn', 'click', function() {
        var id = $('.js_id').text(),
            photo = $('.js_photo').val()=='' ? $('.js_photo_prev').attr('src') : $('.js_photo').val(),
            link = $('.js_update_link').val(),
            sort = $('.js_update_sort').val(),
            status = $('.js_select').val();
        var json = {
                api: config.apiServer + 'user/post',
                type: 'post',
                data: {
                    actionxm: 'updateUser',
                    id: id,
                    params: {
                        img: photo,
                        link: link,
                        zorder: sort,
                        status: status
                    }
                }
            };
        var callback = function(res) {
            if(res.status==0) {
                alert(res.msg);
                window.location.reload();
            } else {
                alert(res.msg);
            }
        };
        json.callback = callback;
        Utils.requestData(json);
    });
    $('#photo').uploadifive({
        fileTypeDesc: '上传文件',
        fileTypeExts: '*.jpg;*.jpeg;*.gif;*.png',
        multi: false,
        buttonText: '上传文件',
        height: '25',
        width: '100',
        method: 'post',
        fileObjName: 'uploadfile',
        uploadScript: config.apiServer + 'user/post',
        formData: {
            'actionxm': 'upload_photo'
        },
        onUploadComplete: function(file, data, response) {
            result = $.parseJSON(data);
            if(result['status']==0) {
                $('#prevArea').attr('src', result['name']);
            } else {
                alert(result['msg']);
            }
        }
    });



    $('.js_searchFrom').submit(function(e) {
        e.preventDefault();
        var p = $('.js_page li[class=active] a').data('page');
        page.init(p);
    });

});
