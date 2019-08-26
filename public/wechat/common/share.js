 var baseUrl = "http://www.zhengbu121.com/haluo/de/shareDemo/";
	 var baseUrl1 = "http://www.zhengbu121.com/haluo";
    var wxData = {
        "imgUrl" : baseUrl + '1.jpg',
        "link"   : baseUrl1,
        "title"  : '正步迎新春，哈啰单车骑行月卡免费拿',
        "desc"   : '新春福利第二弹'
    };
    wx.config({
        debug: false,
        appId: signPackage.appId,
        timestamp: parseInt(signPackage.timestamp),
        nonceStr: signPackage.nonceStr,
        signature: signPackage.signature,
        jsApiList: [
            'onMenuShareTimeline',
            'onMenuShareAppMessage'
        ]
    });
    wx.ready(function () {
        wx.onMenuShareTimeline({
            title: wxData.title,
            link: wxData.link,
            imgUrl: wxData.imgUrl,
            success: function () {
            }
        });
        wx.onMenuShareAppMessage({
            title: wxData.title,
            desc: wxData.desc,
            link: wxData.link,
            imgUrl: wxData.imgUrl,
            type: 'link',
            dataUrl: '',
            success: function () {
            }
        });
    });