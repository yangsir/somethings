<?php

//��ȡTAG��̬��ַ��  by NIC QQ:59646133
function nic_tag_url($tid)
{
	global $dsql;
	$sql = "select * from #@__tagindex  where id='$tid' ";
    if ($arcRow = $dsql->GetOne($sql)){     
	   
	   //$str = $GLOBALS['cfg_cmspath']."/tag/".ceil($tid/100)."/".Getpinyin($arcRow["tag"])."_".$tid."_1.html";  //Ŀ¼�ṹΪ��ÿ100��IDΪһ��Ŀ¼���� /tag/1/ ��ʼ
       
	   $pinyin_title = GetPinyin(stripslashes($arcRow["tag"]));
       $pinyin_title = str_replace("?","",$pinyin_title);
	   $pinyin_title = str_replace(":","",$pinyin_title);
	   $str = $GLOBALS['cfg_cmspath']."/tag/".$pinyin_title."_".$tid."_1.html";  //Ŀ¼�ṹΪ��/tag/ƴ��_ID_1.html ��ʼ
	}else $str="IDΪ$tid��TAG�ѱ�ɾ����";

	return $str;
}


//��ȡָ�����µ�TAG�����TAG�б�ҳ�ĵ�ַ��  by NIC QQ:59646133
function nic_arc_tag_link($aid)
{
	global $dsql;
	$sql = "select tid from #@__taglist  where aid='$aid' group by tid ";
    $dsql->Execute('ala',$sql);
	while($row=$dsql->GetObject('ala')){ 
	   $url=nic_tag_url($row->tid);
       if ($arcRow = $dsql->GetOne("select * from #@__tagindex  where id='".$row->tid."' ")) $tag=$arcRow["tag"];
	   else $tag="";
	   $str.=" <a href='".$url."' target=_blank>".$tag."</a> ";
	}
	return $str;
}

/****************  
      function GetOneImgUrl  
      @@ ���ܣ���ȡ�Զ����ֶ�ͼƬ��ַ
    *****************/  
      
    function GetOneImgUrl($img,$ftype=1){   
        if($img <> ''){   
            $dtp = new DedeTagParse();   
            $dtp->LoadSource($img);   
            if(is_array($dtp->CTags)){   
                foreach($dtp->CTags as $ctag){   
                    if($ctag->GetName()=='img'){   
                        $width = $ctag->GetAtt('width');   
                        $height = $ctag->GetAtt('height');   
                        $imgurl = trim($ctag->GetInnerText());   
                        $img = '';   
                        if($imgurl != ''){   
                            if($ftype==1){   
                                $img .= $imgurl;   
                            }   
                            else{   
                                $img .= '<img src="'.$imgurl.'" width="'.$width.'" height="'.$height.'" />';   
                            }   
                        }   
                                   
                    }   
                }   
            }   
            $dtp->Clear();   
            return $img;       
        }   
    } 
?>