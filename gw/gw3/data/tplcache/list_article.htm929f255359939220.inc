<?php
$z[0]=Array("field","",110,129);
$z[0][4]['name']="title";
$z[1]=Array("global","",130,156);
$z[1][4]['name']="cfg_webname";
$z[2]=Array("field","",197,226);
$z[2][4]['name']="keywords";
$z[3]=Array("field","",266,324);
$z[3][4]['name']="description";
$z[3][4]['function']="html2text(@me)";
$z[4]=Array("global","",342,374);
$z[4][4]['name']="cfg_templets_skin";
$z[5]=Array("global","",458,490);
$z[5][4]['name']="cfg_templets_skin";
$z[6]=Array("global","",562,594);
$z[6][4]['name']="cfg_templets_skin";
$z[7]=Array("global","",668,700);
$z[7][4]['name']="cfg_templets_skin";
$z[8]=Array("global","",765,797);
$z[8][4]['name']="cfg_templets_skin";
$z[9]=Array("global","",1309,1334);
$z[9][4]['name']="cfg_cmsurl";
$z[10]=Array("channel","\r\n      	<li><a href='[field:typeurl/]' [field:rel/]><span>[field:typename/]</span></a></li>\r\n      	",1374,1617);
$z[10][4]['type']="top";
$z[10][4]['row']="10";
$z[10][4]['currentstyle']="<li class='hover'><a href='~typelink~' ~rel~><span>~typename~</span></a></li>";
$z[11]=Array("global","",1907,1935);
$z[11][4]['name']="cfg_memberurl";
$z[12]=Array("php","\r\n        if(preg_match(\"#2#\", \$safe_gdopen))\r\n        {\r\n        echo '\r\n        <li>\r\n        <label>验 证:</label>\r\n         <span><input type=\"text\" name=\"vdcode\" size=\"8\" class=\"ipt-txt\" />\r\n         <img id=\"vdimgck\" align=\"absmiddle\" onClick=\"this.src=this.src+\\'?\\'\" style=\"cursor:pointer;margin-left:0px;text-transform:uppercase;\" alt=\"看不清？点击更换\" src=\"'.\$cfg_cmspath.'/include/vdimgck.php\"/></span></li>\r\n        ';\r\n        }\r\n        ",2519,3002);
$z[13]=Array("global","",3114,3142);
$z[13][4]['name']="cfg_memberurl";
$z[14]=Array("global","",3208,3236);
$z[14][4]['name']="cfg_memberurl";
$z[15]=Array("list","\r\n			<!-- 图片 -->\r\n			<li class=\"post\">\r\n            <span class=\"sc\"><a href=\"#\">喜欢</a></span>\r\n            <span class=\"view\"><a href=\"#\"></a></span>\r\n			<div class=\"link\">\r\n			<span class=\"img\">\r\n			[field:array runphp='yes']@me = (empty(@me['litpic']) ? \"\" : \"<a href='{@me['arcurl']}' target='_blank'><img src='{@me['litpic']}'/></a>\"); [/field:array]\r\n			<span class=\"arr\"><span></span></span>			</span>\r\n			<span class=\"text\">\r\n			<em>\r\n            <b><a href=\"[field:arcurl/]\" class=\"title\">[field:title/]</a></b>\r\n			<p>\r\n				[field:description/]... \r\n            </p>\r\n			</em>\r\n            <p class=\"info\">\r\n                <a href=\"#\" class=\"view\" title=\" 访问\">[field:click/]</a>\r\n                <a href=\"#\" class=\"good\" title=\"好评\">[field:scores/]</a>\r\n             </p>\r\n            </span>\r\n            </div>\r\n            </li>            \r\n    \r\n            ",3572,4498);
$z[15][4]['pagesize']="80";
$z[16]=Array("pagelist","",4574,4606);
$z[16][4]['listitem']="next";

?>