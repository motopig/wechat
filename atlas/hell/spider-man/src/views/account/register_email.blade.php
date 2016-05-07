@include('EcdoSpiderMan::account.mailheader')
        <h3>【一点云客】帐号注册确认</h3>
        <table>
            <tr>
                <td>
                <p>尊敬的用户 {{$email}}，您好：</p>
                <p>您使用了邮箱 {{$email}} 注册成为【云客】的商家用户。请点击以下链接进行邮件确认：</p>
                </td>
            </tr>
            <tr>
                <td>
                    <a class="button" href="{{$url}}" target="_blank">确认注册邮件</a>
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

