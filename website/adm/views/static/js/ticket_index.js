$(function() {
    var page = {

        // 页面初始化方法
        init: function(p) {
            var self = this,
                json = {
                    api: config.apiServer + 'ticket/get',
                    type: 'get',
                    data: {
                        actionxm: 'search',
                        page: !p ? 1 : p,
                        size: 10,
                        keyword: $('#search_title').val()
                    }
                };
            var callback = function(res) {
                // if(res.status == 0) {
                    console.log(res.list);
                    // function compare(property){
                    //     return function(a,b){
                    //         var value1 = a[property];
                    //         var value2 = b[property];
                    //         return value1 - value2;
                    //     }
                    // }
                    // res.list = res.list.sort(compare('status'));

                    var idx = 1,
                        list = res['list'],
                        status = ['Unread','Read'],
                        listTpl = '<tr><th>Serial No.</th><th>Product ID</th><th>Type</th><th>Title</th><th>Title(Chinese)</th><th>Operation</th></tr>';
                    for(var i in list) {
                        var listid = parseInt(i)+1;
                        // if(list[i]['name_cn'] ==undefined){
                        //   list[i]['name_cn'] = ''
                        // }
                        listTpl += '<tr>';
                        listTpl += '<td>' + listid + '</td>';
                        listTpl += '<td>' + list[i]['productId'] + '</td>';
                        listTpl += '<td>' + list[i]['type'] + '</td>';
                        listTpl += '<td>' + list[i]['title'] + '</td>';
                        listTpl += '<td>' + list[i]['title_cn'] + '</td>';
                        // listTpl += '<td>' + list[i]['roomTypeNameShort'] + '</td>';
                        listTpl += '<td><button type="button" class="btn btn-sm btn-primary js_edit" data-toggle="modal" data-target="#editModal" data-id="' + list[i]['id'] + '">Edit</button></td>';
                        listTpl += '</tr>';
                    }
                    $('.js_table').html(listTpl);
                // } else {
                //     alert(res.msg);
                // }

                    // for(var i in list){
                    //   $('.selectSection').eq(i).val(list[i]['status']);
                    //   $('.selectRecommend').eq(i).val(list[i]['recommend']);
                    // }

                    // 处理分页
                    var pageTpl = '',
                        total = parseInt(res.total),//18
                        size = parseInt(res.size),//5
                        page = parseInt(res.page),//4
                        itemNum = Math.ceil(total / size),//4
                        itemStart = 1,
                        itemMax = 1,
                        fisrtItemCls = page==1 ? ' class="disabled"' : '',
                        lastItemCls = page==itemNum ? ' class="disabled"' : '';
                    pageTpl += '<li ' + fisrtItemCls + '><a href="javascript:void(0)" aria-label="Previous" data-page="1" class="js_pageItem"><span aria-hidden="true">&laquo;</span></a></li>';
                    if(page>4) {
                        itemStart = (page + 2) > itemNum ? itemNum - 4 : page - 2;//2
                        itemMax = (page + 2) > itemNum ? itemNum : page + 2;//4
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


    };

    $('body').delegate('.js_pageItem', 'click', function(e) {
        var p = $(e.currentTarget).data('page');
        page.init(p);
    });

    $('body').delegate('.js_edit', 'click', function(e) {
        var id = $(e.currentTarget).data('id');
        window.location.href = '/adm/ticket/edit/' + id;
    });

    $('body').delegate('.selectSection', 'change', function(e) {

      var id = $(e.currentTarget).data('id');
      var selectedStatus = e.currentTarget.options.selectedIndex;
      page.updateConfirmTip(id,selectedStatus)
    });

    $('body').delegate('.selectRecommend', 'change', function(e) {

      var id = $(e.currentTarget).data('id');
      var selectedRecommend = e.currentTarget.options.selectedIndex;
      page.updateConfirmTip2(id,selectedRecommend)
    });

    $('body').delegate('.js_sure_update', 'click', function(e) {
      var id = $(e.currentTarget).data('id');
      var status = $(e.currentTarget).data('status');
      page.updateItem(id,status);
    });

    $('body').delegate('.js_sure_update2', 'click', function(e) {
      var id = $(e.currentTarget).data('id');
      var recommend = $(e.currentTarget).data('recommend');
      page.updateRecommend(id,recommend);
    });

    $('#confirmModal').on('hide.bs.modal', function () {
      // 执行一些动作...
      window.location.reload();
    })

    $('#confirmModal2').on('hide.bs.modal', function () {
      // 执行一些动作...
      window.location.reload();
    })

    $('.js_searchFrom').submit(function(e) {
        e.preventDefault();
        // var p = $('.js_page li[class=active] a').data('page');
        page.init();
    });



    page.init();





});
