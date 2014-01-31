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
		$this->mapper->save();
	}

	public function liste ($params) {
		return $this->mapper->find('id_user = "'.$params['id'].'"');
	}

	public function view ($params) {
		return $this->mapper->load('id = "'.$params['id'].'" AND id_user = "'.$params['id_user'].'"');
	}

	public function destroy ($params) {
		$res = $this->mapper->load('id = "'.$params['id'].'" AND id_user = "'.$params['id_user'].'"');
		$this->mapper->erase('id = "'.$params['id'].'" AND id_user = "'.$params['id_user'].'"');
		return $res;
	}
}

?>