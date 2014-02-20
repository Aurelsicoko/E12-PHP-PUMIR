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

	public function getUser ($params) {
		$mapper = $this->getMapper('users');
		return $mapper->load(array("id = ? AND block = 0", $params['id']));
	}

	public function addVote ($params) {
		$mapper = $this->getMapper('vote');
		$mapper->copyFrom('POST');
		$mapper->id_user = $params["id_user"];
		$mapper->id_project = $params["id_project"];
		$mapper->save();
	}

	public function voteProject ($params) {
		$mapper = $this->getMapper('projects');
		$mapper->load(array('id = ? AND block = 0', $params["id_project"]));
		$user_vote = unserialize($this->mapper->user_vote);
		$user_vote = serialize(array('originality' => $params['user_vote']['originality'], 'difficulty' => $params['user_vote']['difficulty'], 'style' => $params['user_vote']['style'], 'vote' => $user_vote['vote']+1));
		$mapper->user_vote = $user_vote;
		$mapper->update();
	}

	public function createProject ($params) {
		$mapper = $this->getMapper('projects');
		$mapper->copyFrom('POST');
  		$mapper->photos = $params['photos'];
  		$mapper->id_user = $params['id'];
  		$mapper->user_vote = $params['user_vote'];
  		$mapper->admin_vote = $params['admin_vote'];
		$mapper->save();
	}

	public function waitListProject () {
		return $this->mapper->find(array("block = 1"));
	}

	public function validationProject ($params) {
		$mapper = $this->getMapper('projects');
		$mapper->load(array('id = ?', $params["id"]));
		$mapper->block = 0;
		$mapper->beginDay = mktime (0,0,0, date("n"), date("j")+1, date("Y") );
		$mapper->lastDay = mktime (0,0,0, date("n"), date("j")+14, date("Y") );
		$mapper->update();
	}

	public function adminPreview ($params) {
		return $this->mapper->load(array("id = ? AND block = 1", $params['id']));
	}
}
?>

