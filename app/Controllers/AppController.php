<?php

class AppController extends Controller {

	public function beforeroute ($f3) {
		$f3->set('SESSION.id', 1);
		$f3->set('SESSION.user', 'Jim LAURIE');
	}

	public function __construct(){
		parent::__construct();
		$this->tpl=array('sync'=>'layout.html');
	}

	public function home ($f3) {
		$this->content = 'index';
	}

}

?>