<?php

class ProjectModel extends Model {

private $mapper;
  
	public function __construct(){
		parent::__construct();
		$this->mapper=$this->getMapper('projects');
	}

	public function create ($params) {
  		$this->mapper->copyFrom('POST');
  		$this->mapper->photos = $params['photos'];
  		$this->mapper->beginDay = $params['begin'];
  		$this->mapper->lastDay = $params['last'];
  		$this->mapper->id_user = $params['id'];
  		$this->mapper->user_vote = $params['user_vote'];
  		$this->mapper->admin_vote = $params['admin_vote'];
		$this->mapper->save();
	}

	public function liste ($params) {
		return $this->mapper->find('id_user = "'.$params['id'].'"');
	}

	public function view ($params) {
		$mapper = $this->getMapper('vote');
		$vote = $mapper->find('id_project = "'.$params['id'].'" AND id_user = "'.$params['id_user'].'"');
		$project = $this->mapper->load('id = "'.$params['id'].'" AND id_user = "'.$params['id_user'].'"');
		return array($project, $vote);
	}

	public function destroy ($params) {
		$res = $this->mapper->load('id = "'.$params['id'].'" AND id_user = "'.$params['id_user'].'"');
		$this->mapper->erase('id = "'.$params['id'].'" AND id_user = "'.$params['id_user'].'"');
		return $res;
	}

	public function vote ($params) {
		$mapper=$this->getMapper('vote');
		$mapper->copyFrom('POST');
		$mapper->id_user = $params['id_user'];
		$mapper->id_project = $params['id'];
		$res = $this->mapper->load('id = "'.$params['id'].'" AND id_user = "'.$params['id_user'].'"');
		$vote = unserialize($res->user_vote);
		$vote['originality'] += $mapper->originality;
		$vote['difficulty'] += $mapper->difficulty;
		$vote['style'] += $mapper->style;
		$vote['vote'] += 1;
		$res->user_vote = serialize($vote);
		$res->update();
		$mapper->save();
	}
}

?>