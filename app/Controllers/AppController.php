<?php

class AppController extends Controller {

	public function __construct(){
		parent::__construct();
		$this->tpl=array('sync'=>'layout.html');
	}

	public function home ($f3) {
		$this->content = 'index';
	}

}

?>