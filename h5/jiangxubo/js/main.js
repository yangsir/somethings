function initweixin(a) {
    wx.config({
        debug: !1,
        appId: a.appid,
        timestamp: a.timestamp,
        nonceStr: a.nonceStr,
        signature: a.signature,
        jsApiList: ["onMenuShareTimeline", "onMenuShareAppMessage", "onMenuShareQQ", "onMenuShareWeibo"]
    }), wx.ready(function () {
        wx.onMenuShareAppMessage({
            title: a.title,
            desc: a.desc,
            link: a.link,
            imgUrl: a.imgUrl,
            type: "",
            dataUrl: "",
            success: function () {
            },
            cancel: function () {
            }
        }), wx.onMenuShareTimeline({
            title: a.title, link: a.link, imgUrl: a.imgUrl, success: function () {
            }, cancel: function () {
            }
        }), wx.onMenuShareQQ({
            title: a.title, desc: a.desc, link: a.link, imgUrl: a.imgUrl, success: function () {
            }, cancel: function () {
            }
        }), wx.onMenuShareWeibo({
            title: a.title, desc: a.desc, link: a.link, imgUrl: a.imgUrl, success: function () {
            }, cancel: function () {
            }
        })
    })
}
function settitle(data) {
    data = eval(data), versiondata = data, document.title = data.title, thum = data.thum ? data.thum : "", viewer_isvip = 0 == data.vip || "false" == data.vip || void 0 == data.vip ? !1 : !0, data.status < 0 && (window.location.href = "offline.html")
}
function hideSP() {
    $("#sppage").transition({y: "100%"}).fadeOut()
}
//$(window).width() > $(window).height() && (location.href = "../mobile/pcviewer.php?id=" + _pid_);
var viewer_isvip = !1, thum, versiondata;
$.ajax({
    type: "get", url: get_version_url, dataType: "json", data: {id: _pid_}, success: function (json) {
        settitle(json), getversion(json)
    }, error: function () {
    }
});