
$(function() {
    var page = {
        init: function() {
            $('#descript').summernote({
                toolbar: [
                    <!--字体工具-->
                    ['fontname', ['fontname']], //字体系列
                    ['style', ['bold', 'italic', 'underline', 'clear']], // 字体粗体、字体斜体、字体下划线、字体格式清除
                    ['font', ['strikethrough', 'superscript', 'subscript']], //字体划线、字体上标、字体下标
                    ['fontsize', ['fontsize']], //字体大小
                    ['color', ['color']], //字体颜色

                    <!--段落工具-->
                    ['style', ['style']],//样式
                    ['para', ['ul', 'ol', 'paragraph']], //无序列表、有序列表、段落对齐方式
                    ['height', ['height']], //行高

                    <!--插入工具-->
                    ['table',['table']], //插入表格
                    ['hr',['hr']],//插入水平线
                    ['link',['link']], //插入链接
                    // ['picture',['picture']], //插入图片
                    // ['video',['video']], //插入视频

                    <!--其它-->
                    ['fullscreen',['fullscreen']], //全屏
                    ['codeview',['codeview']], //查看html代码
                    ['undo',['undo']], //撤销
                    ['redo',['redo']], //取消撤销
                    ['help',['help']], //帮助
                ],
                minHeight: 200,
                placeholder: 'Please enter the description',
                dialogsFade: true,
                dialogsInBody: true,
                disableDragAndDrop: false,
                // callbacks: {
                //     onImageUpload: function(files) {
                //         var $files = $(files),
                //             url = config.apiServer + 'pic_content/post?actionxm=upload_contentImg';
                //         $files.each(function() {
                //             var file = this;
                //             var data = new FormData();
                //             data.append('file', file);
                //             $.ajax({
                //                 data: data,
                //                 type: 'POST',
                //                 url: url,
                //                 cache: false,
                //                 contentType: false,
                //                 processData: false,
                //                 success: function(res) {
                //                     var data = JSON.parse(res);
                //                     $('#descript_en').summernote('insertImage', data.name, function($image) { });
                //
                //                 },
                //                 error: function() {
                //                     console.error('error');
                //                 }
                //             });
                //         });
                //     }
                // }
            });

            $('#descriptCh').summernote({
                toolbar: [
                    <!--字体工具-->
                    ['fontname', ['fontname']], //字体系列
                    ['style', ['bold', 'italic', 'underline', 'clear']], // 字体粗体、字体斜体、字体下划线、字体格式清除
                    ['font', ['strikethrough', 'superscript', 'subscript']], //字体划线、字体上标、字体下标
                    ['fontsize', ['fontsize']], //字体大小
                    ['color', ['color']], //字体颜色

                    <!--段落工具-->
                    ['style', ['style']],//样式
                    ['para', ['ul', 'ol', 'paragraph']], //无序列表、有序列表、段落对齐方式
                    ['height', ['height']], //行高

                    <!--插入工具-->
                    ['table',['table']], //插入表格
                    ['hr',['hr']],//插入水平线
                    ['link',['link']], //插入链接
                    // ['picture',['picture']], //插入图片
                    // ['video',['video']], //插入视频

                    <!--其它-->
                    ['fullscreen',['fullscreen']], //全屏
                    ['codeview',['codeview']], //查看html代码
                    ['undo',['undo']], //撤销
                    ['redo',['redo']], //取消撤销
                    ['help',['help']], //帮助
                ],
                minHeight: 200,
                placeholder: 'Please enter the descriptionCh',
                dialogsFade: true,
                dialogsInBody: true,
                disableDragAndDrop: false,
                // callbacks: {
                //     onImageUpload: function(files) {
                //         var $files = $(files),
                //             url = config.apiServer + 'pic_content/post?actionxm=upload_contentImg';
                //         $files.each(function() {
                //             var file = this;
                //             var data = new FormData();
                //             data.append('file', file);
                //             $.ajax({
                //                 data: data,
                //                 type: 'POST',
                //                 url: url,
                //                 cache: false,
                //                 contentType: false,
                //                 processData: false,
                //                 success: function(res) {
                //                     var data = JSON.parse(res);
                //                     $('#descript_en').summernote('insertImage', data.name, function($image) { });
                //
                //                 },
                //                 error: function() {
                //                     console.error('error');
                //                 }
                //             });
                //         });
                //     }
                // }
            });

        },


    };

    page.init();

    // $('body').delegate('.js_delete_pic', 'click', function(e) {
    //     $(e.currentTarget).parent().parent().remove();
    // });

    $('body').delegate('.js_submit', 'click', function() {

        var propertyID = $('#propertyID').val(),
            propertyName = $('#propertyName').val(),
            propertyPhone = $('#propertyPhone').val(),
            propertyEmail = $('#propertyEmail').val(),
            propertyAddress1 = $('#propertyAddress1').val(),
            propertyAddress2 = $('#propertyAddress2').val(),
            propertyCity = $('#propertyCity').val(),
            propertyState = $('#propertyState').val(),
            propertyZip = $('#propertyZip').val(),
            propertyCountry = $('#propertyCountry').val(),
            propertyLatitude = $('#propertyLatitude').val(),
            propertyLongitude = $('#propertyLongitude').val(),
            propertyCheckInTime = $('#propertyCheckInTime').val(),
            propertyCheckOutTime = $('#propertyCheckOutTime').val(),
            propertyLateCheckOutType = $('#propertyLateCheckOutType').val(),
            propertyLateCheckOutValue = $('#propertyLateCheckOutValue').val(),
            propertyTermsAndConditions = $('#propertyTermsAndConditions').val(),
            propertyAmenities = $('#propertyAmenities').val(),
            propertyDescription = $('#descript').summernote('code'),
            propertyCurrencyCode = $('#propertyCurrencyCode').val(),
            propertyCurrencySymbol = $('#propertyCurrencySymbol').val(),
            propertyCurrencyPosition = $('#propertyCurrencyPosition').val(),
            propertyStatus = $('.selectStatus').val(),
            propertyRecommend = $('.selectRecommend').val(),
            propertyNameCh = $('#propertyNameCh').val(),
            propertyAddressCh = $('#propertyAddressCh').val(),
            propertyDescriptionCh = $('#descriptCh').summernote('code');


        // // 获取图片json
        // var prevPicDom = $('.prev-pic'),
        //     picArr = new Array();
        // for(var i=0; i<prevPicDom.length; i++) {
        //     picArr.push($(prevPicDom[i]).attr('src'));
        // }
        // pic =  picArr.toString();
        // if(title_en == '') {
        //     alert('请输入标题（en）！');
        //     return;
        // }
        // if(title_tc == '') {
        //     alert('请输入标题（tc）！');
        //     return;
        // }
        // if(clazz_id == '') {
        //     alert('请选择分类！');
        //     return;
        // }
        // if(pic == '') {
        //     alert('请上传图片！');
        //     return;
        // }
        // if(num == '') {
        //     alert('请输入标号！');
        //     return;
        // }
        // if(descript_tc == '') {
        //     alert('请输入描述（tc）!');
        //     return;
        // }
        // if(sort == '') {
        //     alert('请输入排序!');
        //     return;
        // }
        var id = $('.js_id').val();
        var json = {
            api: config.apiServer + 'hotel/post',
            type: 'post',
            data: {
                actionxm: 'update',
                id: id,
                params: {
                    en: {
                      propertyName: propertyName||'',
                      propertyPhone: propertyPhone||'',
                      propertyEmail: propertyEmail||'',
                      propertyAddress1: propertyAddress1||'',
                      propertyAddress2: propertyAddress2||'',
                      propertyCity: propertyCity||'',
                      propertyState: propertyState||'',
                      propertyZip: propertyZip||'',
                      propertyCountry: propertyCountry||'',
                      propertyLatitude: propertyLatitude||'',
                      propertyLongitude: propertyLongitude||'',
                      propertyCheckInTime: propertyCheckInTime||'',
                      propertyCheckOutTime: propertyCheckOutTime||'',
                      propertyLateCheckOutType: propertyLateCheckOutType||'',
                      propertyLateCheckOutValue: propertyLateCheckOutValue||'',
                      propertyTermsAndConditions: propertyTermsAndConditions||'',
                      propertyAmenities: propertyAmenities||'',
                      propertyDescription: propertyDescription||'',
                      propertyCurrencyCode: propertyCurrencyCode||'',
                      propertyCurrencySymbol: propertyCurrencySymbol||'',
                      propertyCurrencyPosition: propertyCurrencyPosition||'',
                      recommend: propertyRecommend||'',
                      status: propertyStatus||''
                    },
                    ch: {
                      propertyID: propertyID,
                      propertyName: propertyNameCh,
                      propertyAddress: propertyAddressCh,
                      propertyDescription: propertyDescriptionCh
                    }

                }
            }
        };
        var callback = function(res) {
            if(res.status == 0) {
                alert('Save success!');
                window.location.href = "/adm/hotel";
            } else {
                alert(res.msg);
            }
        };
        json.callback = callback;
        Utils.requestData(json);

    });

});
