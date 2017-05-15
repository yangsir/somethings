var domain_url  = 'http://www.yanghehong.cn/meibao/';
//var domain_url  = 'http://www.test.com/meibao/';
var share_title = '美包包有奖游戏，动动手指，美的真皮手袋让你带回家，走起吧！';
var share_link  = domain_url + 'index.php/Credit';
var share_imgurl = domain_url + 'front/images/sy_bg02.jpg';
var callback_url = domain_url + 'index.php/Credit/shareDoubleCredit';

//weixin share funciton
function WxShareTimeline() {
    wx.onMenuShareTimeline({
        title: share_title,
        link: share_link,
        imgUrl: share_imgurl,
        success: function (res) {
            $.post(callback_url,{},function(data) {
              alert(data);
            });
        },
        cancel: function (res) {
          alert('取消不会获得双倍积分哦~');
        }
    });
}

function WxShareAppMessage(ucode) {
    wx.onMenuShareAppMessage({
        title: share_title,
        link: share_link+'?share_ucode='+ucode,
        imgUrl: share_imgurl,
        success: function (res) {}
    });
}

//微信拉取卡卷
function WxaddCard(card) {
    var request_url =domain_url + 'index.php/Credit/cardReduce';
    wx.addCard({
      cardList: [{
        cardId: card.card_id,
        cardExt: '{"code": "", "openid": "'+card.openid+'", "timestamp": "'+card.timestamp+'", "signature":"'+card.signature+'"}'
      }],
      success: function (res) {
        $.post(request_url,{card_id:card.card_id},function(msg) {
            if(msg) window.location.href=domain_url+"front/dhcg.html";
        });
        //alert('你好' + JSON.stringify(res.cardList));
      }
    });
}
