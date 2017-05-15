<?php
class AwardAction extends BaseAction {

	private $cardM = null;
	
	public function _initialize(){
		parent::_initialize();
		
		$this->awardM = new AwardModel();
	}

    //手袋人员列表
    public function lists(){
		$keyword = $_POST['keyword'];
		$where = array();
		if ( $keyword ) {
			$where['wxu.nickname'] = array( 'like',"%{$keyword}%" );
		}

		$count = $this->awardM->getMemCount($where);
		$lists = array();
		if ( $count ) {			
			import("ORG.Util.Page");
			import("ORG.Util.PageAce");
			$Page = new Page($count,20);
			$pagehtml = $Page->show();
			$this->assign( 'pagehtml',$pagehtml );
			$this->assign( 'count',$count );

			$lists = $this->awardM->getMemLists($where,$_REQUEST['p'],20);
		}
		
		$this->assign( 'tickets',$lists );
		$this->assign( 'req_data',$_POST );
		$this->display();
    }

	// 手袋联系人地址列表
    public function addrlists() {
        $keyword = $_POST['keyword'];
		$where = array();
		if ( $keyword ) {
			$where['am.name'] = array( 'like',"%{$keyword}%" );
		}

		$count = $this->awardM->getCount($where);
		$lists = array();
		if ( $count ) {
			import("ORG.Util.Page");
			import("ORG.Util.PageAce");
			$Page = new Page($count,20);
			$pagehtml = $Page->show();
			$this->assign( 'pagehtml',$pagehtml );
			$this->assign( 'count',$count );

			$lists = $this->awardM->getLists($where,$_REQUEST['p'],20); // 用户卡列表
		}
		
		$this->assign( 'tickets',$lists );
		$this->assign( 'req_data',$_POST );
		$this->display();
    }

    //人员导出
	function export(){
        $list = $this->awardM->getMemLists(array(),$_REQUEST['p'],5000); // 会员卡列表

        $title_data = array (
            'serial'     =>'序号',
            'headimgurl' =>'头像',
            'nickname'   =>'用户',
            'add_time'   =>'时间',
            'credit'     =>'积分',
        );
        $newList = array();
        $tempKey=array_keys($title_data);
        foreach($list as $key=>$listV){
            //$tempList=array();
            foreach($tempKey as $tempKeyV){
				if ($tempKeyV== 'serial' )  $tempList[$tempKeyV] = $key+1;
				elseif ($tempKeyV== 'add_time' )  $tempList[$tempKeyV] = date('Y-m-d',$listV[$tempKeyV]);
                else $tempList[$tempKeyV] = $listV[$tempKeyV]?$listV[$tempKeyV]:'';                
            }
            $newList[]=$tempList;
            $tempList=array();
        }
		
		$this->export_exl( array_values($title_data),$newList,'手袋中奖列表' );
	}

    //地址导出
    function addrexport(){
        $list = $this->awardM->getLists(array(),$_REQUEST['p'],5000); // 会员卡列表

        $title_data = array (
            'serial'     =>'序号',
            'nickname'   =>'用户',
            'phone'      =>'电话',
            'address'    =>'地址',
            'add_time'   =>'填写时间',
        );
        $newList = array();
        $tempKey=array_keys($title_data);
        foreach($list as $key=>$listV){
            //$tempList=array();
            foreach($tempKey as $tempKeyV){
				if ($tempKeyV== 'serial' )  $tempList[$tempKeyV] = $key+1;
				elseif ($tempKeyV== 'add_time' )  $tempList[$tempKeyV] = date('Y-m-d',$listV[$tempKeyV]);
                else $tempList[$tempKeyV] = $listV[$tempKeyV]?$listV[$tempKeyV]:'';                
            }
            $newList[]=$tempList;
            $tempList=array();
        }
		
		$this->export_exl( array_values($title_data),$newList,'手袋中奖人地址列表' );
	}

}
