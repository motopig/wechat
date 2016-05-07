/**
 * Created by moto on 6/17/15.
 */

Handlebars.registerHelper('isEven', function(index, options){
    if (index%2 == 0) return options.fn(this);
});

Handlebars.registerHelper('isOdd', function(index, options){
    if (index%2 == 1) return options.fn(this);
});

Handlebars.registerHelper('isMuti', function(array){
    return (array.length > 1 ? 'multiple-message' : '');
});

Handlebars.registerHelper('isFirst', function(index, options){
    if (index == 0) return options.fn(this);
});

Handlebars.registerHelper('isNotFirst', function(index, options){
    if (index != 0) return options.fn(this);
});

Handlebars.registerHelper('formatDate', function(gmtModified) {
    if(gmtModified && gmtModified != "null"){
        return gmtModified.split(' ')[0];
    }
});

Handlebars.registerHelper('renderContent', function(content) {
    return new Handlebars.SafeString(content);
});

Handlebars.registerHelper('isHasSubitem', function(article, options) {
//            console.log(article);
    if(article && article.length === 1) {
        return options.fn(article[0]);
    }
});

Handlebars.registerHelper('isUsers', function (item, options) {
    if (item === 0) {
        return options.fn(this);
    }
});
Handlebars.registerHelper('isNotUsers', function (item, options) {
    if (item === 1) {
        return options.fn(this);
    }
});
Handlebars.registerHelper('isText', function (item, options) {
    if (item === "text" && this.content.indexOf('.gif') >= 0) {
        return options.fn(this);
    }else if(item === "text"){
        return emojiReplace(options.fn(this));
    }
});
Handlebars.registerHelper('isImage', function (item, options) {
    if (item === "image") {
        var info = $(ajaxGet(item, this.content));
        info.find('.item-overlay').remove();
        info.find('img').css('width', '300px').css('height', '50%');
        return info.html();
    }
});
Handlebars.registerHelper('isShortvideo', function (item, options) {
    if (item === "shortvideo") {
        return ajaxGet(item, this.content);
    }
});
Handlebars.registerHelper('isLocation', function (item, options) {
    if (item === "location") {
        return ajaxGet(item, this.content);
    }
});
Handlebars.registerHelper('isLink', function (item, options) {
    if (item === "link") {
        return this.content;
    }
});

Handlebars.registerHelper('isMaterial', function (item, options) {
    if (item === "material") {
        return ajaxGet(item, this.content);
    }
});
Handlebars.registerHelper('isGraphics', function (item) {
    if (item === "graphics") {
        return ajaxGet(item, this.content);
    }
});
Handlebars.registerHelper('isVideo', function (item, options) {
    if (item === "video") {
        var info = $(ajaxGet(item, this.content));
        info.find('.data-preview-trash').remove();
        return info.html();
    }
});
Handlebars.registerHelper('isVoice', function (item, options) {
    if (item === "voice") {
        var info = $(ajaxGet(item, this.content));
        info.find('.data-preview-trash').remove();
        return info.html();
    }
});
Handlebars.registerHelper('isEvent', function (item, options) {
    //var etype = [ 'subscribe', 'unsubscribe', 'scan', 'location', 'click', 'view'];
    if (item === "subscribe") {
        return '「关注公众号」';
    }else if(item === "unsubscribe"){
        return '「取消关注公众号」';
    }else if(item === "scan"){
        return '「扫描二维码」';
    }else if(item === "location"){
        return '「地理位置」';
    }else if(item === "click" || item === "view"){
        return options.fn(this);
    }
});
Handlebars.registerHelper('msgType', function (item, options) {
    // 0=>text 1=>image 2=>voice 3=>video 4=>shortvideo 5=>location 6=>link 7=>graphics
    // 1: 0=>subscribe 1=>unsubscribe 2=>scan 3=>location 4=>click 5=>view
    var type = [ 'text', 'image', 'voice', 'video', 'shortvideo', 'location', 'link', 'graphics' ];
    var etype = [ 'subscribe', 'unsubscribe', 'scan', 'location', 'click', 'view'];

    if(this.mold == 0){
        if (type[item] === "text") {
            return this.content;
        }else if(type[item] === "image"){
            return '「图片」';
        }else if(type[item] === "shortvideo"){
            return '「小视频」';
        }else if(type[item] === "location"){
            return '「地理位置」';
        }else if(type[item] === "link"){
            return '「链接」';
        }else if(type[item] === "graphics"){
            return '「图文」';
        }else if(type[item] === "video"){
            return '「视频」';
        }else if(type[item] === "voice"){
            return '「音频」';
        }
    }else{
        if (etype[item] === "subscribe") {
            return '「关注公众号」';
        }else if(etype[item] === "unsubscribe"){
            return '「取消关注公众号」';
        }else if(etype[item] === "scan"){
            return '「扫描二维码」';
        }else if(etype[item] === "location"){
            return '「地理位置」';
        }else if(etype[item] === "click" || etype[item] === "view"){
            return '「点击菜单」';
        }
    }

});

Handlebars.registerHelper('replay', function(item, options){
    if (item){
        return '回复:' + this.replay.content;
    }
});

Handlebars.registerHelper('firstItem', function (item, options) {
    if (item) {
        return options.fn(item[0]);
    }
});
Handlebars.registerHelper('subItem', function (item, options) {
    if (item && item.length > 1) {
        var subItem = "";
        for (var i = 0, len = item.length - 1; i < len; i++) {
            subItem += options.fn(item[i + 1]);
        }
        return subItem;
    }
});
Handlebars.registerHelper('isSubMessage', function (items) {
    if (items && items.length > 1) {
        return "multiple-message";
    }
});

Handlebars.registerHelper('isHasSubitem', function (article, options) {
    if (article && article.length === 1) {
        return options.fn(article[0]);
    }
});

//用户消息列表的handlebars函数

Handlebars.registerHelper('imgType', function (item, options) {
    if (item.mType === "img") {
        return options.fn(item);
    }
});
Handlebars.registerHelper('textType', function (item, options) {
    if (item.mType === "text") {
        return emojiReplace(options.fn(item));
    }
});
Handlebars.registerHelper('jsonParse', function (item, options) {
    return options.fn(JSON.parse(item));
});
Handlebars.registerHelper('enCode', function (item, options) {
    return encodeURIComponent(item);
});
Handlebars.registerHelper('hideName', function (item, options) {
    return "*" + item.substring(1, item.length);
});
Handlebars.registerHelper('hasHistory', function (item, options) {
    if (item.length >= 30) {
        var linkItem = '<li class="history-msg"><a href="">查看更多消息</a></li>';
        return linkItem;
    }
});
Handlebars.registerHelper('hasMoreMsg', function (item, options) {
    var context = (item > 99) ? "..." : (item > 0) ? item : null;
    if (context) {
        return "<span class='tip' data-num='" + item + "'>" + context + "</span>";
    }
});
Handlebars.registerHelper('unRead', function (item, options) {
    if (item > 0 && item < 100) {
        return "还有<span>" + item + "</span>条未读消息";
    } else if (item >= 100) {
        return "还有新未读消息";
    } else if (item === 0) {
        return "暂无未读消息";
    }
});

function ajaxGet(type, id){
    var url = $('.bolt-modal-preview-url').attr('data-url');
    var data = new FormData();

    data.append('csrf_token', $('#csrf_token').val());
    data.append('csrf_guid', $('#csrf_guid').val());
    data.append('type', type);
    data.append('id', id);
    var info = '';

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        contentType: false,
        processData: false,
        async: false,

        success:function(result) {
            var data = jQuery.parseJSON(result);
            if (data.html != '') {
                info = data.html;
            }
        }
    });
    return info;
}

function emojiReplace(text){
    return wechatFace.faceToHTML(text);
}