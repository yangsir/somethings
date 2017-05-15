<?php
/** 
 *
 * 菜单操作
 *
 */

class MenuModel extends Model{
	
	function getCount($where=array()){
		$re = $this->where($where)->count();
		return $re;
	}
	
	function getLists($where=array(),$page=1,$num=10,$order='menu_id'){
		$page = $page ? $page : 0;
		$num  = $num ? $num : 20;
		$re = $this->where($where)->page($page,$num)->order("$order desc")->select();
		return $re;
	}
	
}
