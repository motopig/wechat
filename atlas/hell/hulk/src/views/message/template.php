

<!--用户列表模板-->
<script id="userListTemp" type="text/x-handlebars-template">
    <div id="hasNewMsg">
        <a href="">
            你有新消息，点击查看
        </a>
    </div>
    <ul class="list-ul">
        {{#each data}}
        <li class="mitem user-item" data-member_id="{{member_id}}">
            <div class="photo fm-left">
                <a href="./?toUserId={{member_id}}">
                    <img src="{{head}}"
                         alt="头像">
                </a>
            </div>
            <div class="msgBox fm-right">
                <div class="message fm-left">
                    <div class="left fm-left">
                        <p class="name">
                            <a href="./?toUserId={{member_id}}">
                                {{name}}
                            </a>
                        </p>
                        <p class="msg">
                            留言:
                            {{msgType type}}<br>
                            {{replay replay}}
                        </p>
                    </div>
                    <div class="right fm-left">

                        <div class="time fm-right">
                            {{updated_at}}
                        </div>
                    </div>
                </div>
            </div>
        </li>
        {{/each}}
    </ul>

</script>


<!--单用户聊天消息模板-->
<script id="chat-message-model" type="text/x-handlebars-template">
    {{#hasHistory data}}{{/hasHistory}}
    <input type="hidden" class="morePage" value="1">
    {{#each data}}
    <li class="recent-message-item fn-clear">
        <div class="message-time">{{create_time}}</div>
        <div class="message-content  {{#isUsers op_id}}fm-left{{/isUsers}}{{#isNotUsers op_id}}fm-right{{/isNotUsers}}" data-msgid="{{id}}">

            {{#isText type}}
            <p class="content-text word-bread {{#isUsers ../op_id}}left-bg{{/isUsers}} {{#isNotUsers ../op_id}}right-bg{{/isNotUsers}}">
                {{{../content}}}
                <i class="{{#isUsers ../op_id}}arrow-left{{/isUsers}} {{#isNotUsers ../op_id}}arrow-right{{/isNotUsers}}"></i>
            </p>
            {{/isText}}

            {{{isGraphics type}}}

            {{{isImage type}}}

            {{{isVideo type}}}

            {{{isVoice type}}}

            {{{isShortvideo type}}}

            {{{isLocation type}}}

            {{{isLink type}}}

            {{#isEvent type}}
            <p class="content-text word-bread {{#isUsers ../op_id}}left-bg{{/isUsers}} {{#isNotUsers ../op_id}}right-bg{{/isNotUsers}}">
                {{{../content}}}
                <i class="{{#isUsers ../op_id}}arrow-left{{/isUsers}} {{#isNotUsers ../op_id}}arrow-right{{/isNotUsers}}"></i>
            </p>
            {{/isEvent}}

        </div>
    </li>
    {{/each}}
</script>

