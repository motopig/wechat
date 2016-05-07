// 引入微信api-js
document.write('<script type="text/javascript" src="/assets/tower/api/wechat/jweixin-1.0.0.js"></script>');

// 封装wechatjsapi
;(function (window) {
	// 全局变量
	var yk = {
        version: '1.0',
        errcode: 'success',
    	errmsg: '',
    	data: '',
        method: ''
    };

    window.yk = yk;

    // api列表
	var wechatList = [
		'onMenuShareTimeline',
		'onMenuShareAppMessage',
		'onMenuShareQQ',
		'onMenuShareWeibo',
		'onMenuShareQZone',
		'startRecord',
		'stopRecord',
		'onVoiceRecordEnd',
		'playVoice',
		'pauseVoice',
		'stopVoice',
		'onVoicePlayEnd',
		'uploadVoice',
		'downloadVoice',
		'chooseImage',
		'previewImage',
		'uploadImage',
		'downloadImage',
		'translateVoice',
		'getNetworkType',
		'openLocation',
		'getLocation',
		'hideOptionMenu',
		'showOptionMenu',
		'hideMenuItems',
		'showMenuItems',
		'hideAllNonBaseMenuItem',
		'showAllNonBaseMenuItem',
		'closeWindow',
		'scanQRCode',
		'chooseWXPay',
		'openProductSpecificView',
		'addCard',
		'chooseCard',
		'openCard'
	];

    // 公用调用方法
	yk.wechatCommon = function (config, data) {
		wx.config({
			// 开启调试模式,调用的所有api的返回值会在客户端alert出来，
			// 若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
	    	debug: false,
	    	appId: config.appId, // 必填，公众号的唯一标识
	    	timestamp: config.timestamp, // 必填，生成签名的时间戳
	    	nonceStr: config.nonceStr, // 必填，生成签名的随机串
	    	signature: config.signature, // 必填，签名，见附录1
	    	jsApiList: wechatList // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
		});

		wx.ready(function () {
			// 在这里调用微信API
	    	switch (data.method) {
				case 'getNetworkType':
			  		getNetworkType(data);
			  		break;
			  	case 'hideOptionMenu':
			  		hideOptionMenu(data);
			  		break;
			  	case 'showOptionMenu':
			  		showOptionMenu(data);
			  		break;
			  	case 'closeWindow':
			  		closeWindow(data);
			  		break;
			  	case 'onMenuShareAppMessage':
			  		alert('请至微信右上角，分享给朋友！');
			  		onMenuShareAppMessage(data);
			  		break;
			  	case 'onMenuShareTimeline':
			  		alert('请至微信右上角，分享到朋友圈！');
			  		onMenuShareTimeline(data);
			  		break;
			  	case 'onMenuShareQQ':
			  		alert('请至微信右上角，分享到QQ！');
			  		onMenuShareQQ(data);
			  		break;
			  	case 'onMenuShareQZone':
			  		alert('请至微信右上角，分享到QQ空间！');
			  		onMenuShareQZone(data);
			  		break;
			  	case 'onMenuShareWeibo':
			  		alert('请至微信右上角，分享到腾讯微博！');
			  		onMenuShareWeibo(data);
			  		break;
			  	case 'openLocation':
			  		openLocation(data);
			  		break;
			  	case 'getLocation':
			  		getLocation(data);
			  		break;
			  	case 'scanQRCode':
			  		scanQRCode(data);
			  		break;
			  	case 'addCard':
			  		addCard(data);
			  		break;
			}
	  	});

	  	wx.error(function (res) {
	    	// config信息验证失败会执行error函数，如签名过期导致验证失败，
	    	// 具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
	    	alert(res.errMsg);
		});
	};

	// 获取网络状态接口
	var getNetworkType = function (data) {
		wx.getNetworkType({
		    success: function (res) {
		        // 回调函数
				wechatSuccess('getNetworkType', res);
		    },

		    cancel: function (res) { 
		        wechatCancel('getNetworkType', res);
		    },

		    fail: function (res) {
	        	alert(res.errMsg);
	      	}
		});
	};

	// 隐藏右上角菜单接口
	var hideOptionMenu = function (data) {
		wx.hideOptionMenu();
	};

	// 显示右上角菜单接口
	var showOptionMenu = function (data) {
		wx.showOptionMenu();
	};

	// 关闭当前网页窗口接口
	var closeWindow = function (data) {
		wx.closeWindow();
	};

	// 分享给朋友
	var onMenuShareAppMessage = function (data) {
		wx.onMenuShareAppMessage({
			title: data.title, // 分享标题
		    desc: data.desc, // 分享描述
		    link: data.link, // 分享链接
		    imgUrl: data.imgUrl, // 分享图标

		    success: function (res) {
				wechatSuccess('onMenuShareAppMessage', res);
		    },

		    cancel: function (res) {
		        wechatCancel('onMenuShareAppMessage', res);
		    },

		    fail: function (res) {
	        	alert(res.errMsg);
	      	}
		});
	};

	// 分享到朋友圈
	var onMenuShareTimeline = function (data) {
		wx.onMenuShareTimeline({
			title: data.title, // 分享标题
		    link: data.link, // 分享链接
		    imgUrl: data.imgUrl, // 分享图标

		    success: function (res) {
				wechatSuccess('onMenuShareTimeline', res);
		    },

		    cancel: function (res) {
		        wechatCancel('onMenuShareTimeline', res);
		    },

		    fail: function (res) {
	        	alert(res.errMsg);
	      	}
		});
	};

	// 分享到QQ
	var onMenuShareQQ = function (data) {
		wx.onMenuShareQQ({
			title: data.title, // 分享标题
		    desc: data.desc, // 分享描述
		    link: data.link, // 分享链接
		    imgUrl: data.imgUrl, // 分享图标

		    success: function (res) {
				wechatSuccess('onMenuShareQQ', res);
		    },

		    cancel: function (res) {
		        wechatCancel('onMenuShareQQ', res);
		    },

		    fail: function (res) {
	        	alert(res.errMsg);
	      	}
		});
	};

	// 分享到QQ空间
	var onMenuShareQZone = function (data) {
		wx.onMenuShareQZone({
			title: data.title, // 分享标题
		    desc: data.desc, // 分享描述
		    link: data.link, // 分享链接
		    imgUrl: data.imgUrl, // 分享图标

		    success: function (res) {
				wechatSuccess('onMenuShareQZone', res);
		    },

		    cancel: function (res) {
		        wechatCancel('onMenuShareQZone', res);
		    },

		    fail: function (res) {
	        	alert(res.errMsg);
	      	}
		});
	};

	// 分享到腾讯微博
	var onMenuShareWeibo = function (data) {
		wx.onMenuShareWeibo({
			title: data.title, // 分享标题
		    desc: data.desc, // 分享描述
		    link: data.link, // 分享链接
		    imgUrl: data.imgUrl, // 分享图标

		    success: function (res) {
				wechatSuccess('onMenuShareWeibo', res);
		    },

		    cancel: function (res) {
		        wechatCancel('onMenuShareWeibo', res);
		    },

		    fail: function (res) {
	        	alert(res.errMsg);
	      	}
		});
	};

	// 使用微信内置地图查看位置接口
	var openLocation = function (data) {
		wx.openLocation({
		    latitude: data.latitude, // 纬度，浮点数，范围为90 ~ -90
		    longitude: data.longitude, // 经度，浮点数，范围为180 ~ -180。
		    name: data.name, // 位置名
		    address: data.address, // 地址详情说明
		    scale: data.scale, // 地图缩放级别,整形值,范围从1~28。默认为最大
		    infoUrl: data.infoUrl // 在查看位置界面底部显示的超链接,可点击跳转
		});
	};

	// 获取地理位置接口
	var getLocation = function (data) {
		wx.getLocation({
		    type: data.type, // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
		    
		    success: function (res) {
				wechatSuccess('getLocation', res);
		    },

		    cancel: function (res) { 
		        wechatCancel('getLocation', res);
		    },

		    fail: function (res) {
	        	alert(res.errMsg);
	      	}
		});
	};

	// 调起微信扫一扫接口
	var scanQRCode = function (data) {
		wx.scanQRCode({
		    needResult: data.needResult, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
	    	scanType: data.scanType, // 可以指定扫二维码还是一维码，默认二者都有
		    
		    success: function (res) {
				wechatSuccess('scanQRCode', res);
		    },

		    cancel: function (res) { 
		        wechatCancel('scanQRCode', res);
		    },

		    fail: function (res) {
	        	alert(res.errMsg);
	      	}
		});
	};

	// 批量添加卡券接口
	var addCard = function (data) {
		wx.addCard({
		    cardList: [{
		        cardId: data.cardId,
		        cardExt: data.cardExt
		    }], // 需要添加的卡券列表

		    success: function (res) {
		        wechatSuccess('addCard', res);
		    }
		});
	};

	// 回调函数执行
	var wechatSuccess = function (method, res) {
		yk.method = method;

		switch (method) {
			case 'getNetworkType':
		  		yk.errmsg = '获取网络状态接口成功!';
		  		yk.data = res.networkType;
		  		break;
		  	case 'onMenuShareAppMessage':
		  	case 'onMenuShareTimeline':
		  	case 'onMenuShareQQ':
		  	case 'onMenuShareQZone':
		  	case 'onMenuShareWeibo':
		  		yk.errmsg = '用户已经分享!';
		  		break;
		  	case 'getLocation':
		  		yk.errmsg = '用户获取地理位置成功!';
		  		break;
		  	case 'scanQRCode':
		  		yk.errmsg = '用户调起微信扫一扫成功!';
		  		yk.data = res.resultStr;
		  		break;
		  	case 'addCard':
		  		yk.errmsg = '调用领取微信卡券接口成功!';
		  		yk.data = res.cardList;
		  		break;
		}

		wechatCallBack(yk);
	};

	// 取消函数执行
	var wechatCancel = function (method, res) {
		yk.method = method;
		yk.errcode = 'error';

		switch (method) {
			case 'getNetworkType':
		  		yk.errmsg = '获取网络状态接口失败!';
		  		break;
		  	case 'onMenuShareAppMessage':
		  	case 'onMenuShareTimeline':
		  	case 'onMenuShareQQ':
		  	case 'onMenuShareQZone':
		  	case 'onMenuShareWeibo':
		  		yk.errmsg = '用户取消分享!';
		  		break;
		  	case 'getLocation':
		  		yk.errmsg = '用户获取地理位置失败!';
		  		break;
		  	case 'scanQRCode':
		  		yk.errmsg = '用户调起微信扫一扫失败!';
		  		break;
		  	case 'addCard':
		  		yk.errmsg = '调用领取微信卡券接口失败!';
		  		break;
		}

		wechatCallBack(yk);
	};
})(window);
