/**
 * Created by moto on 5/25/15.
 */


$(document).ready(function() {
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

    //排序触发
    $('#menuSortBtn').click(function(){
        //主菜单
        $('.custom').addClass('ui-sortable').sortable({
          update: function( event, ui ) {//移动后的 序列
            var pdata = {
                after: ui.item.index(),
                which: 'p'
            };
            var start_position = JSON.parse($('#sortable_record').attr('position'));
            $('#sortable_record').attr('position', JSON.stringify($.extend(start_position, pdata)));
            $('#sortable_record').append(positionRecord('p', start_position.before, JSON.stringify($.extend(start_position, pdata))));
            $('#sortable_record').attr('position', '');
          },
          start: function( event, ui){//移动前的 序列
            var pdata = {
                before: ui.item.index(),
                which: 'p'
            };
            $('#sortable_record').attr('position', JSON.stringify(pdata));
          },
          opacity: 0.5,
          revert: true
        });
        //子菜单
        $('.subMenu-list').addClass('ui-sortable').sortable({
          update: function( event, ui ) {//移动后的 序列
            var pdata = {
                after: ui.item.index(),
                which: 'c'
            };
            var start_position = JSON.parse($('#sortable_record').attr('position'));
            $('#sortable_record').attr('position', JSON.stringify($.extend(start_position, pdata)));
            $('#sortable_record').append(positionRecord('c', start_position.before, JSON.stringify($.extend(start_position, pdata))));
            $('#sortable_record').attr('position', '');
          },
          start: function( event, ui){//移动前的 序列
            var pdata = {
                before: ui.item.index(),
                which: 'c',
                cparent: ui.item.parent().parent().index()
            };
            $('#sortable_record').attr('position', JSON.stringify(pdata));
          },
          opacity: 0.5,
          revert: true,
        });
        $(this).css('display', 'none');
        $('.sortControl').css('display', 'block');
        event.preventDefault();

    });
    //排序确认
    $('#saveSort, #cancelSort').click(function(){
        $('#menuSortBtn').css('display', 'block');
        $('.sortControl').css('display', 'none');
        //排序取消
        if($(this).hasClass('btn-gray')){
            $('.custom').sortable('cancel');
            $('.subMenu-list').sortable('cancel');
        }else{
            //针对预览做排序
            if($('.sortable_record_info').length){
                var cp = new Array();
                var sp = new Array();
                $.each($('.sortable_record_info'), function(i, n){
                    var positionInfo = JSON.parse($(this).attr('position'));
                    if(positionInfo.hasOwnProperty('cparent')){//是子菜单排序
                        cp.push(positionInfo);
                    }else{//是主菜单排序
                        sp.push(positionInfo);
                    }
                });
            }

            if(cp.length){//先对子菜单进行排序
                $.each(cp, function(i, n){
                    var parent = $('#previewBtnLists .preview-items:eq(' + (parseInt(n.cparent) + 1) + ')');
                    var afterplus = parent.find('.preview-sub-items li:eq(' + (parseInt(n.after) + 1 ) + ')');
                    var aftermin = parent.find('.preview-sub-items li:eq(' + (parseInt(n.after) - 1 ) + ')');
                    var after = parent.find('.preview-sub-items li:eq(' + n.after + ')');
                    var before = parent.find('.preview-sub-items li:eq(' + n.before + ')');
                    if(n.after > n.before){//如果移动后的位置索引 「大于」 移动前的位置索引
                        after.after(before);
                    }else{//如果移动后的位置索引 「小于」 移动前的位置索引
                        after.before(before);
                    }
                    //二级菜单调整顺序后 更新预览窗口JSON数据
                    var orgMenuData = JSON.parse(parent.children(':first-child').attr('data-menudata'));
                    delete orgMenuData.subMenuItems;
                    var aobject = parent.find('.preview-sub-items li');
                    var items = new Array();
                    $.each(aobject, function(i, n){
                        var alink = $(this).children(':first');
                        if( typeof(alink.attr('data-menudata')) !== "undefined" ){//去除最后一个箭头li
                            items.push(JSON.parse(alink.attr('data-menudata')));
                        }
                    });
                    orgMenuData.subMenuItems = items;
                    parent.children(':first-child').attr('data-menudata', JSON.stringify(orgMenuData));
                });

            }
            delete cp;
            
            if(sp.length){//对主菜单进行排序
                $.each(sp, function(i, n){
                    var afterplus = $('#previewBtnLists .preview-items:eq(' + (parseInt(n.after) + 1 ) + ')');
                    var aftermin = $('#previewBtnLists .preview-items:eq(' + (parseInt(n.after) - 1 ) + ')');
                    var before = $('#previewBtnLists .preview-items:eq(' + (parseInt(n.before) + 1 ) + ')');
                    var after = $('#previewBtnLists .preview-items:eq(' + (parseInt(n.after) + 1 ) + ')');
                    if(n.after > n.before){//如果移动后的位置索引 「大于」 移动前的位置索引
                        after.after(before);
                    }else{//如果移动后的位置索引 「小于」 移动前的位置索引
                        after.before(before);
                    }
                });
            }
            delete sp;
            $('#sortable_record').empty();
            //保存当前排序
            saveCurMenu();
        }
        $('.custom').removeClass('ui-sortable').sortable('destroy');
        $('.subMenu-list').removeClass('ui-sortable').sortable('destroy');
        event.preventDefault();
    });

    //主菜单取消添加动作
    $('.custom').on('click', '.new-menu-wrap .cancelNewName',  function(){
        $(this).parent().html(regMenuHtml());
    });
    //添加主菜单动作
    $('.custom').on('click', '.new-menu-main',  function(){
        if($('.new-subMenu-wrap .cancelNewName')){
            $('.new-subMenu-wrap .cancelNewName').parent().html(regSubMenuHtml());
        }
        removeEditing();
        //主菜单数量判断 最多3个
        var items = $(this).parent().parent().children('div .menu-list');
        if(items.length >= 3){
            $('.new-menu-wrap').css('display', 'none');
        }else{
            var Menu = addMenuHtml();
            $(this).parent().html(Menu);
        }

    });
    //主菜单保存名称动作
    $('.custom').on('click', '.new-menu-wrap .saveNewName',  function(){
        var parentName = $('.editing').val();
        if(parentName){
            remenuHtml = menuHtml(parentName);
            $('.new-menu-wrap').before(remenuHtml);
            $('.editing').val('');
            var Menu = regMenuHtml();
            $(this).parent().html(Menu);

            //主菜单保存名称后 生成预览数据
            var actionType = '';
            var actionContent = '';
            var appId = '';
            var disable = '';
            var key = '';
            var type = '';
            var messageId = '';
            BuildPreviewHtml($(this), parentName, actionContent, actionType, messageId, key, type);
            resetPreviewWidth();
            $(this).parent().parent().css('display', 'none');
        }else{
            alertify.alert('主菜单名称不能为空!');
        }

    });
    //主菜单添加动作
    $('.custom').on('click', '.list-parent .name', function(){
        if($(this).parent().next().children('div .list-sub').length >= 1 ){
            removeAction();
            $(this).next().next().css('display', 'block');
        }else{
            removeAction();
            //主菜单添加动作的时候 子菜单不能处于编辑状态
            if( $(this).parent().next().find('.editing').length > 0 ){
                $('.cancelNewName').click();
            }
            //如果主菜单已经配置过动作，那么加载预览数据至 tplContainer div 中
            //previewContainer();
            $(this).next().css('display', 'block');
        }
    });
    //主菜单无法添加 弹出提示窗口 之后的动作
    $('.custom').on('click', '.list-parent .noActionContainer .btn-wrap .saveBtn', function(){
        $(this).parent().parent().css('display', 'none');
    });
    //关闭按钮动作
    $('.custom').on('click', '.cancelBtn', function(){
        $(this).parent().css('display', 'none');
        //去除当前编辑菜单的 编辑 class
        $(this).parent().prev().find('.curEditMenu').removeClass('curEditMenu');
    });

    //子菜单添加动作
    $('.custom').on('click', '.list-sub .name', function(){
        removeAction();
        $(this).next().css('display', 'block');
        if($(this).next().find('.infoTextArea')){
            $(this).next().find('.infoTextArea').focus();
        }
    });

    //编辑主菜单 时取消编辑
    $('.custom').on('click', '.list-parent .cancelNewName, .list-sub .cancelNewName',  function(){
        var name = $('.editing').val();
        Name = '<span class="name noAction" data-name="'+ name +'">' + name + '';
        $(this).prev().prev().before(Name);
        $(this).prev().prev().remove();
        $(this).prev().remove();
        $(this).remove();
        listItemOnHover();
    });

    //编辑主菜单 时保存编辑
    $('.custom').on('click', '.list-parent .saveNewName, .list-sub .saveNewName',  function(){
        var name = $(this).prev().val();
        Name = '<span class="name noAction" data-name="'+ name +'">' + name + '';
        //主菜单重构预览数据
        if($(this).parent().hasClass('list-parent')){
            var data = {
                name: name,
                position: $(this).parent().parent().index(),
            };
            rebuildPreviewJson($(this), data);
        }else{
            //子菜单重构预览数据
            var sub = {
                name: name,
                position: $(this).parent().index()
            };
            var data = {
                position: $(this).parent().parent().parent().index()
            };
            rebuildPreviewJson($(this), data, sub);
        }

        $(this).prev().before(Name);
        $(this).prev().remove();
        $(this).next().remove();
        $(this).remove();
        listItemOnHover();

    });

    listItemOnHover();

    //子菜单取消添加
    $('.custom').on('click', '.new-subMenu-wrap .cancelNewName',  function(){
        var subMenu = regSubMenuHtml();
        $(this).parent().html(subMenu);
    });

    $('.custom').on('click', '.new-menu-sub',  function(){
        //添加动作前判断其主菜单是否配置过
        
        if(!$(this).parent().parent().prev().children(':first-child').hasClass('noAction') &&  !$(this).parent().prev().length){
            _this = $(this);
            alertify.confirm("确定要添加子菜单？添加子菜单后，当前设置的菜单效果将会被清除。", function (e) {
                if (e) {
                    if($('.new-menu-wrap .cancelNewName')){
                        $('.new-menu-wrap .cancelNewName').parent().html(regMenuHtml());
                    }
                    removeEditing();
                    //添加子菜单是 隐藏配置主菜单的功能
                    _this.parent().parent().prev().children('div .actionContainer').css('display', 'none');
                    var subMenu = addSubMenuHtml();
                    _this.parent().html(subMenu);
                }else{
                    return false;
                }
            });
        }else{
            if($('.new-menu-wrap .cancelNewName')){
                $('.new-menu-wrap .cancelNewName').parent().html(regMenuHtml());
            }
            removeEditing();
            //添加子菜单是 隐藏配置主菜单的功能
            $(this).parent().parent().prev().children('div .actionContainer').css('display', 'none');
            var subMenu = addSubMenuHtml();
            $(this).parent().html(subMenu);
        }

    });

    $('.custom').on('click', '.new-subMenu-wrap .saveNewName',  function(){
        var items = $(this).parent().parent().children('div .list-item');
        //判断其主菜单是否已经配置过，如果配置过 需要清除主菜单配置动作
        var noconfig = $(this).parent().parent().prev().children(':first-child').hasClass('noAction');
        var subName = $('.editing').val();
        if(!subName){
            alertify.alert('子菜单名称不能为空!');
            return false;
        }
        remenuHtml = addSubMenuListHtml(subName);
        $(this).parent().before(remenuHtml);
        $('.editing').val('');
        //去除主菜单未配置样式class
        $(this).parent().parent().prev().children(':first-child').removeClass('noAction').children('b').remove();
        //添加子菜单后 同步预览效果HTML结构 subMenuItems
        //先更新对应主菜单预览效果 再增加子菜单预览效果HTML结构
        var sub = {
            name: subName,
            actionContent: '',
            actionType: '',
            appId: '',
            disable: '',
            key: '',
            messageId: '',
            type: '',
            position: items.length ? items.length : 0,
        };
        ++sub.position;
        if(noconfig){
            var data = {
                position: $(this).parent().parent().parent().index(),
            };
        }else{
            var data = {
                position: $(this).parent().parent().parent().index(),
                actionContent: '',
                actionType: '',
                messageId: '',
            };
        }

        rebuildPreviewJson($(this), data, sub);

        if(items.length >= 4){
            $(this).parent().html('');
        }else{
            var subMenu = regSubMenuHtml();
            $(this).parent().html(subMenu);
        }

        //子菜单保存时 取消主菜单 noactive 状态
        if($(this).parent().parent().prev().children(':first-child').hasClass('noAction')){
            $(this).parent().parent().prev().children(':first-child').removeClass('noAction');
            $(this).parent().parent().prev().children(':first-child').find('b').remove();
        }
    });

    //回车保存菜单名称
    $('.custom').on('keypress', '.editing', function(event) {
      var keycode = (event.keyCode ? event.keyCode : event.which);  
      if (keycode == '13') {
         if ($(this).val() == '') {
            alertify.alert('请输入菜单名称!');
         }else{
            $('.custom .saveNewName').click();
         }
      }
    });

    //主菜单改名称
    $('.custom').on('click', '.list-parent .rename', function(){
        $(this).parent().parent().removeClass('hover');
        listItemOffHover();
        removeEditing();
        var oldName = $(this).parent().prev().prev().prev().attr('data-name');
        $(this).parent().addClass('fn-hide');
        $(this).parent().prev().addClass('fn-hide');
        $(this).parent().prev().prev().prev().remove();
        $(this).parent().prev().prev().before(addMenuHtml(oldName));
    });
    //子菜单改名称
    $('.custom').on('click', '.list-sub .rename', function(){
        $(this).parent().parent().removeClass('hover');
        listItemOffHover();
        removeEditing();
        var oldName = $(this).parent().prev().prev().attr('data-name');
        $(this).parent().addClass('fn-hide');
        $(this).parent().prev().prev().before(addSubMenuHtml(oldName));
        $(this).parent().prev().prev().remove();
    });

    //删除子菜单
    $('.custom').on('click', '.list-sub .del', function(){
        var _this = $(this);
        alertify.confirm("确认删除此子菜单吗?", function (e) {
            if (e) {
                var items = _this.parent().parent().parent().children('div .list-item');
                if(items.length == 5){
                    _this.parent().parent().siblings().last().append(regSubMenuHtml());
                }
                //删除子菜单后 重构preview HTML结构和JSON数据
                var data = {
                    position: _this.parent().parent().parent().parent().index()
                };
                var del = {};
                var sub = {
                    position: _this.parent().parent().index()
                };
                rebuildPreviewJson(_this, data, sub, del);
                saveCurMenu();
                _this.parent().parent().remove();
            } else {
                return false;
            }
        });

    });
    //删除主菜单
    $('.custom').on('click', '.list-parent .del', function(){
        var _this = $(this);
        alertify.confirm("确认删除此主菜单吗? 此主菜单下的子菜单将全部删除!", function (e) {
            if (e) {
                if($('.new-menu-wrap').css('display') == 'none'){
                    $('.new-menu-wrap').css('display', 'block');
                }
                //删除主菜单后 重构preview HTML结构和JSON数据
                var data = {
                    position: _this.parent().parent().parent().index()
                };
                var del = {
                    parent: true
                };
                var sub = {};
                rebuildPreviewJson(_this, data, sub, del);
                saveCurMenu();
                _this.parent().parent().parent().remove();
            } else {
                return false;
            }
        });
    });

    //选择菜单类型 下拉动作
    $('.custom').on('click', '.actionContainer .selectInput', function(){
        if( $(this).next().css('display') == 'block' ){
            $(this).next().slideUp();
        }else {
            $(this).next().slideDown();
        }
    });

    selectLiItemOnHover();

    //给主菜单和子菜单配置时选择菜单类型动作
    $('.custom').on('click', '.actionSelect li', function(){
        //判断类型，进行弹窗或者显示输入框
        var type = $(this).attr('val');
        if( $.inArray(type, ['info', 'promotion', 'ecshop', 'member'] ) == -1){
            //显示输入框
            $(this).parent().css('display', 'none');
            //去除之前选择的其他类型
            removeOtherType($(this));
            //填充内容
            $(this).parent().parent().next().next().html(linkHtml());

        }else if($.inArray(type, ['ecshop'] ) != -1){
            //处理微商城逻辑
            $(this).parent().css('display', 'none');
            //去除之前选择的其他类型
            removeOtherType($(this));
            //填充内容
            $(this).parent().parent().next().next().html(ecshopHtml());
        }else if($.inArray(type, ['member'] ) != -1){
            //处理会员中心逻辑
            $(this).parent().css('display', 'none');
            //去除之前选择的其他类型
            removeOtherType($(this));
            //填充内容
            $(this).parent().parent().next().next().html(memberHtml());
        }else{
            //弹出窗口
            $(this).parent().css('display', 'none');
            //去除之前选择的其他类型
            removeOtherType($(this));
            //类型内容选择
            $(this).parent().parent().next().next().children('.container-tip').text('').after(infoHtml());
            //光标聚焦输入框
            $(this).parent().parent().next().next().children('.container-tip').next().children('.infoTextArea').focus();
        }
        //自身添加选中样式 class
        $(this).addClass('active');
        //去除同辈元素选中样式
        $(this).siblings().removeClass('active');
        //显示保存按钮
        $(this).parent().parent().next().next().next().css('display', 'block');
        //设置类型选中状态
        $(this).parent().prev().html($(this).find('.typeName').html());
    });

    //根据所选菜单类型 进行选择对应内容动作
    $('.custom').on('click', '.tplContainer .infoUl .infoLi', function(){
        if($(this).hasClass('semoji')) {
            emotionlist = $(this).parent().parent().parent().children('div .emotion-list');

            if (emotionlist.length != 0) {
                if (emotionlist.css('display') == 'block') {
                    emotionlist.css('display', 'none');
                } else {
                    emotionlist.css('display', 'block');
                }
            } else {
                $(this).parent().parent().prev().before(emoji());
            }
            $(this).addClass('curEditMenu');
        }
        if(emotionlist = $(this).parent().parent().parent().children('div .emotion-list')){
            if(!$(this).hasClass('semoji')){
                $(this).addClass('curEditMenu');
                $(this).siblings().removeClass('curEditMenu');
                emotionlist.css('display', 'none');
            }
        }

        //添加 active 样式 其他类型去除 active 样式
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
    });


    //菜单动作保存 增加预览JSON数据和HTML结构
    $('.custom').on('click', '.list-item .actionContainer .btn-wrap .saveBtn', function(){
        //去除未配置标示
        $(this).parent().parent().prev().removeClass('noAction');
        $(this).parent().parent().removeClass('notSelectType');
        //var actionType = $(this).parent().parent().find('.active').attr('val');
        var actionType = $(this).parent().parent().find('.active').attr('val');
        $(this).parent().parent().attr('data-actiontype', actionType);
        $(this).parent().parent().prev().find('b').remove();

        //没有当前编辑标示 应该是再次点击了编辑 不过没有操作。。
        if($(this).parent().prev().children('div .infoType').find('.curEditMenu').length) {

            //type
            var type = $(this).parent().prev().children('div .infoType').find('.curEditMenu').attr('data-type');

            var istext = false;
            if (type == 'text') {
                var istext = true;
                predata = $(this).parent().prev().children('div .infoType').children('div .onCur').attr('updata');
            } else {
                predata = JSON.parse($(this).parent().prev().children('div .infoType').children('div .onCur').attr('updata'));
            }
            //确认更新哪个菜单 //已经存在预览数据 修改预览数据
            if ($(this).parent().parent().parent().hasClass('list-parent')) {
                var data = {
                    actionContent: istext ? predata : predata.title,
                    actionType: type,
                    messageId: istext ? '1' : predata.id,
                    position: $(this).parent().parent().parent().parent().index(),
                };
                rebuildPreviewJson($(this), data);
            } else {
                var sub = {
                    actionContent: istext ? predata : predata.title,
                    actionType: type,
                    messageId: istext ? '1' : predata.id,
                    position: $(this).parent().parent().parent().index(),
                };
                var data = {
                    position: $(this).parent().parent().parent().parent().parent().index(),
                };
                rebuildPreviewJson($(this), data, sub);
            }
            //去除当前编辑状态标示
            $(this).parent().prev().children('div .infoType').find('.curEditMenu').removeClass('curEditMenu');
        }
        //判断是否是添加了 link类型
        if($(this).parent().prev().children('div .inputWrap').length){
            var inputWrap = $(this).parent().prev().children('div .inputWrap');
            var link = inputWrap.children('div .typeContentInput').val();
            //确认更新哪个菜单 //已经存在预览数据 修改预览数据
            if ($(this).parent().parent().parent().hasClass('list-parent')) {
                var data = {
                    actionContent: link,
                    actionType: 'link',
                    messageId: '1',
                    position: $(this).parent().parent().parent().parent().index(),
                };
                rebuildPreviewJson($(this), data);
            } else {
                var sub = {
                    actionContent: link,
                    actionType: 'link',
                    messageId: '1',
                    position: $(this).parent().parent().parent().index(),
                };
                var data = {
                    position: $(this).parent().parent().parent().parent().parent().index(),
                };
                rebuildPreviewJson($(this), data, sub);
            }   
        }
        //判断是否添加了微商城 ecshop 类型
        if($(this).parent().prev().find('.ecshopConfig').length){

            var type = $(this).parent().prev().find('.active').attr('val');
            if(!type){
                alertify.alert('请选择微商城页面');
                return false;
            }
            //确认更新哪个菜单 //已经存在预览数据 修改预览数据
            if ($(this).parent().parent().parent().hasClass('list-parent')) {
                var data = {
                    actionContent: type,
                    actionType: 'ecshop',
                    messageId: '1',
                    position: $(this).parent().parent().parent().parent().index(),
                };
                rebuildPreviewJson($(this), data);
            } else {
                var sub = {
                    actionContent: type,
                    actionType: 'ecshop',
                    messageId: '1',
                    position: $(this).parent().parent().parent().index(),
                };
                var data = {
                    position: $(this).parent().parent().parent().parent().parent().index(),
                };
                rebuildPreviewJson($(this), data, sub);
            }
        }
        //判断是否添加了会员中心 member 类型
        if($(this).parent().prev().find('.memberConfig').length){

            var type = $(this).parent().prev().find('.active').attr('val');
            if(!type){
                alertify.alert('请选择会员中心页面');
                return false;
            }
            //确认更新哪个菜单 //已经存在预览数据 修改预览数据
            if ($(this).parent().parent().parent().hasClass('list-parent')) {
                var data = {
                    actionContent: type,
                    actionType: 'member',
                    messageId: '1',
                    position: $(this).parent().parent().parent().parent().index(),
                };
                rebuildPreviewJson($(this), data);
            } else {
                var sub = {
                    actionContent: type,
                    actionType: 'member',
                    messageId: '1',
                    position: $(this).parent().parent().parent().index(),
                };
                var data = {
                    position: $(this).parent().parent().parent().parent().parent().index(),
                };
                rebuildPreviewJson($(this), data, sub);
            }
        }
        $(this).parent().parent().slideUp();
        //保存当前菜单数据
        saveCurMenu();
    });

    $('#previewBtnLists').on('click', '.preview-items a:first-child', function(){

        //配置过动作的菜单才能预览
        if(!$(this).hasClass('noAction')){
            //主菜单预览
            if ($(this).parent().hasClass('preview-items')) {
                if ($(this).next().length) {
                    if ($(this).next().css('display') == 'none') {
                        $(this).next().css('display', 'block');
                        //隐藏其他预览窗口
                        $(this).parent().siblings().find('.preview-sub-items').css('display', 'none');
                    } else {
                        $(this).next().css('display', 'none');
                    }
                } else {
                    var position = parseInt($(this).parent().index()) - 1;
                    if ($(this).hasClass('text') || $(this).hasClass('link')) {
                        var sdata = {
                            actionType: $(this).hasClass('text') ? 'text' : 'link',
                        };
                        var data = $('.custom .menu-list:eq(' + position + ')').find('.onCur').val();
                        getPriviewInfo(data, sdata);
                    }else if($(this).hasClass('ecshop') ){
                        alertify.alert('请在微信预览');
                    }else if($(this).hasClass('member') ){
                        alertify.alert('请在微信预览');
                    }else {
                        var sdata = JSON.parse($(this).attr('data-menudata'));
                        var data = JSON.parse($('.custom .menu-list:eq(' + position + ')').find('.onCur').attr('updata'));
                        if (sdata.messageId && sdata.actionType) {
                            getPriviewInfo(data, sdata);
                        }
                    }
                }
            } else {
                var position = parseInt($(this).parent().parent().parent().index()) - 1;
                var cposition = parseInt($(this).parent().index());

                if ($(this).hasClass('text') || $(this).hasClass('link')) {
                    var sdata = {
                        actionType: $(this).hasClass('text') ? 'text' : 'link',
                    }
                    if ($(this).hasClass('text')) {
                        var data = $('.custom .menu-list:eq(' + position + ')').find('.list-sub:eq(' + cposition + ')').find('.onCur').html();
                    } else {
                        var data = $('.custom .menu-list:eq(' + position + ')').find('.list-sub:eq(' + cposition + ')').find('.onCur').val();
                    }
                    getPriviewInfo(data, sdata);
                }else if($(this).hasClass('ecshop') ){
                    alertify.alert('请在微信预览');
                }else if($(this).hasClass('member') ){
                    alertify.alert('请在微信预览');
                } else {
                    var sdata = JSON.parse($(this).attr('data-menudata'));
                    var data = JSON.parse($('.custom .menu-list:eq(' + position + ')').find('.list-sub:eq(' + cposition + ')').find('.onCur').attr('updata'));

                    if (sdata.messageId && sdata.actionType) {
                        getPriviewInfo(data, sdata);
                    }
                }
            }
        }
        
    });

    $('.custom').on('keyup', '.infoTextArea, .typeContentInput', function(){
        if($(this).hasClass('typeContentInput')){
            $(this).attr('updata', $(this).val());    
        }else{
            //只输入文字的时候添加 当前编辑状态
            var content = removeHTMLTag($(this).html());
            $(this).attr('updata', content);
            var preinfo = JSON.parse($(this).next().attr('updata'));
            preinfo.actionContent = content;
            $(this).next().attr('updata', JSON.stringify(preinfo) );
            $(this).addClass('onCur');
            $(this).prev().find('.semoji').addClass('curEditMenu');
        }
    });

    // 发布菜单信息至微信
    $('#menuSaveBtn').click(function(){
        saveCurMenu('true');
    });

});

function removeHTMLTag(str) {
    str = str.replace(/<\/?[^>]*>/g,''); //去除HTML tag
    str = str.replace(/[ | ]*\n/g,'\n'); //去除行尾空白
    //str = str.replace(/\n[\s| | ]*\r/g,'\n'); //去除多余空行
    str=str.replace(/&nbsp;/ig,'');//去掉&nbsp;
    return str;
}

function getPriviewInfo(data, sdata){

    switch(sdata.actionType) {

        case 'graphics':
            var pHtml = graphicsPreview(data);
            break;
        case 'voice':
            var pHtml = voicePreview(data);
            break;
        case 'video':
            var pHtml = videoPreview(data);
            break;
        case 'image':
            var pHtml = imagePreview(data);
            break;
        case 'material':
            var pHtml = materialPreview(data);
            break;
        case 'text':
            var pHtml = textPreview(data);
            break;
        case 'link':
            var pHtml = linkPreview(data);
            break;

    }

    $('#previewShowBox').empty().append(pHtml);
}


//
function removeOtherType(_this){
    _this.parent().parent().next().next().children('.container-tip').siblings().remove();
}

//保存当前菜单数据
function saveCurMenu(sync){
    var menu = new Array();
    $.each($('#previewBtnLists').children(':first-child').siblings().children(':first-child'), function(i, n) {
        menu.push($(this).attr('data-menudata'));
    });
    var url = $('.root_url').val()+'/angel/wechat/menu/toEdit';
    $.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
        data: {mjson:menu,csrf_token:$('#csrf_token').val(),csrf_guid:$('#csrf_guid').val(),mid:$('.thisid').val(),sync: sync ? sync : ''},
        success: function(re){
            if(re.success){
                if(sync){
                    alertify.success('菜单内容同步微信成功!');
                }else {
                    alertify.success('菜单内容保存成功!');
                }
            }else{
                if(sync){
                    alertify.error('菜单内容同步微信失败 ' + re.error);
                }else{
                    alertify.error('菜单内容保存失败!');
                }
            }
            setTimeout("window.location.reload()",1000);
        },

    });
}

//重置菜单预览窗口宽度
function resetPreviewWidth(){
    //获取当前主菜单个数
    var previeWidth = 190 / ($('#previewBtnLists .preview-items').length - 1);
    $('#previewBtnLists .preview-items').css('width', previeWidth);
    $('#previewBtnLists .preview-items.dialog').css('width', '37px');
}

//生成预览菜单 JSON 数据和 HTML 结构
function rebuildPreviewJson(_this, data, sub, del){

    data.position = parseInt(data.position) + 1;
    var oldJsonstr = $('#previewBtnLists .preview-items:eq(' + data.position + ')').find('a').attr('data-menudata');
    var oldJson = JSON.parse(oldJsonstr);
    //删除主(子)菜单
    if(del !== undefined){
        //删除主菜单
        if(del.parent){
            $('#previewBtnLists .preview-items:eq(' + data.position + ')').remove();
            resetPreviewWidth();
        //删除子菜单
        }else{
            //删除主菜单中 subMenuItems 数据
            oldJson.subMenuItems.splice(sub.position, 1);
            if(oldJson.subMenuItems.length == 0){
                //删除 hasSubMenu 样式
                $('#previewBtnLists .preview-items:eq(' + data.position + ')').find(':first').removeClass('hasSubMenu');
            }
            //删除子菜单预览 HTML 结构
            $('#previewBtnLists .preview-items:eq(' + data.position + ')').find('.preview-sub-items li:eq(' + sub.position + ')').remove();
        	oldJson = JSON.stringify(oldJson);
    		$('#previewBtnLists .preview-items:eq(' + data.position + ')').find('a:first').attr('data-menudata', oldJson);
        }
    }else {
        $.each(data, function (i, n) {
            if (i != 'position') {
                oldJson[i] = n;
            }
        });
        resetPreviewWidth();
        //需要设置子菜单预览数据
        if (sub) {
            //更新
            if (oldJson.hasOwnProperty('subMenuItems')) {
                //如果此二级菜单已经添加过
                if (oldJson.subMenuItems.hasOwnProperty(sub.position)) {
                    $.each(oldJson.subMenuItems[sub.position], function (i, n) {
                        if (i != 'position' && sub.hasOwnProperty(i)) {
                            oldJson.subMenuItems[sub.position][i] = sub[i];
                        }
                    });
                    //更新子菜单条目HTML结构和数据
                    $('#previewBtnLists .preview-items:eq(' + data.position + ')').find('.preview-sub-items li:eq(' + sub.position + ') a').attr('data-menudata', JSON.stringify(oldJson.subMenuItems[sub.position])).text(sub.name);
                    if(sub.messageId){
                        $('#previewBtnLists .preview-items:eq(' + data.position + ')').find('.preview-sub-items li:eq(' + sub.position + ') a').removeClass('noAction');
                    }
                    if($.inArray(sub.actionType, ['link', 'text', 'ecshop', 'member'] ) != -1){
                        $('#previewBtnLists .preview-items:eq(' + data.position + ')').find('.preview-sub-items li:eq(' + sub.position + ') a').addClass(sub.actionType);
                    }else{
                        $('#previewBtnLists .preview-items:eq(' + data.position + ')').find('.preview-sub-items li:eq(' + sub.position + ') a').removeClass(sub.actionType);
                    }
                //如果此二级菜单没有添加过
                } else {
                    var subData = {
                        name: sub.name,
                        actionContent: sub.actionContent,
                        actionType: sub.actionType,
                        appId: sub.appId,
                        disable: sub.disable,
                        key: sub.key,
                        messageId: sub.messageId,
                        type: sub.type,
                    };
                    oldJson.subMenuItems.push(subData);
                    //插入此二次菜单数据
                    subDataMenu = JSON.stringify(subData);

                    $('#previewBtnLists .preview-items:eq(' + data.position + ')').find('.preview-sub-items li:last').before(subHtml(subDataMenu, sub.name));
                }

                //还没有二级菜单生成过 插入
            } else {
                oldJson.subMenuItems = [];
                var subData = {
                    name: sub.name,
                    actionContent: sub.actionContent,
                    actionType: sub.actionType,
                    appId: sub.appId,
                    disable: sub.disable,
                    key: sub.key,
                    messageId: sub.messageId,
                    type: sub.type,
                };
                oldJson.subMenuItems.push(subData);
                subDataMenu = JSON.stringify(subData);
                $('#previewBtnLists .preview-items:eq(' + data.position + ')').append(previewSubItemsHtml(subDataMenu, sub.name));
            }
        }
    

	    oldJson = JSON.stringify(oldJson);
	    $('#previewBtnLists .preview-items:eq(' + data.position + ')').find('a:first').attr('data-menudata', oldJson);
	    if(data.messageId){
	        $('#previewBtnLists .preview-items:eq(' + data.position + ')').find('a:first').removeClass('noAction');
	    }
	    
	    //如果传入主菜单名称 那么同时修改预览处的主菜单名称
	    if (data.name) {
	        $('#previewBtnLists .preview-items:eq(' + data.position + ')').find('a:first').html(data.name);
	    }
	    if($.inArray(data.actionType, ['link', 'text', 'ecshop', 'member'] ) != -1){
	        $('#previewBtnLists .preview-items:eq(' + data.position + ')').find('a:first').addClass(data.actionType);
	    }else{
	        $('#previewBtnLists .preview-items:eq(' + data.position + ')').find('a:first').removeClass(data.actionType);
	    }
	    //如果有子菜单 那么添加 hasSubMenu 样式
	    if (sub) {
	        $('#previewBtnLists .preview-items:eq(' + data.position + ')').find('a:first').addClass('hasSubMenu');
	        $('#previewBtnLists .preview-items:eq(' + data.position + ')').find('a:first').removeClass('noAction');
	    }
	}
    
}

//{"name":"333","actionContent":"ffd","actionType":"in","appId":"","disable":"","key":"","messageId":"50454","type":""}
function BuildPreviewHtml(_this, name, content, actiontype, messageid, position, update, skey, stype){
    var odata = {
        name: name, actionContent: content, actionType: actiontype, messageId: messageid, key: skey, type: stype
    };
    var data = JSON.stringify(odata);
    resetPreviewWidth();
    //更新 JSON 数据
    if(update == 'true'){
        position = ++position;
        $('#previewBtnLists .preview-items:eq('+position+')').find('a').attr('data-menudata', '').attr('data-menudata', data).html(name);
    }else{
    //生成HTML结构
        $('#previewBtnLists').append(previewHtml(data,name));
    }
}


//主菜单添加动作的时候 子菜单不能处于编辑动作状态
function removeAction(){
    $('.actionContainer').css('display', 'none');
    $('.noActionContainer').css('display', 'none');
}

//恢复其他主菜单和子菜单的非编辑状态
function removeEditing(){
    $('.cancelNewName').click();
}

//主菜单和子菜单 去除 hover 时的样式
function listItemOffHover(){
    $('.custom').off('mouseenter mouseleave', '.list-item');
}

//主菜单和子菜单 hover 时的样式设置
function listItemOnHover(){
    $('.custom').on({
        mouseenter: function(){
            if(!$(this).children('span:first-child').hasClass('editing') && !($(this).parent().parent().hasClass('ui-sortable') || $(this).parent().hasClass('ui-sortable') )){
                $(this).addClass('hover');
            }
        },
        mouseleave: function(){
            $(this).removeClass('hover');
        }
    },'.list-item');
}

//给菜单设置动作 hover 时的样式设置
function selectLiItemOnHover(){
    $('.custom').on({
        mouseenter: function(){
            $(this).addClass('hover');
        },
        mouseleave: function(){
            $(this).removeClass('hover');
        }
    },'.actionContainer .actionSelect li');
}

function listParentName(name){
    return '<span class="name noAction">' + name + '<b class="notActive"></b></span>';
}


// 父节点 name
// 是否增加子节点
// 子节点名称
function menuHtml(parentName, subName){

    var menuHtml = '';
    menuHtml += '<div class="menu-list">';
    menuHtml += '<div class="list-item list-parent">';
    menuHtml += '<span class="name noAction" data-name="'+ parentName +'">' + parentName + '';
    menuHtml += '<b class="notActive"></b>';
    menuHtml += '</span>';

    menuHtml += actionContainerHtml();

    menuHtml += '<div class="noActionContainer fn-hide">';
    menuHtml += '<div class="msg">';
    menuHtml += '<i class="iconfont" title="提示"></i>';
    menuHtml += '<span>已有子菜单，无法设置菜单效果</span>';
    menuHtml += '</div>';
    menuHtml += '<div class="btn-wrap">';
    menuHtml += '<input class="btn-small btn-orange saveBtn" type="button" value="确定">';
    menuHtml += '</div>';

    menuHtml += '<span class="left-point"></span>';
    menuHtml += '<a class="cancelBtn">×</a>';
    menuHtml += '</div>';
    menuHtml += '<div class="edit">';
    menuHtml += '<a class="rename">编辑</a>';
    menuHtml += '<a class="del">删除</a>';
    menuHtml += '</div>';

    menuHtml += '<span class="sort-icon"></span>';
    menuHtml += '</div>';

    var subMenuList = addSubMenuListHtml(subName);

    var subMenuHtml = '';

    subMenuHtml = '<div class="subMenu-list">';

    if(subName){
        subMenuHtml += subMenuList;
    }

    subMenuHtml += '<div class="new-subMenu-wrap">';
    subMenuHtml += '<a href="javascript:;" class="new-menu new-menu-sub">+ 添加子菜单</a>';
    subMenuHtml += '</div></div>';

    if(subName){
        return menuHtml + subMenuList + subMenuHtml;
    }else{
        return menuHtml + subMenuHtml;
    }
}

function addSubMenuListHtml(subName){
    var subMenuList = '';

    subMenuList += '<div class="list-item list-sub">';
    subMenuList += '<span class="name noAction" data-name="'+ subName +'">' + subName + '';
    subMenuList += '<b class="notActive"></b>';
    subMenuList += '</span>'
    subMenuList +=  actionContainerHtml();
    subMenuList += '<div class="edit">';
    subMenuList += '<a class="rename">编辑</a>';
    subMenuList += '<a class="del">删除</a>';
    subMenuList += '</div>';

    subMenuList += '<span class="sort-icon"></span>';
    subMenuList += '</div>';

    return subMenuList;
}

function addSubMenuHtml(name){
    var subMenuHtml = '';

    if(name){
        subMenuHtml += '<input maxlength="8" placeholder="二级菜单名，最多可输入7个字" class="editing" value="'+ name +'" type="text">';
    }else{
        subMenuHtml += '<input maxlength="8" placeholder="二级菜单名，最多可输入7个字" class="editing" type="text">';
    }

    subMenuHtml += '<a class="newNameAction saveNewName"></a>';
    subMenuHtml += '<a class="newNameAction cancelNewName">取消</a>';

    return subMenuHtml;
}

function regSubMenuHtml(){
    return '<a href="javascript:;" class="new-menu new-menu-sub">+ 添加子菜单</a>';
}

function addMenuHtml(name){
    var MenuHtml = '';
    if(name){
        MenuHtml += '<input maxlength="8" placeholder="主菜单名，最多可输入4个字" class="editing" value="'+ name +'" type="text">';
    }else{
        MenuHtml += '<input maxlength="8" placeholder="主菜单名，最多可输入4个字" class="editing" type="text">';
    }

    MenuHtml += '<a class="newNameAction saveNewName"></a>';
    MenuHtml += '<a class="newNameAction cancelNewName">取消</a>';

    return MenuHtml;
}

function regMenuHtml(){
    return '<a href="javascript:;" class="new-menu new-menu-main">+ 添加主菜单</a>';
}

//主菜单预览HTML结构
function previewHtml(parentData, parentName){
    var previewHtml = '';
    previewHtml += '<div class="preview-items">';
    previewHtml += '<a href="#" data-menudata='+parentData+' class="menu menu-action noAction">'+ parentName +'</a>';
    previewHtml += '</div>';
    return previewHtml;
}

//子菜单预览HTML结构
function previewSubItemsHtml(subData, subName){
    var previewSubHtml = '';
    previewSubHtml += '<ul class="preview-sub-items">';
    previewSubHtml += '<li><a href="#" class="sub-menu menu-action noAction" data-menudata='+ subData +' class="sub-menu menu-action noAction">'+ subName +'</a></li>';
    previewSubHtml += '<li class="btm-point"></li>';
    previewSubHtml += '</ul>';
    return previewSubHtml
}

//子菜单条目预览HTML结构
function subHtml(subData, subName){
    var subHtml = '';
    subHtml += '<li>';
    subHtml += '<a href="#" data-menudata='+ subData +' class="sub-menu menu-action noAction">'+ subName +'</a>';
    subHtml += '</li>';

    return subHtml;
}

function actionContainerHtml(){
    var actionContainerHtml = '';
    actionContainerHtml += '<div class="actionContainer fn-hide notSelectType" data-actiontype="" >';
    actionContainerHtml += '<h3 class="title">菜单类型</h3>';
    actionContainerHtml += '<div class="select-box">';
    actionContainerHtml += '<div class="selectInput">请选择类型</div>';
    actionContainerHtml += '<ul class="actionSelect fn-hide" style="display: none;">';
    actionContainerHtml += '<li val="info" class="deep-back "> <span class="typeName info"  > 推送消息 </span> <span class="explain"> 图文,音频,视频等 </span></li>';
    //actionContainerHtml += '<li val="promotion" > <span class="typeName promotion"  > 活动场景选择 </span> <span class="explain"> 场景选择 </span></li>';
    actionContainerHtml += '<li val="ecshop" > <span class="typeName ecshop"  > 微商城 </span> <span class="explain"> 微商城 </span></li>';
    actionContainerHtml += '<li val="link" class="deep-back "> <span class="typeName link"  > 自定义链接 </span> <span class="explain"> 跳转链接 </span></li>';
    actionContainerHtml += '<li val="member" > <span class="typeName member"  > 会员中心 </span> <span class="explain"> 会员中心 </span></li>';
    actionContainerHtml += '</ul></div>';
    actionContainerHtml += '<div class="container-title"><b>菜单效果</b></div>';
    actionContainerHtml += '<div class="tplContainer"><div class="container-tip">请先选择菜单类型</div></div>';
    actionContainerHtml += '<div class="btn-wrap"><input class="btn-small btn-orange saveBtn" type="button" value="保存"></div>';
    actionContainerHtml += '<span class="left-point"></span><a class="cancelBtn">x</a>';
    actionContainerHtml += '</div>';

    return actionContainerHtml;
}

function infoHtml(){
    var infoHtml = '';
    infoHtml += '<div class="infoType">';
    infoHtml += '<ul class="infoUl">';
    infoHtml += '<li class="infoLi semoji btn btn-default btn-sm" data-type="text"><i class="fa fa-smile-o"></i>表情</li>';
    infoHtml += '<li class="infoLi image btn btn-default btn-sm bolt-modal-click" data-type="image"><i class="fa fa-picture-o"></i>图片</li>';
    infoHtml += '<li class="infoLi graphics btn btn-default btn-sm bolt-modal-click" data-type="graphics"><i class="fa  fa-file-o"></i>微信图文</li>';
    infoHtml += '<li class="infoLi material btn btn-default btn-sm bolt-modal-click"  data-type="material"><i class="fa  fa-files-o"></i>高级图文</li>';
    infoHtml += '<li class="infoLi voice btn btn-default btn-sm bolt-modal-click"  data-type="voice"><i class="fa fa-microphone"></i>语音</li>';
    infoHtml += '<li class="infoLi video btn btn-default btn-sm bolt-modal-click"  data-type="video"><i class="fa fa-video-camera"></i>视频</li>';
    infoHtml += '</ul>';
    infoHtml += '<div class="infoTextArea" contenteditable="true"></div>';
    infoHtml += '<div class="emoji_preview spreview"></div>';
    infoHtml += '<div class="image_preview spreview"></div>';
    infoHtml += '<div class="graphics_preview spreview"></div>';
    infoHtml += '<div class="material_preview spreview"></div>';
    infoHtml += '<div class="voice_preview spreview"></div>';
    infoHtml += '<div class="video_preview spreview"></div>';
    infoHtml += '</div>';

    return infoHtml;
}

//显示链接配置HTML
function linkHtml(){
    var linkHtml = '';
    linkHtml += '<div class="container-tip">用户点击该菜单后，将跳转到以下网页地址。</div>';
    linkHtml += '<div class="inputWrap">';
    linkHtml += '<input  placeholder="请填写以http://或https://开头的网页地址" class="typeContentInput onCur" type="text">';
    linkHtml += '<div class="link_preview"></div>';
    linkHtml += '</div>';
    linkHtml += '<div class="errorMsg"><i class="iconfont" title="出错"></i>请填写正确的网页地址。</div>';

    return linkHtml;
}

//显示微商城链接配置 HTML
function ecshopHtml(){
    var ecshopHtml = '';
    ecshopHtml += '<div class="container-tip">用户点击该菜单后，将跳转到微商城。</div>';
    ecshopHtml += '<div class="select-box">';
    ecshopHtml += '<div class="selectInput">请选择页面</div>';
    ecshopHtml += '<ul class="actionSelect ecshopConfig" style="display: none;">';
    ecshopHtml += '<li val="lp" class="deep-back "> <span class="typeName lp"  > 首页 </span> <span class="explain"> 首页 </span></li>';
    ecshopHtml += '<li val="member" class="deep-back "> <span class="typeName member"  > 会员中心 </span> <span class="explain"> 会员中心 </span></li>';
    ecshopHtml += '<li val="order" class="deep-back "> <span class="typeName order"  > 我的订单 </span> <span class="explain"> 我的订单 </span></li>';
    ecshopHtml += '</ul></div>';

    return ecshopHtml;
}

//显示会员中心链接配置 HTML
function memberHtml(){
    var memberHtml = '';
    memberHtml += '<div class="container-tip">用户点击该菜单后，将跳转到会员中心。</div>';
    memberHtml += '<div class="select-box">';
    memberHtml += '<div class="selectInput">请选择页面</div>';
    memberHtml += '<ul class="actionSelect memberConfig" style="display: none;">';
    memberHtml += '<li val="member" class="deep-back "> <span class="typeName member"  > 首页 </span> <span class="explain"> 首页 </span></li>';
    memberHtml += '</ul></div>';

    return memberHtml;
}

//模态框选择内容后的回调函数处理逻辑
var modalCallBack = function (type, id) {
    //将选中信息存储在预览 JSON 数据和HTML结构中
    getInfo(type, id);
}

function getInfo(type, id){

    $.ajax({
        type: "GET",
        data: {type:type,id:id},
        dataType: 'json',
        url: $('.root_url').val()+"/angel/wechat/menu/getInfo",
        success: function(rs){
            var curEditElement = $('.custom').find('.curEditMenu');

            desElement = type + '_preview';
            curEditElement.parent().parent().children('.'+desElement).attr('actionType', type);
            curEditElement.parent().parent().children('.'+desElement).attr('messageId', id);

            //添加数据
            curEditElement.parent().parent().children('.'+desElement).attr('updata', JSON.stringify(rs));
            curEditElement.parent().parent().children('.spreview').empty();
            switch(type) {
                case 'image':
                    curEditElement.parent().parent().children('.'+desElement).empty();
                    curEditElement.parent().parent().children('.'+desElement).append('<img src="'+ $('.root_url').val() + '/'+ rs.url +'"/>');
                    break;
                case 'voice':
                    curEditElement.parent().parent().children('.'+desElement).empty();
                    var gHtml = voicePreview(rs);
                    curEditElement.parent().parent().children('.'+desElement).append(gHtml);
                    break;
                case 'video':

                    break;
                case 'graphics':
                    var gHtml = graphicsPreview(rs);
                    curEditElement.parent().parent().children('.'+desElement).append(gHtml);
                    break;
            }
            //设置自身有效
            curEditElement.parent().parent().children('.'+desElement).addClass('onCur');
            //设置其他类型无效
            curEditElement.parent().parent().children('.'+desElement).siblings().removeClass('onCur');
            //设置文本输入框不显示
            curEditElement.parent().parent().children('.infoTextArea').css('display', 'none');
        },
    });

}


function graphicsPreview(rs){

    var gHtml = '';
    gHtml += '<div class="left-show fn-left" id="messageList">';
    gHtml += '<ul class="show-cont ui-sortable" id="J_showCont">';
    gHtml += '<li class="first-item state-disabled multiMsgItem" id="item_0">';
    gHtml += '<div class="multiMsgMode">';
    gHtml += '<div class="multimessage-show-title">';
    gHtml += '<h1 class="J_change_title" data-title="title" data-default="标题">';
    if(rs.hasOwnProperty('title')){
        gHtml += rs.title+'</h1>';
    }else{
        rsa = '';
        rs = getPreviewInfo('graphics', rs.messageId);
        gHtml += rs.title+'</h1>';
    }
    
    gHtml += '<div class="title-mask-bg"></div></div><div class="cover-pic J_change_image" data-image="image" data-default="封面图片">';
    gHtml += '<img src="'+ $('.root_url').val() +'/'+ rs.img_url +'" height="100%" width="100%"></div></div></li>';

    if(rs.items){
        $.each(rs.items, function(i, n){

            gHtml += '<li class="show-item fn-clear state-disabled" id="item_1">';
            gHtml += '<div class="cover-pic J_change_image" data-image="image" data-default="缩略图"><img src="'+ $('.root_url').val()+'/'+ n.img_url +'" height="100%" width="100%"></div>';
            gHtml += '<h1 class="show-title title-break J_change_title" data-title="title" data-default="标题">';
            gHtml += n.title;
            gHtml += '</h1></li>';

        });
    }
    gHtml += '</ul></div>';

    return gHtml;    
}


function voicePreview(rs){
    
    var gHtml = '';
    gHtml += '<div class="preview_voice">';
    // gHtml += '<span class="voice_name">' + rs.name ? rs.name : '' + '</span>';
    if(rs.hasOwnProperty('url')){
        gHtml += '<audio controls="controls"><source src="'+ $('.root_url').val() + '/' + rs.url +'" type="audio/mpeg"></audio>';    
    }else{
        rsa = '';
        rsa = getPreviewInfo('voice', rs.messageId);
        gHtml += '<audio controls="controls"><source src="'+ $('.root_url').val() + '/' + rsa.url +'" type="audio/mpeg"></audio>';
    }
    
    gHtml += '</div>';
    return gHtml;
}

function videoPreview(rs){
    var gHtml = '';

    return gHtml;
}


function imagePreview(rs){
    var gHtml = '';
    gHtml += '<div class="preview_img">';
    if(rs.hasOwnProperty('url')){
        gHtml += '<img src="'+ $('.root_url').val() + '/'+ rs.url +'"/>';    
    }else{
        rsa = '';
        rsa = getPreviewInfo('image', rs.messageId);
        gHtml += '<img src="'+ $('.root_url').val() + '/'+ rsa.url +'"/>';
    }
    
    gHtml += '</div>';

    return gHtml;
}

function materialPreview(rs){
    var gHtml = '';

    return gHtml;
}

function textPreview(rs){
    var gHtml = '';
    gHtml += '<div class="preview_text">';
    gHtml += rs;
    gHtml += '</div>';
    return gHtml;
}

function linkPreview(rs){
    var gHtml = '';
    gHtml += '<div class="preview_link">';
    gHtml += '<iframe src="'+ rs + ' "></iframe>';
    gHtml += '</div>';
    return gHtml;
}

function getPreviewInfo(type, id){
    var result = "";
    $.ajax({
        type: "GET",
        data: {type:type,id:id},
        dataType: 'json',
        async: false,//同步
        url: $('.root_url').val()+"/angel/wechat/menu/getInfo",
        success: function(rs){
            result = rs;
        },
    });
    
    return result;
}

function positionRecord(which, pindex, pdata){
    var pHtml = '<span class="sortable_record_info" position=' + pdata + ' > </span>';    
    
    return pHtml;
}

resetPreviewWidth();