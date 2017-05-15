function disablesystemcall() {
    document.onmousemove = function (a) {
        var b = a || event;
        b.returnValue = system_call
    }, document.ontouchmove = function (a) {
        var b = a || event;
        b.returnValue = system_call
    };
    document.onkeydown = function (a) {
        var b = a || window.event || arguments.callee.caller.arguments[0];
        if (console.log(b.keyCode), b && 27 == b.keyCode, b && 113 == b.keyCode, b && 46 == b.keyCode) {
            if (delete_lock)return;
            delete_call && delete_call.call(this)
        }
        if (b && 8 == b.keyCode) {
            if (delete_lock)return;
            delete_call && delete_call.call(this)
        }
        b && 38 == b.keyCode && upkey_call && upkey_call.call(this), b && 40 == b.keyCode && downkey_call && downkey_call.call(this), b && 37 == b.keyCode && leftkey_call && leftkey_call.call(this), b && 39 == b.keyCode && rightkey_call && rightkey_call.call(this)
    }
}
function randomString(a) {
    a = a || 32;
    var b = "ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678", c = b.length, d = "";
    for (i = 0; i < a; i++)d += b.charAt(Math.floor(Math.random() * c));
    return d
}
function convertImgToBase64(a, b, c, d) {
    var e = document.createElement("CANVAS"), f = e.getContext("2d"), g = new Image;
    g.crossOrigin = "Anonymous", g.onload = function () {
        e.height = g.height, e.width = g.width, f.drawImage(g, 0, 0);
        var a = e.toDataURL(d || "image/png"), h = new Array;
        h.id = b, h.data = a, c.call(this, h), e = null
    }, g.src = a
}
function makapreloader() {
    this.queue = new Array, this.endnum = 0, this.curnum = 0, this.adding_i = 0, this.cdnlock = 5e3, this.usecdn = !0, $("#persent").html("0%")
}
function makapageeffetc() {
}
function makacard(a, b, c, d) {
    this.test = d;
    this.data = a, this.containner = b, this.current = 0, c && (this.music = c, this.musicsrc = "" + c.id), this.lastcallback = function () {
    }, this.firstcallback = function () {
    }, this.init(a)
}
function makapage(a) {
    this.data = a, this.init(a)
}
function makaelement(a, b) {
    this.data = a, this.isresize = !1, this.iscrop = !1, this.istyping = !1, this.page = b, this.toolcallback = new Array, this.lazy = null, this.loaded = !1, this.url = null, this.init(a)
}
function makaajax(a, b) {
    this.url = project_get_url, this.pid = a, this.jsoncallback = b
}
function getversion(a) {
    a.user_thum && $("#user_thum").attr({src: a.user_thum}), a.author && $("#authorname").html(a.author), viewer_version_data = a, viewer_isvip = 0 == a.vip || "false" == a.vip || void 0 == a.vip || "free_user" == a.vip || "wemedia" == a.vip ? !1 : !0, viewer_isvip || $("#supad").hide().html("使用./创作").fadeIn(), viewer_version = a.p_version, editor_makaproject.jsonp(function () {
    })
}
function getjsonsuccess(a) {
    viewer_pubdata = JSON.parse(a.pubdata),
    a.data.json && (a.data.pdata = JSON.parse(a.data.json));
    var pages = [];
    var bgs = a.data.bgs.split(",");
    for(var i=0;i< bgs.length; i++){
        var page = {
            bgcolor: " rgba(255,255,255,1)",
            bgpic: bgs[i],
            bgpicheight: "auto",
            bgpicleft: "0",
            bgpictop: "0",
            bgpicwidth: "640",
            content: [],
            effect: "cubedown",
            elementData: {buttonData: [], chartData: "", formData: "", imgData: [], magicData: "", pButtonData: [], shapeData: [], slideData: [], textData: []},
            justShoweffect: "pt-page-rotateCubeTopIn",
            lock: false,
            opacity: 1,
            showeffect: "pt-page-rotateCubeTopIn"
        };
        pages.push(page);
    }
    a.data.pdata = pages;
    a.status;
    "" == a.thum && (a.thum = ""), view_thum = a.thum ? a.thum : "";
    var b = a.thum ? a.thum.replace("", "") : "", c = a.thum ? a.thum.replace("", "") : "", d = a.content;
    /*
    initweixin({
        appid: versiondata.wx.appId,
        timestamp: versiondata.wx.timestamp,
        nonceStr: versiondata.wx.nonceStr,
        signature: versiondata.wx.signature,
        title: a.title,
        desc: d,
        link: versiondata.wx.link,
        imgUrl: c
    }),
    */
        $("#cover").attr("src", b), editor_makacard = a.data.music ? new makacard(a.data.pdata, $("#makadym"), a.data.music, a.test) : new makacard(a.data.pdata, $("#makadym"), null, a.test),
        editor_makacard.start(0),
        editor_makacard.fitscreen(1);
    var e = new Image;
    e.src = b, viewer_preloader.loadstart();
    var f = ($(window).height(), new Audio);
    editor_makacard.musicsrc && "" != editor_makacard.musicsrc ? ($("#musiccontroloff").hide(), $("#musiccontrolon").show(), f.loop = !0, f.autoplay = !0, f.src = editor_makacard.musicsrc, f.play(), $("body").on("touchstart", function () {
        f.paused && ($("body").unbind(), f.loop = !0, f.autoplay = !0, f.src = editor_makacard.musicsrc, f.play(), $("#musiccontroloff").hide(), $("#musiccontrolon").show())
    }), $("#musiccontroloff").on("click", function () {
        $("#musiccontroloff").hide(), $("#musiccontrolon").show(), f.play(), $("body").on("touchstart", function () {
            $("body").unbind(), f.play()
        })
    }), $("#musiccontrolon").on("click", function () {
        $("#musiccontroloff").show(), $("#musiccontrolon").hide(), f.pause(), $("body").unbind()
    })) : ($("#musiccontroloff").hide(), $("#musiccontrolon").hide())
}
function pshape(a, b) {
    this.data = a, this.page = b, this.toolcallback = new Array, this.init(this.data), this.effectleng = 0
}
function ptext(a, b) {
    this.data = a, this.isresize = !1, this.iscrop = !1, this.istyping = !1, this.page = b, this.toolcallback = new Array, this.lazy = null, this.loaded = !1, this.url = null, this.init(a), this.effectleng = 0
}
function ppictrue(a, b) {
    this.data = a, this.page = b, this.toolcallback = new Array, this.init(this.data), this.effectleng = 0
}
function eleform(a, b) {
    this.data = a, this.isresize = !1, this.iscrop = !1, this.istyping = !1, this.page = b, this.toolcallback = new Array, this.lazy = null, this.loaded = !1, this.url = null, this.init(a), this.effectleng = 0
}
function makaswiper(a) {
    this.data = a, this.init(a)
}
function elechart(a, b) {
    this.data = a, this.isresize = !1, this.iscrop = !1, this.istyping = !1, this.page = b, this.toolcallback = new Array, this.lazy = null, this.loaded = !1, this.url = null, this.init(a), this.effectleng = 0
}
function elechartpie(a, b) {
    this.data = a, this.isresize = !1, this.iscrop = !1, this.istyping = !1, this.page = b, this.toolcallback = new Array, this.lazy = null, this.loaded = !1, this.url = null, this.init(a), this.effectleng = 0
}
var project_get_url = "", home_url = "../", url = window.document.location.href.toString(), get_version_url = "./data/v.json", get_json_url = "../json/", re = /\w{8}/, _pid_ = url.match(re)&&url.match(re)[0].substr(0, 8)||"123", report_url = "../ext/report/ajax_fresh?id=" + _pid_, system_call = !1, delete_call, delete_lock = !1, upkey_call, downkey_call, leftkey_call, rightkey_call;
makapreloader.prototype.chooseroad = function () {
    return
}, makapreloader.prototype.addpic = function (a, b, c) {
    if (void 0 != b) {
        var d = new Object;
        d.tar = a, d.src = b, d.page = c, this.queue.push(d), 0 == c && this.endnum++
    }
}, makapreloader.prototype.loadstart = function () {
    $(".wthum").hide(), $("#covert").show(), $("#cover").fadeIn(), $("#arrow").fadeIn();
    var a = this;
    a.chooseroad(), this.endnum > this.queue.length && (this.endnum = this.queue.length - 1), this.adding = setInterval(function () {
         0 == a.endnum && (a.curnum++, a.endnum++), a.adding_i / 360 > a.curnum / a.endnum || (parseInt(100 * a.curnum / a.endnum) - a.adding_i / 3.6 > 10 ? a.adding_i += 5 : a.adding_i++, a.adding_i >= 0 ? ($("#persent").html("100%"), clearInterval(a.preloadtiming), $(".pie2").css({rotate: "0deg"}).show(), a.loadend()) : 0 == a.endnum ? ($("#persent").html("100%"), $(".pie2").css({rotate: "0deg"}).show(), clearInterval(a.preloadtiming)) : (a.adding_i <= 180 ? ($(".pie1").css({rotate: a.adding_i + "deg"}).show(), $(".pie2").css({background: "white"}).show()) : a.adding_i > 180 && a.adding_i <= 360 && ($(".pie1").css({rotate: "180deg"}).show(), $(".pie2").css({background: "rgba(175,228,221,1)"}), $(".pie2").css({rotate: a.adding_i + "deg"}).show()), $("#persent").html(parseInt(a.adding_i / 3.6) + "%")))
    }, 25), this.loadone(0)
}, makapreloader.prototype.loadone = function (a) {
    if (a > this.curnum && (this.curnum = a), !(a >= this.queue.length)) {
        var b = this;
        if (!this.queue[a].src || 0 == this.queue[a].src)return void b.loadone(a + 1);
        a == this.endnum || a == this.queue.length && a < this.endnum;
        var c = new Image;
        this.queue[a].src.replace("", ""), c.src = this.queue[a].src,  c.complete ? (b.loadone(a + 1), b.queue[a].tar.attr("src", b.queue[a].src)) : (c.onload = function () {
            b.loadone(a + 1), b.queue[a].tar.attr("src", b.queue[a].src), console.log("load onload" + a + "/" + b.queue.length + " 2src" + b.queue[a].src)
        }, c.onerror = function () {
            console.log("load error" + a + "/" + b.queue.length + " 2src" + b.queue[a].src), b.loadone(a + 1)
        })
    }
}, makapreloader.prototype.loadend = function () {
    clearInterval(this.ifcdn), clearInterval(this.adding), clearInterval(this.preloadtiming), setTimeout(function () {
        $("#preloadarea").fadeOut(),
            //editor_makacard.start(0),
            editor_makacard.initswipeaction()
    }, 500)
};
var cubedown = ["pt-page-rotateCubeTopOut", "pt-page-rotateCubeTopIn", "pt-page-rotateCubeBottomOut", "pt-page-rotateCubeBottomIn", "swipedown", "swipeup"], cubeup = ["pt-page-rotateCubeBottomOut", "pt-page-rotateCubeBottomIn", "pt-page-rotateCubeTopOut", "pt-page-rotateCubeTopIn", "swipeup", "swipedown"], cubeleft = ["pt-page-rotateCubeLeftOut", "pt-page-rotateCubeLeftIn", "pt-page-rotateCubeRightOut", "pt-page-rotateCubeRightIn", "swiperight", "swipeleft"], cuberight = ["pt-page-rotateCubeRightOut", "pt-page-rotateCubeRightIn", "pt-page-rotateCubeLeftOut", "pt-page-rotateCubeLeftIn", "swipeleft", "swiperight"], flipup = ["pt-page-flipOutTop", "pt-page-flipInBottom pt-page-delay500", "pt-page-flipOutBottom ", "pt-page-flipInTop pt-page-delay500", "swipedown", "swipeup"], moveup = ["pt-page-moveToTop", "pt-page-moveFromBottom", "pt-page-moveToBottom", "pt-page-moveFromTop", "swipedown", "swipeup"], pushup = ["pt-page-rotatePushTop", "pt-page-moveFromBottom pt-page-delay100", "pt-page-rotatePushBottom", "pt-page-moveFromTop pt-page-delay100", "swipedown", "swipeup"], news = ["pt-page-rotateOutNewspaper", "pt-page-rotateInNewspaper pt-page-delay500", "pt-page-rotateOutNewspaper", "pt-page-rotateInNewspaper pt-page-delay500", "swipedown", "swipeup"], scaleup = ["pt-page-scaleDown", "pt-page-scaleUpDown pt-page-delay300", "pt-page-scaleDownUp", "pt-page-scaleUp pt-page-delay300", "swipedown", "swipeup"], roomup = ["pt-page-rotateRoomTopOut pt-page-ontop", "pt-page-rotateRoomTopIn", "pt-page-rotateRoomBottomOut pt-page-ontop", "pt-page-rotateRoomBottomIn", "swipedown", "swipeup"], carouup = ["pt-page-rotateCarouselTopOut pt-page-ontop", "pt-page-rotateCarouselTopIn", "pt-page-rotateCarouselBottomOut pt-page-ontop", "pt-page-rotateCarouselBottomIn", "swipedown", "swipeup"], fall = ["pt-page-rotateFall pt-page-ontop", "pt-page-scaleUp", "pt-page-scaleDown", "pt-page-moveFromBottom", "swipeup", "swipedown"], toup = ["pt-page-moveToTopSlow", "noeffect", "noeffect", "pt-page-moveFromTopSlow", "swipeup", "swipedown"], effects = {
    cubedown: cubedown,
    cubeup: cubeup,
    cubeleft: cubeleft,
    cuberight: cuberight,
    flipup: flipup,
    moveup: moveup,
    pushup: pushup,
    scaleup: scaleup,
    roomup: roomup,
    carouup: carouup,
    fall: fall,
    news: news,
    toup: toup
};
makacard.prototype.init = function (a) {
    this.pages = new Array;
    for (var b in a) {
        var c = new makapage(a[b]);
        this.pages.push(c), this.containner.prepend(c.page)
    }
    a ? 1 == a.length && ($("#arrow").fadeOut(), 1 != viewer_isvip && $("#makaad").fadeIn()) : $("#arrow").fadeOut();
    for (var b in viewer_version_data.wemedia_link_list) {
        var d = $('<div class="tjxm" link="' + viewer_version_data.wemedia_link_list[b].link + '"></div>'), e = $('<img class="tjthum" src="' + viewer_version_data.wemedia_link_list[b].thumurl + '"/>'), f = $(' <div class="tjtitle">' + viewer_version_data.wemedia_link_list[b].title + "</div>");
        d.on("click", function () {
            window.location.href = $(this).attr("link")
        }), d.append(e), d.append(f), $("#tjlistcontent").append(d)
    }
    $("#authorname").on("click", function () {
        window.location.href = viewer_version_data.wemedia_url
    })
}, makacard.prototype.addele = function (a, b) {
    this.pages[this.current].addele(a, b), this.update()
}, makacard.prototype.update = function () {
    this.thumbar.update(this.current)
}, makacard.prototype.removepage = function (a) {
    1 != this.pages.length && (this.current == a && this.prevpage(), this.pages[a].page.remove(), this.pages = this.pages.slice(0, a).concat(this.pages.slice(a + 1, this.length)), this.thumbar.removepage(a))
}, makacard.prototype.pagemoveloc = function (a) {
    var b = a[0], c = a[1];
    if (b != c)if (c == this.current && (c == this.pages.length - 1 ? this.current-- : this.current++), 0 != c ? this.pages[c].page.after(this.pages[b].page) : this.pages[c].page.before(this.pages[b].page), c > b) {
        var d = this.pages.slice(0, b), e = this.pages.slice(b + 1, c + 1), f = this.pages.slice(c + 1, this.pages.length), g = d.concat(e.concat(this.pages[b]), f);
        this.pages = g
    } else {
        var d = this.pages.slice(0, c), e = this.pages.slice(c, b), f = this.pages.slice(b + 1, this.pages.length), g = d.concat(this.pages[b]).concat(e, f);
        this.pages = g
    }
};
var pagelock = !0;
makacard.prototype.initswipeaction = function () {
    var a = this;
    var onRender = function() {
        onPageRender(a);
    };
    this.containner.unbind(), this.containner.on("swipeup", function () {
        pagelock && (pagelock = !1, setTimeout(function () {
            pagelock = !0
        }, 750), a.nextpage()),onRender()
    }).on("swipedown", function () {
        pagelock && (pagelock = !1, setTimeout(function () {
            pagelock = !0
        }, 750), a.prevpage()),onRender()
    });
    onRender();
}, makacard.prototype.start = function (a) {
    if (this.pages[a] && (this.pages[a].page.show(), this.pages[a].showeffect(), this.current = a, 0 == editor_editingmode))for (var b in this.pages)this.pages[b].loadelespic(b);
}, makacard.prototype.nextpage = function () {
    if (this.current == this.pages.length - 1)return viewer_version_data.specialevent && 1 == viewer_version_data.specialevent.status ? ($("#sppageframe").attr("src") || $("#sppageframe").attr({src: viewer_version_data.specialevent.url}), void $("#sppage").css({y: "100%"}).show().transition({y: "0%"})) : void("wemedia" == viewer_version_data.vip && ($("#wemediaad").show().transition({y: "-100%"}), $("#wemediaad").unbind(), $("#wemediaad").on("swipedown", function () {
        $("#wemediaad").transition({y: "0%"})
    })));
    this.current == this.pages.length - 2 && ($("#arrow").fadeOut(), 1 != viewer_isvip && $("#makaad").fadeIn());
    var a = this.pages[this.current].page, b = this.pages[this.current + 1].page, c = a.attr("effect"), d = effects[c][1], e = effects[c][0];
    b.show().removeClass();
        a.removeClass();
        a.addClass("page " + e).fadeOut("slow");
//        $(".pageactive").fadeOut("slow");
        $(".pageactive").removeClass("pageactive");
        b.addClass("page active " + d).fadeIn("fast").addClass("pageactive");
        this.pages[this.current + 1].showelement();
        this.current++;
}, makacard.prototype.prevpage = function () {
    if(this.current===this.data.length-1) return;//最后一页答题不可向上滑动
    if ( 0 != this.current) {
        $("#arrow").fadeIn();
        var a = this.pages[this.current].page, b = this.pages[this.current - 1].page, c = b.attr("effect");
        if (c)var d = effects[c][3], e = effects[c][2]; else var d = null, e = null;
        a.removeClass().addClass("page " + e).fadeOut("fast");
        b.removeClass().addClass("page active " + d).css({opacity: 1}).fadeIn("slow").addClass("pageactive");
        this.pages[this.current - 1].showelement();
        this.current--;
    }
}, makacard.prototype.topage = function (a) {
}, makacard.prototype.fitscreen = function (a) {
    var b = a * $(window).width() / 640, c = a * $(window).height() / 1010;
    this.sc = b > c ? b : c, this.scale = a, pwidth = 640 * this.sc, pheight = 1012 * this.sc;
    $(window).width() / 2 - .5 * pwidth, $(window).height() / 2 - .5 * pheight;
    $("#sppageframe").css({scale: this.sc});
    for (var d in this.pages) {
        var e = this.pages[d];
        e.fitscreen(a)
    }
};
var viewer_sc;
makapage.prototype.init = function (a) {
    var b = a;
    if (this.pageeffect = b.effect ? b.effect : "pushup", this.bgcolor = b.bgcolor ? b.bgcolor : "black", 0 == editor_editingmode) {
        if (b.bgpic && "string" == typeof b.bgpic) {
            var c = /id\/\d{1,6}/, d = b.bgpic;
            if (d.match(c))var e = d.match(c)[0].substr(3);
            this.bgpic = e ? b.bgpic.replace("", "") : b.bgpic.replace("", "")
        }
    } else this.bgpic = b.bgpic ? b.bgpic : null;
    this.bgpictop = b.bgpictop ? b.bgpictop : 0;
    this.bgpicleft = b.bgpicleft ? b.bgpicleft : 0;
    this.bgpicheight = b.bgpicheight ? b.bgpicheight : 0;
    this.bgpicwidth = b.bgpicwidth && "null" != b.bgpicwidth ? b.bgpicwidth : "auto";
    this.bgcropx = b.bgcropx ? b.bgcropx : 0;
    this.bgcropy = b.bgcropy ? b.bgcropy : 0;
    this.bgcropw = b.bgcropw ? b.bgcropw : 0;
    this.bgcroph = b.bgcroph ? b.bgcroph : 0;
    this.content = b.content ? b.content : null;
    this.opacity = b.opacity ? b.opacity : 1;
    this.rotate = b.rotate ? b.rotate : 0;
    this.blur = b.blur ? b.blur : 0;
    this.page = $('<div class="page"  effect="' + this.pageeffect + '"> </div>');
    this.pagebg = $('<div class="pagebg" style="overflow:hidden;background:' + this.bgcolor + '"></div>');
    this.picscale = b.bgscale ? b.bgscale : 1;
    if (this.bgpic) {
        this.pagebgimg = $('<img src="' + this.bgpic + '" style="position:absolute;top:' + this.bgpictop + "px;left:" + (parseInt(this.bgpicleft) - 1) + "px;width:" + (parseInt(this.bgpicwidth) + 2) + 'px; " />'), this.pagebgimg.css({scale: this.picscale});
        var f = this;
        if (1 != this.picscale) {
            var g = new Image;
            g.src = b.bgpic, g.complete ? $("#console").append("<br>ratiopp" + parseInt(f.bgpictop - f.picscale * g.naturalHeight / 2 * (640 / g.naturalWidth) + g.naturalHeight / 2 * (640 / g.naturalWidth)) + "px") : g.onload = function () {
                $("#console").append("<br>ratio" + parseInt(f.bgpictop - f.picscale * g.naturalHeight / 2 * (640 / g.naturalWidth) + g.naturalHeight / 2 * (640 / g.naturalWidth)) + "px"), console.log("ratio" + g.naturalWidth), f.pagebgimg.css({top: parseInt(f.bgpictop - f.picscale * g.naturalHeight / 2 * (640 / g.naturalWidth) + g.naturalHeight / 2 * (640 / g.naturalWidth)) + "px"}), f.pagebgimg.css({left: parseInt(f.bgpicleft - f.picscale * g.naturalWidth / 2 * (640 / g.naturalWidth) + g.naturalWidth / 2 * (640 / g.naturalWidth)) + "px"}), f.pagebgimg.css({width: f.picscale * (parseInt(f.bgpicwidth) + 2)}), f.pagebgimg.css({scale: 1}), console.log("bgpic" + f.bgpictop - f.picscale * g.naturalHeight + g.naturalHeight + "px")
            }
        }
    } else this.pagebgimg = $('<img style="position:absolute;top:' + this.bgpictop + "px;left:" + this.bgpicleft + 'px;width:0px; " />');
    this.pagebgimg.css({rotate: this.rotate}), this.pagebgimg.css({"-webkit-filter": "blur(" + this.blur + "px)"}), this.elearray = [], this.ismove = !0, this.pagebg.append(this.pagebgimg), this.pagecontent = $('<div class="pagecontent"></div>');
    for (var h in this.content)if (console.log("eletype " + this.content[h].type), console.log("eleleft " + this.content[h].left), console.log("eletop " + this.content[h].top), console.log("elewidth " + this.content[h].w), console.log("eleheight " + this.content[h].h), !(this.content[h].left > 1200 || this.content[h].left < -1200 || this.content[h].top > 1200 || this.content[h].top < -1200))if ("pshape" == this.content[h].type) {
        var i = new pshape(this.content[h], this);
        this.elearray.push(i), this.pagecontent.append(i.get())
    } else if ("eleform" == this.content[h].type) {
        var i = new eleform(this.content[h], this);
        this.elearray.push(i), this.pagecontent.append(i.get())
    } else if ("ptext" == this.content[h].type) {
        var i = new ptext(this.content[h], this);
        this.elearray.push(i), this.pagecontent.append(i.get())
    } else if ("swiper" == this.content[h].type) {
        var i = new k(this.content[h], this);
        this.elearray.push(i), this.pagecontent.append(i.get())
    } else if ("charts" == this.content[h].type) {
        if (console.log(this.content[h]), !this.content[h].content)continue;
        if ("pie" == this.content[h].content.type) {
            console.log(this.content[h]);
            var i = new elechartpie(this.content[h], this);
            this.elearray.push(i), this.pagecontent.append(i.get())
        } else {
            var i = new elechart(this.content[h], this);
            this.elearray.push(i), this.pagecontent.append(i.get())
        }
    } else {
        var i = new makaelement(this.content[h], this);
        this.elearray.push(i), this.pagecontent.append(i.get())
    }
    if (1 == op) {
        var j = new Object;
        j.top = 200, j.left = 0, j.height = 700, j.width = 640;
        var i = new elechartpie(j, this);
        this.elearray.push(i), this.pagecontent.append(i.get());
        var j = new Object;
        j.top = 200, j.left = 0, j.height = 700, j.width = 640;
        var i = new elechart(j, this);
        this.elearray.push(i), this.pagecontent.append(i.get());
        var k = new Object;
        k.top = 10, k.left = 10, k.height = 500, k.width = 500, k.bgcolor = "rgba(200,200,200,0.4)", k.show = "fadeIn", k.delay = 0, k.speed = 1e3, k.slides = new Array;
        var l = new Array, m = new Array, n = new Object, o = new Object;
        n.eletype = "pic", n.top = 0, n.left = 0, n.width = 500, n.height = 300, n.inw = 500, n.intop = 0, n.inleft = 0, n.picid = "36595", o.eletype = "text", o.top = 300, o.left = 0, o.width = 500, o.con = "测试测试asdasdasdsadasd", o.ftsize = "40px", o.ftcolor = "green", o.textalign = "center", l.push(n), l.push(o);
        var p = jQuery.extend(!0, {}, n);
        n.picid = "365953", m.push(p), m.push(o), k.slides.push(l), k.slides.push(m);
        var i = new makaswiper(k, this);
        this.elearray.push(i), this.pagecontent.append(i.get())
    }
    this.page.append(this.pagebg), this.page.append(this.pagecontent);
    this.update()
};
var op = 0;
makapage.prototype["delete"] = function () {
}, makapage.prototype.get = function () {
    return this.page
}, makapage.prototype.showeffect = function (a) {
    a || (a = this.pageeffect), this.page.removeClass(effects[a][0]), this.page.removeClass(effects[this.pageeffect][0]), this.page.addClass(effects[a][1]), this.showelement()
}, makapage.prototype.update = function () {
    this.pagebgimg.css({opacity: this.opacity}), this.pagebg.css({background: this.bgcolor}), this.pagebgimg.attr({src: this.bgpic}), this.pagebgimg.css({left: this.bgpicleft}), this.pagebgimg.css({top: this.bgpictop}), this.pagebgimg.css({"-webkit-filter": "blur(" + this.blur + "px)"}), this.pagebgimg.css({rotate: this.rotate}), editor_makacard && editor_makacard.update()
}, makapage.prototype.showelement = function () {
    for (var a in this.elearray) {
        var b = this.elearray[a];
        b.clean()
    }
    for (var a in this.elearray) {
        var b = this.elearray[a];
        b.showeffect()
    }
}, makapage.prototype.loadelespic = function (a) {
    this.bgpic && viewer_preloader.addpic(this.pagebgimg, this.bgpic, a);
    for (var b in this.elearray) {
        var c = this.elearray[b];
        c.loadpic(a)
    }
}, makapage.prototype.fitscreen = function (a) {
    var b = a * $(window).width() / 636,
        c = a * $(window).height() / 1004;
    var bb = b / c < 1.5 ? b : c;
    $(window).width() > $(window).height() ? (this.sc = c > b ? b : c, this.msc = c > b ? b : c) : (this.sc = b > c ? b : c, this.msc = c > b ? b : c), viewer_sc = this.msc, pwidth = 640 * this.sc, pheight = 1012 * this.sc;
    var d = $(window).width() / 2 - .5 * pwidth, e = $(window).height() / 2 - .5 * pheight;
    editor_preview || (this.page.css({width: pwidth, height: pheight}), this.page.css({
        left: d,
        top: e
    }), this.pagecontent.css({scale: this.msc}), this.pagebg.css({scale: bb + ',' + c})), editor_preview && (this.page.css({
        left: 0,
        top: 0
    }), this.pagecontent.css({scale: a}), this.pagebg.css({scale: a}))
}, makaelement.prototype.init = function (a) {
    this.top = a.top, this.left = a.left, this.width = a.w, this.height = a.h, this.bgcolor = a.bgcolor, this.opacity = a.opacity, this.eletype = a.type, this.con = a.con, this.show = a.show, this.speed = a.speed, this.delay = a.delay, this.tl = a.tl ? a.tl : 0, this.picid = a.picid ? a.picid : 0, this.shape = a.shape ? a.shape : 0, this.loopeffect = a.loopeffect ? a.loopeffect : 0, this.shapeeffect = a.shapeeffect ? a.shapeeffect : "noeffect", this.frame = a.frame ? a.frame : 0, this.stylecolor = a.stylecolor ? a.stylecolor : "rgba(0,0,0,0)", this.styleopacity = a.styleopacity ? a.styleopacity : 0, console.log("init" + this.picid), this.borderradius = a.borderradius ? a.borderradius : 0, this.boxshadow = a.boxshadow ? a.boxshadow : -1, this.elementout = $('<div class="elementout " style="z-index:0;height:0;width:0px;position:relative;opacity:' + this.opacity + ";-webkit-transform:rotate(" + this.rotate + 'deg);"></div>');
    var b = this.width > this.height ? this.width : this.height, c = this.borderradius / 100 * b;
    if (this.element = $('<div class="element" style="z-index:0;border-radius:' + c + "px;top:" + this.top + "px;left:" + this.left + "px;width:" + this.width + "px;height:" + this.height + "px;background:" + this.bgcolor + '" ></div>'), 0 != this.shape && this.shapearea.css({"-webkit-mask-image": this.shape}), this.borderstyle = a["border-style"] ? a["border-style"] : "none", this.bordercolor = a["border-color"] ? a["border-color"] : "black", this.borderwidth = a["border-width"] ? a["border-width"] : "0px", this.rotate = a.rotate ? a.rotate : 0, "pic" == this.eletype) {
        if (this.defaultw = a.defaultw ? a.defaultw : 0, this.defaulth = a.defaulth ? a.defaulth : 0, this.inw = a.inw, this.inh = "auto", this.intop = parseInt(a.intop), this.inleft = parseInt(a.inleft), 0 != this.picid && this.picid) {
            var d = 100;
            this.inw < 100 ? d = 100 : 0 != this.defaultw && this.defaultw ? d = 100 * parseInt(this.inw / this.defaultw) : null == this.inw ? (this.inw = "auto", d = 100) : d = 100, d > 100 && (d = 100), 0 == d && (d = 100), console.log("def" + this.defaultw + " inw " + this.inw + "scale " + d), this.con = this.picid
        } else this.lazy = this.data.con, this.con = this.data.con;
        this.contentarea = $('<img src="' + this.con + '" class="oldele"  style="position:absolute;width:' + this.inw + "px;top:" + this.intop + "px;left:" + this.inleft + 'px" />')
    } else("text" == this.eletype || "btn" == this.eletype) && ("btn" == this.eletype && (this.url = a.url ? a.url : ""), this.element.css({"box-shadow": "1px 1px " + this.boxshadow + "px rgb(40,40,40)"}), this.element.css({overflow: "hidden"}), this.element.css({"border-style": this.borderstyle}), this.element.css({"border-width": this.borderwidth}), this.element.css({"border-color": this.bordercolor}), this.element.css({height: "auto"}), this.fontbold = a.ftbold ? a.ftbold : !1, this.fontcolor = a.ftcolor ? a.ftcolor : "black", this.fontitalic = a.itc ? a.itc : !1, this.fontsize = a.ftsize ? a.ftsize : "20px", this.udl = a.udl ? a.udl : !1, this.lineheight = a.lineheight ? a.lineheight : 2, this.textalign = a.textalign ? a.textalign : "none", this.textvalign = a.textvalign ? a.textvalign : "top", this.prepara = a.prepara ? a.prepara : "0", this.afterpara = a.afterpara ? a.afterpara : "0", this.contentarea && (this.fontbold && this.contentarea.css({"font-weight": "bold"}), this.fontitalic && this.contentarea.css({"font-style": "italic"}), this.udl && this.contentarea.css({"text-decoration": "underline"})), this.contentarea = $('<div  style="color:' + this.fontcolor + ";padding-right:" + this.afterpara + "px padding-left:" + this.prepara + "px;width:" + (this.width - this.afterpara - this.prepara) + 'px;height:100%;;background:rgba(0,0,0,0);word-wrap: break-word; word-break: normal;">' + this.con + "</div>"), this.contentarea.css({"font-size": this.fontsize}));
    0 != this.frame, this.styleopacity && (this.colorarea = $('<div style="background:' + this.stylecolor + ";opacity:" + this.styleopacity + ';  position:absolute;width:100%;height:100%;"></div>'), this.element.append(this.colorarea)), this.element.append(this.contentarea), this.elementout.append(this.element);
    var e = parseInt(this.height) / 2 + parseInt(this.top);
    this.elementout.css({"transform-origin": parseInt(this.left) + parseInt(this.width / 2) + "px " + e + "px"}), console.log("ot" + e), this.timeout, this.sc = .7, this.update(), this.viewmodeinit(a)
}, makaelement.prototype.viewmodeinit = function (a) {
    if (1 != editor_editingmode && "btn" == this.eletype) {
        this.url = a.url ? a.url : "./";
        var b = this;
        b.elementout.css({"z-index": 999}), b.element.on("click", function () {
            "" != b.url.substr(0, 7) && (b.url = "" + b.url), console.log("jumpto" + b.url), window.location.href = b.url
        })
    }
}, makaelement.prototype.clean = function () {
    this.element.removeClass(this.show), this.element.removeClass("floating"), "noeffect" != this.show && this.elementout.css({opacity: 0}), this.timeout && clearTimeout(this.timeout), this.looptimeout && clearTimeout(this.looptimeout), this.iscrop = !1, delete_call = null, upkey_call = null, downkey_call = null, leftkey_call = null, rightkey_call = null, console.log("clean")
}, makaelement.prototype.loadpic = function (a) {
    if ("pic" == this.eletype) {
        return void(this.con && viewer_preloader.addpic(this.contentarea, this.con, a))
    }
}, makaelement.prototype.update = function () {
    this.elementout.css({opacity: this.opacity});
    var a = parseInt(this.height) / 2 + parseInt(this.top);
    this.elementout.css({"transform-origin": parseInt(this.left) + parseInt(this.width / 2) + "px " + a + "px"});
    var b = this.width < this.height ? this.width : this.height, c = this.borderradius / 200 * b;
    this.element.css({"border-radius": c + "px"}), this.elementout.css({rotate: this.rotate}), this.element.css({top: this.top}), this.element.css({left: this.left}), this.element.css({width: this.width}), this.element.css({background: this.bgcolor}), this.resizer && this.resizer.updatecontroylayer();
    for (var d in this.toolcallback)this.toolcallback[d].call(this);
    if ("text" == this.eletype || "btn" == this.eletype)if (this.contentarea.css({"padding-left": this.prepara}), this.contentarea.css({"padding-right": this.afterpara}), this.contentarea.css({"text-align": this.textalign}), this.contentarea.css({"line-height": this.lineheight}), "top" == this.textvalign)this.contentarea.css({"margin-top": 0}); else if ("middle" == this.textvalign) {
        var e = this.height / 2 - this.tl / 2;
        this.contentarea.css({"margin-top": e})
    } else if ("bottom" == this.textvalign) {
        var e = this.height - this.tl;
        this.contentarea.css({"margin-top": e})
    }
    this.page.update()
}, makaelement.prototype.get = function () {
    return this.elementout
}, makaelement.prototype.showeffect = function () {
    "noeffect" == this.show && this.element.show(), this.element.css({"animation-duration": this.speed / 1e3 + "s"}), this.element.css({"-webkit-animation-duration": this.speed / 1e3 + "s"});
    var a = this;
    this.timeout && clearTimeout(this.timeout), this.timeout = setTimeout(function () {
        a.elementout.css({opacity: a.opacity}), a.contentarea.addClass(a.shapeeffect), a.framearea && a.framearea.addClass(a.shapeeffect), a.element.hide().addClass(a.show).show()
    }, a.delay)
}, makaajax.prototype.jsonp = function (a) {
    var b = this;
    $.ajax({
        type: "get",
        url: "./data/setting.json",
        dataType: "json",
        cache: !0,
        success: function (a) {
            getjsonsuccess(a), clearTimeout(b.timeout)
        },
        error: function () {
        }
    }), this.timeout = setTimeout(function () {
        $.ajax({
            type: "get",
            url: project_get_url,
            dataType: "jsonp",
            data: {id: b.pid, version: viewer_version},
            jsonpCallback: b.jsoncallback,
            success: function (b) {
                a.call(this, b), location.reload()
            },
            error: function () {
            }
        })
    }, 15e3)
}, makaajax.prototype.uploadprojectdata = function (data, callback) {
    $.ajax({
        type: "get",
        url: project_upload_url,
        dataType: "jsonp",
        data: {id: this.pid, data: data},
        jsonpCallback: callback,
        success: function (json) {
            eval(json)
        },
        error: function () {
        }
    })
}, disablesystemcall();
var editor_makaproject, editor_makacard, editor_makatoolbar, editor_editingmode = 0, editor_preview = !1, preload_loadingprequeue = 0, viewer_version, viewer_version_data, virus_array = new Object, viewer_pubdata = new Object, viewer_preloader = new makapreloader;
editor_makaproject = new makaajax(_pid_, "getjsonsuccess", "getversion");
var viewer_version, view_thum;
$(window).resize(function () {
}), pshape.prototype.generate = function () {
    console.log("url", this.eletype);
    var a = {
        top: this.top,
        left: this.left,
        w: this.width,
        h: this.height,
        rotate: this.rotate,
        bgcolor: this.bgcolor,
        opacity: this.opacity,
        type: "pshape",
        con: this.con,
        show: this.show,
        speed: this.speed,
        delay: this.delay,
        borderradius: this.borderradius,
        shapecolor: this.shapecolor,
        shape: this.shape
    };
    return a
}, pshape.prototype.init = function (a) {
    this.top = a.top, this.left = a.left, this.width = a.w, "null" == this.width && (this.width = 640), this.height = a.h, this.shapecolor = a.shapecolor, this.opacity = a.opacity ? a.opacity : 1, this.eletype = "pshape", this.show = a.show ? a.show : "fadeIn", this.speed = a.speed ? a.speed : 1, this.delay = a.delay ? a.delay : 1, this.tl = a.tl ? a.tl : 0, this.picid = a.picid ? a.picid : 0, this.shape = a.shape ? a.shape : 0, console.log("init" + this.picid), this.borderradius = a.borderradius ? a.borderradius : 0, this.boxshadow = a.boxshadow ? a.boxshadow : -1, this.elementout = $('<div class="elementout" style="z-index:0;height:0;width:0px;position:relative;opacity:' + this.opacity + ";-webkit-transform:rotate(" + this.rotate + 'deg);"></div>');
    {
        var b = this.width > this.height ? this.width : this.height;
        this.borderradius / 100 * b
    }
    this.element = $('<div class="element" style="z-index:1;top:' + this.top + "px;left:" + this.left + "px;width:" + this.width + "px;height:" + this.height + 'px;" ></div>'), this.shapearea = $('<div class="shapearea" style="z-index:0;position:absolute;width:100%;height:100%;overflow:hidden;background:' + this.shapecolor + '"></div>'), 0 != this.shape && this.shapearea.css({"-webkit-mask-box-image": "url(./plugin/shape/" + this.shape + ")  0 0 0 0 stretch stretch"}), this.rotate = a.rotate ? a.rotate : 0, this.fontsize = a.ftsize ? a.ftsize : "20px", this.udl = a.udl ? a.udl : !1, this.fontbold = a.fontbold ? a.fontbold : !1, this.fontitalic = a.fontitalic ? a.fontitalic : !1, this.fontcolor = a.ftcolor ? a.ftcolor : "black", this.lineheight = a.lineheight ? a.lineheight : 2, this.textalign = a.textalign ? a.textalign : "none", this.textvalign = a.textvalign ? a.textvalign : "top", this.prepara = a.prepara ? a.prepara : "0", this.afterpara = a.afterpara ? a.afterpara : "0", this.contentarea = $('<div contenteditable="false" style="overflow-y:hidden;width:' + (this.width - this.prepara - this.afterpara) + 'px;word-wrap: break-word;border:none;overflow:hidden;background:rgba(0,0,0,0);border:1px;line-height:100%;">' + this.con + "</div>"), this.element.append(this.shapearea), this.elementout.append(this.element);
    var c = parseInt(this.height) / 2 + parseInt(this.top);
    this.elementout.css({"transform-origin": parseInt(this.left) + parseInt(this.width / 2) + "px " + c + "px"}), console.log("ot" + c), this.timeout, this.sc = .7, this.update(), this.viewmodeinit(a)
}, pshape.prototype.loadpic = function () {
}, pshape.prototype.viewmodeinit = function () {
}, pshape.prototype.update = function () {
    this.elementout.css({opacity: this.opacity});
    var a = parseInt(this.height) / 2 + parseInt(this.top);
    this.elementout.css({"transform-origin": parseInt(this.left) + parseInt(this.width / 2) + "px " + a + "px"});
    this.width < this.height ? this.width : this.height;
    this.shapearea.css({background: this.shapecolor}), this.elementout.css({rotate: this.rotate}), this.element.css({top: this.top}), this.element.css({left: this.left}), this.element.css({width: this.width}), this.element.css({height: this.height}), 0 != this.shape ? this.shapearea.css({"border-radius": "0px"}) : (this.shapearea.css({"border-radius": this.borderradius + "px"}), this.shapearea.css({"-webkit-mask-image": ""})), this.resizer && this.resizer.updatecontroylayer(), this.page.update()
}, pshape.prototype.get = function () {
    return this.elementout
}, pshape.prototype.showeffect = function () {
    "noeffect" == this.show && this.element.show(), this.element.css({"animation-duration": this.speed / 1e3 + "s"}), this.element.css({"-webkit-animation-duration": this.speed / 1e3 + "s"});
    var a = this, b = parseInt(a.delay);
    console.log("delay" + b), this.timeout = setTimeout(function () {
        a.elementout.css({opacity: a.opacity}), a.element.show(), a.element.addClass(a.show)
    }, b)
}, pshape.prototype.showeffectpreview = function () {
    this.element.css({"animation-duration": this.speed / 1e3 + "s"}), this.element.css({"-webkit-animation-duration": this.speed / 1e3 + "s"});
    var a = this;
    setTimeout(function () {
        a.elementout.css({opacity: a.opacity}), a.element.show().addClass(a.show)
    }, 10)
}, pshape.prototype.resettodefault = function () {
    1 != this.isme && (this.contentarea.css({cursor: "move"}), this.istext = !1, $(".onedit").removeClass("onedit"))
}, pshape.prototype.clean = function () {
    this.element.removeClass(this.show), "noeffect" == this.show && this.show || this.elementout.css({opacity: 0}), console.log("clean" + this.show), this.timeout && clearTimeout(this.timeout), delete_call = null, upkey_call = null, downkey_call = null, leftkey_call = null, rightkey_call = null, console.log("clean")
}, pshape.prototype.unsethammer = function () {
    this.mc && this.mc.destroy()
}, pshape.prototype.focusedit = function () {
}, pshape.prototype.onfocus = function () {
}, pshape.prototype.sethammer = function () {
}, ptext.prototype.generate = function () {
    console.log("url", this.eletype);
    var a = {
        top: this.top,
        left: this.left,
        w: this.width,
        h: this.height,
        rotate: this.rotate,
        bgcolor: this.bgcolor,
        opacity: this.opacity,
        type: this.eletype,
        con: this.con,
        show: this.show,
        speed: this.speed,
        delay: this.delay,
        borderradius: this.borderradius,
        boxshadow: this.boxshadow,
        "border-style": this.borderstyle,
        "border-color": this.bordercolor,
        "border-width": this.borderwidth
    };
    return a.con = this.contentarea.html(), a.lineheight = this.lineheight, a.textalign = this.textalign, a.textvalign = this.textvalign, a.prepara = this.prepara, a.afterpara = this.afterpara, a.ftcolor = this.fontcolor, a.ftsize = this.fontsize, a.tl = this.tl, a.fontbold = this.fontbold, a.fontitalic = this.fontitalic, a.udl = this.udl, a
}, ptext.prototype.init = function (a) {
    this.top = a.top, this.left = a.left, this.width = a.w, "null" == this.width && (this.width = 640), this.height = a.h, this.bgcolor = a.bgcolor, this.opacity = a.opacity, this.eletype = a.type, this.con = a.con, this.show = a.show, this.speed = a.speed, this.delay = a.delay, this.tl = a.tl ? a.tl : 0, this.picid = a.picid ? a.picid : 0, console.log("init" + this.picid), this.borderradius = a.borderradius ? a.borderradius : 0, this.boxshadow = a.boxshadow ? a.boxshadow : -1, this.elementout = $('<div class="elementout ptext" style="z-index:0;height:0;width:0px;position:relative;opacity:' + this.opacity + ";-webkit-transform:rotate(" + this.rotate + 'deg);"></div>');
    var b = this.width > this.height ? this.width : this.height, c = this.borderradius / 100 * b;
    this.element = $('<div class="element" style="z-index:0;border-radius:' + c + "px;top:" + this.top + "px;left:" + this.left + "px;width:" + this.width + "px;height:auto;background:" + this.bgcolor + '" ></div>'), this.borderstyle = a["border-style"] ? a["border-style"] : "none", this.bordercolor = a["border-color"] ? a["border-color"] : "black", this.borderwidth = a["border-width"] ? a["border-width"] : "0px", this.element.css({border: this.borderstyle + " " + this.borderwidth + "px " + this.bordercolor}), this.rotate = a.rotate ? a.rotate : 0, this.fontsize = a.ftsize ? a.ftsize : "20px", this.udl = a.udl ? a.udl : !1, this.fontbold = a.fontbold ? a.fontbold : !1, this.fontitalic = a.fontitalic ? a.fontitalic : !1, this.fontcolor = a.ftcolor ? a.ftcolor : "white", this.lineheight = a.lineheight ? a.lineheight : 2, this.textalign = a.textalign ? a.textalign : "none", this.textvalign = a.textvalign ? a.textvalign : "top", this.prepara = a.prepara ? a.prepara : "0", this.afterpara = a.afterpara ? a.afterpara : "0", this.contentarea = $('<div contenteditable="false" style="overflow-y:hidden;width:' + (this.width - this.prepara - this.afterpara) + 'px;word-wrap: break-word;border:none;overflow:hidden;background:rgba(0,0,0,0);border:1px;line-height:100%;">' + this.con + "</div>"), this.contentarea.css({"margin-left": this.prepara + "px"}), this.contentarea.css({"margin-right": this.afterpara + "px"}), this.contentarea.css({"text-align": this.textalign}), a.version >= 21 && this.contentarea.addClass("no-margin"), this.fontbold && this.contentarea.css({"font-weight": "bold"}), this.fontitalic && this.contentarea.css({"font-style": "italic"}), this.udl && this.contentarea.css({"text-decoration": "underline"}), this.contentarea.css({"font-size": this.fontsize}), this.contentarea.css({"line-height": this.lineheight}), this.element.append(this.contentarea), this.elementout.append(this.element);
    var d = parseInt(this.height) / 2 + parseInt(this.top);
    if (this.elementout.css({"transform-origin": parseInt(this.left) + parseInt(this.width / 2) + "px " + d + "px"}), console.log("ot" + d), this.timeout, this.sc = .7, this.update(), 1 == editor_editingmode) {
        this.resizer = new makaresizer(this);
        "pic" == this.eletype || "ptext" == this.eletype || "btn" == this.eletype
    }
    this.viewmodeinit(a)
}, ptext.prototype.loadpic = function () {
}, ptext.prototype.viewmodeinit = function (a) {
    if (1 != editor_editingmode && "btn" == this.eletype) {
        this.url = a.url ? a.url : "./";
        var b = this;
        b.get().css({"z-index": 999}), b.get().on("click", function () {
            var a = encodeURIComponent("" + b.url);
            window.location.href = "" + a
        })
    }
}, ptext.prototype.update = function (a) {
    this.elementout.css({opacity: this.opacity});
    var b = parseInt(this.height) / 2 + parseInt(this.top);
    this.elementout.css({"transform-origin": parseInt(this.left) + parseInt(this.width / 2) + "px " + b + "px"});
    var c = this.width < this.height ? this.width : this.height, d = this.borderradius / 200 * c;
    return this.element.css({"border-radius": d + "px"}), this.elementout.css({rotate: this.rotate}), this.contentarea.html(this.con), "self" == a && this.element.css({top: this.top}), this.element.css({left: this.left}), this.element.css({width: this.width}), this.contentarea.css(1 == this.fontbold ? {"font-weight": 700} : {"font-weight": "normal"}), this.contentarea.css(1 == this.fontitalic ? {"font-style": "italic"} : {"font-style": "normal"}), this.contentarea.css(1 == this.udl ? {"text-decoration": "underline"} : {"text-decoration": "none"}), this.contentarea.css({color: this.fontcolor}), this.element.css({background: this.bgcolor}), this.contentarea.css({"font-size": this.fontsize}), this.contentarea.css({"line-height": this.lineheight}), this.contentarea.css({"text-align": this.textalign}), this.contentarea.css({"margin-left": this.prepara}), this.contentarea.css({width: this.width - this.prepara - this.afterpara}), this.resizer && this.resizer.updatecontroylayer(), this.element.css({border: this.borderstyle + " " + this.borderwidth + "px " + this.bordercolor}), this.element.css({background: this.bgcolor}), void this.element.css({"box-shadow": "1px 1px " + this.boxshadow + "px rgb(40,40,40)"})
}, ptext.prototype.get = function () {
    return this.elementout
}, ptext.prototype.showeffect = function () {
    "noeffect" == this.show && this.element.show(), this.element.css({"animation-duration": parseInt(this.speed) / 1e3 + "s"}), this.element.css({"-webkit-animation-duration": parseInt(this.speed) / 1e3 + "s"});
    var a = this, b = parseInt(a.delay);
    console.log("delay" + b), this.timeout = setTimeout(function () {
        a.elementout.css({opacity: a.opacity}), a.element.show(), a.element.addClass(a.show)
    }, b)
}, ptext.prototype.resettodefault = function () {
}, ptext.prototype.clean = function () {
    this.element.removeClass(this.show), "noeffect" == this.show && this.show || this.elementout.css({opacity: 0}), console.log("clean" + this.show), this.timeout && clearTimeout(this.timeout), delete_call = null, upkey_call = null, downkey_call = null, leftkey_call = null, rightkey_call = null, console.log("clean")
}, ptext.prototype.unsethammer = function () {
    this.mc && this.mc.destroy()
}, ptext.prototype.focusedit = function () {
    var a = this, b = a.element, c = (this.sc, this.page);
    c.clearresizer(), a.resizer.disableresize(), a.resizer.enableresize(), editor_makatoolbar.settoolbar(a, a.eletype), a.candelete = !0, $(".onedit").removeClass("onedit"), b.addClass("onedit"), a.page.ismove = !1
}, ptext.prototype.onfocus = function () {
    var a = this, b = a.element, c = (this.sc, this.page);
    this.istext = !0, a.isediting = !1, this.contentarea.attr({contenteditable: !0}), this.contentarea.css({cursor: "text"}), this.contentarea.on("keyup", function () {
        a.resizer.updatecontroylayer(), a.con = a.contentarea.html(), a.isediting = !1
    }), this.contentarea.on("mousedown", function () {
    }), startY = parseInt(b.css("top")), startX = parseInt(b.css("left")), startYinner = parseInt(b.find("img").css("top")), startXinner = parseInt(b.find("img").css("left")), editing_lock = 1, a.isme = !0, c.clearresizer(), a.resizer.enableresize(), editor_makatoolbar.settoolbar(a, a.eletype), a.isme = !1, $(".onedit").removeClass("onedit"), b.addClass("onedit"), system_call = !0, a.page.ismove = !1;
    b.attr("type");
    $(".pagecontent").css({overflow: "visible"})
}, ptext.prototype.sethammer = function () {
    {
        var a = this, b = a.element, c = this.sc;
        this.page
    }
    b.each(function () {
        a.mc = new Hammer.Manager(this)
    }), mc = a.mc, mc.add(new Hammer.Pan({threshold: 0, pointers: 0})), mc.add(new Hammer.Tap({
        event: "doubletap",
        taps: 2
    })), mc.add(new Hammer.Tap), mc.on("tap touchstart", function () {
        a.page.ismove = !1, console.log("tap"), a.onfocus(), a.startY = parseInt(b.css("top")), a.startX = parseInt(b.css("left")), a.startYinner = parseInt(b.find("img").css("top")), a.startXinner = parseInt(b.find("img").css("left"))
    }).on("panstart", function () {
        editor_makatoolbar.settoolbar(a, a.eletype), a.startY = parseInt(b.css("top")), a.startX = parseInt(b.css("left")), a.startYinner = parseInt(b.find("img").css("top")), a.startXinner = parseInt(b.find("img").css("left"))
    }).on("pan", function (b) {
        1 != a.istext && (system_call = !1, a.left = a.startX + b.deltaX / c, a.top = a.startY + b.deltaY / c, a.update("self"))
    }).on("panend", function () {
        system_call = !1, a.istyping = !1
    }).on("doubletap", function () {
    })
}, ppictrue.prototype.generate = function () {
}, ppictrue.prototype.init = function (a) {
    this.top = a.top, this.left = a.left, this.width = a.w, "null" == this.width && (this.width = 640), this.height = a.h, this.inw = a.inw, "null" != this.inw && this.inw || (this.inw = "auto"), this.inh = "auto", this.lockratio = !0, this.shapecolor = a.shapecolor, this.opacity = a.opacity ? a.opacity : 1, this.eletype = "ppictrue", this.show = a.show ? a.show : "fadeIn", this.speed = a.speed ? a.speed : 1, this.delay = a.delay ? a.delay : 1, this.tl = a.tl ? a.tl : 0, this.rotate = a.rotate ? a.rotate : 0, this.picid = a.picid ? a.picid : 0, console.log("piicd" + this.picid), this.picid < 1e4 && (this.con = "" + this.picid + "/thumb/29"), this.con = "" + this.picid + "/thumb/100", this.shape = a.shape ? a.shape : 0, console.log("init" + this.picid), this.stylecolor = a.stylecolor ? a.stylecolor : "rgba(0,0,0,0)", this.styleopacity = a.styleopacity ? a.styleopacity : 0, this.intop = parseInt(a.intop), this.inleft = parseInt(a.inleft), a.inTop && (this.intop = a.inTop), a.inLeft && (this.inleft = a.inLeft), this.borderradius = a.borderradius ? a.borderradius : 0, this.boxshadow = a.boxshadow ? a.boxshadow : -1, this.elementout = $('<div class="elementout ppic" style="z-index:0;height:0;width:0px;position:relative;opacity:' + this.opacity + ";-webkit-transform:rotate(" + this.rotate + 'deg);"></div>');
    {
        var b = this.width > this.height ? this.width : this.height;
        this.borderradius / 100 * b
    }
    this.element = $('<div class="element" style="z-index:1;top:' + this.top + "px;left:" + this.left + "px;width:" + this.width + "px;height:" + this.height + 'px;" ></div>'), this.shapearea = $('<div class="shapearea p" style="z-index:0;position:absolute;width:100%;height:100%;overflow:hidden;background:' + this.shapecolor + '"></div>'), this.contentarea = $('<img class="ppic" style="position:absolute;width:' + this.inw + ";top:" + this.intop + "px;left:" + this.inleft + 'px" />'), this.colorarea = $('<div style="position:absolute;width:100%;height:100%;"></div>'), this.element.append(this.contentarea), this.element.append(this.colorarea), this.elementout.append(this.element);
    var c = parseInt(this.height) / 2 + parseInt(this.top);
    this.elementout.css({"transform-origin": parseInt(this.left) + parseInt(this.width / 2) + "px " + c + "px"}), console.log("ot" + c), this.timeout, this.sc = .7, this.update(), this.viewmodeinit(a), this.contentarea.eraser()
}, ppictrue.prototype.loadpic = function (a) {
    this.con && viewer_preloader.addpic(this.contentarea, this.con, a)
}, ppictrue.prototype.viewmodeinit = function () {
}, ppictrue.prototype.update = function () {
    this.colorarea.css({opacity: this.styleopacity}), this.colorarea.css({background: this.stylecolor});
    var a = parseInt(this.height) / 2 + parseInt(this.top);
    this.elementout.css({"transform-origin": parseInt(this.left) + parseInt(this.width / 2) + "px " + a + "px"});
    var b = this.width < this.height ? this.width : this.height;
    this.shapearea.css({background: this.shapecolor}), this.elementout.css({rotate: this.rotate}), this.element.css({top: this.top}), this.element.css({left: this.left}), this.element.css({width: this.width}), this.element.css({height: this.height}), this.contentarea.css({width: this.inw}), this.contentarea.css({top: this.intop}), this.contentarea.css({left: this.inleft}), this.boxshadow && this.element.css({"box-shadow": "1px 1px " + this.boxshadow + "px rgb(40,40,40)"});
    var b = this.width < this.height ? this.width : this.height, c = this.borderradius / 200 * b;
    this.element.css({"border-radius": c + "px"}), this.contentarea.attr({src: this.con}), this.resizer && this.resizer.updatecontroylayer(), this.page.update()
}, ppictrue.prototype.get = function () {
    return this.elementout
}, ppictrue.prototype.showeffect = function () {
    "noeffect" == this.show && this.element.show(), this.element.css({"animation-duration": this.speed / 1e3 + "s"}), this.element.css({"-webkit-animation-duration": this.speed / 1e3 + "s"});
    var a = this, b = parseInt(a.delay);
    console.log("delay" + b), this.timeout = setTimeout(function () {
        a.elementout.css({opacity: a.opacity}), a.element.show(), a.element.addClass(a.show)
    }, b)
}, ppictrue.prototype.showeffectpreview = function () {
    this.element.css({"animation-duration": this.speed / 1e3 + "s"}), this.element.css({"-webkit-animation-duration": this.speed / 1e3 + "s"});
    var a = this;
    setTimeout(function () {
        a.elementout.css({opacity: a.opacity}), a.element.show().addClass(a.show)
    }, 10)
}, ppictrue.prototype.resettodefault = function () {
    1 != this.isme && (this.contentarea.css({cursor: "move"}), this.istext = !1, $(".onedit").removeClass("onedit"))
}, ppictrue.prototype.clean = function () {
    this.element.removeClass(this.show), "noeffect" == this.show && this.show || this.elementout.css({opacity: 0}), console.log("clean" + this.show), clearTimeout(this.timeout), delete_call = null, upkey_call = null, downkey_call = null, leftkey_call = null, rightkey_call = null, console.log("clean")
}, ppictrue.prototype.unsethammer = function () {
    this.mc && this.mc.destroy()
}, ppictrue.prototype.focusedit = function () {
    var a = this, b = a.element, c = (this.sc, this.page);
    c.clearresizer(), a.resizer.disableresize(), a.resizer.enableresize(), editor_makatoolbar.settoolbar(a, a.eletype), a.candelete = !0, $(".onedit").removeClass("onedit"), b.addClass("onedit"), delete_call = function () {
        var b = a.page.elearray.indexOf(a);
        a.elementout.remove(), a.page.elearray = a.page.elearray.slice(0, b).concat(a.page.elearray.slice(b + 1, a.page.elearray.length))
    }, a.page.ismove = !1
}, ppictrue.prototype.onfocus = function () {
    var a = this, b = a.element, c = (this.sc, this.page);
    this.istext = !0, a.isediting = !1, startY = parseInt(b.css("top")), startX = parseInt(b.css("left")), startYinner = parseInt(b.find("img").css("top")), startXinner = parseInt(b.find("img").css("left")), editing_lock = 1, delete_call = function () {
        var b = a.page.elearray.indexOf(a);
        a.elementout.remove(), a.page.elearray = a.page.elearray.slice(0, b).concat(a.page.elearray.slice(b + 1, a.page.elearray.length))
    }, upkey_call = function () {
        a.top -= 2, a.update()
    }, downkey_call = function () {
        a.isediting || (a.top += 2, a.update())
    }, leftkey_call = function () {
        a.isediting || (a.left -= 2, a.update())
    }, rightkey_call = function () {
        a.isediting || (a.left += 2, a.update())
    }, a.isme = !0, c.clearresizer(), a.resizer.enableresize(), editor_makatoolbar.settoolbar(a, a.eletype), a.isme = !1, $(".onedit").removeClass("onedit"), b.addClass("onedit"), system_call = !0, a.page.ismove = !1;
    b.attr("type");
    $(".pagecontent").css({overflow: "visible"})
}, ppictrue.prototype.sethammer = function () {
    {
        var a = this, b = a.element, c = this.sc;
        this.page
    }
    b.each(function () {
        a.mc = new Hammer.Manager(this)
    }), mc = a.mc, mc.add(new Hammer.Pan({threshold: 0, pointers: 0})), mc.add(new Hammer.Tap({
        event: "doubletap",
        taps: 2
    })), mc.add(new Hammer.Tap), mc.on("tap touchstart panstart", function () {
        a.onfocus(), a.startY = parseInt(b.css("top")), a.startX = parseInt(b.css("left")), a.startYinner = parseInt(b.find("img").css("top")), a.startXinner = parseInt(b.find("img").css("left"))
    }).on("pan", function (b) {
        system_call = !1, a.left = a.startX + b.deltaX / c, a.top = a.startY + b.deltaY / c, a.update("self")
    }).on("panend", function () {
        system_call = !1, a.istyping = !1
    })
}, eleform.prototype.generate = function () {
    console.log("url", this.eletype);
    var a = {top: this.top, left: this.left, w: this.width, h: this.height};
    return a.qlist = this.qlist, a.formcolor = this.formcolor, a.type = "eleform", a.formid = this.formid, a
}, eleform.prototype.init = function (a) {
    var b = this;
    this.top = a.top, this.left = a.left, this.qlist = a.qlist ? a.qlist : "0", this.formcolor = a.formcolor, this.sucmsg = a.sucmsg, this.opacity = 1, this.formid = a.formid ? a.formid : randomString(10), this.width = 500, this.height = 600, this.eletype = a.type, console.log("initform"), this.elementout = $('<div class="elementout" style="z-index:0;height:0;width:0px;position:relative;"></div>');
    var c = this;
    this.element = $('<div class="element eleform" style="z-index:0;top:' + this.top + "px;left:" + this.left + "px;width:" + this.width + 'px;" ></div>'), c.warnarea = $('<div style="z-index:999;position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(42,50,65,1)"></div>');
    var d = this.warnarea, e = 0;
    e = 2 == this.qlist.length ? 30 : 10, this.inputarray = new Array;
    for (var f in this.qlist) {
        var g = $('<div style="width:98%;height:70px;margin-bottom:' + e + 'px;;overflow:hidden;"></div>'), h = $("<input pos=" + f + ' style="border:0;display:block;text-indent:10px;width:98%;outline:none;;height:60px;line-height:60px;font-size:25px;" name="' + this.qlist[f].id + '"placeholder="' + this.qlist[f].name + '"></input>');
        g.css({border: "solid 2px " + this.formcolor}), g.append(h), this.element.append(g), c.inputarray.push(h), h.on("focus", function () {
            b.element.css({y: -100 * $(this).attr("pos") + "px"})
        }), h.on("blur", function () {
            b.element.css({y: "0px"})
        })
    }
    this.btnname = a.btn_name ? a.btn_name : "提交", this.btn = $('<div style="text-align:center;margin-top:30px;font-size:50px;color:white;margin-bottom:10px;width:100%;height:80px;line-height:80px;font-size:30px;background:' + this.formcolor + '" >' + this.btnname + "</div>"), this.btn.on("click", function () {
        d.show(), c.page.page.append(d);
        var a = new Object;
        a.formdata = new Object;
        for (var b in c.inputarray)a.formdata[c.inputarray[b].attr("name")] = c.inputarray[b].val();
        var e = /^\d*$/, f = $('<img style="width:140px;position:absolute;left:50%;top:30%;margin-left:-70px;" src="plugin/sending.gif"/>'), g = $('<p style="margin:0;width:80%;position:absolute;top:50%;left:10%;text-align:center;color:white;font-size:40px;">提交中...</p>');
        c.inputarray[0].val() ? e.test(c.inputarray[1].val()) && c.inputarray[1].val() ? (a.formid = _pid_ + "_" + c.formid, d.append(f), d.append(g), $.ajax({
            type: "post",
            url: "",
            dataType: "html",
            cache: !1,
            data: a,
            success: function () {
                g.html("提交成功!<br>" + c.sucmsg), f.attr("src", "plugin/sendsucess.png"), d.css({background: "rgba(0,0,0,0.8)"}), d.show()
            },
            error: function () {
            }
        })) : (g = $('<p style="margin:0;width:80%;position:absolute;top:50%;left:10%;text-align:center;color:white;font-size:40px;">请输入正确' + c.qlist[1].name + "</p>"), d.append(g), setTimeout(function () {
            d.fadeOut(200, function () {
                c.inputarray[1].focus(), g.remove()
            })
        }, 500)) : (g = $('<p style="margin:0;width:80%;position:absolute;top:50%;left:10%;text-align:center;color:white;font-size:40px;">请输入' + c.qlist[0].name + "</p>"), d.append(g), setTimeout(function () {
            d.fadeOut(200, function () {
                c.inputarray[0].focus(), g.remove()
            })
        }, 500)), d.on("click", function () {
            d.remove()
        })
    }), this.element.append(this.btn), this.elementout.append(this.element), this.timeout, this.sc = .7, this.update()
}, eleform.prototype.loadpic = function () {
}, eleform.prototype.viewmodeinit = function () {
}, eleform.prototype.update = function () {
}, eleform.prototype.get = function () {
    return this.elementout
}, eleform.prototype.showeffect = function () {
    "noeffect" == this.show && this.element.show(), this.element.css({"animation-duration": this.speed / 1e3 + "s"}), this.element.css({"-webkit-animation-duration": this.speed / 1e3 + "s"});
    var a = this, b = parseInt(a.delay);
    console.log("delay" + b), this.timeout = setTimeout(function () {
        a.elementout.css({opacity: a.opacity}), a.element.show(), a.element.addClass(a.show)
    }, b)
}, eleform.prototype.showeffectpreview = function () {
    this.element.css({"animation-duration": this.speed / 1e3 + "s"}), this.element.css({"-webkit-animation-duration": this.speed / 1e3 + "s"});
    var a = this;
    setTimeout(function () {
        a.elementout.css({opacity: a.opacity}), a.element.show().addClass(a.show)
    }, 10)
}, eleform.prototype.resettodefault = function () {
    $(".onedit").removeClass("onedit")
}, eleform.prototype.clean = function () {
    var a = this;
    for (var b in a.inputarray)a.inputarray[b].val("");
    this.warnarea.empty(), this.warnarea.remove(), this.element.removeClass(this.show), "noeffect" == this.show && this.show || this.elementout.css({opacity: 0}), console.log("clean" + this.show), clearTimeout(this.timeout), console.log("clean")
}, eleform.prototype.unsethammer = function () {
}, eleform.prototype.focusedit = function () {
}, eleform.prototype.onfocus = function () {
}, eleform.prototype.sethammer = function () {
}, makaswiper.prototype.generate = function () {
    var a = {
        top: this.top,
        left: this.left,
        w: this.width,
        h: this.height,
        rotate: this.rotate,
        bgcolor: this.bgcolor,
        opacity: this.opacity,
        type: this.eletype,
        con: this.con,
        show: this.show,
        speed: this.speed,
        delay: this.delay,
        borderradius: this.borderradius,
        boxshadow: this.boxshadow,
        "border-style": this.borderstyle,
        "border-color": this.bordercolor,
        "border-width": this.borderwidth
    };
    return a
};
var swc = 0;
makaswiper.prototype.init = function (a) {
    this.swc = swc++;
    this.opacity = 1, this.rotate = 0, this.elementout = $('<div class="elementout" style="z-index:0;height:0;width:0px;position:relative;opacity:' + this.opacity + ";-webkit-transform:rotate(" + this.rotate + 'deg);"></div>'), this.top = a.top, this.left = a.left, this.width = a.width, this.height = a.height, this.show = a.show ? a.show : "fadeIn", this.speed = a.speed ? a.speed : 1, this.delay = a.delay ? a.delay : 1, this.slides = a.slides, this.bgcolor = a.bgcolor, this.element = $('<div class="element swiper-container" style="z-index:0;top:' + this.top + "px;left:" + this.left + "px;width:" + this.width + "px;height:" + this.height + "px;background:" + this.bgcolor + '" ></div>'), this.wrapper = $('<div class="swiper-wrapper"></div>');
    for (var b in this.slides) {
        {
            var c = $('<div class="swiper-slide"></div> '), d = this.slides[b];
            d.eles
        }
        for (var e in d) {
            var f = d[e];
            if ("pic" == f.eletype) {
                var g = $('<div style="overflow:hidden;position:absolute;top:' + f.top + "px;left:" + f.left + "px;width:" + f.width + "px;height:" + f.height + 'px"></div>'), h = $('<img style="position:absolute;top:' + f.intop + "px;left:" + f.inleft + "px;width:" + f.inw + 'px;" src=""/>');
                g.append(h), c.append(g)
            }
            if ("text" == f.eletype) {
                var g = $('<div style="line-height:' + f.lineheight + ";word-wrap: break-word;text-align:" + f.textalign + ";font-size:" + f.ftsize + ";color:" + f.ftcolor + ";overflow:hidden;position:absolute;top:" + f.top + "px;left:" + f.left + "px;width:" + f.width + 'px;"></div>');
                g.html(f.con), c.append(g)
            }
        }
        this.wrapper.append(c)
    }
    var i = $('  <div class="swiper-pagination"></div>');
    this.element.append(this.wrapper), this.element.append(i);
    var j = this.element;
    if (0 == this.swc) {
        console.log("swc" + this.swc);
        {
            new Swiper(j, {speed: 400, spaceBetween: a.width - a.width, pagination: i})
        }
    } else {
        new Swiper(j, {speed: 400, spaceBetween: a.width, pagination: i})
    }
    this.elementout.append(this.element)
}, makaswiper.prototype.loadpic = function () {
    if (console.log("ele load type" + this.eletype), "pic" == this.eletype && !this.loaded) {
        console.log("ele load start"), preload_loadingprequeue++;
        var a = new Image;
        console.log("loadpic" + this.lazy), a.src = this.lazy, this.con = this.lazy;
        var b = this;
        if (a.complete) {
            if (b.loaded)return;
            b.contentarea.attr({src: b.con}), loadingonepic(), clearTimeout(b.lazytimer), b.loaded = !0, console.log("ele load end")
        } else a.onload = function () {
            b.loaded || (loadingonepic(), clearTimeout(b.lazytimer), b.contentarea.attr({src: b.con}), b.loaded = !0, console.log("ele load end"))
        }, a.onerror = function () {
            loadingonepic(), clearTimeout(b.lazytimer), console.log("ele load error")
        };
        this.lazytimer = setTimeout(function () {
            b.loaded = !0, loadingonepic(), b.contentarea.attr({src: b.con})
        }, 5e3)
    }
}, makaswiper.prototype.viewmodeinit = function (a) {
    if (1 != editor_editingmode && "btn" == this.eletype) {
        this.url = a.url ? a.url : "./";
        var b = this;
        b.get().css({"z-index": 999}), b.get().on("click", function () {
            var a = encodeURIComponent("" + b.url);
            window.location.href = "" + a
        })
    }
}, makaswiper.prototype.update = function () {
    this.elementout.css({opacity: this.opacity});
    var a = parseInt(this.height) / 2 + parseInt(this.top);
    this.elementout.css({"transform-origin": parseInt(this.left) + parseInt(this.width / 2) + "px " + a + "px"});
    var b = this.width < this.height ? this.width : this.height, c = this.borderradius / 200 * b;
    this.element.css({"border-radius": c + "px"}), this.elementout.css({rotate: this.rotate}), this.element.css({top: this.top}), this.element.css({left: this.left}), this.element.css({width: this.width}), this.element.css({height: this.height}), this.element.css({border: this.borderstyle + " " + this.borderwidth + "px " + this.bordercolor}), this.element.css({background: this.bgcolor}), this.page.update()
}, makaswiper.prototype.get = function () {
    return this.elementout
}, makaswiper.prototype.clean = function () {
    this.element.removeClass(this.show), "noeffect" != this.show && this.elementout.css({opacity: 0}), clearTimeout(this.timeout), this.iscrop = !1, delete_call = null, upkey_call = null, downkey_call = null, leftkey_call = null, rightkey_call = null, console.log("clean")
}, makaswiper.prototype.showeffect = function () {
    "noeffect" == this.show && this.element.show(), this.element.css({"animation-duration": this.speed / 1e3 + "s"}), this.element.css({"-webkit-animation-duration": this.speed / 1e3 + "s"});
    var a = this, b = parseInt(a.delay) + 100 * Math.random();
    console.log("delay" + b), this.timeout = setTimeout(function () {
        a.elementout.css({opacity: a.opacity}), a.element.show(), a.element.addClass(a.show)
    }, b)
}, makaswiper.prototype.showeffectpreview = function () {
    this.element.css({"animation-duration": this.speed / 1e3 + "s"}), this.element.css({"-webkit-animation-duration": this.speed / 1e3 + "s"});
    var a = this;
    setTimeout(function () {
        a.elementout.css({opacity: a.opacity}), a.element.show().addClass(a.show)
    }, 10)
}, elechart.prototype.init = function (a) {
    var b = this;
    console.log(a), this.top = a.top, this.left = a.left, this.width = a.width, this.height = a.height, b.content = a.content, b.chartdata = b.content.data, this.max = b.content.options.yAxis.max, this.min = b.content.options.yAxis.min, this.background = "rgba(0,0,0,0)", this.tcolor = a.content.options.lineColor ? a.content.options.lineColor : "gray", console.log(a.content);
    var c = a.content.multiple;
    this.charttype = c, 1 == c ? (this.stack = "none", this.multi = !1) : 2 == c ? (this.stack = null, this.multi = !0) : 3 == c && (this.stack = "normal", this.multi = !0), this.temp;
    var d = b.content, e = d.options.colors;
    for (var f in b.chartdata)b.chartdata[f].color = e[f];
    var d = a.content;
    this.opacity = 1, this.elementout = $('<div class="elementout" style="background:' + this.background + ";position:absolute;z-index:0;top:" + this.top + "px;left:" + this.left + "px;width:" + this.width + "px;height:" + this.height + 'px"></div>'), this.swipewarn = $('<img style="display:none;z-index:99;position:absolute;width:180px;left:230px;top:50%;margin-top:-90px" src="plugin/swipewarn.png" />'), this.elementout.append(this.swipewarn), this.chartlegends = $('<div   style="text-align:center;position:absolute;z-index:0;top:10px;left:0px;width:' + this.width + 'px;"></div>'), this.element = $(this.multi ? '<div class="element"  style="z-index:0;top:0px;left:0px;width:' + this.width + "px;height:" + this.height + 'px;"></div>' : '<div class="element"  style="z-index:0;top:50px;left:0px;width:' + this.width + "px;height:" + (this.height - 50) + 'px;"></div>'), this.elementout.append(this.element);
    var g = b.chartdata.length;
    if (b.cli = 0, this.element.unbind(), b.showallcdata(), !this.multi) {
        for (var f in b.chartdata) {
            var h = $('<span class="chartlegend" style="color:' + b.tcolor + '">' + b.chartdata[f].name + "</span>");
            h.on("click", function () {
                b.changecdata($(this).index())
            }), this.chartlegends.append(h)
        }
        this.elementout.append(this.chartlegends), b.changecdata(0), this.element.on("swipeleft", function () {
            b.cli = b.cli == g - 1 ? 0 : b.cli + 1, b.changecdata(b.cli)
        }), this.element.on("swiperight", function () {
            b.cli = 0 == b.cli ? g - 1 : b.cli - 1, b.changecdata(b.cli)
        })
    }
}, elechart.prototype.loadpic = function () {
}, elechart.prototype.viewmodeinit = function () {
}, elechart.prototype.update = function () {
}, elechart.prototype.get = function () {
    return this.elementout
}, elechart.prototype.showeffect = function () {
    var a = this;
    1 == this.charttype && (setTimeout(function () {
        a.swipewarn.fadeIn()
    }, 1500), setTimeout(function () {
        a.swipewarn.fadeOut()
    }, 3500)), a.swipewarn.unbind(), a.swipewarn.on("touchstart", function () {
        $(this).fadeOut()
    }), setTimeout(function () {
        a.showallcdata()
    }, 500)
}, elechart.prototype.showallcdata = function () {
    var a = this, b = a.content, c = (this.element.highcharts(), a.chartdata);
    a.element.highcharts({
        chart: {
            type: b.type,
            marginTop: 50,
            animation: {duration: 300},
            plotBorderWidth: 1,
            backgroundColor: a.background
        },
        legend: {
            enabled: a.multi, useHTML: !0, labelFormatter: function () {
                return '<div style="font-size:18px;color:' + a.tcolor + '">' + this.name + "</div>"
            }
        },
        credits: {enabled: !1},
        tooltip: {style: {fontSize: "18px"}},
        xAxis: {
            lineColor: a.tcolor,
            gridLineColor: a.tcolor,
            categories: b.options.xAxis.categories,
            tickColor: a.tcolor,
            labels: {staggerLines: 1, distance: 50, style: {paddingTop: "30px", fontSize: "18px", color: a.tcolor}}
        },
        plotOptions: {series: {stacking: a.stack, lineWidth: 2, borderWidth: 0}},
        yAxis: {
            lineColor: a.tcolor,
            gridLineColor: a.tcolor,
            max: a.max,
            min: a.min,
            title: {text: b.options.yAxis.title.text, fontSize: "18px", style: {color: a.tcolor}},
            labels: {style: {lineHeight: "40px", fontSize: "18px", color: a.tcolor}}
        },
        title: {text: null},
        series: c
    })
}, elechart.prototype.changecdata = function (a) {
    this.cli = a, this.chartlegends.find(".chartlegend").removeClass("active"), this.chartlegends.find(".chartlegend").css({color: this.tcolor}), this.chartlegends.find(".chartlegend").eq(a).addClass("active");
    var b = this, c = (b.content, this.element.highcharts()), d = b.chartdata;
    this.templist = new Array;
    for (var e in d)c.series[e].hide();
    c.series[a].show(), this.clean(), this.showallcdata()
}, elechart.prototype.showeffectpreview = function () {
}, elechart.prototype.resettodefault = function () {
}, elechart.prototype.clean = function () {
    this.swipewarn.hide();
    var a = this;
    a.element.highcharts() && a.element.highcharts().destroy()
}, elechart.prototype.unsethammer = function () {
}, elechart.prototype.focusedit = function () {
}, elechart.prototype.onfocus = function () {
}, elechart.prototype.sethammer = function () {
}, elechartpie.prototype.init = function (a) {
    var b = this, c = this;
    console.log(a), this.top = a.top, this.left = a.left, this.width = a.width, this.height = a.height, b.content = a.content, this.background = "rgba(0,0,0,0)", this.tcolor = a.content.options.lineColor ? a.content.options.lineColor : "gray", this.hcolor = a.content.options.highlightColor ? a.content.options.highlightColor : "black", this.piedata = b.content.data;
    a.content;
    if (this.opacity = 1, this.elementout = $('<div class="elementout" style="background:' + this.background + ";position:absolute;z-index:0;top:" + this.top + "px;left:" + this.left + "px;width:" + this.width + "px;height:" + this.height + 'px"></div>'), this.pietitle = $('<div   style="text-align:center;position:absolute;z-index:0;top:0px;left:0px;width:' + this.width + 'px;"></div>'), this.swipewarn = $('<img style="z-index:99;position:absolute;width:180px;left:230px;top:50%;margin-top:-90px" src="plugin/swipewarn.png" />'), this.elementout.append(this.swipewarn), this.piedata.length > 1 ? (this.element = $('<div class="element"  style="z-index:0;top:50px;left:0px;width:' + this.width + "px;height:" + (this.height - 50) + 'px;"></div>'), this.elementout.append(this.pietitle)) : this.element = $('<div class="element"  style="z-index:0;top:0px;left:0px;width:' + this.width + "px;height:" + (this.height - 50) + 'px;"></div>'), this.elementout.append(this.element), this.piedata.length > 1) {
        for (var d in this.piedata) {
            var e = this.piedata[d].name, f = $('<span class="chartlegend" >' + e + "</span>");
            this.pietitle.append(f), f.on("click", function () {
                c.showonedata($(this).index())
            })
        }
        c.cli = 0, this.element.on("swipeleft", function () {
            cl = b.piedata.length, c.cli = c.cli == cl - 1 ? 0 : c.cli + 1, c.showonedata(c.cli)
        }), this.element.on("swiperight", function () {
            cl = b.piedata.length, c.cli = 0 == c.cli ? cl - 1 : c.cli - 1, c.showonedata(c.cli)
        })
    } else c.showonedata(0)
}, elechartpie.prototype.loadpic = function () {
}, elechartpie.prototype.viewmodeinit = function () {
}, elechartpie.prototype.update = function () {
}, elechartpie.prototype.get = function () {
    return this.elementout
}, elechartpie.prototype.showeffect = function () {
    var a = this, b = this;
    return void(this.piedata.length > 1 ? (setTimeout(function () {
        b.swipewarn.fadeIn()
    }, 1500), setTimeout(function () {
        b.swipewarn.fadeOut()
    }, 3500), b.swipewarn.unbind(), b.swipewarn.on("touchstart", function () {
        $(this).fadeOut()
    }), setTimeout(function () {
        a.showonedata(a.cli)
    }, 500)) : setTimeout(function () {
        a.showonedata(0)
    }, 500));
    var a
}, elechartpie.prototype.showonedata = function (a) {
    if (this.piedata[a]) {
        this.cli = a, this.pietitle.find(".chartlegend").removeClass("active"), this.pietitle.find(".chartlegend").css({color: this.tcolor}), this.pietitle.find(".chartlegend").eq(a).addClass("active");
        var b = this.width, c = new Object;
        c.name = this.piedata[a].name, c.data = new Array;
        var d = this.content.options.colors;
        for (var e in this.piedata[a].data) {
            var f = new Object;
            f.name = this.piedata[a].data[e][0], f.y = this.piedata[a].data[e][1], f.color = d[e], c.data.push(f)
        }
        var g = this;
        for (var e in c.data);
        this.element.highcharts({
            chart: {type: "pie", backgroundColor: g.background},
            title: {text: null},
            legend: {
                useHTML: !0, labelFormatter: function () {
                    return '<div style="color:' + g.tcolor + ';font-size:18px;">' + this.name + "</div>"
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: !0, dataLabels: {
                        formatter: function () {
                            return parseInt(this.percentage) + "%"
                        }, distance: -b / 6, color: "white", style: {fontSize: "40px"}
                    }, showInLegend: !0
                }
            },
            credits: {enabled: !1},
            tooltip: {pointFormat: "{series.name}: <b>{point.y:.1f}%</b>", style: {fontSize: "18px"}},
            series: [c]
        })
    }
}, elechartpie.prototype.showallcdata = function () {
    var a = this.width;
    this.element.highcharts({
        chart: {type: "pie"},
        title: {text: null},
        legend: {
            useHTML: !0, labelFormatter: function () {
                return '<div style="font-size:18px;">' + this.name + "</div>"
            }
        },
        plotOptions: {
            pie: {
                dataLabels: {
                    formatter: function () {
                        return parseInt(this.percentage) + "%"
                    }, distance: -a / 6, color: "white", style: {fontSize: "40px"}
                }, showInLegend: !0
            }
        },
        credits: {enabled: !1},
        tooltip: {pointFormat: "{series.name}: <b>{point.y:.1f}%</b>", style: {fontSize: "18px"}},
        series: [{}]
    })
}, elechartpie.prototype.changecdata = function () {
}, elechartpie.prototype.showeffectpreview = function () {
}, elechartpie.prototype.resettodefault = function () {
}, elechartpie.prototype.clean = function () {
    this.swipewarn.hide();
    var a = this;
    a.element.highcharts() && a.element.highcharts().destroy()
}, elechartpie.prototype.unsethammer = function () {
}, elechartpie.prototype.focusedit = function () {
}, elechartpie.prototype.onfocus = function () {
}, elechartpie.prototype.sethammer = function () {
};
