<?php
class AdminAppModel extends Model{
  
private $mapper;
  
	public function __construct(){
		parent::__construct();
		$this->mapper=$this->getMapper('viewProject');
	}

	public function waitListProject () {
		return $this->mapper->find(array("block = 1"));
	}

	public function validationProject ($params) {
		$mapper = $this->getMapper('projects');
		$mapper->load(array('id = ?', $params["id"]));
		$mapper->block = 0;
		$mapper->beginDay = mktime (0,0,0, date("n"), date("j")+1, date("Y") );
		$mapper->lastDay = mktime (0,0,0, date("n"), date("j")+7, date("Y") );
		$mapper->update();
	}

	public function adminPreview ($params) {
		return $this->mapper->load(array("id = ? AND block = 1", $params['id']));
	}

	public function refuseProject ($params) {
		$mapper = $this->getMapper('projects');
		$mapper->load(array('id = ? AND block = 1', $params['id']));
		$mapper->block = -1;
		$mapper->update();
	}

	public function setLego ($params) {
		$mapper = $this->getMapper('projects');
		$mapper->load(array('id = ? AND block = 0', $params['id']));	
		$mapper->lego = 1;
		$mapper->update();
	}

	public function todayProjects ($params) {
 		$mapper = $this->getMapper('projects');
 		return $mapper->find(array('lastDay = ? AND block = 0', $params['date']));	
 	}
}
?>

