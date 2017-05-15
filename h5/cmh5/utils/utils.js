/**
 * Created by Administrator on 14-7-9.
 */
/**
 * 项目通用工具集，非纯原生，有些地方的调用需要用到jquery以及某些插件
 * */
var commonUtils = {
    /**
     * 解析url，获取后带参数
     * */
    getQueryString: function(name) {
        var queryStr = window.location.hash || window.location.search;
        var paramsArr = queryStr.split("?");
        if (paramsArr.length > 1) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
            var r = paramsArr[1].match(reg);
            if (r != null) return decodeURI(r[2]);
            return null;
        }
        return null;
    },
    getHashStr: function() {
        return window.location.hash.split("?")[0] || "";
    },
    /**
     * 消息提示框
     * */
    showTip: function(content) {
        if ($("#showTip").html()) {
            $("#showTip").find("div").text(content);
            $("#showTip").show();
        } else {
            var html = '<div class="tip-show" id="showTip"><div>';
            html += content;
            html += '</div></div>';
            $(html).appendTo($('body'));
        }
        setTimeout(function() {
            $("#showTip").hide();
        }, 2000);
    },
    /**
     * loading框显示
     * */
    showLoading: function() {
        if ($("#loadingDiv").length == 0) {
             var html = '<div id="loadingDiv"><div><img src="../../css/images/ajaxloader.gif"><p>努力加载中...</p></div></div>';
            $(html).appendTo($('body'));
        }
        $("#loadingDiv").show();
        return true;
    },
    /**
     * loading框隐藏
     * */
    hideLoading: function() {
        $("#loadingDiv").hide();
    },
    /**
     * 显示遮罩层
     * */
    showCover: function() {
        if ($("#coverBg").html()) {
            $("#coverBg").show();
        } else {
            var html = '<div id="coverBg" class="cover-bg"></div>';
            $(html).appendTo($('body'));
            $("#coverBg").show();
        }
    },
    /**
     * 隐藏遮罩层
     * */
    hideCover: function() {
        ("#coverBg").hide();
    },
    /**
     * 计算字符串长度
     * */
    strlen: function(str) { //在IE8 兼容性模式下 不会报错
        var s = 0;
        for (var i = 0; i < str.length; i++) {
            if (str.charAt(i).match(/[\u0391-\uFFE5]/)) {
                s += 2;
            } else {
                s++;
            }
        }
        return s;
    },
    /**
     * 替换所有的回车换行
     * * */
    transferString: function(content) {
        var string = content;
        if (!string) return null;
        try {
            string = string.replace(/\r\n/g, "<br>")
            string = string.replace(/\n/g, "<br>");
        } catch (e) {
            alert(e.message);
        }
        return string;
    },
    /**
     * 判断当前浏览器是否为微信浏览器
     * */
    isOpenInWechat: function() {
        var ua = navigator.userAgent.toLowerCase();
        if (ua.match(/MicroMessenger/i) == "micromessenger") {
            return true;
        } else {
            return false;
        }
    },
    /**
     * 显示swipebox
     * */
    showSwipeBox: function(data, option) {
        var htmlStr = [],swiperContaierWrap = $("#swiper-containerWrap");
        var data = data;
        //console.dir(data);
        htmlStr.push("<div class=\"swiper-container full\" id=\"swiper-container\"><div class=\"swiper-wrapper\" id=\"fullSwiper\">");
        for(var i = 0,len = data.length; i < len; i++){
            var val = data[i].img||data[i].img.img;
            htmlStr.push("<div class=\"swiper-slide\"><img  src=\""+val+"\" index = \"" + i);
            htmlStr.push("\" class=\"swiper-lazy\" /></div>");
        }
        htmlStr.push("</div><div class=\"swiper-pagination\"></div></div>")

        if(!$("body").find("#swiper-containerWrap").html()){
            $("body").append(swiperContaierWrap);
        }
        swiperContaierWrap.html(htmlStr.join(""));
        swiperContaierWrap.show();
        var setting = {
            autoplay: false,
            pagination: '.swiper-pagination',
            paginationClickable: true,
            // Disable preloading of all images
            preloadImages: true,
            // Enable lazy loading
            lazyLoading: false,
            lazyLoadingInPrevNext:true,
            initialSlide:0
        };
        $.extend(setting, option || {})
        var swiper = new Swiper('#swiper-container', setting);
        swiperContaierWrap.on("click",function(){
            swiperContaierWrap.hide();
        })
        return swiper;
    },
    /**
     * 图片没加载出来时的默认图片
     * */
    imgError: function() {
        $('img').error(function() {
            $(this).attr('src', '../../images/default_img.png');
        });
    },
    /**
     * 回到顶部
     * */
    goTop: function() {
        var gotopHtml = '<a href="javascript:void(0)" class="totop_btn" id="gotop"></a>';
        $(gotopHtml).appendTo($('body'));
        var oBtn = document.getElementById('gotop');
        window.onscroll = function() {
            var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
            if (scrollTop >= 250) {
                $('#gotop').show();
            } else {
                $('#gotop').hide();
            }
        }
        oBtn.onclick = function() {
            $("html,body").animate({
                scrollTop: 0
            }, 500);
        }
    },
    /**
     * 臭美券密码
     * */
    ticketNoStr: function() {
        var $ticketno = $('.ticketno');
        var ticketnoStr = '';
        var ticketnoArr = [];
        $ticketno.each(function(index, obj) {
            ticketnoStr = $(this).text();
            ticketnoStr = ticketnoStr.substring(0, 4) + '&nbsp;' + ticketnoStr.substring(4, 8);
            $(this).html(ticketnoStr);
        });
    },
    /**
     * 图片预加载
     * */
    loaderImg: function(options) {
        var _options = {
            data: [],
            onFinish: function() {},
            onProgress: function(precent) {}
        };
        _options = $.extend(_options, options || {});
        var total = _options.data.length;
        var loaded = 0;

        function loadImage(src) {
            var img = new Image();
            img.onload = function() {
                loaded++;
                checkLoadComplete();
            };
            img.onerror = function() {
                loaded++;
                checkLoadComplete();
            }
            img.src = src;
        }

        function checkLoadComplete() {
            checkLoadProgress();
            if (loaded == total) {
                _options.onFinish();
            }
        }

        function checkLoadProgress() {
            _options.onProgress(parseFloat(loaded / total));
        }
        for (var i = 0; i < total; i++) {
            loadImage(_options.data[i]);
        }
    },
    /**
     * @param fileList type:Array desc:文件URL列表
     * @param onFinish 文件列表加载完成后
     * @param processor 文件列表加载进度
     * */
     require:function(options){
        var _options = {
            fileList: [],
            onFinish: function() {},
            processor: function(percentage) {}
        };
        _options = $.extend(_options, options || {});
        var fileNum = _options.fileList.length,total = fileNum,percentage = 0;
        _options.fileList.forEach(function(val,index){
            add(val);
        })
        var _checkProcess  = function(){
            _options.processor(1 - (--fileNum)/total);
            if(fileNum==0){
                _options.onFinish();
            }
        }
        function add(file){
            var e,type=file.substring(file.lastIndexOf(".")+1);
            if(/^(png|jpg|jpeg|gif)$/.test(type)){
                e = new Image();
                e.src = file;
                e.onerror = e.onload = function(){
                    _checkProcess();
                }
            }else{
                $.get(file,function(){
                    _checkProcess();
                    if(type=="css"){
                        $("head").append("<link rel=\"stylesheet\" type=\"text/css\" href=\""+file+"\">");
                    }
                })
            }
        }
    },
    /**
     * 异步请求数据
     * @param data 请求数据-参数
     * @param url 请求链接
     * @param successCallback 请求成功回调方法
     * @param method 数据请求方式：post/get
     * @param dataType 返回数据格式，默认json
     * @param completeCallback 请求完成回调方法
     * @param showLoading 显示加载层
     * @param async 同步或异步,默认同步
     * */
    ajaxRequest: function(args) {
        var that = this,
            retrunData = null,
            setting = {
                data :{
                    ver: "1.0",
                    time: parseInt(new Date().getTime()/1000),
                    from: window.navigator.userAgent,
                    seq: that.randomString(),
                    body:{userId:""}//just for test
                },
                url:apiConfig.userApi,
                successCallback: null,
                method: "post",
                dataType: "json",
                async: false,
                completeCallback: null,
                showLoading: true
            };
        $.extend(true,setting, args);
        setting.showLoading && that.showLoading();
        $.ajax({
            type: setting.method,
            url: setting.url,
            data: {code:JSON.stringify(setting.data)},
            async: setting.async,
            dataType: setting.dataType,
            success: function(data) {
                if (setting.successCallback) {
                    setting.successCallback(data);
                    setting.showLoading && that.hideLoading();
                    return;
                }
                retrunData = data;
                setting.showLoading && that.hideLoading();
            },
            complete: function() {
                if (setting.completeCallback) {
                    setting.completeCallback();
                }
            },
            error: function(xhr,status) {
                setting.showLoading && that.hideLoading();
                var message = "数据请求失败，请稍后再试!";
                if(status === "parseerror") message = "响应数据格式异常!";
                if(status === "timeout")    message = "请求超时，请稍后再试!"; 
                if(status === "offline")    mesage  = "网络异常，请稍后再试!";                 
                that.showTip(message);
            }
        });
        return retrunData;
    },
    /**
     * 异步表单提交
     * */
    ajaxFormSubmit:function(args){
        var that = this,
            setting = {
                data :{
                    ver: "1.0",
                    time: parseInt(new Date().getTime()/1000),
                    from: window.navigator.userAgent,
                    seq: that.randomString()
                },
                url:apiConfig.fileAddress,
                successCallback: null,
                progressCallback:null,
                method: "post",
                dataType: "json",
                formId:"form",
                completeCallback: null,
                showLoading: true
            };
        $.extend(true,setting, args);
        setting.showLoading && that.showLoading();
        var xhr = new XMLHttpRequest();
        var formObj =  document.getElementById(setting.formId);
        var formData = new FormData(formObj);
        /* event listners */
        xhr.upload.addEventListener("progress", function(e){
            if (setting.progressCallback) {
                setting.showLoading && that.hideLoading();
                setting.progressCallback(e.currentTarget.response);
                return;
            }
        }, false);
        xhr.addEventListener("load", function(e){
            if (setting.successCallback) {
                setting.successCallback(e.currentTarget.response);
                setting.showLoading && that.hideLoading();
                return;
            }
        }, false);
        xhr.addEventListener("error", function(e){ setting.showLoading ? that.hideLoading() : "";that.showTip('网络异常，请稍后再试!')}, false);
        xhr.addEventListener("abort", function(e){ setting.showLoading ? that.hideLoading() : "";that.showTip('网络异常，请稍后再试!')}, false);
        /* Be sure to change the url below to the url of your upload server side script */
        xhr.open(setting.method, setting.url);
        xhr.send(formData);
    },
    /**
     * 检测用户登录信息
     * */
    checkLogin: function() {
        var user = JSON.parse(window.sessionStorage.getItem("user") || window.localStorage.getItem("user"));
        if (user) {
            return user;
        } else {
            commonUtils.alert("你还没登录！请先登录！",function(){
                window.sessionStorage.setItem("location", window.location);
                window.location = "../user/userLogin.html";
            });
        }
    },
    /**
     * 检测本地缓存定位信息
     * */
    checkGeoLoc:function(){
        var addrlati = window.localStorage.getItem("addrlati"),addrlong = window.localStorage.getItem("addrlong");
        if(!addrlati&&!addrlong){
            if (window.navigator.geolocation) {
                commonUtils.showLoading();
                var options = {
                    enableHighAccuracy: true,
                    timeout : 7000
                };
                window.navigator.geolocation.getCurrentPosition(function(position){
                    // 获取到当前位置经纬度
                    addrlong = position.coords.longitude;
                    addrlati = position.coords.latitude;
                    var point = new BMap.Point(addrlong, addrlati);
                    //原始坐标转换成百度坐标
                    BMap.Convertor.translate(point,0,function(){
                        addrlong = point.lng;
                        addrlati = point.lat;
                        window.localStorage.setItem("addrlong",addrlong);
                        window.localStorage.setItem("addrlati",addrlati);
                        commonUtils.hideLoading();
                    });
                }, function(err){
                    commonUtils.hideLoading();
					commonUtils.alert("我们为您准备了更好的推荐服务，请允许微信访问地理位置信息\n您可以在“设置”中允许微信访问位置信息。");
                }, options);
            } else {
                commonUtils.alert("浏览器不支持html5来获取地理位置信息");
            }
        }
    },
    /**
     * 随机字符串
     * */
    randomString: function(len) {
        len = len || 32;
        var $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';
        /****默认去掉了容易混淆的字符oOLl,9gq,Vv,Uu,I1****/
        var maxPos = $chars.length;
        var pwd = '';
        for (var i = 0; i < len; i++) {
            pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
        }
        return pwd;
    },
    /**
     * 检测是否是手机号码
     * */
    isPhone: function(phone) {
        var partten = /^1[3,5,8,7]\d{9}$/;
        return partten.test(phone);
    },

    confirm : function(message,options,callback){
        if($(".confirm").length > 0) return;
        if($.isFunction(options)){
            callback = options;
            options  = null;
        }
        options = $.extend({},options,{
            cancelButtonText : options && options.cancelButtonText || "取消",
            okButtonText     : options && options.okButtonText || "确定",
            message          : message || ""
        });
        var html = "";
        html +='<div class="confirm">';
        html +='<div class="confirm-mask"></div>';
        html +='<div class="confirm-div">';
        html +='<div class="confirm-div-top">';
        html +='<p>'+options.message+'</p>';
        html +='</div>';
        html +='<div class="confirm-div-bottom">';
        html +='<span id ="confirm_ok_button">'+options.okButtonText+'</span>';
        html +='<span id ="confirm_cancel_button">'+options.cancelButtonText+'</span>';
        html +='</div>';
        html +='</div>';  
        html +='</div>';  

        $('body').append(html);
        $(".confirm").fadeIn('fast');
        $("#confirm_cancel_button").on('click',function(e){
            e.stopPropagation();
            $(".confirm").fadeOut('fast');
            $(".confirm").remove();
            $("#confirm_cancel_button").off();
            callback && callback(false);
        }) 
        $("#confirm_ok_button").on('click',function(e){
            e.stopPropagation();
            $(".confirm").fadeOut('fast');
            $(".confirm").remove();
            $("#confirm_ok_button").off();
            callback && callback(true);
        })       
    },

    alert : function(message,options,callback){
        if($(".alert").length > 0) return;
        if($.isFunction(options)){
            callback = options;
            options  = null;
        }
        options = $.extend({},options,{
            okButtonText : options && options.okButtonText || "确定",
            message      : message || ""
        });
        var html = "";
        html += '<div class="alert">';
        html += '<div class="alert-mask"></div>';
        html += '<div class="alert-div">';
        html += '<div class="alert-div-top"><p>'+options.message+'</p></div>';
        html += '<div class="alert-div-bottom"><p>'+options.okButtonText+'</p></div>';
        html += '</div>';    
        html += '</div>';
        $('body').append(html);
        $(".alert").fadeIn('fast');
        $(".alert-div-bottom > p").on('click',function(e){
             e.stopPropagation();
             $(".alert").fadeOut('fast');
             $(".alert").remove();
             $(".alert-div-bottom > p").off();
             callback && callback();
        });
    }  
};
/**
 * 查找数组中是否包含某个元素的方法
 * @return index
 * */
Array.prototype.indexOf = function(Object) {
    for (var i = 0; i < this.length; i++) {
        if (this[i] == Object) {
            return i;
        }
    }
    return -1;
};
