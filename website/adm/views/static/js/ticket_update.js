
$(function() {
    var page = {
        init: function() {
          $('#introduce').summernote({
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
              placeholder: 'Please enter the terms and conditions',
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
            $('#introduce_cn').summernote({
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
            $('#clause').summernote({
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
                placeholder: 'Please enter the terms and conditions',
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
              $('#clause_cn').summernote({
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

        },


    };

    page.init();

    // $('body').delegate('.js_delete_pic', 'click', function(e) {
    //     $(e.currentTarget).parent().parent().remove();
    // });

    $('body').delegate('.js_submit', 'click', function() {

        var id = $('#id').val(),
            productId = $('#productId').val(),
            // type = $('#type').val(),
            title = $('#title').val(),
            title_cn = $('#title_cn').val(),
            introduce = $('#introduce').summernote('code');
            introduce_cn = $('#introduce_cn').summernote('code');
            clause = $('#clause').summernote('code');
            clause_cn = $('#clause_cn').summernote('code');
        // var id = $('.js_id').val();
        var json = {
            api: config.apiServer + 'ticket/post',
            type: 'post',
            data: {
                actionxm: 'save',
                id: id,
                // type: type,
                params: {
                  title: title,
                  introduce: introduce,
                  clause: clause,
                  productId: productId,
                  title_cn: title_cn,
                  introduce_cn: introduce_cn,
                  clause_cn: clause_cn
                }
            }
        };
        var callback = function(res) {
            if(res.status == 0) {
                alert('Save success!');
                window.location.href = "/adm/ticket";
            } else {
                alert(res.msg);
            }
        };
        json.callback = callback;
        Utils.requestData(json);

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
    $('#photo').uploadifive({
        fileTypeDesc: 'uploadfile',
        fileTypeExts: '*.jpg;*.jpeg;*.gif;*.png',
        multi: false,
        buttonText: 'uploadfile',
        height: '25',
        width: '100',
        method: 'post',
        fileObjName: 'uploadfile',
        uploadScript: config.apiServer + 'hotel/post',
        formData: {
            'actionxm': 'upload_photo'
        },
        onUploadComplete: function(file, data, response) {
            result = $.parseJSON(data);
            if(result['status']==0) {
                $('#prevArea').attr('src', result['name']);
                $('.js_img_thumb').val(result['thumbName']);
            } else {
                alert(result['msg']);
            }
        }
    });

});
