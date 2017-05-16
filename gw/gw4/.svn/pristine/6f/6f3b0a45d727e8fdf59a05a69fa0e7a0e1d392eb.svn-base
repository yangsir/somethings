/*
»ÃµÆÆ¬ÇÐ»»
*/
(function() {
    var focus = document.getElementsByName("focus");
    for (var i = 0,
    len = focus.length; i < len; i++) {
        new pc.tab.focus({					 
            target: pc.getElems('li', focus[i].parentNode),			
            effect: 'slide',
			autoPlay:true,
			control:  pc.getElems('#fCtrl a'),
			merge:true,
			stay:3000,
			delay:0
        });
    };
})();

/*
ÉàÇ©ÇÐ»»
*/
(function(){
function childNodes(ele,tagName)
{
	var childs=ele.childNodes;
	var temp=[];
	for(var i=0;i<childs.length;i++)
	{
		if(childs[i].nodeType==1&&(tagName?(childs[i]["tagName"]==tagName):true))
		{
			temp.push(childs[i]);
		};
	};
	return temp;
};

function removeAll(ele)
{
	for(var i=0;i<ele.length;i++)
	{
		ele[i].className=ele[i].className.replace(/cur/," ");
	};
};
function hideAll(ele)
{
	for(var i=0;i<ele.length;i++)
	{
		ele[i].className=ele[i].className.replace(/hide/," ")+" hide";
	};
};
function showImg(ele)
{
	var imgs=ele.getElementsByTagName("img");
	for(var i=0;i<imgs.length;i++)
	{
		var src=imgs[i].getAttribute("_src");
		if(src&&src!="")
		{
			imgs[i].setAttribute("src",src);
			imgs[i].setAttribute("_src","");
		};
	};
};
var tabs=document.getElementsByName("tab");
for(var i=0;i<tabs.length;i++)
{
	var divs=childNodes(tabs[i].parentNode,"DIV");
    var nav=divs[0];
	var temp=childNodes(nav);
	var tempParent=temp[0].tagName=="UL"?temp[0]:temp[1];
	var navsTemp=childNodes(tempParent,"LI");
	var list=divs[1];
	var listsTemp=childNodes(list);
    for(var j=0;j<navsTemp.length;j++)
	{
		(function(){
		var cur=j;
		var lists=listsTemp;
		var navs=navsTemp;
		function handle()
		{
			try
			{
			if(!navs[cur].className.indexOf("cur")>-1)
			{
				removeAll(navs);
				navs[cur].className+=" cur";
				hideAll(lists);
				lists[cur].className=lists[cur].className.replace(/hide/," ");
				showImg(lists[cur]);
			};
			}catch(e){};
		};
		$U.hoverDelay(navs[cur],handle,function(){},50,0)
		})();
	};
};

})();

var tuan=new pc.tab({
    target: pc.getElems('#J-tuan ul'),
    effect: 'slide',
	autoPlay:true,
	control: false,
	merge:true,
	stay:3000,
	delay:0,
    direction: 'x'
});


function getFav(type){
  switch(type){
    case "qq":
    window.open("http://v.t.qq.com/share/share.php?url="+enUrl+"&title="+enTitle);
    break;
    case "kaixin":
    window.open("http://www.kaixin001.com/repaste/bshare.php?rcontent="+encodeURIComponent(website)+"&rtitle="+enTitle+"&rurl="+enUrl);
    break;
    case "renren":
    window.open("http://share.renren.com/share/buttonshare.do?link="+enUrl+"&title="+enTitle);
    break;
    case "taobao":
    window.open("http://share.jianghu.taobao.com/share/addShare.htm?url="+enUrl);
    break;
    case "sina":
    window.open("http://v.t.sina.com.cn/share/share.php?&title="+enTitle+"&url="+enUrl);
    break;
    case "netease":
    window.open("http://t.163.com/article/user/checkLogin.do?link=http://news.163.com/&source="+encodeURIComponent(website)+"&info="+enTitle+" "+enUrl+"&"+new Date().getTime());
    break;
    case "douban":
    window.open("http://www.douban.com/recommend/?url="+enUrl+"&title="+enTitle+"&v=1");
    break;
    case "sohu":
    window.open("http://t.sohu.com/third/post.jsp?url="+enUrl+"&title="+curTitle);
    break;
  }
}

