/**
 * Created by moto on 7/16/15.
 */


$('.sclose').click(function(){

    if($('.sclose').css('display') == 'none'){
        return false;
    }

    //disable 抽奖按钮
    if($('.not').val() == 1){
        return false;
    }
    //$('.sclose').css('display', 'none');
    $('.not').val(1);
    var data = new FormData();
    data.append('openid', $('.openid').val());
    data.append('guid', $('.guid').val());
    data.append('id', $('.sid').val());

    reset = function () {
        alertify.set({
            labels : {
                ok     : "确认",
                cancel : "取消"
            },
            delay : 5000,
            buttonReverse : false,
            buttonFocus   : "ok"
        });
    };
    reset();

    $.ajax({
        url: $('.prize_action').val(),
        type: 'POST',
        data: data,
        contentType: false,
        processData: false,

        success:function(result) {
            var rs = jQuery.parseJSON(result);

            if(rs.errcode == 'success'){
                if(rs.data != ''){//中奖
                    $('.sclose').css('display', 'none');
                    $('.win').css('display', 'block');
                    $('.loser').css('display', 'none');
                    $('.not').val(0);
                    alertify.confirm('恭喜您获得优惠券: ' + rs.data.prize.title + ', 点击「确认」去消费', function (e) {
                        if (e) {
                            $('.win').css('display', 'none');
                            $('.sclose').css('display', 'block');
                            //todo jump
                            window.location.href = rs.data.url;
                        }else{
                            $('.win').css('display', 'none');
                            $('.sclose').css('display', 'block');
                        }
                    });
                }else{//未中奖
                    $('.sclose').css('display', 'none');
                    $('.win').css('display', 'none');
                    $('.loser').css('display', 'block');
                    alertify.confirm(rs.errmsg, function(e) {
                        $('.loser').css('display', 'none');
                        $('.sclose').css('display', 'block');
                    });
                    $('.not').val(0);
                }
            }else{
                $('.not').val(0);
                alertify.alert(rs.errmsg);
            }

        }
    });



});
