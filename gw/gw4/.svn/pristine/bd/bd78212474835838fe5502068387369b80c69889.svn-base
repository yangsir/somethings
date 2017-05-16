<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_MakeHtml');
set_time_limit(0);

if(!isset($upnext)) $upnext = 1;
if(empty($gotype)) $gotype = '';
if(empty($pageno)) $pageno = 0;
if(empty($mkpage)) $mkpage = 1;
if(empty($typeid)) $typeid = 0;
if(!isset($uppage)) $uppage = 0;
if(empty($maxpagesize)) $maxpagesize = 50;
$adminID = $cuserLogin->getUserID();

//检测获取所有TAG的ID
	if($upnext==1 || $typeid==0)
	{
		if($typeid>0) {
			$idArray = array();
			$idArray[] = $typeid; //单个TAG的ID
		} else {
			$sql="select * from #@__tagindex order by tag desc"; 
            $dsql->Execute('al',$sql);
			while($row=$dsql->GetObject('al')){       
                $idArray[] = $row->id;
	        }			
		}
	} else {
		$idArray = array();
		$idArray[] = $typeid; //单个TAG的ID
	}



//当前更新栏目的ID
$totalpage=count($idArray);
if(isset($idArray[$pageno]))
{
	$tid = $idArray[$pageno];
}
else
{
		ShowMsg("完成所有TAG列表更新！","javascript:;");
		exit();	
}

$reurl = '';

//更新数组所记录的TAG
if(!empty($tid))
{  
	//筛选是否需生成
	if($all=='0'){        
        $sql="select * from #@__tagindex where id=".$tid; 
        $nic_row=$dsql->GetOne($sql);
		if ($nic_row["maketime"]==0) $nic_make=true;
		else{
			//获取该ID的最新一篇文章，比较生成时间
            $sql="select a.senddate from #@__taglist l left join #@__archives a on l.aid=a.id  where a.arcrank<>-1 and l.tid=".$tid."  order by a.senddate desc"; 
            $nic_row2=$dsql->GetOne($sql);
			if ($nic_row2["senddate"]>$nic_row["maketime"]) $nic_make=true;
			else { 
				$nic_make=false;
				$finishType = true;
			}
			      
		}
	}else{
		$nic_make=true;
	}
	
	if ($nic_make) {
		require_once(DEDEINC."/arc.taghtml.class.php");	
		$lv = new TagListView($tid);
		$ntotalpage = $lv->TotalPage;	
		//如果栏目的文档太多，分多批次更新
		if($ntotalpage <= $maxpagesize || $lv->TypeLink->TypeInfos['ispart']!=0 || $lv->TypeLink->TypeInfos['isdefault']==-1)
		{
			$reurl = $lv->MakeHtml();
			$finishType = true;
		}
		else
		{
			$reurl = $lv->MakeHtml($mkpage,$maxpagesize);
			$finishType = false;
			$mkpage = $mkpage + $maxpagesize;
			if( $mkpage >= ($ntotalpage+1) ) $finishType = true;
		}
		if ($finishType == true){
		  //记录该TAG的此次生成时间：
		  $rec_sql="update #@__tagindex set maketime=".time()." where id=".$tid; 
		  $dsql->Execute('rec',$rec_sql);
		}
	}

}//!empty

$nextpage = $pageno+1;

if($nextpage >= $totalpage && $finishType)
{		
		$reurl = '../tags.html'; 
		ShowMsg("完成所有TAG列表更新！<a href='$reurl' target='_blank'>浏览tag</a>","javascript:;");
		exit();	
}
else
{
	if($finishType)
	{
		$gourl = "makehtml_tag_action_list.php?gotype={$gotype}&uppage=$uppage&maxpagesize=$maxpagesize&typeid=$typeid&pageno=$nextpage&all=$all";
		ShowMsg("成功创建TAG：".$tid."，继续进行操作！",$gourl,0,100);
		exit();
	}
	else
	{
		$gourl = "makehtml_tag_action_list.php?gotype={$gotype}&uppage=$uppage&mkpage=$mkpage&maxpagesize=$maxpagesize&typeid=$typeid&pageno=$pageno&all=$all";
		ShowMsg("TAG：".$tid."，继续进行操作...",$gourl,0,100);
		exit();
	}
}
?>