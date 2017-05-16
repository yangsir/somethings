function CheckLogin(){
	  var taget_obj = document.getElementById('_userlogin');
	  myajax = new DedeAjax(taget_obj,false,false,'','','');
	  myajax.SendGet2("http://www.newme.com/member/ajax_loginsta.php");
	  DedeXHTTP = null;
	}
// Thumbnail
$(document).ready(function(){
	$(".list_img_two li, .list_img_five li").find("a").animate({opacity:0.65});
	$(".list_img_two li, .list_img_five li").hover(function(){
		$(this).find("a").animate({top:0,opacity:0.65},{queue:false,duration:250});
	},function(){
		$(this).find("a").animate({top:120,opacity:0.65},{queue:false,duration:250});
	});
});

$(document).ready(function(){
	$(".list_img_seven li").find("a").animate({opacity:0.65});
	$(".list_img_seven li").hover(function(){
		$(this).find("a").animate({top:0,opacity:0.65},{queue:false,duration:350});
	},function(){
		$(this).find("a").animate({top:300,opacity:0.65},{queue:false,duration:350});
	});
});
$(function(){
			$('#slides').slides({
				preload: true,
				preloadImage: 'http://www.newme.com/templets/meme/images/loading.gif',
				play: 5000,
				pause: 2500,
				hoverPause: true,
				animationStart: function(current){
					$('.caption').animate({
						bottom:-150
					},200);
					if (window.console && console.log) {
						// example return of current slide number
						console.log('animationStart on slide: ', current);
					};
				},
				animationComplete: function(current){
					$('.caption').animate({
						bottom:0
					},200);
					if (window.console && console.log) {
						// example return of current slide number
						console.log('animationComplete on slide: ', current);
					};
				},
				slidesLoaded: function() {
					$('.caption').animate({
						bottom:0
					},200);
				}
			});
		});
// Nav
$(document).ready(function(){
	$(".nav").superfish();
});

// Add
$(document).ready(function(){
	$(".products li:nth-child(2n+2)").addClass("no");
	$(".brand li:nth-child(3n+3)").addClass("no");
	$(".recommend_list li:nth-child(2n+3)").addClass("no");
	$(".list_fashion li:nth-child(2n+2)").addClass("no");
	$(".list_img_two li:nth-child(2n+2)").addClass("no");
	$(".list_img_three li:nth-child(3n+3)").addClass("no");
	$(".list_shop li:nth-child(3n+3)").addClass("no");
	$(".sidebox .menu li:nth-child(3n+3)").addClass("no");
	$(".sidebox .blogroll li:nth-child(3n+3)").addClass("no");
	$(".widget_categories ul li:nth-child(3n+3)").addClass("no");
	$(".widget_meta ul li:nth-child(3n+3)").addClass("no");
	$(".widget_archive ul li:nth-child(3n+3)").addClass("no");
	$(".widget_pages ul li:nth-child(3n+3)").addClass("no");
	$(".category_store li:nth-child(3n+3)").addClass("no");
	$(".related_post li:nth-child(4n+4)").addClass("no");
	$(".recommend_list li").first().addClass("none");
	$(".main_right li .title_a").first().addClass("nobg");
	$(".list_img_five li:nth-child(5n+5)").addClass("no");
	$(".list_img_seven li:nth-child(7n+7)").addClass("no");
});

// Thumbnail
$(document).ready(function(){
	$(".list_img_two li, .list_img_five li").find("a").animate({opacity:0.65});
	$(".list_img_two li, .list_img_five li").hover(function(){
		$(this).find("a").animate({top:0,opacity:0.65},{queue:false,duration:250});
	},function(){
		$(this).find("a").animate({top:120,opacity:0.65},{queue:false,duration:250});
	});
});

$(document).ready(function(){
	$(".list_img_seven li").find("a").animate({opacity:0.65});
	$(".list_img_seven li").hover(function(){
		$(this).find("a").animate({top:0,opacity:0.65},{queue:false,duration:350});
	},function(){
		$(this).find("a").animate({top:300,opacity:0.65},{queue:false,duration:350});
	});
});

