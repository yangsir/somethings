<?php
class CreditAction extends BaseAction {
	private $creditM = null;
	
	public function _initialize(){
		parent::_initialize();
		
		$this->creditM = new CreditModel();
	}

	// 获取总排名、总积分
    public function lists(){
		$keyword = $_POST['keyword'];
		$where = array();
		if ( $keyword ) {
			$where['wxu.nickname'] = array( 'like',"%{$keyword}%" );
		}

		$count = $this->creditM->getCount($where);
		$lists = array();
		if ( $count ) {
			import("ORG.Util.Page");
			import("ORG.Util.PageAce");
			$Page = new Page($count,20);
			$pagehtml = $Page->show();
			$this->assign( 'pagehtml',$pagehtml );
			$this->assign( 'count',$count );

			$lists = $this->creditM->getLists($where,$_REQUEST['p'],20,'c.credit'); // 用户卡列表
		}

        $flag = 0;
        $setaward = M('setaward')->find(1);
        if($setaward['cont']) {
            $dateArr = explode(',',$setaward['cont']);
            $year = date('Y');
            foreach($dateArr as $val) {
                $newdate = date('m-d',strtotime($year.'-'.$val));
                if(date('m-d') == $newdate) {
                    $current = strtotime(date('Y-m-d'));
                    $count = M('packet_member')->where("add_time = $current")->count();
                    if(!$count) $flag = 1;
                    break;
                }
            }
        }
		
        $this->assign('flag',$flag); //可以兑换
		$this->assign( 'tickets',$lists );
		$this->assign( 'req_data',$_POST );
		$this->display();
    }

    //添加编辑积分
    public function config(){

        $credit_id = $_REQUEST['credit_id'];
        if($credit_id) {
            $data = $this->creditM->getItem(array('credit_id' => $credit_id));
            $this->assign('data',$data);
        } 
		$this->display();

    }

    //添加编辑积分处理
    public function doconfig(){

        $del       = $_REQUEST['del'];
        $set       = $_REQUEST['set'];
        $credit_id = $_REQUEST['credit_id'];
        if(!$_POST && !$del && !$set) exit();

        if(!$del && !$set) { //编辑
            $credit    = intval($_POST['credit']);
            $oldcredit = intval($_POST['oldcredit']);
            if($credit != $oldcredit) {
                $data = array('credit' => $credit);
                M('credit')->where("credit_id = $credit_id")->save($data);
            }
        } else if($set) { //设置中奖名单
            $creditArr = M('credit')->field('user_code,credit')->find($credit_id);
            $this->creditM->delCredit($creditArr['credit'],$creditArr['user_code']);

            $data = array(
                'user_code' => $creditArr['user_code'],
                'credit'    => $creditArr['credit'],
                'add_time'  => strtotime(date('Y-m-d')),
            );
            M('packet_member')->add($data);
        } else { //删除
            M('credit')->where("credit_id = $credit_id")->delete();
        }

        redirect(U('Credit/lists'));
    }
	
	function export(){
        $list = $this->creditM->getLists(array(),$_REQUEST['p'],5000); // 用户卡列表

        $title_data = array (
            'serial'=>'序号',
            'nickname'=>'用户',
            'credit'=>'积分',
        );
        $newList = array();
        $tempKey=array_keys($title_data);
        foreach($list as $key=>$listV){
            //$tempList=array();
            foreach($tempKey as $tempKeyV){
				if ($tempKeyV== 'serial' )  $tempList[$tempKeyV] = $key+1;
                else $tempList[$tempKeyV] = $listV[$tempKeyV]?$listV[$tempKeyV]:'';                
            }
            $newList[]=$tempList;
            $tempList=array();
        }
		
		$this->export_exl( array_values($title_data),$newList,'用户积分列表' );
	}

}
