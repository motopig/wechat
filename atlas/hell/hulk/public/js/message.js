/**
 * Created by moto on 6/16/15.
 */


function getFormatDate() {			//获取当前时间(yyyy-mm-dd hh:mm)
    var date = new Date();
    return date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate() + " " + date.getHours() + ":" + date.getMinutes();
}



$('#replyListUI').on('click', '.list-ul .photo, .list-ul .msgBox', function(e){
    var member_id = $(this).parent().attr('data-member_id');
    var _this = $(this);

    if(_this.parent().find('.sendMsgBox').length){
        _this.parent().removeClass('selected');
        _this.parent().find('.sendMsgBox').html('').remove();
        $('#MsgModal').css('display', 'none');
    }else{
        //隐藏其他聊天窗口
        _this.parent().siblings().find('.sendMsgBox').html('').remove();
        //去除其他聊天窗口的聊天状态
        _this.parent().siblings().removeClass('selected');
        //去除消息数目提示
        $('.tip').length && $('.tip').remove();
        var cat = window.location.search ? window.location.search.split('=')[1] : '';
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: $('#getMemberMessageAction').val(),
            data: {member_id:member_id,cat:cat},
            success: function(rs){

                var source = $('#chat-message-model').html();
                var template = Handlebars.compile(source);
                var messageListDom = template(rs);

                _this.parent().addClass('selected');
                _this.parent().append($('#msgBox').html());
                _this.parent().find('.sendMsgBox').removeClass('fn-hide');
                _this.parent().find('.recent-message-list').empty().append(messageListDom);
                $('.recent-message-list').scrollTop($('.recent-message-list li:last').position().top);
            },
        });
        //使当前输入窗口focus
        $('#editor').focus();
        $('#MsgModal').css('display', 'block');
    }
});

// 模态返回函数
var modalCallBack = function (type, id) {
    var url = $('.bolt-modal-preview-url').attr('data-url');
    var data = new FormData();

    data.append('csrf_token', $('#csrf_token').val());
    data.append('csrf_guid', $('#csrf_guid').val());
    data.append('type', type);
    data.append('id', id);

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        contentType: false,
        processData: false,

        success:function(result) {
            var data = jQuery.parseJSON(result);

            if (data.html != '') {
                $('#editor').hide();
                $('#modal-editor').empty().append(data.html).show();
                $('#modal-editor-data').attr('data-type', data.type).attr('data-id', data.id);
            }
        }
    });
}


$('#replyListUI').on('click', '.message-reply-click', function(e){

    var messageId = $('#modal-editor-data').attr('data-id');
    var messageType = $('#modal-editor-data').attr('data-type');
    var userId = $('#replyListUI').find('.selected').attr('data-member_id');
    var appendData = '<li class="recent-message-item fn-clear"><div class="message-time">' + getFormatDate() + '</div><div class="message-content fm-right">';

    if(!messageId && !$('#editor').html()){
        alertify.error('请输入消息内容!');return false;
    }
    if($('#modal-editor').html()){
        $('#modal-editor').find('.item-overlay').removeClass('item-overlay overlay-modal-preview opacity r ');
        $('#modal-editor').find('.text-center').remove();
    }

    var content = $('#modal-editor').html() ? $('#modal-editor').html() : HtmlDecode($('#editor').html());

    $('#modal-editor').empty();

    if(messageId){
        appendData +=  content;
    }else{
        appendData += '<p class="content-text word-bread right-bg">' + content + '<i class="arrow-right"></i></p>';
    }

    appendData += '</div><span class="send-statue"><img src="../../atlas/hell/hulk/images/message_send_loading.gif" alt="loding"></span></li>';
    $(".recent-message-list").append(HtmlDecode(appendData));

    sendMsg(content, userId, messageId, messageType);
    //清空聊天窗口内容
    $('#editor').html('').css('display', 'block').focus();
    $('#modal-editor').html('');
    $('#modal-editor-data').attr('data-type', '').attr('data-id', '');

});

$('#replyListUI').on('click', '.data-preview-trash', function() {
        $('#modal-editor-data').attr('data-type', '').attr('data-id', '');
        $('#modal-editor').empty().hide();
        $('#editor').show().focus();
});


//发送消息给用户
function sendMsg(content, userId, messageId, messageType){

    var url = $('.message-send-url').val();
    var data = new FormData();

    data.append('csrf_token', $('#csrf_token').val());
    data.append('csrf_guid', $('#csrf_guid').val());
    messageId ? data.append('message_id', messageId) : data.append('content', content);
    messageType && data.append('type', messageType);
    data.append('user_id', userId);

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        contentType: false,
        processData: false,
        dataType: 'json',

        success:function(rs) {
            if(rs.status == 'success'){
                //去除loading状态
                $('.send-statue').css('display', 'none');
                alertify.success('发送消息成功!');
            }else{
                alertify.error(rs.status);
            }
        }
    });

}

//转换 HTML 标签
function HtmlDecode(str){

    var  s  =  "";
    if(str.length    ==    0) return    "";

    s  =  str.replace(/&lt;/g," <");
    s  =  s.replace(/&gt;/g,">");
    s  =  s.replace(/&nbsp;/g," ");
    s  =  s.replace(/'/g,"\'");
    s  =  s.replace(/&quot;/g,"\"");
    s  =  s.replace(/<br>/g,"\n");
    return    s;
}


function checkNewMessage(){
    $.ajax({
        url: $('#checkNewMessage').val(),
        type: 'GET',
        data: {curTime:$('.curTime').val()},
        dataType: 'json',

        success:function(rs) {
            if(rs.nums >0 ){
                //有新消息
                $('#hasNewMsg').css('display', 'block').attr('nums', 1);
                clearInterval(check);
            }
        }
    });
}

//定时查询
$(document).ready(function(){
    if($('.curTime').val() > 0){
        check = setInterval(checkNewMessage, 2000);
    }
});

$('#hasNewMsg').click(function(e){
    e = e || event;
    e.preventDefault();
    window.location.reload();
});

//加载更多
$('#replyListUI').on('click', '.history-msg', function(e){
    _ = $(this);
    e = e || event;
    e.preventDefault();
    $.ajax({

        url: $('#moreMessage').val(),
        dataType: 'json',
        data: {page:$('.morePage').val(), member_id: _.parent().parent().parent().attr('data-member_id')},
        type: 'GET',
        success: function(rs){
            if(!rs.data.length){
                alertify.error('没有更多消息了！');  return false;
            }
            var source = $('#chat-message-model').html();
            var template = Handlebars.compile(source);
            var messageListDom = template(rs);
            _.next().next().before(messageListDom);
            _.remove();
            $('.morePage').val( (parseInt($('.morePage').val()) + 1) );
        }
    });
});

////类型查看
//$('.nav-tabs li').click(function(e){
//    _ = $(this);
//    _.addClass('active').siblings().removeClass('active');
//    var cat = _.find('a').attr('data-type');
//
//    $.ajax({
//
//        url: $('#catMessage').val(),
//        data: {cat:cat},
//        type: 'GET',
//        dataType: 'json',
//        success:function(rs){
//
//            $('#replyListUI').empty();
//            var source = $('#userListTemp').html();
//            var template = Handlebars.compile(source);
//            var messageListDom = template(rs);
//            $('#replyListUI').append(messageListDom);
//            clearInterval(check);
//        }
//
//    });
//});