<?php

class UserModel {

	private
		$users;

	function __construct ($f3) {
		$this->users = new DB\SQL\Mapper($f3->get('dB'),'users');
	}

	function login ($f3, $params) {
		return $this->users->load('email = "'.$params['email'].'" AND password = "'.$params['password'].'"');
	}

}

?>