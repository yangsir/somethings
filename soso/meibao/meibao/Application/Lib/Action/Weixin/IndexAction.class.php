<?php
class IndexAction extends BaseAction {
	
	public function _initialize(){
		parent::_initialize();
	}

    public function index(){
        echo 'Hello Meibao!';
		die();
    }

}