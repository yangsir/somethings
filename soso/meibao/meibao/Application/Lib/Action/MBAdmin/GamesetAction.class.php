<?php
class GamesetAction extends BaseAction {

	public function _initialize(){
		parent::_initialize();
	}

	//游戏说明
    public function config(){

        if(!$_POST) {
            $gameshow = M('gameshow')->find(1);
            $this->assign('data',$gameshow);
            $this->display();
        } else {
            $data = array(
    			'starttime' => strtotime($_POST['starttime']),
    			'endtime'   => strtotime($_POST['endtime']),
    			'tip1'  => $_POST['tip1'],
    			'tip2'  => $_POST['tip2'],
    			'tip3'  => $_POST['tip3'],
    			'tip4'  => $_POST['tip4'],
    		);
            M('gameshow')->where('gameshow_id = 1')->save($data);

            redirect(U('Gameset/config'));
        }

    }

    //游戏开奖设置
    public function dateconfig(){

        if(!$_POST) {
            $gameshow = M('setaward')->find(1);
            $this->assign('data',$gameshow);
            $this->display();
        } else {
            $data = array(
    			'cont'  => $_POST['cont'],
    		);
            M('setaward')->where('setaward_id = 1')->save($data);

            redirect(U('Gameset/dateconfig'));
        }

    }

	//兑奖说明
    public function exchange(){

        if(!$_POST) {
            $tmplace = C('TMPL_KIND');    // 模板样式根目录
            $this->assign( 'kindeditor',$tmplace );

            $gameshow = M('awardintro')->find(1);
            $this->assign('data',$gameshow);
            $this->display();
        } else {
            $data = array(
    			'content' => ($_POST['content']),
    		);
            M('awardintro')->where('awardintro_id = 1')->save($data);

            redirect(U('Gameset/exchange'));
        }

    }


}
