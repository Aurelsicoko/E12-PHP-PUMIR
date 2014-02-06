<?php
class SessionModel extends Model{
  
private $mapper;
  
	public function __construct(){
		parent::__construct();
		$this->mapper=$this->getMapper('users');
	}

	public function login ($params) {
		return $this->mapper->load('email = "'.$params['email'].'" AND password = "'.$params['password'].'"');
	}

}
?>