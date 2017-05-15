/**
 * Created by tody on 15-4-20.
 * qq:53050855
 * 1.限时制游戏，时间1分钟
 * 2.三只猫分为5分、10分、20分，打中即可获得分数，一次游戏最高能获得1000分，即全部打中；
 * 3.游戏时间越少，猫出现的频率相应越来越快，即打中的难度越大
 */

var t1_c=800;//创建的时间间隔
var t2_up;//给地鼠上去或下去的时间
var t3_down;//地鼠停留的时间
var t4_time=600;//时间走的快慢
var t_crt;//创建的计时器
var t_holedown=[];//给地鼠下去的计时器
var t_time;//剩余时间计时器
var t_start;//开始计时器
var end_boolean=true;//判断是否结束,true 结束或暂停 flase 进行中
var easy=true;//游戏难易度
var score=0;//得分
var hole_kind=['m_10','m_5','m_20'];//地鼠洞的地鼠种类 m_5 60次 m_10 30次 m_20 20次
var mouse_type={'m_10':30,'m_5':60,'m_20':20,'m_1':20};
var holes = $('.hole').find("img");//地鼠洞
var scorediv =$('.score');//分数div
var time = $('.timer_bar');//时间div
var total_mouse=0;//地鼠出现的总数量
var timew = time.width();
var present_1=timew/100;
var translate_height=$("img[src*='m_0']").height();
var history_score=0;//历史得分
var isaward=0;//是否要填写领手袋信息
/*微信卡卷start*/
var readyFunc = function onBridgeReady(card) {
    //document.querySelector('#batchAddCard').addEventListener('click',function(e) {
        var card_id = card.card_id;
        WeixinJSBridge.invoke('batchAddCard', {
                "card_list": [
                    {
                        "card_id": card_id,
                        "card_ext":"{\"code\":\"\",\"openid\":\""+card.openid+"\",\"timestamp\":\""+card.timestamp+"\",\"signature\":\""+card.signature+"\"}"
                    }
                ]
            },
            function(res) {});
    //});
}
/*微信卡卷end*/

function create() {
    var emptys =$("img[src*='m_0']");
    var hole_rdm = ~~(Math.random()*emptys.length);//随机洞穴
    var mouse;
    var mouse_img;
    if(emptys.length!=0){
        for(var i= 0,len=holes.length;i<len;i++){//拿到随机要出地鼠的地洞下标
            if(emptys[hole_rdm]==holes[i]){
                mouse = ~~(Math.random()*hole_kind.length);//随机地鼠种类下标
                mouse_img=hole_kind[mouse];
                if(--mouse_type[hole_kind[mouse]]<=0&&hole_kind.length>1){
                    hole_kind=$.grep(hole_kind,function(item,i){if(i!=mouse) return true;});//遍历地鼠种类，把已出现完毕的地鼠类去掉
                }
                break;
            }
        }
        emptys.eq(hole_rdm).attr("src","./images/"+mouse_img+".png").animate({translateY:"-"+translate_height+"px"},t2_up,"ease-in-out",function(){
            var me=$(this);
            me.on("click",function(){
                var src=me.attr("src");
                setScore(parseInt(src.match(/\d{1,2}/)[0]));//计算分数
                me.attr("src","./images/m_hit.gif").off("click");
            });
            t_holedown[i]=setTimeout(function(){
                me.animate({translateY:0},200,"ease-in-out",function(){
                    me.attr("src","./images/m_0.png").off("click");
                });
            }, t3_down);
        });
    }
    switch(++total_mouse){
        case 8:hole_kind.push("m_1");break;
        case 30:level(2);break;
        case 70:level(3);break;
        case 110:level(4);
    }
    !end_boolean&&(t_crt=setTimeout(create,t1_c));
}

/**
 * 显示用户得分
 * @param num
 */
function setScore(num){
    num==1&&(num=-20);
    score+=num;
    score<0&&(score=0);
    scorediv.data("score",score);
}



function time_sur(){
    timew-=present_1;
    if(timew<=1){game_end();time.width(0);return;}
    time.width(timew);
    !end_boolean&&(t_time=setTimeout(time_sur,t4_time));
}

function level(num){
    //t1_c创建的时间间隔
    //t2_up给地鼠上去或下去的时间
    //t3_down地鼠停留的时间
    //t4_time时间走的快慢

    if(num==1){
        t1_c=600;t2_up=450;t3_down=700;t4_time=600;
    }
    else if(num==2){
        t1_c=500;t2_up=350;t3_down=600;t4_time=600;
    }
    else if(num==3){
        t1_c=400;t2_up=300;t3_down=500;t4_time=600;
    }
    else if(num==4){
        t1_c=300;t2_up=250;t3_down=400;t4_time=600;
    }
}

/**
 * 清除时间定时器
 */
function cleart(){
    $(".timer").removeClass("begin");
    if(t_crt){clearTimeout(t_crt);}
    if(t_time){clearTimeout(t_time);}
    if(t_start){clearTimeout(t_start);}
}
function restart(){
    cleart();
    restate();
    end_boolean=false;
    $(".timer").addClass("begin");
    time_sur();
    create();
}
function restate(){
    end_boolean=true;
    if(easy){level(1);}
    score=0;
    $(".mouses").css("margin-top","-"+translate_height+"px");
    $('.hole').css({"transform":"translateY("+translate_height+"px)","-webkit-transform":"translateY("+translate_height+"px)"});
    translate_height-=20;
}

/**
 * 游戏ready提示
 */
function ready(){
    var ready=$(".ready");
    ready.show().animate({"scale":"1.3"},"800","linear",function(){
        ready.text("GO!").css("left","40%").animate({"scale":"1"},"800","linear",function(){
            ready.hide();
        });
    });
}
/**
 * 游戏开始
 */
function game_start() {
    ready();
    t_start=setTimeout(function(){
        restart();
        getAward();//得到奖品信息
        $(".pause").on("click",function(){
            //暂停游戏
            $(".d_score").data("score",score);
            getList();
            showDialog($("#pause_dialog"));
            game_pause();
        });
    },2500);
}

/**
 * 游戏结束
 */
function game_end(){
    cleart();
    end_boolean=true;
    $(".dialog").hide();
    $(".full_mask").hide();
    addCredit();
    setTimeout(function(){
        var ready=$(".ready").css({"left":"0","visibility":"hidden"}).text("Game over!");
        ready.show().css({"left":($(window).width()-ready.width())/2,"visibility":"visible"}).animate({"scale":"1.3"},"800","linear",function(){
        });
    },1000);
   setTimeout(function(){
       var total_score=score+history_score;
       var $dag=$("#open_lp_dialog");
       var flag=false;
//       var txtAry={"3000":"喵，主人，<br/>您已获得89元换购精品钱包的特权，<br/>时间有限，快点点击兑换吧！",
//           "5000":"喵，主人，<br/>您已获得199元购精美手袋的特权，<br/>快快点击兑换吧！<br/>离免费大奖不远啦，加油！",
//           "10000":"喵，主人，<br/>您终于达到10000+积分啦，<br/>看看自己的排名吧，挺住第一名啊，<br/>免费手袋离你不远啦"
//       };
       var btn_10000=$(".btn[data-credit='10000']");
       var btn_5000=$(".btn[data-credit='5000']");
       var btn_3000=$(".btn[data-credit='3000']");
       if(total_score>=10000){
           btn_10000.removeClass("btn_bdbdbd").addClass("btn_fe8453");
           btn_5000.removeClass("btn_bdbdbd").addClass("btn_fe8453 no_b_a");
           btn_3000.removeClass("btn_bdbdbd").addClass("btn_fe8453 no_b_a");
           flag=true;
           $("#lp_tips").html(btn_10000.data("descrption"));
           $("#lp_img").attr("src",btn_10000.data("img"));
       }else if(total_score>=5000){
           btn_5000.removeClass("btn_bdbdbd").addClass("btn_fe8453");
           btn_3000.removeClass("btn_bdbdbd").addClass("btn_fe8453 no_b_a");
           flag=true;
           $("#lp_tips").html(btn_5000.data("descrption"));
           $("#lp_img").attr("src",btn_5000.data("img"));
       }else if(total_score>=3000){
           btn_3000.removeClass("btn_bdbdbd").addClass("btn_fe8453");
           flag=true;
           $("#lp_tips").html(btn_3000.data("descrption"));
           $("#lp_img").attr("src",btn_3000.data("img"));
       }

       if(flag){
           showDialog($dag);
           $(".btn_bdbdbd").on("click",function(){
               showDialog($('#lp_xq_dialog'));
           });
           $(".btn_fe8453").on("click",function(){
               var me=$(this),id=me.data("id");
               if(me.data("credit")=="10000"){
                   if(isaward=="1"){
                       var from=$("#lp_from_dialog")
                       showDialog(from);
                       $(".btn_a").on("click",function(){
                           var name=$("#name").val().trim();
                           var phone=$("#phone").val().trim();
                           var address=$("#address").val().trim();
                           if(!name){
                               $.fn.toolTip("姓名不能为空！");
                               return;
                           }
                           if(!phone){
                               $.fn.toolTip("联系手机不能为空！");
                               return;
                           }else if(phone&&(!/^1\d{10}$/.test(phone))){
                               $.fn.toolTip("联系手机填写不正确！");
                               return;
                           }
                           if(!address){
                               $.fn.toolTip("收货地址不能为空！");
                               return;
                           }
                           try{
                               $.ajax({
                                   url:domain_url+"index.php/Credit/awardMember",
                                   type:"post",
                                   dataType:"json",
                                   data:{"name":name,"phone":phone,"address":address},
                                   async:false,//同步请求
                                   success:function(result){
                                       typeof result=="string"&&(result=JSON.parse(result));
                                       (result&&result.result==1)?(function(){
                                           from.hide();
                                       }()):showError("保存信息失败，请重试");
                                   },
                                   error:function(){
                                       showError("保存信息失败，请重试");
                                   },
                                   complete:function(){
                                       $(".loading").hide();
                                   }
                               });
                           }catch(e){}
                       });
                   }else{
                       alert("看看自己的排名吧，挺住第一名啊，免费手袋离你不远啦！");
                   }
               }else{
//                   try{
//                       $.ajax({
//                           url:domain_url+"index.php/Credit/getCard",
//                           type:"post",
//                           dataType:"json",
//                           //data:{"pagename":"game","award_id":id},
//                           data:{"award_id":id},
//                           async:false,//同步请求
//                           success:function(result){
//                               typeof result=="string"&&(result=JSON.parse(result));
//                               (result&&result.result==1)?(function(){
//                                   //卡卷拉取
//                                   /*微信卡卷start*/
//                                   if (typeof WeixinJSBridge === "undefined") {//到这里
//                                       document.addEventListener('WeixinJSBridgeReady',readyFunc(result.card), false);
//                                   } else {
//                                       readyFunc(result.card);
//                                   }
//                                   /*微信卡卷end*/
//                               }()):showError("获得卡券信息失败");
//                           },
//                           error:function(){
//                               showError("获得卡券信息失败");
//                           },
//                           complete:function(){
//                               $(".loading").hide();
//                           }
//                       });
//                   }catch(e){}
               }
           });
       }else{
           location.href="./game_end.html?score="+score;
       }
   },2000);
}

/**
 * 游戏暂停
 */
function game_pause(){
    cleart();
    end_boolean=true;
}

/**
 * 游戏继续
 */
function game_continue(){
    end_boolean=false;
    $(".timer").addClass("begin");
    if(total_mouse<130){//少天110次方可继续
        time_sur();
        create();
    }else{
        game_end();//结束
    }
}


function loadImg(){
    new Image().src="./images/m_0.png";
    new Image().src="./images/m_1.png";
    new Image().src="./images/m_5.png";
    new Image().src="./images/m_10.png";
    new Image().src="./images/m_20.png";
    new Image().src="./images/m_hit.gif";
    new Image().src="./images/game_bg_lp.jpg";
}

/**
 * 得到奖品信息
 */
function getAward(){
    try{
        $.ajax({
            url:domain_url+"index.php/Credit/getAward",
            type:"post",
            dataType:"json",
//            data:{pagename:"game"},
            async:false,//同步请求
            success:function(result){
                typeof result=="string"&&(result=JSON.parse(result));
                (result&&result.result==1)?(function(){
                    var html="";
                    $.each(result.data,function(){
                        html+='<a href="javascript:;" class="btn bold btn_bdbdbd" data-credit="'+this.credit+'" data-id="'+this.award_id+'" data-descrption="'+this.descrption+'" data-img="'+this.img+'">'+this.card_name+'</a>';
                        new Image().src=this.img;
                    });
                    $("#open_lp_dialog").find(".btns").html(html);
                }()):showError("得到奖品信息失败");
            },
            error:function(){
                showError("得到奖品信息失败");
            },
            complete:function(){
                $(".loading").hide();
            }
        });
    }catch(e){}
}

function getList(){
    //请求当前用户的名次及前三名的排名信息
    try{
        $.ajax({
            url:domain_url + 'index.php/Credit/getList',
            type:"post",
            dataType:"json",
//            data:{pagename:"game"},
            async:false,//同步请求
            success:function(result){
                typeof result=="string"&&(result=JSON.parse(result));
                (result&&result.result==1)?(function(){
                    var html="";
                    isaward=result.isaward;
                    history_score=parseInt(result.current.creadit);//得到当前微信号的游戏历史得分
                    $.each(result.data,function(i,item){
                        if(i>=3) return false;//只取前3条
                        html+='<li data-num="'+item.rank+'"><div><img class="one" src="'+item.headimgurl+'"/><span class="two">'+item.nickname+'</span><span class="three">'+item.creadit+'</span></div></li>';
                    });
                    html+='<li data-num="'+result.current.rank+'"><div><img class="one" src="'+result.current.headimgurl+'"/><span class="two">你的排名</span><span class="three">'+history_score+'</span></div></li>';
                    $(".ranking_list").find("ul").html(html);

                    /*weixin js share start*/
                    wx.config({
                        debug: true,
                        appId: result.signPackage.appId,
                        timestamp: result.signPackage.timestamp,
                        nonceStr: result.signPackage.nonceStr,
                        signature: result.signPackage.signature,
                        jsApiList: [
                            'onMenuShareTimeline',
                            'onMenuShareAppMessage',
                        ]
                    });
                    weixin_share(result.data.ucode);
                    /*weixin js share end*/

                }()):showError("请求分数及名次失败");
            },
            error:function(){
                showError("请求分数及名次失败");
            },
            complete:function(){

            }
        });
    }catch(e){}
}

/**
 * 添加积分
 */
function addCredit(){
    try{
        $.ajax({
            url:domain_url+"index.php/Credit/addCredit",
            type:"post",
            dataType:"json",
            data:{credit:score},
            async:false,//同步请求
            success:function(result){
                typeof result=="string"&&(result=JSON.parse(result));
                (result&&result.result==1)?console.log(result):showError("添加积分失败");
            },
            error:function(){
                showError("添加积分失败");
            },
            complete:function(){
                $(".loading").hide();
            }
        });
    }catch(e){}
}

/**
 * 游戏规则
 */
function getGameShow(){
    try{
        $.ajax({
            url:domain_url+"index.php/Credit/gameshow",
            type:"post",
            dataType:"json",
            data:{credit:score},
            async:false,//同步请求
            success:function(result){
                typeof result=="string"&&(result=JSON.parse(result));
                (result&&result.result==1)?(function(r){
                    var dd=$("#explain_dialog").find("dd");
                    var formateDate=function(date){
                            date=new Date(parseInt(date));
                            return date.getFullYear()+"年"+(date.getMonth()+1)+"月"+date.getDate()+"日";
                        };
                    dd.eq(0).text(r.starttime+"-"+r.endtime);
                    dd.eq(1).text(r.tip1);
                    dd.eq(2).text(r.tip2);
                    dd.eq(3).text(r.tip3);
                    dd.eq(4).text(r.tip4);
                }(result.data)):showError("请求游戏规则失败");
            },
            error:function(){
                showError("请求游戏规则失败");
            },
            complete:function(){
                $(".loading").hide();
            }
        });
    }catch(e){}
}
