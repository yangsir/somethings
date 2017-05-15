<?php
/** 商家店铺类
 *
 * 商家店铺操作
 *
 */

class BusinessModel extends Model{
	
	private $BusinessM;
	private $ucode; // 用户标识代码
	
	// 涉及表
	private $t_business = 'business_shop';
	private $t_pre    = 'mb_';
	
	
	function __construct($ucode=''){
		$this->BusinessM = M($this->t_business);
		$this->ucode = $ucode;
		
	}
	
	/*
	 * 获取总数
	 */
	function getCount($where=array()){
		$re = $this->BusinessM->where($where)->count();
		return $re;
	}
	
	/*
	 * 获取列表
	 */
	function getLists($where=array(),$page=1,$num=10,$order='business_shop_id desc'){
		$page = $page ? $page : 0;
		$num  = $num ? $num : 20;
		$re = $this->BusinessM
			->where($where)->page($page,$num)->order($order)->select();
		return $re;
	}
	
	/*
	 * 获取内容
	 */
	function getBase($where=array()){
		$data = $this->BusinessM->where($where)->find();
		return $data;
	}
	
	/*
	 * 添加
	 */
	function add($data){
		$data = $this->BusinessM->add($data);
		return $data;
	}
	
	/*
	 * 更新内容
	 */
	function updateBase($data,$where=array()){
		$data = $this->BusinessM->where($where)->save($data);
		return $data;
	}
	
	/*
	 * 删除内容
	 */
	function dele($where=array()){
		$data = $this->BusinessM->where($where)->delete();
		return $data;
	}
	
	
}
