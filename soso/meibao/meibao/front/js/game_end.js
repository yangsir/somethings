/**
 * Created by Administrator on 15-5-2.
 */

var history_score=0;
var isaward=0;//是否要填写领手袋信息
/*微信卡卷start*/
var readyFunc = function onBridgeReady(card) {
    try{
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
    }catch(e){}
}
/*微信卡卷end*/


/**
 * 得到奖品信息
 */
function getAward(){
    try{
        $.ajax({
            url:domain_url+"index.php/Credit/getAward",
            type:"post",
            dataType:"json",
            //data:{pagename:"game_end"},
            data:{},
            async:false,//同步请求
            success:function(result){
                typeof result=="string"&&(result=JSON.parse(result));
                (result&&result.result==1)?(function(){
                    var classes=["btn_ffb852","btn_75d5c7","btn_fe8453 no_b_a"],
                        c='btn_bdbdbd';
                    var html="";
                    $.each(result.data,function(i,item){
                        if(history_score>=this.credit){
                            html+='<a href="javascript:;" class="btn bold '+classes[i]+'" data-id="'+this.award_id+'" data-credit="'+this.credit+'">'+this.card_name+'</a>';
                        }else{
                            html+='<a href="javascript:;" class="btn bold '+c+'">'+this.card_name+'</a>';
                        }

                    });
                    $(".small").html(html).find(".btn").on("click",function(){
                        var me=$(this),id=me.data("id");
                        if(id){
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
                                //try{
                                //    $.ajax({
                                //        url:domain_url+"index.php/Credit/getCard",
                                //        type:"post",
                                //        dataType:"json",
                                //        data:{"pagename":"game_end","award_id":id},
                                //        async:false,//同步请求
                                //        success:function(result){
                                //            typeof result=="string"&&(result=JSON.parse(result));
                                //            (result&&result.result==1)?(function(){
                                //                //卡卷拉取
                                //                /*微信卡卷start*/
                                //                //if (typeof WeixinJSBridge === "undefined") {//到这里
                                //                //    document.addEventListener('WeixinJSBridgeReady',readyFunc(result.card), false);
                                //                //} else {
                                //                //    readyFunc(result.card);
                                //                //}
                                //                /*微信卡卷end*/
                                //            }()):showError("获得卡券信息失败");
                                //        },
                                //        error:function(){
                                //            showError("获得卡券信息失败");
                                //        },
                                //        complete:function(){
                                //            $(".loading").hide();
                                //        }
                                //    });
                                //}catch(e){}
                                            $(".loading").hide();
                            }

                        }else{
                            showDialog($('#lp_xq_dialog'));
                        }
                    });
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
            type:"get",
            dataType:"json",
            //data:{pagename:"game"},
            data:{},
            async:false,//同步请求
            success:function(result){
                typeof result=="string"&&(result=JSON.parse(result));
                (result&&result.result==1)?(function(){
                    var html="";
                    isaward=result.isaward;
                    history_score=parseInt(result.current.creadit);//得到当前微信号的游戏历史得分
                    html+='<li data-num="'+result.current.rank+'"><div><img class="one" src="'+result.current.headimgurl+'"/><span class="two">你的排名</span><span class="three">'+history_score+'</span></div></li>';
                    $.each(result.data,function(i,item){
                        if(i>=15) return false;
                        html+='<li data-num="'+this.rank+'"><div><img class="one" src="'+this.headimgurl+'"/><span class="two">'+this.nickname+'</span><span class="three">'+this.creadit+'</span></div></li>';
                    });
                    for(var i=result.data.length;i<3;i++){
                        html+='<li data-num="'+(i+1)+'"></li>';
                    }
                    $(".ranking_list").find("ul").html(html);
                }()):showError("请求分数及名次失败");
            },
            error:function(){
                showError("请求分数及名次失败");
            },
            complete:function(){
                $(".loading").hide();
            }
        });
    }catch(e){}
}
