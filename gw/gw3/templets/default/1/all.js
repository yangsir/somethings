<!--
//首页的标签切换
    function changePreview(tabNumber) {
      var tabNames;
      
      for (i = 1; i <= 4; i++) {
        document.getElementById('infotable' + i).style.display = 'none';
      }
      document.getElementById('infotable' + tabNumber).style.display = '';

      tds = document.getElementById('infotable').getElementsByTagName('td');
      for(i = 0; i < tds.length; i++) {
        if (tds[i].className == 'active menu')
          tds[i].className = 'menu';
      }
      document.getElementById('tab' + tabNumber).className = 'active menu';
    }
  
   //-->
  
function sc1(){
  document.getElementById("JavascriptDiv1").style.top=(document.documentElement.scrollTop+(document.documentElement.clientHeight-document.getElementById("JavascriptDiv1").offsetHeight)/2)+"px";
 document.getElementById("JavascriptDiv1").style.right=(document.documentElement.scrollLeft)+"px";
}
/*顶部固定条 */
function sc2(){
 document.getElementById("by_tool_bar").style.top=(document.documentElement.scrollTop)+"px";
 document.getElementById("by_tool_bar").style.left=(document.documentElement.scrollLeft)+"px";
}

function scall(){
 sc1();
 sc2();
}
window.onscroll=scall;
window.onresize=scall;
window.onload=scall;
//-->


//跳转菜单
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}






function ChangeHideIt(sItem) 
{

	  //hideAll();
	if (document.getElementById(sItem).style.display=="none") {
	 document.getElementById(sItem).style.display="";
	 } else {
	   document.getElementById(sItem).style.display="none"; 
	 }
  
}

 
function killerrors() { 
return true; 
} 
window.onerror = killerrors; 
 