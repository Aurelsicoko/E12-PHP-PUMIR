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
}

?>