/**
 * 公用模态窗
 *
 * @category yunke
 * @package atlas\hell\spider-man\src\controllers\account
 * @author no<no>
 * @copyright © ECDO, Inc. All rights reserved.
 */

// 带参弹窗交互
$('.opWin').click(function() {
    var path = $('#path').attr('action'); // 获取公用路径
    var uri = $(this).attr('uri'); // 获取路由路径
    var params = $(this).attr('params') ? "params=" + $(this).attr('params') : ''; // 获取参数
    var big = $(this).attr('big'); // 获取大窗口参数
    var method = $(this).attr('post'); // 获取窗口是否需要提交数据
    var property = 'viewModal'; // 窗口属性
    var title = $(this).attr('w-title') != undefined ? $(this).attr('w-title') : '&nbsp;'; // 窗口标题
    if (big != undefined) {
        property = 'viewModalMax';
    }

    $('#'+property).modal();
    $.ajax({
        type: "GET",
        data: params,
        url: path+"/"+uri,
    }).done(function(html_form) {
        // 窗口带表单提交
        if (method != undefined) {
            $('#'+property+'Form').attr('method', 'post');
            $('#'+property+'Form').attr('action', path+"/"+uri);
        }
        
        $('#'+property+'Label').html(title);
        $('#'+property+'body').html(html_form);
        $('#'+property).show();
    });
});
