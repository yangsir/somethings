
function setTab(name,cursel,n)
{
 for(i=0;i<=n;i++){
   var menu=document.getElementById(name+i);
   var con=document.getElementById("con_"+name+"_"+i);
   menu.className=i==cursel?"hover":"nohover";
   con.style.display= i==cursel?"block":"none";   
   //alert(con.style.display);
	}
}

function setTab2(name,cursel,n)
{
 for(i=0;i<=n;i++){
   var menu=document.getElementById(name+i);
   var con=document.getElementById("con_"+name+"_"+i);
   menu.className=i==cursel?"hover":"nohover";
   con.style.display= i==cursel?"block":"none";   
   //alert(con.style.display);
	}
}

function setTab3(name,cursel,n)
{
 for(i=0;i<=n;i++){
   var menu=document.getElementById(name+i);
   var con=document.getElementById("con_"+name+"_"+i);
   menu.className=i==cursel?"hover":"nohover";
   con.style.display= i==cursel?"block":"none";   
   //alert(con.style.display);
	}
}

function setTab4(name,cursel,n)
{
	for(i=0;i<=n;i++){
	   var con=document.getElementById("con_"+name+"_"+i);
	   con.style.display= i==cursel?"block":"none";   
	}
}

function setTab5(name,cursel,n)
{
 for(i=0;i<=n;i++){
   var menu=document.getElementById(name+i);
   var con=document.getElementById("con_"+name+"_"+i);
   menu.className=i==cursel?"hover":"nohover";
   con.style.display= i==cursel?"block":"none";   
   //alert(con.style.display);
	}
}

