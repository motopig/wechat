@include('EcdoSpiderMan::account.mailheader')
        <h3>【一点云客】密码修改确认</h3>
        <table>
            <tr>
                <td>
                请点击以下链接，以确认是您本人申请修改您的密码：    
                </td>
            </tr>
            <tr>
                <td>
                    <a class="button" href="{{$url}}" target="_blank">确认修改</a>
                </td>
            </tr>
            <tr>
                <td style="color:#999;font-size:12px;">
                    如果以上链接不能点击，你可以复制网址URL粘贴到浏览器打开。<br>
                    如果你没有或不需要变更密码，请忽略本邮件。
                </td>
            </tr>
        </table>
@include('EcdoSpiderMan::account.mailfooter')
