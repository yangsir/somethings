/**
 * Created by tody on 2015/03/21
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

FastClick.attach(document.body);//引入fastClick.js,避免tap点击遮罩层时影响到底层

// 定义变量
var screenHeight=$(window).height();
var pageNumber=0;
var currentDistance=0;
var contentList=$(".content-list");
var tmpEndY,tmpStartY;
var metro=$(".box");
var isDataReady=false;//ajax请求数据是否准备好
var loader=$(".loading");//加载loading
var arrow=$(".up_arrow");
var ws_title=$(".ws_title").find("img");
var isIos=/iphone|ipad|ipod/i.test(navigator.userAgent.toLowerCase());
var tutorId=0;//用于保存用户点击导师id
// 绑定翻页
contentList.on("touchstart",function(event){
    if (!event.touches.length) {
        return;
    }
    tmpEndY = 0;
    var touch = event.touches[0];
    tmpStartY = touch.pageY;
}).on("touchmove",function(event){
        event.preventDefault();
        if (!event.touches.length) {
            return;
        }
        var touch = event.touches[0];
        tmpEndY = touch.pageY;
    }).on("touchend",function(event){
        // 触摸结束时判断执行上翻或者下翻
        var endY = tmpEndY;
        var startY = tmpStartY;
        if (endY && endY !== startY && endY-startY<=-25) {
            screenForward(true);//第2屏不能向下滑，只能点人物列表进入
        }else if(endY && endY !== startY && endY-startY>=25){
            screenBack();
        }
    });

$(window).on("resize",function () {
    $(".content-li").each(function () {
        $(this).css("height", $(window).height());
    });
    screenHeight = $(window).height();
}).trigger("resize");

/**
 * 用户退出微信时向服务器保存导师id
 */
//window.onbeforeunload=function(){
//    setTutorId(tutorId);
//};

metro.on("click",function(){
    var intro=$(this).data("intro");
    tutorId=$(this).data("id");
    //alert(tutorId);
    loader.show();
    $(".p2").parent().css("background",intro.bgColor).find("img").each(function(i){
        i==0&&$(this).attr("src",intro.head);
        i==1&&$(this).attr("src",intro.ewm);
    });
    var img=new Image();
    var run=function(){
        screenForward(false);//点击人物进入向页面向下滑一页
        loader.hide();
        setTutorId(tutorId);
    };
    img.src=intro.head;
    if(img.complete){
        run();
    }else{
        img.onload=function(){
            run();
        };
    }
});

/**
 * 把用户点击过的导师id传到服务器
 * @param id
 */
function setTutorId(id){
    //todo some_url换成服务器地址
    id&&$.get("record.php?id="+id);
}
// 渐显元素
function showElement(){
    switch (pageNumber){
        case 0://首页
            break;
        case 1://第二页
            !ws_title.hasClass("scaleIn_2")&&ws_title.addClass("scaleIn_2");
            $(".ewm").removeClass("shake");
            isDataReady&&(isDataReady=false,setTimeout(function(){
                rolatedImg();
            },600));
            break;
        default ://第三页
            $(".ewm").addClass("shake");
    }
}

/**
 * 向上翻一屏
 * 当是第1屏标上向上拖动
 */
function screenBack(){
    var translateString,transitionString;
    pageNumber--;
    if(pageNumber<0){
        pageNumber=0;
    }
    currentDistance=screenHeight*pageNumber;
    translateString="translate3d(0, -"+currentDistance+"px, 0)";
    transitionString="all 0.5s ease-in";
    contentList.css({"-webkit-transform":translateString,"transform":translateString,"-webkit-transition":transitionString,"transition":transitionString});
    showElement();
}

/**
 * 向下翻一屏
 * 当是第3屏向下拖动时则向上翻一页
 * @param flag 当flag为true第2页不能向下滑
 */
function screenForward(flag){
    var translateString,transitionString;
    pageNumber++;
    if(pageNumber>=3){
        pageNumber=2;
        screenBack();
        return;
    }
    if(pageNumber==2&&flag){//第2页不能向下滑
        pageNumber=1;
        return;
    }
    currentDistance=screenHeight*pageNumber;
    translateString="translate3d(0, -"+currentDistance+"px, 0)";
    transitionString="all 0.5s ease-in";
    contentList.css({"-webkit-transform":translateString,"transform":translateString,"-webkit-transition":transitionString,"transition":transitionString});
    showElement();
}

/**
 * 开始页面初始化
 */
function begin(){
    ajax_headList(true);
    //1分钟向后台请求一次数据
    setInterval(function(){
        ajax_headList(false);
    },60000);
}

/**
 * 把ajax请求回来的json对象组成人员的背面图
 * @param data json数据
 * @param flag 页面初始化时传true，其它可不传
 */
function change_img(data,flag){
    if(data){
        metro.each(function(i){
            var one=data[i],flip=$(this).data("state"),$flip=$(this).find(".flip").find("img");
            new Image().src=one.src;//加快图片加载
            $(this).data("intro",JSON.stringify(one.intro)).data("id",one.id).find("."+flip).find("img").attr("src",one.src);
            if(isIos&&/head_00/.test($flip.attr("src"))){
                $flip.attr("src",one.src);
            }
        });
        isDataReady=true;//图像数据已准备
        pageNumber==1&&(isDataReady=false,rolatedImg());//如果是第2页的话，请求有数据则更换人物头像
    }
}

/**
 * 翻转图片
 */
function rolatedImg(){
    if(isIos){
        var h=$(".box").height();
        //ios系统
        metro.each(function(){
            var flip=$(this).data("state"),me=$(this);
            me.find("."+flip).css({"-webkit-transform":"translateY(-"+h+"px)"}).animate({"translateY":"0px","opacity":"1"},500,"linear",function(){
                me.find("."+flip).siblings().animate({"translateY":"-"+h+"px","opacity":"0"},500,"linear",function(){
                   $(this).css({"-webkit-transition-duration":"0","-webkit-transform":"translateY("+h+"px)"});
                });
            });
            $(this).data("state",flip=="fliped"?"flip":"fliped");
        });
    }else{
        //其它
        metro.each(function(){
            var flip=$(this).data("state");
            $(this).find("."+flip).css("-webkit-transform","rotate3d(1,0,0,0deg)").siblings().css("-webkit-transform","rotate3d(1,0,0,-180deg)");
            $(this).data("state",flip=="fliped"?"flip":"fliped");
        });
    }
}

/**
 * 向服务器请求人员头像列表
 * 报文格式：
 * [{id:"01","src":"./images/head_01.jpg","intro":{"head":"./images/intro.jpg","ewm":"./images/ewm.png","bgColor":"#fdf5dc"}}]
 */
function ajax_headList(flag){
    $.ajax({
        url:"data.php",//todo 服务器地址
        type:"get",
        dataType:"json",
        data:"",
        success:function(result){
            if(result){//请求成功，则把请求到的人员列表写入第2页
                change_img(result,flag);
            }else{
                $.fn.toolTip("抱歉！获得人员列表出错，请刷新页面！");
            }
        },
        error:function(){
            $.fn.toolTip("抱歉！获得人员列表出错，请刷新页面！");
        }
    });
}
