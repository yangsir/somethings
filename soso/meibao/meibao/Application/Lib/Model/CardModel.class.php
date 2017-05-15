<?php
/** 用户卡类
 *
 * 用户卡操作
 *
 */

class CardModel extends Model{
	
	private $CreditM; // 用户卡Model
	private $ucode; // 用户标识代码
	
	// 涉及表
	private $t_config = 'card_config';
	private $t_member = 'card_member';
	private $t_cblog  = 'card_member_clog';
	private $t_wxuser = 'wxuser';
	private $t_user = 'admin';
	private $t_pre    = 'mb_';
	
	
	function __construct($ucode=''){
		$this->CreditM = M($this->t_member.' as m ');
		$this->ucode = $ucode;
		
	}
	
	/*
	 * 获取用户卡总数
	 */
	function getCount($where=array()){
		$re = $this->CreditM->join( "LEFT JOIN {$this->t_pre}{$this->t_wxuser} wxu ON wxu.user_code=m.user_code" )
			->where($where)->count();
		return $re;
	}
	
	/*
	 * 获取用户卡列表
	 */
	function getLists($where=array(),$page=1,$num=10,$order='m.card_member_id'){
		$page = $page ? $page : 0;
		$num  = $num ? $num : 20;
		$re = $this->CreditM->join( "LEFT JOIN {$this->t_pre}{$this->t_wxuser} wxu ON wxu.user_code=m.user_code" )
			->where($where)->page($page,$num)->order("$order desc")->select();
		return $re;
	}
	
	/*
	 * 获取用户卡
	 */
	function getBase($ucode=''){
		$ucode = $ucode ? $ucode : $this->ucode;
		$where = array( 'user_code' => $ucode );

        $count = $this->CreditM->where($where)->count();
        if(!$count) $this->makeCard($ucode);  //不存在生成卡

		$data = $this->CreditM->where($where)->find();
		return $data;
	}
	
	/*
	 * 获取用户卡
	 */
	function getBaseNew($where){
		$data = $this->CreditM->where($where)->find();
		return $data;
	}
	
	/*
	 * 获取用户卡
	 */
	function getBaseByNo($no=''){
		if ( !$no ) return false;
		
		$where = array( 'cart_no' => $no );
		$data = $this->CreditM->where($where)->find();//echo $this->CreditM->getLastSql();
		return $data;
	}
	
	/*
	 * 获取用户卡
	 */
	function updeBase($data,$where=array()){
		if ( !$data || !$where ) return false;
		
		$data = $this->CreditM->where($where)->save($data);
		return $data;
	}

    /*
     *检查用户卡
     * */
    function checkCard($ucode='') {
    
        $ucode = $ucode ? $ucode : $this->ucode;
		$where = array( 'user_code' => $ucode );

        $count = $this->CreditM->where($where)->count();
        if(!$count) $this->makeCard($ucode);  //不存在生成卡

		return 1;

    }

    /*
	 * 生成用户卡
	 */
	function makeCard($ucode=''){

		$ucode = $ucode ? $ucode : $this->ucode;
        $cart_no = createCardNo();

        //$cart_no = $this->CreditM->max('cart_no');
        //$cart_no = $cart_no ? $cart_no+1 : 80000001;
        $data = array(
            'user_code' => $ucode,
            'cart_no'   => $cart_no,
            'add_time'  => time(),
        );

        $insertid = M('card_member')->add($data); //$this->CreditM调用报错
		return $insertid;
	}
	
	/*
	 * 获取用户卡用户信息
	 */
	function updateCardMember($data,$where=array()){
		$re = $this->CreditM->where($where)->save($data);

		return $re;
	}
	
	/*
	 * 获取用户卡配置
	 */
	function getConfig(){
		 $insertid = M($this->t_config)->find();

		return $insertid;
	}
	
	/*
	 * 配置用户卡
	 */
	function updateCard($data){
		$re = M($this->t_config)->save($data);
		return $re;
	}
	
	
	//////////////////
	// 用户卡积分日志
	//////////////////
	/*
	 * 获取用户卡积分日志总数
	 */
	function getCLogCount($where=array()){
		$re = M($this->t_cblog.' as cl')->join( "LEFT JOIN {$this->t_pre}{$this->t_wxuser} wxu ON wxu.user_code=cl.user_code" )
		->where($where)->count();//echo  M($this->t_cblog.' as cl')->getLastSql();
		return $re;
	}
	
	/*
	 * 获取用户卡积分日志列表
	 */
	function getCLogLists($where=array(),$page=1,$num=10,$order='cl.card_member_clog_id desc'){
		$page = $page ? $page : 0;
		$num  = $num ? $num : 20;
		$field = 'cl.*, u.nickname as username, m.cart_no,m.card_value,m.member_name,m.member_tel,m.add_time mem_time, wxu.nickname,wxu.headimgurl';
		$re = M($this->t_cblog.' as cl')->field($field)
		->join( "LEFT JOIN {$this->t_pre}{$this->t_member} m ON m.user_code=cl.user_code" )
		->join( "LEFT JOIN {$this->t_pre}{$this->t_wxuser} wxu ON wxu.user_code=cl.user_code" )
		->join( "LEFT JOIN {$this->t_pre}{$this->t_user} u ON u.admin_id=cl.operate_uid" )
		->where($where)->page($page,$num)->order($order)->select();
		return $re;
	}
	
	/*
	 * 获取用户卡消费记录总和
	 */
	function getCLogSum($where=array()){
		$re = M($this->t_cblog.' as cl')->field('sum(cl.cvalue) as valsum')->where($where)->find();
		return $re['valsum'];
	}
	
	/*
	 * 获取用户卡消费记录总和
	 */
	function addCLog($data){
		$insertid = M($this->t_cblog)->add($data);//echo M($this->t_cblog)->getLastSql();die();
		if ( $data['cvalue'] ) {
			M($this->t_member)->where( array('user_code'=>$data['user_code']) )->setDec( 'card_value',$data['cvalue'] );//echo $m->getLastSql();
		}
		if ( $data['cdeduction'] ) {
			M($this->t_member)->where( array('user_code'=>$data['user_code']) )->setInc( 'card_value',$data['cdeduction'] );
		}
		return $insertid;
	}
}
