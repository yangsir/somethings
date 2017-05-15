/**
 * Created by tody on 15-4-13.
 * qq:53050855
 */
;(function($){
    $.fn.toolTip=function(option){
        /**
         * 错误提示信息框
         * @type {string}
         */
        var w=$(window);
        var defaults={
            css:{},
            msg:"错误提示",
            delay:3500,
            arrow:false
        };
        typeof option=="object"&&$.extend(defaults,option);
        typeof option=="string"&&(defaults.msg=option);
        $(".toolTip_error_msg").size()==0&&$("body").append('<div class="toolTip_error_msg"><div class="arrow"></div><p>错误提示</p></div>');
        var tip=$(".toolTip_error_msg");
        defaults.arrow?tip.find(".arrow").removeClass("hide"):tip.find(".arrow").addClass("hide");
        tip.css({"top":w.scrollTop()+w.height()*0.8+"px","visibility":"hidden"}).find("p").html(defaults.msg);
        tip.css(defaults.css).show();
        tip.css({"visibility":"visible","left":(w.width()-tip[0].offsetWidth)/2});
        setTimeout(function(){
            tip.hide();
        },defaults.delay);
    };
})(Zepto);

(function() {
    var t = {
        _audioNode: $(".js-audio"),
        _audio: null,
        _audio_val: !0,
        _videoNode: $(".j-video"),
        audio_init: function () {
            if (!(t._audioNode.length <= 0)) {
                var e = {
                    loop: !0,
                    preload: "auto",
                    src: this._audioNode.attr("data-src")
                };
                this._audio = new Audio();
                for (var i in e) e.hasOwnProperty(i) && i in this._audio && (this._audio[i] = e[i])
            }
        },
        audio_addEvent: function () {
            if (!(this._audioNode.length <= 0)) {
                var e = (this._audioNode.find(".txt_audio"), this._audioNode.find(".js-music-btn")),
                    i = this,
                    n = /android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()),
                    s = n ? "touchstart" : "click";
                $(this._audioNode).on(s, function () {
                    i.audio_contorl()
                }), $(this._audio).on("play", function () {
                    t._audio_val = !1, e.removeClass("ui-music-off")
                }), $(this._audio).on("pause", function () {
                    t._audio_val = !0, e.addClass("ui-music-off")
                })
            }
        },
        audio_contorl: function () {
            t._audio_val ? t.audio_play() : t.audio_stop()
        },
        audio_play: function () {
            t._audio_val = !1, t._audio && t._audio.play()
        },
        audio_stop: function () {
            t._audio_val = !0, t._audio && t._audio.pause()
        },
        video_init: function () {
            this._videoNode.each(function () {
                var t = {
                        controls: "controls",
                        preload: "none",
                        width: $(this).attr("data-width"),
                        height: $(this).attr("data-height"),
                        src: $(this).attr("data-src")
                    }, e = $('<video class="f-hide"></video>')[0],
                    i = $(this).find(".img");
                for (var n in t) t.hasOwnProperty(n) && n in e && (e[n] = t[n]), this.appendChild(e);
                $(e).on("play", function () {
                    i.hide(), $(e).removeClass("f-hide")
                }), $(e).on("pause", function () {
                    i.show(), $(e).addClass("f-hide")
                })
            })
        },
        media_control: function () {
            this._audio && ($("video").length <= 0 || ($(this._audio).on("play", function () {
                $("video").each(function () {
                    this.paused || this.pause()
                })
            }), $("video").on("play", function () {
                t._audio_val || t.audio_contorl()
            }), $("video").on("pause", function () {
                t._audio_val && t.audio_contorl()
            })))
        },
        media_init: function () {
            this.audio_init(), this.video_init(), this.audio_addEvent(), this.media_control()
        }
    };
    window.Media = t
})(window);


$(function(){
    FastClick.attach(document.body);//引入fastClick.js,避免tap点击遮罩层时影响到底层

    /**
     * 加点击效果
     */
    $(".btn,.d_close,.pause,.btn_a").live("touchstart",function(){
        var me=$(this).addClass("on");
        setTimeout(function(){
            me.removeClass("on");
        },100)
    });

    try {
        window.Media.media_init();
        Media.audio_play();
    } catch (e) {}
    $(window).on("beforeunload", function () {
        window.Media.audio_stop();
    });
    $("body").one('touchstart', function(e){
        Media.audio_play();
    });

    // 控制声音
    $(".speaker").on("click",function(){
        var me=$(this);
        if(me.hasClass("speaker_muted")){
            $(".speaker").removeClass("speaker_muted");
            Media.audio_play();
        }else{
            $(".speaker").addClass("speaker_muted");
            Media.audio_stop();
        }
    });
});

/**
 * 输入参数名得到URL中参数对应的值
 * @param key     参数名
 * @returns {*}   参数对应的值，没有找到返回""
 */
function getParamFormURL(key){
    var search=location.search,paramAry=[],value="";
    if(!search||!new RegExp(key,"g").test(search)) return "";
    paramAry=search.replace("?","").split("&");
    for(var i= 0,len=paramAry.length;i<len;i++){
        var k, v,ary;
        ary=paramAry[i].split("=");
        k=ary[0];
        v=ary[1];
        if(k==key){
            value=v;
            break;
        }
    }
    return value;
}
/**
 * 居中显示对话框
 * @param $dom
 */
function showDialog($dom){
    $(".full_mask").show();
    $dom.show().css("visibility","hidden");
    var h=$dom.height();
    h=($(window).height()-h)/2;
    h=h<=0?0:h;
    $dom.css({"top":h,"visibility":"visible"});
}

function showError(msg){
    $.fn.toolTip(msg);
      setTimeout(function(){
        location.href=domain_url+"index.php/Weixin/Credit";
      },2000);
}

function fx(id){
    $("#"+id).show();
    setTimeout(function(){
        $(".fx").hide();
    },2000);
}
function timelineTips() {
    //请点击右上角分享至朋友圈~'
    fx("fxpyj");
}
function appmessageTips() {
    //请点击右上角分享给朋友~'
    fx("fxpy");
}

$(".fx").on("click",function(){
    $(this).hide();
});
