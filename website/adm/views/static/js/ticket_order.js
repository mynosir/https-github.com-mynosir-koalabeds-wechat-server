$(function() {
    var page = {
        init: function(p, nickname, status) {
            var json = {
                api: config.apiServer + 'ticket_order/get',
                type: 'get',
                data: {
                    actionxm: 'getTicket',
                    page: !p ? 1 : p,
                    size: 20,
                    nickname: nickname,
                    status: status
                }
            };
            var callback = function(res) {
                // 处理表格数据
                var list = res['list'],
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
                    listTpl = '<tr><th>Serial No.</th><th>Trade No</th><th>Wechat Nickname</th><th>Total Price</th><th>Type</th><th>Title</th><th>FirstName</th><th>LastName</th><th>Passport</th><th>Guest Email</th><th>Country Code</th><th>Telephone</th><th>Guest Selected Address</th><th>Create Time</th></tr>';
                    listTpl2 = '<tr><th>Status</th></tr>';
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
                    listTpl2 += '<tr>';
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
                    // listTpl += '<td>' + getStatus(list[i]['status']) + '</td>';
                    listTpl2 += '<td>' + getStatus(list[i]['status']) + '</td>';

                    listTpl += '</tr>';
                    listTpl2 += '</tr>';
                }
                $('.js_table').html(listTpl);
                $('.js_table2').html(listTpl2);
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
        getDetail: function(id) {
            var json = {
                api: config.apiServer + 'ticket_order/get',
                type: 'get',
                data: {
                    actionxm: 'getDetail',
                    id: !id ? 1 : id
                }
            };
            var callback = function(res) {
                $('.js_id').text(res.id);
                $('.js_photo_prev').attr('src', res.img);
                $('.js_update_link').val(res.link);
                $('.js_select').val(res.status);
                $('.js_update_sort').val(res.zorder);
            };
            json.callback = callback;
            Utils.requestData(json);
        },
        export: function() {
            window.location.href = config.apiServer+'ticket_order/get?actionxm=export';
        },
        deleteConfirmTip: function(id) {
            $('#confirmModal').find('.js_sure_delete').attr('data-id', id);
            $('#confirmModal').modal('show');
        },
        deletItem: function(id) {
            var json = {
                api: config.apiServer + 'ticket/post',
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
                api: config.apiServer + 'ticket/post',
                type: 'post',
                data: {
                    actionxm: 'addTicket',
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
                api: config.apiServer + 'ticket/post',
                type: 'post',
                data: {
                    actionxm: 'updateTicket',
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
        uploadScript: config.apiServer + 'ticket/post',
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
        // var p = $('.js_page li[class=active] a').data('page');
        var nickname = $('#nickname').val();
        var status = $('#status').val();
        // console.log(nickname);
        // console.log(status);
        page.init(1, nickname, status);
    });

});
