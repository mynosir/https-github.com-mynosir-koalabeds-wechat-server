$(function() {
    var page = {
        init: function(p) {
            var json = {
                api: config.apiServer + 'order/get',
                type: 'get',
                data: {
                    actionxm: 'getOrder',
                    page: !p ? 1 : p,
                    size: 20,
                    classify: 0
                }
            };
            var callback = function(res) {
                // 处理表格数据
                var list = res['list'],
                    show = ['show','hide'],
                    status = ['not_confirmed','confirmed','canceled','checked_in','checked_out','no_show'],
                    listTpl = '<tr><th>no.</th><th>openid</th><th>酒店id</th><th>startDate</th><th>endDate</th><th>guestFirstName</th><th>guestLastName</th><th>guestCountry</th><th>guestZip</th><th>guestEmail</th><th>guestPhone</th><th>预定房间信息</th><th>预订的房间房型</th><th>预订的房间数量</th><th>预订的大人房间信息</th><th>预订的大人房间类型</th><th>预订的大人房间数量</th><th>预订的儿童房间信息</th><th>预订的儿童房间类型</th><th>预订的儿童房间数量</th><th>订单状态</th><th>总价格</th><th>小程序计算的总价格</th><th>余款</th><th>余款细节</th><th>分配的酒店房间详情</th><th>未分配的酒店房间详情</th><th>信用卡信息</th><th>预定id</th><th>到店时间，24小时制</th><th>订单生成时间</th><th>订单编号</th><th>交易单号</th><th>交易详情</th><th></th></tr>';
                for(var i in list) {
                    listTpl += '<tr>';
                    listTpl += '<td>' + list[i]['id'] + '</td>';
                    listTpl += '<td>' + list[i]['openid'] + '</td>';
                    listTpl += '<td>' + list[i]['propertyID'] + '</td>';
                    listTpl += '<td>' + list[i]['startDate'] + '</td>';
                    listTpl += '<td>' + list[i]['endDate'] + '</td>';
                    listTpl += '<td>' + list[i]['guestFirstName'] + '</td>';
                    listTpl += '<td>' + list[i]['guestLastName'] + '</td>';
                    listTpl += '<td>' + list[i]['guestLastName'] + '</td>';
                    listTpl += '<td>' + list[i]['guestCountry'] + '</td>';
                    listTpl += '<td>' + list[i]['guestZip'] + '</td>';
                    listTpl += '<td>' + list[i]['guestEmail'] + '</td>';
                    listTpl += '<td>' + list[i]['guestPhone'] + '</td>';
                    listTpl += '<td>' + list[i]['guestPhone'] + '</td>';
                    listTpl += '<td>' + list[i]['rooms'] + '</td>';
                    listTpl += '<td>' + list[i]['rooms_roomTypeID'] + '</td>';
                    listTpl += '<td>' + list[i]['rooms_quantity'] + '</td>';
                    listTpl += '<td>' + list[i]['adults'] + '</td>';
                    listTpl += '<td>' + list[i]['adults_roomTypeID'] + '</td>';
                    listTpl += '<td>' + list[i]['adults_quantity'] + '</td>';
                    listTpl += '<td>' + list[i]['children'] + '</td>';
                    listTpl += '<td>' + list[i]['children_roomTypeID'] + '</td>';
                    listTpl += '<td>' + list[i]['children_quantity'] + '</td>';
                    listTpl += '<td>' + status[list[i]['status']] + '</td>';
                    listTpl += '<td>' + list[i]['total'] + '</td>';
                    listTpl += '<td>' + list[i]['frontend_total'] + '</td>';
                    listTpl += '<td>' + list[i]['balance'] + '</td>';
                    listTpl += '<td>' + list[i]['balanceDetailed'] + '</td>';
                    listTpl += '<td>' + list[i]['assigned'] + '</td>';
                    listTpl += '<td>' + list[i]['unassigned'] + '</td>';
                    listTpl += '<td>' + list[i]['cardsOnFile'] + '</td>';
                    listTpl += '<td>' + list[i]['reservationID'] + '</td>';
                    listTpl += '<td>' + list[i]['estimatedArrivalTime'] + '</td>';
                    listTpl += '<td>' + list[i]['create_time'] + '</td>';
                    listTpl += '<td>' + list[i]['outTradeNo'] + '</td>';
                    listTpl += '<td>' + list[i]['transaction_id'] + '</td>';
                    listTpl += '<td>' + list[i]['transaction_info'] + '</td>';
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
        getDetail: function(id) {
            var json = {
                api: config.apiServer + 'order/get',
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
        deleteConfirmTip: function(id) {
            $('#confirmModal').find('.js_sure_delete').attr('data-id', id);
            $('#confirmModal').modal('show');
        },
        deletItem: function(id) {
            var json = {
                api: config.apiServer + 'order/post',
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
                api: config.apiServer + 'order/post',
                type: 'post',
                data: {
                    actionxm: 'addOrder',
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
                api: config.apiServer + 'order/post',
                type: 'post',
                data: {
                    actionxm: 'updateOrder',
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
        uploadScript: config.apiServer + 'order/post',
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
});
