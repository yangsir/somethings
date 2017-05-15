<?php
/** 通知类
 *
 * 通知操作
 *
 */

class NoticeModel extends Model{
	
	private $NoticeM; // 用户卡Model
	private $ucode; // 用户标识代码
	
	// 涉及表
	private $t_notice = 'notice';
	private $t_log    = 'notice_log';
	private $t_wxuser = 'wxuser';
	private $t_pre    = 'mb_';
	
	
	function __construct($ucode=''){
		$this->NoticeM = M($this->t_notice.' as n ');
		$this->LogM    = M($this->t_log.' as l ');
		$this->ucode = $ucode;
		
	}
	
	/*
	 * 获取通知总数
	 */
	function getCount($where=array()){
		if ( empty($where['n.end_time']) ) {
			$where['_string'] = " n.end_time>".time()." OR n.end_time=0 ";
		}
		$re = $this->NoticeM->where($where)->count();
		return $re;
	}
	
	/*
	 * 获取通知列表
	 */
	function getLists($where=array(),$page=1,$num=10,$order='n.notice_id desc'){
		$page = $page ? $page : 0;
		$num  = $num ? $num : 20;
		if ( empty($where['n.end_time']) ) {
			$where['_string'] = " n.end_time>".time()." OR n.end_time=0 ";
		}
		$re = $this->NoticeM
			->where($where)->page($page,$num)->order($order)->select();
		return $re;
	}
	
	/*
	 * 获取通知内容
	 */
	function getBase($where=array()){
		$data = $this->NoticeM->where($where)->find();
		return $data;
	}
	
	/*
	 * 获取通知内容
	 */
	function updateBase($data,$where=array()){
		$data = M($this->t_notice)->where($where)->save($data);
		return $data;
	}
	
	/*
	 * 添加通知
	 */
	function add($data){
		$insertid = M($this->t_notice)->add($data);
		return $insertid;
	}
	
	/*
	 * 删除内容
	 */
	function dele($where=array()){
		$data = M($this->t_notice)->where($where)->delete();
		return $data;
	}
	
	// ==============
	// 通知日志
	// ==============
	/*
	 * 记录查看通知日志
	 */
	function addLog($data){
		$insertid = M($this->t_log)->add($data);
		return $insertid;
	}
	
	/*
	 * 获取查看通知日志
	 */
	function getLogCount($where){
		$data = $this->LogM->where($where)->select();
		return $data;
	}
	
	/*
	 * 获取通知日志列表
	 */
	function getLogLists($where=array(),$page=1,$num=10,$order='l.notice_log_id desc'){
		$page = $page ? $page : 0;
		$num  = $num ? $num : 20;
		$re = $this->LogM
			->where($where)->page($page,$num)->order($order)->select();//echo $this->LogM->getLastSql();
		return $re;
	}
	

	
}
