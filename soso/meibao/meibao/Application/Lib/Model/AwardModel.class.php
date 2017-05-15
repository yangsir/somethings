<?php
/** 用户卡类
 *
 * 用户卡操作
 *
 */

class AwardModel extends Model{
	
	private $AvardM; // 用户卡Model
	private $ucode; // 用户标识代码
	
	// 涉及表
	private $t_amemeber = 'award_member';
	private $t_packet = 'packet_member';
	private $t_wxuser = 'wxuser';
	private $t_pre    = 'mb_';
	
	
	function __construct($ucode=''){
		$this->AvatarM = M($this->t_amemeber.' as am ');
		$this->Packet  = M($this->t_packet.' as pa ');
		$this->ucode   = $ucode;
		
	}

    /*
	 * 获取中奖人总数
	 */
	function getMemCount($where=array()){
		$re = $this->Packet->join( "LEFT JOIN {$this->t_pre}{$this->t_wxuser} wxu ON wxu.user_code=pa.user_code" )
			->where($where)->count();
		return $re;
	}
	
	/*
	 * 获取中奖人列表
	 */
	function getMemLists($where=array(),$page=1,$num=10,$order='pa.packet_member_id'){
		$page = $page ? $page : 0;
		$num  = $num ? $num : 20;
		$re = $this->Packet->join( "LEFT JOIN {$this->t_pre}{$this->t_wxuser} wxu ON wxu.user_code=pa.user_code" )
			->where($where)->field("wxu.headimgurl,wxu.nickname,pa.*")->page($page,$num)->order("$order desc")->select();
		return $re;
	}

	/*
	 * 获取用户兑奖地址总数
	 */
	function getCount($where=array()){
		$re = $this->AvatarM->join( "LEFT JOIN {$this->t_pre}{$this->t_wxuser} wxu ON wxu.user_code=am.user_code" )
			->where($where)->count();
		return $re;
	}
	
	/*
	 * 获取用户兑奖地址列表
	 */
	function getLists($where=array(),$page=1,$num=10,$order='am.award_member_id'){
		$page = $page ? $page : 0;
		$num  = $num ? $num : 20;
		$re = $this->AvatarM->join( "LEFT JOIN {$this->t_pre}{$this->t_wxuser} wxu ON wxu.user_code=am.user_code" )
			->where($where)->field("wxu.headimgurl,wxu.nickname,am.*")->page($page,$num)->order("$order desc")->select();
		return $re;
	}
	
}
