//璁句负棣栭〉
$(function(){
	$(".setHomePage").live("click",function(){
		if(jQuery.browser.msie) {
			document.body.style.behavior = 'url(#default#homepage)';
			document.body.setHomePage("http://www.baidu.com");
		}else if(jQuery.browser.safari){
			window.sidebar.addPanel("涓婃捣鏀跨敵淇℃伅绉戞妧鏈夐檺鍏徃", "http://baidu.com", "");
		}else {
			alert("鎮ㄧ殑娴忚鍣ㄧ姝㈡鎿嶄綔");
		}
		return false;
	});	
});

//鍔犲叆鏀惰棌
$(function(){
	$('.CG_addFav').live('click',function(){
		try {
			window.external.addFavorite("http://baidu.com", "涓婃捣鏀跨敵淇℃伅绉戞妧鏈夐檺鍏徃");
		}
		catch (e) {
			try {
				window.sidebar.addPanel("涓婃捣鏀跨敵淇℃伅绉戞妧鏈夐檺鍏徃", "http://baidu.com", "");
			}
			catch (e) {
//				alert("Sorry!璇锋墜鍔ㄦ坊鍔 ");
			}
		} 
	});
});



//鎼滅储鍒囨崲
var searchGroup = {
	clickCorner: function () {
		$(".cornerBtn").click(
			function () {
				if($('.changeSearch').is(":hidden")){
					$('.changeSearch').show();
				}else{
					setTimeout(function(){$('.changeSearch').hide();},200);
				}
			}
		).blur(function(){
			setTimeout(function(){$('.changeSearch').hide();},200);
		});
		
	},
	clickTab: function () {
		$(".changeSearch a").live("click", function () {
				$(".changeSearch").hide();
				var c = $(this).attr("class");
				if(c && c.indexOf("current") != -1) return;
				searchGroup.searchTab(this);
			});
	},
	searchTab: function (me) {
		$(".changeSearch a.current").removeClass("current");
		$(me).addClass("current");

		var i = $(me).index();
		
		$(".formSearch form:visible").hide();
		$(".formSearch form").eq(i).show();
	}
	
}


//澶у浘杞挱
var indexEye = {
	autoTime:0,
	
	init: function () {
		var eyeObj = $("#ban_box ul li a:eq(0) img:eq(0)");
		eyeObj.load(function () {
			indexEye.autoTime = setTimeout(function () {
			indexEye.autoPlay();
  		}, 10000);
		});

		$("#ban_number a").live("mouseover", function() {
			if($(this).attr("class").indexOf("on") > 0) return;
			indexEye.autoPlay(this);
		});
		
		$("#prevIcon").unbind("click").bind("click", function() {
			indexEye.toPrev();
			
		});
		$("#nextIcon").unbind("click").bind("click", function() {
			indexEye.toNext();
		});

	},

	autoPlay:function (me) {
		clearTimeout(this.autoTime);
		this.turnNumber(me);
		var now = $("#ban_number a.on").index();		
		setTimeout(function () {
			$("#ban_box ul li:visible").fadeOut(0, function() {
				$("#ban_box ul li").eq(now).fadeIn(0);				
			});
		}, 200);
		this.autoTime = setTimeout("indexEye.autoPlay()", 6000);
	},

	turnNumber:function(me) {
		if(typeof(me) == 'undefined') {
			var i = $("#ban_number a.on").index();
			i = i >= $("#ban_number a").length - 1 ? 0 : i + 1;
			me = $("#ban_number a").eq(i);
		}
		$("#ban_number a.on").each(function () {
  		$(this).removeClass("on").addClass('off');
		});
		$(me).removeClass("off").addClass('on');
	},
	
	toNext:function() {
		clearTimeout(this.autoTime);
		var i = $("#ban_number a.on").index();
		if(i >= $("#ban_number a").length - 1 || i < 0){
			return;
		}else{
			i = i + 1;
			$("#ban_box ul li:visible").hide();
			$("#ban_box ul li").eq(i).fadeIn(0);
			var me = $("#ban_number a").eq(i);
			$("#ban_number a.on").each(function () {
				$(this).removeClass("on").addClass('off');
			});
			$(me).removeClass("off").addClass('on');

		}
	},
	
	toPrev: function() {
		clearTimeout(this.autoTime);
		var i = $("#ban_number a.on").index();
		if(i > $("#ban_number a").length - 1 || i <= 0){
			return;
		}else{
			i = i - 1;
			$("#ban_box ul li:visible").hide();
			$("#ban_box ul li").eq(i).fadeIn(0);
			var me = $("#ban_number a").eq(i);
			$("#ban_number a.on").each(function () {
				$(this).removeClass("on").addClass('off');
			});
			$(me).removeClass("off").addClass('on');
		
		}
	}
}

//鍏抽棴鏍囩
var closeBox = {
	init: function () {
		$("#closeBox01 a.closeBtn01").live("click", function() {
			$("#closeBox01").hide();
		});
		
		$("#closeBox02 a.closeBtn02").live("click", function() {
			$("#closeBox02").hide();
		});
	}
}

//鏀跨瓥姹囩紪tab鍒囨崲
var tabLi = {
	init: function() {
//		alert("123");
		$(".policyTit a").live("mouseover", function () {
			var c = $(this).attr("class");
			if(c && c.indexOf("current") != -1) return;
			tabLi.turnNewsTab(this);
		});
	},
		
	turnNewsTab: function (me) {
		$(".policyTit a.current").removeClass("current");
		$(me).addClass("current");

		var i = $(me).index();
		
		$(".policylist ul:visible").hide();
		$(".policylist ul").eq(i).show();
	}
}

//璋冪敤
$(function () {
	searchGroup.clickCorner();
	searchGroup.clickTab();
	indexEye.init();
	closeBox.init();
	tabLi.init();
});

//鍚戜笂婊氬姩
function AutoScroll(obj){
        $(obj).find("ul:first").animate({
                marginTop:"-31px"
        },500,function(){
                $(this).css({marginTop:"0px"}).find("li:first").appendTo(this);
        });
}
$(document).ready(function(){
setInterval('AutoScroll("#scrollHb")',2000);
setInterval('AutoScroll("#scrollHbb")',2000);
});
