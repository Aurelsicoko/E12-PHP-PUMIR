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
	
}
?>

