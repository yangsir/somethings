<?php
/** 积分类
 *
 * 积分操作
 *
 */

class CreditModel extends Model{
	
	private $CreditM; // 积分Model
	private $ucode; // 用户标识代码
	
	// 涉及表
	private $t_credit = 'credit';
	private $t_log = 'credit_log';
	private $t_wxuser = 'wxuser';
	private $t_pre    = 'mb_';
	
	
	function __construct($ucode=''){
		$this->CreditM = M($this->t_credit);
		$this->ucode = $ucode;
		
	}
	
	/*
	 * 获取积分总数
	 */
	function getCount($where=array()){
		$count = M($this->t_credit.' as c')->join( "LEFT JOIN {$this->t_pre}{$this->t_wxuser} wxu ON wxu.user_code=c.user_code" )
			->where($where)->count();
		return $count;
	}

    /*
     *获取积分总数
     * */
    function getNewCount($where = array() ) {
        $user_code = $this->ucode;
        $where = array_merge($where,array('user_code'=>$user_code));
        $re = M('credit')->where($where)->getField('credit');
		return $re;
    }
	
	/*
	 * 获取积分排名
	 */
	function getRank($credit=null){
		if ( is_null($credit) ) {
			$creditinfo = $this->getCreditBase();
			if ( $creditinfo === false ) return false;
			$credit = $creditinfo['credit'];
		}
		$where = array('credit'=>array('gt',$credit));
		$re = $this->CreditM->where($where)->count();
		
		return $re+1;
	}
	
	/*
	 * 获取积分列表
	 */
	function getLists($where=array(),$page=1,$num=20,$order='c.credit_id'){
		$page = $page ? $page : 0;
		$num  = $num ? $num : 20;
		$re = M($this->t_credit.' as c')->join( "LEFT JOIN {$this->t_pre}{$this->t_wxuser} wxu ON wxu.user_code=c.user_code" )
			->where($where)->page($page,$num)->order("$order desc")->select();
		return $re;
	}

    /*
	 * 获取积分列表
	 */
	function getItem($where=array()){
		$re = M($this->t_credit.' as c')->join( "LEFT JOIN {$this->t_pre}{$this->t_wxuser} wxu ON wxu.user_code=c.user_code" )
			->where($where)->find();
		return $re;
	}
	
	/*
	 * 添加积分
	 */
	function addCredit($value=0,$share_ucode=''){
		if ( !$this->ucode || !$value ) return false;

        //子集记录
        $credit_log = array(
            'user_code'    => $this->ucode,
            'credit'       => $value,
            'change_type'  => 1,
            'to_user_code' => $share_ucode ? $share_ucode : '',
            'add_time'     => time(),
        );
        $re1 = M('credit_log')->add($credit_log);

        if($re1) {//日志添加成功
            //自己的记录
		    $base = $this->getCreditBase();
		    if ( !$base ){
		    	$data = array(
		    		'user_code'   => $this->ucode,
		    		'credit'      => $value,
                    'add_time'    => time(),
                    'update_time' => time(),
		    	);
		    	$re = $this->CreditM->add($data);
		    } else {
		    	$data = array(
		    		'credit_id' => $base['credit_id'],
		    		'credit'    => $value+$base['credit'],
                    'update_time' => time(),
		    	);
		    	$re = $this->CreditM->save($data);
		    }

            //分享者同样的积分
            if($share_ucode) {
                $base = $this->getCreditBase($share_ucode);
		        if ( !$base ){
		        	$data = array(
		        		'user_code'   => $share_ucode,
		        		'credit'      => $value,
                        'add_time'    => time(),
                        'update_time' => time(),
		        	);
		        	$re = $this->CreditM->add($data);
		        } else {
		        	$data = array(
		        		'credit_id' => $base['credit_id'],
		        		'credit'    => $value+$base['credit'],
                        'update_time' => time(),
		        	);
		        	$re = $this->CreditM->save($data);
		        }
            }
        }

		return $re;
	}

    /*
	 * 扣除积分
	 */
	function delCredit($value=0,$ucode='') {
        $ucode = $ucode ? $ucode : $this->ucode;
		if ( !$ucode || !$value ) return false;

        //子集记录
        $credit_log = array(
            'user_code'    => $ucode,
            'credit'       => $value,
            'change_type'  => 2,
        );
        //$count = M('credit_log')->where($credit_log)->count();
        //if($count) return; //劵只可能领一次
    
        $credit_log['add_time'] = time();
        $re1 = M('credit_log')->add($credit_log);
        if($re1) {//日志添加成功
            $data = array( //总积分减少
                'update_time' => time(),      
                'credit'      => array('exp','credit-'.$value)
            );
            $re = $this->CreditM->where(array('user_code' => $ucode))->save($data);
        }

		return $re;
	}
	
	/*
	 * 获取积分信息
	 */
	function getCreditBase($ucode=''){
		$ucode = $ucode ? $ucode : $this->ucode;
		if ( !$ucode ) return false;
		
		$re = $this->CreditM->where(array( 'user_code'=>$ucode ))->find();
		return $re;
	}

    /*
	 * 双倍积分添加
	 */
	function addDoubleCredit() {
		if ( !$this->ucode) return false;
        $ucode = $this->ucode;

        //子集查询？没有记录写入
        $credit_log = M('credit_log')->where("user_code = '$ucode' and remarks ='double credit'")->order('credit_log_id desc')->find();
        if(!$credit_log || date('Y-m-d',$credit_log['add_time']) != date('Y-m-d',time())) { //没有记录
		    $base = $this->getCreditBase();
            if($base['credit']) { //有积分时双倍
                //子集记录
                $credit_log = array(
                    'user_code'    => $ucode,
                    'credit'       => $base['credit'],
                    'change_type'  => 1,
                    'remarks'      => 'double credit',
                    'add_time'     => time(),
                );
                $re1 = M('credit_log')->add($credit_log);            

                //记录分
                $data = array(
		        	'credit_id'   => $base['credit_id'],
		        	'credit'      => 2*$base['credit'],
                    'update_time' => time(),
		        );
		        $re2 = $this->CreditM->save($data);
            }
        }

		return $re2 ? 1 : 0;
	}
	
}
