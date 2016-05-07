/**
 * Created by moto on 6/2/15.
 */

// emoji 图片点击选择后的动作
$('.custom').on('click', '#emoji a', function(){
    var emojiPreview = '';
    var selfImg = $(this).children('img');
    emojiPreview += '<img src="'+ selfImg.attr('src') +'" data="'+  $(this).attr('data') +'" />';
    _insertHtml($(this).parent().next().next().children('div .infoTextArea'),emojiPreview);
    $(this).parent().next().next().children('div .emoji_preview').append(emojiPreview);
    $(this).parent().css('display', 'none');

    //清空其他类型预览存储HTML内容 和 编辑标示
    $(this).parent().next().next().children('.onCur').removeClass('onCur');
    $(this).parent().next().next().children('.infoTextArea').addClass('onCur').css('display', 'block');
    $(this).parent().next().next().children('.infoTextArea').attr('updata', $(this).parent().next().next().children('.infoTextArea').html());
    $(this).parent().next().next().children('.spreview').empty();
    //$(this).parent().next().next().children('.emoji_preview').append($(this).parent().next().next().children('.infoTextArea').html());
});

//锁定编辑器中鼠标光标位置。。
function _insertHtml(_this, str){
    var selection= window.getSelection ? window.getSelection() : document.selection;
    var range= selection.createRange ? selection.createRange() : selection.getRangeAt(0);
    if (!window.getSelection){
        _this.focus();
        var selection= window.getSelection ? window.getSelection() : document.selection;
        var range= selection.createRange ? selection.createRange() : selection.getRangeAt(0);
        range.pasteHTML(str);
        range.collapse(false);
        range.select();
    }else{
        _this.focus();
        range.collapse(false);
        var hasR = range.createContextualFragment(str);
        var hasR_lastChild = hasR.lastChild;
        while (hasR_lastChild && hasR_lastChild.nodeName.toLowerCase() == "br" && hasR_lastChild.previousSibling && hasR_lastChild.previousSibling.nodeName.toLowerCase() == "br") {
            var e = hasR_lastChild;
            hasR_lastChild = hasR_lastChild.previousSibling;
            hasR.removeChild(e)
        }
        range.insertNode(hasR);
        if (hasR_lastChild) {
            range.setEndAfter(hasR_lastChild);
            range.setStartAfter(hasR_lastChild)
        }
        selection.removeAllRanges();
        selection.addRange(range)
    }
}

//显示 emoji 表情HTML
function emoji(_this){

    var emojiHtml = '';

    emojiHtml += '<div id="emoji" class="emotion-list" style="display: block;">';
    emojiHtml += '<a href="javascript:void(0)" data="/::)"><img src="' + $('.root_url').val() +'/assets/tower/emoji/0.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::~"><img src="' + $('.root_url').val() +'/assets/tower/emoji/1.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::B"><img src="' + $('.root_url').val() +'/assets/tower/emoji/2.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::|"><img src="' + $('.root_url').val() +'/assets/tower/emoji/3.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:8-)"><img src="' + $('.root_url').val() +'/assets/tower/emoji/4.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::<"><img src="' + $('.root_url').val() +'/assets/tower/emoji/5.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::$"><img src="' + $('.root_url').val() +'/assets/tower/emoji/6.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::X"><img src="' + $('.root_url').val() +'/assets/tower/emoji/7.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::Z"><img src="' + $('.root_url').val() +'/assets/tower/emoji/8.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::\'("><img src="' + $('.root_url').val() +'/assets/tower/emoji/9.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::-|"><img src="' + $('.root_url').val() +'/assets/tower/emoji/10.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::@"><img src="' + $('.root_url').val() +'/assets/tower/emoji/11.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::P"><img src="' + $('.root_url').val() +'/assets/tower/emoji/12.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::D"><img src="' + $('.root_url').val() +'/assets/tower/emoji/13.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::O"><img src="' + $('.root_url').val() +'/assets/tower/emoji/14.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::("><img src="' + $('.root_url').val() +'/assets/tower/emoji/15.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::+"><img src="' + $('.root_url').val() +'/assets/tower/emoji/16.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data=" /:--b"><img src="' + $('.root_url').val() +'/assets/tower/emoji/17.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::Q"><img src="' + $('.root_url').val() +'/assets/tower/emoji/18.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::T"><img src="' + $('.root_url').val() +'/assets/tower/emoji/19.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:,@P"><img src="' + $('.root_url').val() +'/assets/tower/emoji/20.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:,@-D"><img src="' + $('.root_url').val() +'/assets/tower/emoji/21.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:,@o"><img src="' + $('.root_url').val() +'/assets/tower/emoji/23.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::g"><img src="' + $('.root_url').val() +'/assets/tower/emoji/24.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:|-)"><img src="' + $('.root_url').val() +'/assets/tower/emoji/25.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::!"><img src="' + $('.root_url').val() +'/assets/tower/emoji/26.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::L"><img src="' + $('.root_url').val() +'/assets/tower/emoji/27.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::>"><img src="' + $('.root_url').val() +'/assets/tower/emoji/28.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::,@"><img src="' + $('.root_url').val() +'/assets/tower/emoji/29.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:,@f"><img src="' + $('.root_url').val() +'/assets/tower/emoji/30.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::-S"><img src="' + $('.root_url').val() +'/assets/tower/emoji/31.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:?"><img src="' + $('.root_url').val() +'/assets/tower/emoji/32.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:,@x"><img src="' + $('.root_url').val() +'/assets/tower/emoji/33.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:,@@"><img src="' + $('.root_url').val() +'/assets/tower/emoji/34.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::8"><img src="' + $('.root_url').val() +'/assets/tower/emoji/35.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:,@!"><img src="' + $('.root_url').val() +'/assets/tower/emoji/36.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:!!!"><img src="' + $('.root_url').val() +'/assets/tower/emoji/37.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:xx"><img src="' + $('.root_url').val() +'/assets/tower/emoji/38.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:bye"><img src="' + $('.root_url').val() +'/assets/tower/emoji/39.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:wipe"><img src="' + $('.root_url').val() +'/assets/tower/emoji/40.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:dig"><img src="' + $('.root_url').val() +'/assets/tower/emoji/41.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:handclap"><img src="' + $('.root_url').val() +'/assets/tower/emoji/42.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:&amp;-("><img src="' + $('.root_url').val() +'/assets/tower/emoji/43.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:B-)"><img src="' + $('.root_url').val() +'/assets/tower/emoji/44.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:<@"><img src="' + $('.root_url').val() +'/assets/tower/emoji/45.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:@>"><img src="' + $('.root_url').val() +'/assets/tower/emoji/46.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::-O"><img src="' + $('.root_url').val() +'/assets/tower/emoji/47.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:>-|"><img src="' + $('.root_url').val() +'/assets/tower/emoji/48.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:P-("><img src="' + $('.root_url').val() +'/assets/tower/emoji/49.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::\'|"><img src="' + $('.root_url').val() +'/assets/tower/emoji/50.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:X-)"><img src="' + $('.root_url').val() +'/assets/tower/emoji/51.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/::*"><img src="' + $('.root_url').val() +'/assets/tower/emoji/52.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:@x"><img src="' + $('.root_url').val() +'/assets/tower/emoji/53.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:8*"><img src="' + $('.root_url').val() +'/assets/tower/emoji/54.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:pd"><img src="' + $('.root_url').val() +'/assets/tower/emoji/55.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:<W>"><img src="' + $('.root_url').val() +'/assets/tower/emoji/56.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:beer"><img src="' + $('.root_url').val() +'/assets/tower/emoji/57.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:basketb"><img src="' + $('.root_url').val() +'/assets/tower/emoji/58.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:oo"><img src="' + $('.root_url').val() +'/assets/tower/emoji/59.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:coffee"><img src="' + $('.root_url').val() +'/assets/tower/emoji/60.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:eat"><img src="' + $('.root_url').val() +'/assets/tower/emoji/61.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:pig"><img src="' + $('.root_url').val() +'/assets/tower/emoji/62.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:rose"><img src="' + $('.root_url').val() +'/assets/tower/emoji/63.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:fade"><img src="' + $('.root_url').val() +'/assets/tower/emoji/64.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:showlove"><img src="' + $('.root_url').val() +'/assets/tower/emoji/65.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:heart"><img src="' + $('.root_url').val() +'/assets/tower/emoji/66.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:break"><img src="' + $('.root_url').val() +'/assets/tower/emoji/67.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:cake"><img src="' + $('.root_url').val() +'/assets/tower/emoji/68.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:li"><img src="' + $('.root_url').val() +'/assets/tower/emoji/69.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:bome"><img src="' + $('.root_url').val() +'/assets/tower/emoji/70.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:kn"><img src="' + $('.root_url').val() +'/assets/tower/emoji/71.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:footb"><img src="' + $('.root_url').val() +'/assets/tower/emoji/72.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:ladybug"><img src="' + $('.root_url').val() +'/assets/tower/emoji/73.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:shit"><img src="' + $('.root_url').val() +'/assets/tower/emoji/74.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:moon"><img src="' + $('.root_url').val() +'/assets/tower/emoji/75.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:sun"><img src="' + $('.root_url').val() +'/assets/tower/emoji/76.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:gift"><img src="' + $('.root_url').val() +'/assets/tower/emoji/77.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:hug"><img src="' + $('.root_url').val() +'/assets/tower/emoji/78.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:strong"><img src="' + $('.root_url').val() +'/assets/tower/emoji/79.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:weak"><img src="' + $('.root_url').val() +'/assets/tower/emoji/80.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:share"><img src="' + $('.root_url').val() +'/assets/tower/emoji/81.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:v"><img src="' + $('.root_url').val() +'/assets/tower/emoji/82.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:@)"><img src="' + $('.root_url').val() +'/assets/tower/emoji/83.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:jj"><img src="' + $('.root_url').val() +'/assets/tower/emoji/84.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:@@"><img src="' + $('.root_url').val() +'/assets/tower/emoji/85.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:bad"><img src="' + $('.root_url').val() +'/assets/tower/emoji/86.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:lvu"><img src="' + $('.root_url').val() +'/assets/tower/emoji/87.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:no"><img src="' + $('.root_url').val() +'/assets/tower/emoji/88.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:ok"><img src="' + $('.root_url').val() +'/assets/tower/emoji/89.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:love"><img src="' + $('.root_url').val() +'/assets/tower/emoji/90.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:<L>"><img src="' + $('.root_url').val() +'/assets/tower/emoji/91.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:jump"><img src="' + $('.root_url').val() +'/assets/tower/emoji/92.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:shake"><img src="' + $('.root_url').val() +'/assets/tower/emoji/93.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:<O>"><img src="' + $('.root_url').val() +'/assets/tower/emoji/94.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:circle"><img src="' + $('.root_url').val() +'/assets/tower/emoji/95.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:kotow"><img src="' + $('.root_url').val() +'/assets/tower/emoji/96.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:turn"><img src="' + $('.root_url').val() +'/assets/tower/emoji/97.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:skip"><img src="' + $('.root_url').val() +'/assets/tower/emoji/98.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:oY"><img src="' + $('.root_url').val() +'/assets/tower/emoji/99.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:#-0"><img src="' + $('.root_url').val() +'/assets/tower/emoji/100.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:hiphot"><img src="' + $('.root_url').val() +'/assets/tower/emoji/101.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:kiss"><img src="' + $('.root_url').val() +'/assets/tower/emoji/102.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:<&amp;"><img src="' + $('.root_url').val() +'/assets/tower/emoji/103.gif"></a>';
    emojiHtml += '<a href="javascript:void(0)" data="/:&amp;>"><img src="' + $('.root_url').val() +'/assets/tower/emoji/104.gif"></a>';
    emojiHtml += '</div>';

    return emojiHtml;

}