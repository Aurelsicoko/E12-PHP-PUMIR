<?php

class ProjectController extends Controller {

	function __construct () {}

	function newProject ($f3) {
		$f3->set('page', 'newProject');
	}

	function create ($f3) {
		$project = new DB\SQL\Mapper($f3->get('dB'),'projects');
  		$project->copyFrom('POST');
  		$f3->set('page', 'newProject');
  		$project->name = $_FILES['photo1']['name'];
		if (Web::instance()->receive(
			function ($file) {
				$allowed = $f3->get('allowed');
				echo 'Success';
				return is_array($allowed) && in_array($file['type'],$allowed);
			}
		));
		//$project->save();
	}

}

?>