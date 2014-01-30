<?php

class ProjectModel extends Model {

private $mapper;
  
	public function __construct(){
		parent::__construct();
		$this->mapper=$this->getMapper('projects');
	}

	public function create () {
  		$this->mapper->copyFrom('POST');
  		$this->mapper->photos = $_FILES['photo1']['name'];
		$this->mapper->save();
	}
}

?>