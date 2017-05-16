//改变管理位置标记--------------------------------------------------------------
function changeAdminFlag(Content){
   var row=parent.parent.headFrame.document.all.Trans.rows[0];
   row.cells[3].innerHTML = Content ;
   return true;
}
//删除各种分类节点--------------------------------------------------------------
function ConfirmDelSort(Result,ID)
//删除产品分类节点--------------------------------------------------------------
{
   if (confirm("您确实要删除本类、子类及所有下属信息条目吗？"))
   {
       window.location.href=Result+".asp?Action=Del&ID="+ID
   } 
}
//分类节点展开和折叠(备用)-------------------------------------------------------------
function AddToSort(imagePath){
  window.opener.LPform.LPattern.focus();								
  window.opener.document.LPform.LPattern.value=imagePath;
  window.opener=null;
  window.close();
}
//类别选择分类------------------------------------------------------------------------
function OpenScript(url,width,height)
{
  var win = window.open(url,"SelectToSort",'width=' + width + ',height=' + height + ',resizable=1,scrollbars=yes,menubar=no,status=yes' );
}
//分类节点展开和折叠-------------------------------------------------------------------
function EndSortChange(a,b)
{
	if(eval(a).style.display=='')
	{
		eval(a).style.display='none';
		eval(b).className='SortEndFolderOpen';
	}
	else
	{
		eval(a).style.display='';
		eval(b).className='SortEndFolderClose';
	}
}
function SortChange(a,b)
{
	if(eval(a).style.display=='')
	{
		eval(a).style.display='none';
		eval(b).className='SortFolderOpen';
	}
	else
	{
		eval(a).style.display='';
		eval(b).className='SortFolderClose';
	}
}
//通用选择删除条目（反选-全选）--------------------------------------------------------
function CheckOthers(form)
{
   for (var i=0;i<form.elements.length;i++)
   {
      var e = form.elements[i];
      if (e.checked==false)
      {
	     e.checked = true;
      }
      else
      {
	     e.checked = false;
      }
   }
}

function CheckAll(form)
{
   for (var i=0;i<form.elements.length;i++)
   {
      var e = form.elements[i];
      e.checked = true;
   }
}
//相关条目删除提示------------------------------------------------------------
function ConfirmDel(message)
{
   if (confirm(message))
   {
      document.formDel.submit()
   }
}
//调用在线内容编辑器-----------------------------------------------------------
function OpenDialog(sURL, iWidth, iHeight)
{
   var oDialog = window.open(sURL, "_EditorDialog", "width=" + iWidth.toString() + ",height=" + iHeight.toString() + ",resizable=no,left=0,top=0,scrollbars=no,status=no,titlebar=no,toolbar=no,menubar=no,location=no");
   oDialog.focus();
}
//检验输入字符的有效性（0-9，a-z,-,_）-------------------------------------------
function voidNum(argValue) 
{
   var flag1=false;
   var compStr="1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-";
   var length2=argValue.length;
   for (var iIndex=0;iIndex<length2;iIndex++)
   {
	   var temp1=compStr.indexOf(argValue.charAt(iIndex));
	   if(temp1==-1) 
	   {
	      flag1=false;
			break;							
	   }
	   else
	   { flag1=true; }
   }
   return flag1;
} 
//检查管理员登录------------------------------------------------------------------------------
function CheckAdminLogin()
{
   var check; 
   if (!voidNum(document.AdminLogin.LoginName.value))
   {
	  alert("请正确输入管理员名称（由0-9,a-z,-_任意组合的字符串）。");
      document.AdminLogin.LoginName.focus();
	  return false;
	  exit;
   }    
   if (!voidNum(document.AdminLogin.LoginPassword.value))
   {
	  alert("请输入管理员密码。");
	  document.AdminLogin.LoginPassword.focus();
	  return false;
	  exit;
   }
   if (!voidNum(document.AdminLogin.VerifyCode.value))
   {
      alert("请正确输入验证码。");
      document.AdminLogin.VerifyCode.focus();
	  return false;
	  exit;
   }
   return true;
}
//检查编辑管理员------------------------------------------------------------------------------
function CheckAdminEdit()
{
   if (document.editAdminForm.AdminName.value.length<3 || document.editAdminForm.AdminName.value.length>10 )
   {
	  alert("请正确输入登录名（由0-9,a-z,-_任意组合3-10位的字符串）。");
      document.editAdminForm.AdminName.focus();
	  return false;
	  exit;
   }	
   var check; 
   if (!voidNum(document.editAdminForm.AdminName.value))
   {
	  alert("请正确输入登录名（由0-9,a-z,-_任意组合3-10位的字符串）。");
      document.editAdminForm.AdminName.focus();
	  return false;
	  exit;
   }
}
//检查编辑会员--------------------------------------------------------------------------------
function CheckMemEdit()
{
   if (document.editMemForm.MemName.value.length<3 || document.editMemForm.MemName.value.length>16 )
   {
	  alert("请正确输入登录名（由0-9,a-z,-_任意组合3-16位的字符串）。");
      document.editMemForm.MemName.focus();
	  return false;
	  exit;
   }	
   var check; 
   if (!voidNum(document.editMemForm.MemName.value))
   {
	  alert("请正确输入登录名（由0-9,a-z,-_任意组合3-16位的字符串）。");
      document.editMemForm.MemName.focus();
	  return false;
	  exit;
   }
}
//管理员退出登录提示--------------------------------------------------------------------------
function AdminOut()
{
   if (confirm("您真的要退出管理操作吗？"))
   location.replace("http://www.dgyouji88.com/Script/CheckAdmin.asp?AdminAction=Out")
}
//跳转到第几页-------------------------------------------------------------------------------
function GoPage(Myself)
{
   window.location.href=Myself+"Page="+document.formDel.SkipPage.value;
}
//添加选择路径的ID，数字路径及生成文本路径--------------------------------------------------------
function AddSort(SortNameCh,ID,Path)
{
	window.opener.editForm.SortNameCh.focus();								
	window.opener.document.editForm.SortNameCh.value=SortNameCh;
	window.opener.document.editForm.SortID.value=ID;
	window.opener.document.editForm.SortPath.value=Path;
    window.opener=null;
    window.close();
}

























//选择起始日期-----------------------------------------------------------------
var DS_x,DS_y;

function dateSelector()  //构造dateSelector对象，用来实现一个日历形式的日期输入框。
{
  var myDate=new Date();
  this.year=myDate.getFullYear();  //定义year属性，年份，默认值为当前系统年份。
  this.month=myDate.getMonth()+1;  //定义month属性，月份，默认值为当前系统月份。
  this.date=myDate.getDate();  //定义date属性，日，默认值为当前系统的日。
  this.inputName='';  //定义inputName属性，即输入框的name，默认值为空。注意：在同一页中出现多个日期输入框，不能有重复的name！
  this.display=display;  //定义display方法，用来显示日期输入框。
}

function display()  //定义dateSelector的display方法，它将实现一个日历形式的日期选择框。
{
  var week=new Array('日','一','二','三','四','五','六');

  document.write("<style type=text/css>");
  document.write("  .ds_font td,span  { font: normal 12px 宋体; color: #000000; }");
  document.write("  .ds_border  { border: 1px solid #000000; cursor: hand; background-color: #DDDDDD }");
  document.write("  .ds_border2  { border: 1px solid #000000; cursor: hand; background-color: #DDDDDD }");
  document.write("</style>");

  document.write("<input style='width:72px;text-align:left;' class='textfield' id='DS_"+this.inputName+"' name='"+this.inputName+"' value='"+this.year+"-"+this.month+"-"+this.date+"' title=双击可进行编缉 ondblclick='this.readOnly=false;this.focus()' onblur='this.readOnly=true' readonly>");
  document.write("<button style='width:60px;height:18px;font-size:12px;margin:1px;border:1px solid #A4B3C8;background-color:#DFE7EF;' type=button onclick=this.nextSibling.style.display='block' onfocus=this.blur()>选择日期</button>");

  document.write("<div style='position:absolute;display:none;text-align:center;width:0px;height:0px;overflow:visible' onselectstart='return false;'>");
  document.write("  <div style='position:absolute;left:-60px;top:20px;width:142px;height:165px;background-color:#F6F6F6;border:1px solid #245B7D;' class=ds_font>");
  document.write("    <table cellpadding=0 cellspacing=1 width=140 height=20 bgcolor=#CEDAE7 onmousedown='DS_x=event.x-parentNode.style.pixelLeft;DS_y=event.y-parentNode.style.pixelTop;setCapture();' onmouseup='releaseCapture();' onmousemove='dsMove(this.parentNode)' style='cursor:move;'>");
  document.write("      <tr align=center>");
  document.write("        <td width=12% onmouseover=this.className='ds_border' onmouseout=this.className='' onclick=subYear(this) title='减小年份'>&lt;&lt;</td>");
  document.write("        <td width=12% onmouseover=this.className='ds_border' onmouseout=this.className='' onclick=subMonth(this) title='减小月份'>&lt;</td>");
  document.write("        <td width=52%><b>"+this.year+"</b><b>年</b><b>"+this.month+"</b><b>月</b></td>");
  document.write("        <td width=12% onmouseover=this.className='ds_border' onmouseout=this.className='' onclick=addMonth(this) title='增加月份'>&gt;</td>");
  document.write("        <td width=12% onmouseover=this.className='ds_border' onmouseout=this.className='' onclick=addYear(this) title='增加年份'>&gt;&gt;</td>");
  document.write("      </tr>");
  document.write("    </table>");

  document.write("    <table cellpadding=0 cellspacing=0 width=140 height=20 onmousedown='DS_x=event.x-parentNode.style.pixelLeft;DS_y=event.y-parentNode.style.pixelTop;setCapture();' onmouseup='releaseCapture();' onmousemove='dsMove(this.parentNode)' style='cursor:move;'>");
  document.write("      <tr align=center>");
  for(i=0;i<7;i++)
	document.write("      <td>"+week[i]+"</td>");
  document.write("      </tr>");
  document.write("    </table>");

  document.write("    <table cellpadding=0 cellspacing=2 width=140 bgcolor=#EEEEEE>");
  for(i=0;i<6;i++)
  {
    document.write("    <tr align=center>");
	for(j=0;j<7;j++)
      document.write("    <td width=10% height=16 onmouseover=if(this.innerText!=''&&this.className!='ds_border2')this.className='ds_border' onmouseout=if(this.className!='ds_border2')this.className='' onclick=getValue(this,document.all('DS_"+this.inputName+"'))></td>");
    document.write("    </tr>");
  }
  document.write("    </table>");

  document.write("    <span style=cursor:hand onclick=this.parentNode.parentNode.style.display='none'>【关闭】</span>");
  document.write("  </div>");
  document.write("</div>");

  dateShow(document.all("DS_"+this.inputName).nextSibling.nextSibling.childNodes[0].childNodes[2],this.year,this.month)
}

function subYear(obj)  //减小年份
{
  var myObj=obj.parentNode.parentNode.parentNode.cells[2].childNodes;
  myObj[0].innerHTML=eval(myObj[0].innerHTML)-1;
  dateShow(obj.parentNode.parentNode.parentNode.nextSibling.nextSibling,eval(myObj[0].innerHTML),eval(myObj[2].innerHTML))
}

function addYear(obj)  //增加年份
{
  var myObj=obj.parentNode.parentNode.parentNode.cells[2].childNodes;
  myObj[0].innerHTML=eval(myObj[0].innerHTML)+1;
  dateShow(obj.parentNode.parentNode.parentNode.nextSibling.nextSibling,eval(myObj[0].innerHTML),eval(myObj[2].innerHTML))
}

function subMonth(obj)  //减小月份
{
  var myObj=obj.parentNode.parentNode.parentNode.cells[2].childNodes;
  var month=eval(myObj[2].innerHTML)-1;
  if(month==0)
  {
    month=12;
    subYear(obj);
  }
  myObj[2].innerHTML=month;
  dateShow(obj.parentNode.parentNode.parentNode.nextSibling.nextSibling,eval(myObj[0].innerHTML),eval(myObj[2].innerHTML))
}

function addMonth(obj)  //增加月份
{
  var myObj=obj.parentNode.parentNode.parentNode.cells[2].childNodes;
  var month=eval(myObj[2].innerHTML)+1;
  if(month==13)
  {
    month=1;
    addYear(obj);
  }
  myObj[2].innerHTML=month;
  dateShow(obj.parentNode.parentNode.parentNode.nextSibling.nextSibling,eval(myObj[0].innerHTML),eval(myObj[2].innerHTML))
}

function dateShow(obj,year,month)  //显示各月份的日
{
  var myDate=new Date(year,month-1,1);
  var today=new Date();
  var day=myDate.getDay();
  var selectDate=obj.parentNode.parentNode.previousSibling.previousSibling.value.split('-');
  var length;
  switch(month)
  {
    case 1:
    case 3:
    case 5:
    case 7:
    case 8:
    case 10:
    case 12:
      length=31;
      break;
    case 4:
    case 6:
    case 9:
    case 11:
      length=30;
      break;
    case 2:
      if((year%4==0)&&(year%100!=0)||(year%400==0))
        length=29;
      else
        length=28;
  }
  for(i=0;i<obj.cells.length;i++)
  {
    obj.cells[i].innerHTML='';
    obj.cells[i].style.color='';
    obj.cells[i].className='';
  }
  for(i=0;i<length;i++)
  {
    obj.cells[i+day].innerHTML=(i+1);
    if(year==today.getFullYear()&&(month-1)==today.getMonth()&&(i+1)==today.getDate())
      obj.cells[i+day].style.color='red';
    if(year==eval(selectDate[0])&&month==eval(selectDate[1])&&(i+1)==eval(selectDate[2]))
      obj.cells[i+day].className='ds_border2';
  }
}

function getValue(obj,inputObj)  //把选择的日期传给输入框
{
  var myObj=inputObj.nextSibling.nextSibling.childNodes[0].childNodes[0].cells[2].childNodes;
  if(obj.innerHTML)
    inputObj.value=myObj[0].innerHTML+"-"+myObj[2].innerHTML+"-"+obj.innerHTML;
  inputObj.nextSibling.nextSibling.style.display='none';
  for(i=0;i<obj.parentNode.parentNode.parentNode.cells.length;i++)
    obj.parentNode.parentNode.parentNode.cells[i].className='';
  obj.className='ds_border2'
}

function dsMove(obj)  //实现层的拖移
{
  if(event.button==1)
  {
    var X=obj.clientLeft;
    var Y=obj.clientTop;
    obj.style.pixelLeft=X+(event.x-DS_x);
    obj.style.pixelTop=Y+(event.y-DS_y);
  }
}