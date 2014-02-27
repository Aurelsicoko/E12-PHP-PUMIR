<?php
class AppModel extends Model{
  
private $mapper;
  
	public function __construct(){
		parent::__construct();
		$this->mapper=$this->getMapper('viewProject');
	}

	public function getProjects ($params) {
		return $this->mapper->find(array("beginDay <= ? AND lastDay >= ? AND block = 0", $params["date"], $params["date"]));
	}

	public function getAward () {
		return $this->mapper->find(array("lego = 1"));
	}

	public function getProject ($params) {
		return $this->mapper->load(array("id = ? AND block = 0", $params['id']));
	}

	public function getLego ($params) {
		return $this->mapper->load(array("lastDay = ? AND lego = 1", $params['date']));
	}

	public function getVote ($params){
		$mapper = $this->getMapper('vote');
		return $mapper->load(array("id_user = ? AND id_project = ?", $params['id_user'], $params['id_project']));
	}

	public function login ($params) {
		$mapper = $this->getMapper('users');
		return $mapper->load(array('email = ? AND password = ?', $params['email'], $params['password']));
	}

	public function getUserByCode ($params) {
		$mapper = $this->getMapper('users');
		return $mapper->load(array("password = ? AND code= ? AND block = 1", $params['password'], $params['code']));
	}

	public function	createUser ($params) {
		$mapper=$this->getMapper('users');
		$mapper->copyFrom('POST');
	    $mapper->code = $params['code'];
	  	$mapper->save();
	}

	public function validationAccount ($params) {
		$mapper = $this->getMapper('users');
		$mapper->load(array('id = ?', $params["id"]));
		$mapper->block = 0;
		$mapper->update();
	}
	
}
?>

