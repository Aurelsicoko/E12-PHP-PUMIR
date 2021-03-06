<?php
class AuthAppModel extends Model{
  
private $mapper;
  
	public function __construct(){
		parent::__construct();
		$this->mapper=$this->getMapper('viewProject');
	}

	public function getProject ($params) {
		return $this->mapper->load(array("id = ? AND block = 0", $params['id']));
	}

	public function getProjects ($params) {
		return $this->mapper->find(array("id_user = ?", $params['id']));
	}

	public function getUser($params) {
		$mapper = $this->getMapper('users');
		return $mapper->find(array("id = ? && block = 0", $params['id']));
	}

	public function getIfUserVote($params){
		$mapper = $this->getMapper('vote');
		return $mapper->find(array("id_user = ? && id_project = ?", $params['id_user'], $params['id_project']));
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
  		$mapper->beginDay = mktime (0,0,0, date("n"), date("j"), date("Y") );
		$mapper->save();
	}

	public function validationProject ($params) {
		$mapper = $this->getMapper('projects');
		$mapper->load(array('id = ?', $params["id"]));
		$mapper->block = 0;
		$mapper->beginDay = mktime (0,0,0, date("n"), date("j")+1, date("Y") );
		$mapper->lastDay = mktime (0,0,0, date("n"), date("j")+7, date("Y") );
		$mapper->update();
	}

	public function deleteProject ($params) {
		$this->db->begin();
		$this->db->exec(array('DELETE FROM projects WHERE id="'.$params['id'].'"'));
		$this->db->commit();
	}
}
?>

