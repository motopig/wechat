<?php

/**
 * 第三方api微信地址匹配
 * 
 * @category yunke
 * @package app\config\wormhole
 * @author no<no>
 * @copyright © no. All rights reserved.
 */
return array(

    // 微信平台
    'url' => array(
        // 获取access_token (有效期2小时)
        'getAccessToken' => 'https://api.weixin.qq.com/cgi-bin/token',
        
        // 获取jsapi_ticket (有效期2小时)
        'getJsApiTicket' => 'https://api.weixin.qq.com/cgi-bin/ticket/getticket',

        // 获取微信服务器IP地址
        'getCallbackIp' => 'https://api.weixin.qq.com/cgi-bin/getcallbackip',
        
        // 消息推送
        'sendMsg' => 'https://api.weixin.qq.com/cgi-bin/message/custom/send',
        
        // 自定义菜单创建
        'createMenu' => 'https://api.weixin.qq.com/cgi-bin/menu/create',
        
        // 自定义菜单查询
        'getMenu' => 'https://api.weixin.qq.com/cgi-bin/menu/get',
        
        // 自定义菜单删除
        'deleteMenu' => 'https://api.weixin.qq.com/cgi-bin/menu/delete',
        
        // 创建分组
        'createGroup' => 'https://api.weixin.qq.com/cgi-bin/groups/create',
        
        // 修改分组名
        'updateGroup' => 'https://api.weixin.qq.com/cgi-bin/groups/update',
        
        // 移动用户分组
        'moveGroup' => 'https://api.weixin.qq.com/cgi-bin/groups/members/update',
        
        // 查询分组
        'getGroup' => 'https://api.weixin.qq.com/cgi-bin/groups/get',

        // 删除分组
        'deleteGroup' => 'https://api.weixin.qq.com/cgi-bin/groups/delete',
        
        // 查询用户所在分组
        'getGroupId' => 'https://api.weixin.qq.com/cgi-bin/groups/getid',
        
        // 设置用户备注名
        'userRemark' => 'https://api.weixin.qq.com/cgi-bin/user/info/updateremark',
        
        // 获取用户列表
        'getOpenId' => 'https://api.weixin.qq.com/cgi-bin/user/get',
        
        // 获取用户基本信息
        'getUserInfo' => 'https://api.weixin.qq.com/cgi-bin/user/info',
        
        // 创建二维码ticket (临时二维码: 有效期30分钟)
        'createQrcode' => 'https://api.weixin.qq.com/cgi-bin/qrcode/create',
        
        // 通过ticket换取二维码
        'getQrcode' => 'https://mp.weixin.qq.com/cgi-bin/showqrcode',
        
        // 预览群发消息接口 (订阅号与服务号认证后均可用)
        'massPreview' => 'https://api.weixin.qq.com/cgi-bin/message/mass/preview',
        
        // 根据分组进行群发 (订阅号与服务号认证后均可用)
        'massAll' => 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall',
        
        // 根据OpenID列表群发 (订阅号不可用, 服务号认证后可用)
        'massOpenId' => 'https://api.weixin.qq.com/cgi-bin/message/mass/send',
        
        // 删除群发 (订阅号与服务号认证后均可用)
        'deleteMass' => 'https://api.weixin.qq.com/cgi-bin/message/mass/delete',
        
        // 查询群发消息发送状态 (订阅号与服务号认证后均可用)
        'getMass' => 'https://api.weixin.qq.com/cgi-bin/message/mass/get',
        
        // 获取用户增减数据 (最大时间跨度: 7天)
        'getUserSummary' => 'https://api.weixin.qq.com/datacube/getusersummary',
        
        // 获取累计用户数据 (最大时间跨度: 7天)
        'getUserCumulate' => 'https://api.weixin.qq.com/datacube/getusercumulate',
        
        // 获取图文群发每日数据 (最大时间跨度: 1天)
        'getArticleSummary' => 'https://api.weixin.qq.com/datacube/getarticlesummary',
        
        // 获取图文群发总数据 (最大时间跨度: 1天)
        'getArticleTotal' => 'https://api.weixin.qq.com/datacube/getarticletotal',
        
        // 获取图文统计数据 (最大时间跨度: 3天)
        'getUserRead' => 'https://api.weixin.qq.com/datacube/getuserread',
        
        // 获取图文统计分时数据 (最大时间跨度: 1天)
        'getUserReadHour' => 'https://api.weixin.qq.com/datacube/getuserreadhour',
        
        // 获取图文分享转发数据 (最大时间跨度: 7天)
        'getUserShare' => 'https://api.weixin.qq.com/datacube/getusershare',
        
        // 获取图文分享转发分时数据 (最大时间跨度: 1天)
        'getUserShareHour' => 'https://api.weixin.qq.com/datacube/getusersharehour',
        
        // 获取消息发送概况数据 (最大时间跨度: 7天)
        'getUpStreamMsg' => 'https://api.weixin.qq.com/datacube/getupstreammsg',
        
        // 获取消息分送分时数据 (最大时间跨度: 1天)
        'getUpStreamMsgHour' => 'https://api.weixin.qq.com/datacube/getupstreammsghour',
        
        // 获取消息发送周数据 (最大时间跨度: 30天)
        'getUpStreamMsgWeek' => 'https://api.weixin.qq.com/datacube/getupstreammsgweek',
        
        // 获取消息发送月数据 (最大时间跨度: 30天)
        'getUpStreamMsgMonth' => 'https://api.weixin.qq.com/datacube/getupstreammsgmonth',
        
        // 获取消息发送分布数据 (最大时间跨度: 15天)
        'getUpStreamMsgDist' => 'https://api.weixin.qq.com/datacube/getupstreammsgdist',
        
        // 获取消息发送分布周数据 (最大时间跨度: 30天)
        'getUpStreamMsgDistWeek' => 'https://api.weixin.qq.com/datacube/getupstreammsgdistweek',
        
        // 获取消息发送分布月数据 (最大时间跨度: 30天)
        'getUpStreamMsgDistMonth' => 'https://api.weixin.qq.com/datacube/getupstreammsgdistmonth',
        
        // 获取接口分析数据 (最大时间跨度: 30天)
        'getInterFaceSummary' => 'https://api.weixin.qq.com/datacube/getinterfacesummary',
        
        // 获取接口分析分时数据 (最大时间跨度: 1天)
        'getInterFaceSummaryHour' => 'https://api.weixin.qq.com/datacube/getinterfacesummaryhour',
        
        // 获取客服基本信息
        'getKfList' => 'https://api.weixin.qq.com/cgi-bin/customservice/getkflist',
        
        // 获取在线客服接待信息
        'getOnlineKfList' => 'https://api.weixin.qq.com/cgi-bin/customservice/getonlinekflist',
        
        // 添加客服账号
        'addKfAccount' => 'https://api.weixin.qq.com/customservice/kfaccount/add',
        
        // 设置客服信息
        'updateKfAccount' => 'https://api.weixin.qq.com/customservice/kfaccount/update',
        
        // 删除客服账号
        'deleteKfAccount' => 'https://api.weixin.qq.com/customservice/kfaccount/del',
        
        // 获取客服聊天记录
        'getRecord' => 'https://api.weixin.qq.com/cgi-bin/customservice/getrecord',
        
        // 上传客服头像
        'uploadHeadImgKfAccount' => 'http://api.weixin.qq.com/customservice/kfacount/uploadheadimg',

        // 新增永久图文素材
        'addNews' => 'https://api.weixin.qq.com/cgi-bin/material/add_news',

        // 新增其他类型永久素材
        // 分别有图片（image）、语音（voice）、视频（video）和缩略图（thumb）
        'addMaterial' => 'https://api.weixin.qq.com/cgi-bin/material/add_material',

        // 删除永久图文素材
        'delMaterial' => 'https://api.weixin.qq.com/cgi-bin/material/del_material',

        //下载用户素材
        'downloadMedia' => 'http://file.api.weixin.qq.com/cgi-bin/media/get',

        // 摇一摇周边
        // 申请设备ID
        'shakearoundDeviceApplyid' => 'https://api.weixin.qq.com/shakearound/device/applyid',
        // 编辑设备信息
        'shakearoundDeviceUpdate' => 'https://api.weixin.qq.com/shakearound/device/update',
        // 配置设备与门店的关联关系
        'shakearoundDeviceBindlocation' => 'https://api.weixin.qq.com/shakearound/device/bindlocation',
        // 配置设备与页面的关联关系
        'shakearoundDeviceBindpage' => 'https://api.weixin.qq.com/shakearound/device/bindpage',
        // 查询设备列表
        'shakearoundDeviceSearch' => 'https://api.weixin.qq.com/shakearound/device/search',
        // 根据批次ID查询设备列表
        'shakearoundDeviceApply' => 'https://api.weixin.qq.com/shakearound/device/search',
        // 上传图片素材
        'shakearoundMaterialAdd' => 'https://api.weixin.qq.com/shakearound/material/add',
        // 新增页面
        'shakearoundPageAdd' => 'https://api.weixin.qq.com/shakearound/page/add',
        // 编辑页面信息
        'shakearoundPageUpdate' => 'https://api.weixin.qq.com/shakearound/page/update',
        // 查询页面列表
        'shakearoundPageSearch' => 'https://api.weixin.qq.com/shakearound/page/search',
        // 删除页面
        'shakearoundPageDelete' => 'https://api.weixin.qq.com/shakearound/page/delete',
        // 获取摇周边的设备及用户信息
        'shakearoundUserGetshakeinfo' => 'https://api.weixin.qq.com/shakearound/user/getshakeinfo',
        // 以设备为维度的数据统计接口
        'shakearoundStatisticsDevice' => 'https://api.weixin.qq.com/shakearound/statistics/device',
        // 以页面为维度的数据统计接口
        'shakearoundStatisticsPage' => 'https://api.weixin.qq.com/shakearound/statistics/page',

        // 微信开放平台
        // 获取托管component_access_token
        'componentAccessToken' => 'https://api.weixin.qq.com/cgi-bin/component/api_component_token',
        // 获取托管预授权码
        'apiCreatePreauthcode' => 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode',
        // 微信授权页登入页面
        'componentLoginPage' => 'https://mp.weixin.qq.com/cgi-bin/componentloginpage',
        // 授权处理回调
        'componentApiQueryAuth' => 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth',
        // 获取刷新令牌authorizer_refresh_token
        'authorizerRefreshToken' => 'https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token',
        // 获取授权方的账户信息
        'apiGetAuthorizerInfo' => 'https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info',

        // 微信网页授权获取用户基本信息
        // 网页授权获取用户信息请求code
        'oauth2Authorize' => 'https://open.weixin.qq.com/connect/oauth2/authorize',
        // 开发者机制网页授权获取用户信息通过code换取access_token及openid
        'getAccessTokenOauth2' => 'https://api.weixin.qq.com/sns/oauth2/access_token',
        // 开发者机制通过网页授权access_token获取用户基本信息（需授权作用域为snsapi_userinfo）
        'getRefreshAccessTokenOauth2' => 'https://api.weixin.qq.com/sns/oauth2/refresh_token',
        // 授权机制网页授权获取用户信息通过code换取access_token及openid
        'oauth2Component' => 'https://api.weixin.qq.com/sns/oauth2/component/access_token',
        // 授权机制网页授权获取用户信息刷新access_token及openid（如果需要）
        'oauth2RefreshToken' => 'https://api.weixin.qq.com/sns/oauth2/component/refresh_token',
        // 授权机制通过网页授权access_token获取用户基本信息（需授权作用域为snsapi_userinfo）
        'oauth2UserInfo' => 'https://api.weixin.qq.com/sns/userinfo',

        // 上传接口所需图片(微信端)
        'uploadImg' => 'https://api.weixin.qq.com/cgi-bin/media/uploadimg',

        // 卡券接口
        // 创建卡券
        'cardCreate' => 'https://api.weixin.qq.com/card/create',
        // 删除卡券
        'cardDelete' => 'https://api.weixin.qq.com/card/delete',
        // 更改卡券信息
        'cardUpdate' => 'https://api.weixin.qq.com/card/update',
        // 修改库存
        'modifyStock' => 'https://api.weixin.qq.com/card/modifystock',
        // 卡券二维码
        'cardQrcode' => 'https://api.weixin.qq.com/card/qrcode/create',
        // 卡券核销
        'cardCode' => 'https://api.weixin.qq.com/card/code/consume',

        // 门店接口
        // 创建门店
        'addPoi' => 'http://api.weixin.qq.com/cgi-bin/poi/addpoi',
        // 修改门店服务信息
        'updatePoi' => 'https://api.weixin.qq.com/cgi-bin/poi/updatepoi',
        // 删除门店
        'delPoi' => 'https://api.weixin.qq.com/cgi-bin/poi/delpoi'
    ),

    // 错误返回日志
    'errcode' => array(
        // 云客微信日志定义 - begin
        '-2' => '缺少接口所需参数，无法和微信通信',
        // 云客微信日志定义 - end
        
        '-40001' => '签名验证错误',
        '-40002' => 'xml解析失败',
        '-40003' => 'sha加密生成签名失败',
        '-40004' => 'encodingAesKey 非法',
        '-40005' => 'appid 校验错误',
        '-40006' => 'aes 加密失败',
        '-40007' => 'aes 解密失败',
        '-40008' => '解密后得到的buffer非法',
        '-40009' => 'base64加密失败',
        '-40010' => 'base64解密失败',
        '-40011' => '生成xml失败',
        '-1' => '系统繁忙，此时请开发者稍候再试',
        '40001' => '获取access_token时AppSecret错误，或者access_token无效。
                    请开发者认真比对AppSecret的正确性，或查看是否正在为恰当的公众号调用接口',
        '40002' => '不合法的凭证类型',
        '40003' => '不合法的OpenID，请开发者确认OpenID（该用户）是否已关注公众号，或是否是其他公众号的OpenID',
        '40004' => '不合法的媒体文件类型',
        '40005' => '不合法的文件类型',
        '40006' => '不合法的文件大小',
        '40007' => '不合法的媒体文件id',
        '40008' => '不合法的消息类型',
        '40009' => '不合法的图片文件大小',
        '40010' => '不合法的语音文件大小',
        '40011' => '不合法的视频文件大小',
        '40012' => '不合法的缩略图文件大小',
        '40013' => '不合法的AppID，请开发者检查AppID的正确性，避免异常字符，注意大小写',
        '40014' => '不合法的access_token，请开发者认真比对access_token的有效性（如是否过期），
                    或查看是否正在为恰当的公众号调用接口',
        '40015' => '不合法的菜单类型',
        '40016' => '不合法的按钮个数',
        '40017' => '不合法的按钮个数',
        '40018' => '不合法的按钮名字长度',
        '40019' => '不合法的按钮KEY长度',
        '40020' => '不合法的按钮URL长度',
        '40021' => '不合法的菜单版本号',
        '40022' => '不合法的子菜单级数',
        '40023' => '不合法的子菜单按钮个数',
        '40024' => '不合法的子菜单按钮类型',
        '40025' => '不合法的子菜单按钮名字长度',
        '40026' => '不合法的子菜单按钮KEY长度',
        '40027' => '不合法的子菜单按钮URL长度',
        '40028' => '不合法的自定义菜单使用用户',
        '40029' => '不合法的oauth_code',
        '40030' => '不合法的refresh_token',
        '40031' => '不合法的openid列表',
        '40032' => '不合法的openid列表长度',
        '40033' => '不合法的请求字符，不能包含\uxxxx格式的字符',
        '40035' => '不合法的参数',
        '40038' => '不合法的请求格式',
        '40039' => '不合法的URL长度',
        '40050' => '不合法的分组id',
        '40051' => '分组名字不合法',
        '40053' => '不合法的actioninfo，请开发者确认参数正确',
        '40056' => '不合法的Code码',
        '40071' => '不合法的卡券类型',
        '40072' => '不合法的编码方式',
        '40078' => '不合法的卡券状态',
        '40079' => '不合法的时间',
        '40080' => '不合法的CardExt',
        '40094' => '参数不正确，请检查json字段',
        '40097' => '无效参数',
        '40099' => '卡券已被核销',
        '40100' => '不合法的时间区间',
        '40113' => '不支持的文件类型',
        '40116' => '不合法的Code码',
        '40122' => '不合法的库存数量',
        '40124' => '会员卡设置查过限制的custom_field字段',
        '40125' => 'invalid appsecret, view more at http://t.cn/RAEkdVq',
        '40127' => '卡券被用户删除或转赠中',
        '41001' => '缺少access_token参数',
        '41002' => '缺少appid参数',
        '41003' => '缺少refresh_token参数',
        '41004' => '缺少secret参数',
        '41005' => '缺少多媒体文件数据',
        '41006' => '缺少media_id参数',
        '41007' => '缺少子菜单数据',
        '41008' => '缺少oauth code',
        '41009' => '缺少openid',
        '41011' => '缺少必填字段',
        '41012' => '缺少cardid参数',
        '42001' => 'access_token超时，请检查access_token的有效期，
                    请参考基础支持-获取access_token中，对access_token的详细机制说明',
        '42002' => 'refresh_token超时',
        '42003' => 'oauth_code超时',
        '43001' => '需要GET请求',
        '43002' => '需要POST请求',
        '43003' => '需要HTTPS请求',
        '43004' => '需要接收者关注',
        '43005' => '需要好友关系',
        '43009' => '自定义SN权限，请前往公众平台申请',
        '43010' => '无储值权限，请前往公众平台申请',
        '44001' => '多媒体文件为空',
        '44002' => 'POST的数据包为空',
        '44003' => '图文消息内容为空',
        '44004' => '文本消息内容为空',
        '45001' => '多媒体文件大小超过限制',
        '45002' => '消息内容超过限制',
        '45003' => '标题字段超过限制',
        '45004' => '描述字段超过限制',
        '45005' => '链接字段超过限制',
        '45006' => '图片链接字段超过限制',
        '45007' => '语音播放时间超过限制',
        '45008' => '图文消息超过限制',
        '45009' => '接口调用超过限制',
        '45010' => '创建菜单个数超过限制',
        '45015' => '回复时间超过限制',
        '45016' => '系统分组，不允许修改',
        '45017' => '分组名字过长',
        '45018' => '分组数量超过上限',
        '45021' => '字段超过长度限制，请参考相应接口的字段说明',
        '45030' => '该cardid无接口权限',
        '45031' => '库存为0',
        '45033' => '用户领取次数超过限制get_limit',
        '46001' => '不存在媒体数据',
        '46002' => '不存在的菜单版本',
        '46003' => '不存在的菜单数据',
        '46004' => '不存在的用户',
        '47001' => '解析JSON/XML内容错误',
        '48001' => 'api功能未授权，请确认公众号已获得该接口，可以在公众平台官网-开发者中心页中查看接口权限',
        '50001' => '用户未授权该api',
        '61451' => '参数错误(invalid parameter)',
        '61452' => '无效客服账号(invalid kf_account)',
        '61453' => '客服帐号已存在(kf_account exsited)',
        '61454' => '客服帐号名长度超过限制(仅允许10个英文字符，不包括@及@后的公众号的微信号)
                    (invalid kf_acount length)',
        '61455' => '客服帐号名包含非法字符(仅允许英文+数字)(illegal character in kf_account)',
        '61456' => '客服帐号个数超过限制(10个客服账号)(kf_account count exceeded)',
        '61457' => '无效头像文件类型(invalid file type)',
        '61450' => '系统错误(system error)',
        '61500' => '日期格式错误',
        '61501' => '日期范围错误',
        '65104' => '门店的类型不合法，必须严格按照附表的分类填写',
        '65105' => '图片url不合法，必须使用微信图片上传接口所获取的url',
        '65106' => '门店状态必须未审核通过',
        '65107' => '扩展字段为不允许修改的状态',
        '65109' => '门店名为空',
        '65110' => '门店所在详细街道地址为空',
        '65111' => '门店的电话为空',
        '65112' => '门店所在的城市为空',
        '65113' => '门店所在的省份为空',
        '65114' => '图片列表为空',
        '65115' => 'poi_id不正确',
        '9001001' => 'POST数据参数不合法',
        '9001002' => '远端服务不可用',
        '9001003' => 'Ticket不合法',
        '9001004' => '获取摇周边用户信息失败',
        '9001005' => '获取商户信息失败',
        '9001006' => '获取OpenID失败',
        '9001007' => '上传文件缺失',
        '9001008' => '上传素材的文件类型不合法',
        '9001009' => '上传素材的文件尺寸不合法',
        '9001010' => '上传失败',
        '9001020' => '帐号不合法',
        '9001021' => '已有设备激活率低于50%，不能新增设备',
        '9001022' => '设备申请数不合法，必须为大于0的数字',
        '9001023' => '已存在审核中的设备ID申请',
        '9001024' => '一次查询设备ID数量不能超过50',
        '9001025' => '设备ID不合法',
        '9001026' => '页面ID不合法',
        '9001027' => '页面参数不合法',
        '9001028' => '一次删除页面ID数量不能超过10',
        '9001029' => '页面已应用在设备中，请先解除应用关系再删除',
        '9001030' => '一次查询页面ID数量不能超过50',
        '9001031' => '时间区间不合法',
        '9001032' => '保存设备与页面的绑定关系参数错误',
        '9001033' => '门店ID不合法',
        '9001034' => '设备备注信息过长',
        '9001035' => '设备申请参数不合法',
        '9001036' => '查询起始值begin不合法'
    )
);
