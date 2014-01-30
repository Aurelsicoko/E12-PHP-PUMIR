<?php

class ProjectController extends Controller {


	public function __construct(){
	    parent::__construct();
	    $this->tpl=array('sync'=>'layout.html');
	}

	public function newProject ($f3) {
		$this->content = 'newProject';
	}

	public function create ($f3) {
		foreach ($_FILES as $file) {
			echo $file['name'];
		}
		// if ((isset($_FILES['photo1']['tmp_name']))) {   
		// 	move_uploaded_file($_FILES['photo1']['tmp_name'], $f3->get('UPLOADS').$_FILES['photo1']['name']);     
		// } 
		// $this->model->create();
		// echo 'Projet sended';
		$this->content = 'newProject';
	}

}

?>