// JavaScript Document
$(function(){
	
	//对content使用masonry插件
	$('#content').masonry({
		itemSelector : '.post',
		columnWidth : 251
	});
		
//首先给窗口绑定事件scroll
$(window).bind("scroll",function() {
    // 然后判断窗口的滚动条是否接近页面底部，这里的20可以自定义
    if ($(document).scrollTop() + $(window).height() > $(document).height() - 20) {
 
        var link = $(".next_page a");
        //首先取得下一页的链接地址
        var href = link.attr("href");
        //判断下一页的链接地址是否存在
        if (href != undefined) {
 
            //如果存在的话，用ajax获取数据
            $.ajax({
                type: "get",
                url: href,
                success: function(data) {
                    //将返回的数据进行处理，挑选出class是post的内容块
                    var $res = $(data).find(".post");
 
                    //结合masonry插件，将内容append进ID是content的内容块中
                    $("#content").append($res).masonry('appended', $res);
 
                    //newHref获取返回的内容中的下一页的链接地址
                    var newHref = $(data).find(".next_page a").attr("href");
 
                    //判断下一页地址是否存在，如果存在，替换当前页的链接地址。不存在，将当前页链接地址属性href移除，并替换内容为"下一页没有了"
                    if (newHref != undefined) {
                        link.attr("href", newHref);
                    } else {
                        link.html("下一页没有了").removeAttr("href");
                    };
                }
            });
        };
 
        //返回false，使得点击进入新页面失效
        return false;
 
    }
})
  
});
