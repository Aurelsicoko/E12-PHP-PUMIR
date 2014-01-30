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
		$f3->set('photos', array());
		if (Web::instance()->receive(
			function($file) use($f3) {
				$photos = $f3->get('photos');
				array_push($photos, $file['name']);
				$f3->set('photos', $photos);
				$allowed=$f3->get('allowed');
				return is_array($allowed) && in_array($file['type'],$allowed);
			}
		))

		$begin = mktime (0,0,0, date("n"), date("j")+1, date("Y") );
		$last = mktime (0,0,0, date("n"), date("j")+14, date("Y") );

		$this->model->create(array('photos' => serialize($f3->get('photos')), 'begin' => $begin, 'last' => $last, 'id' => $f3->get('SESSION.id') ));
		$date = new DateTime();
		echo 'Projet Ajouté';
		$this->content = 'newProject';
	}

	public function liste ($f3) {
		$res = $this->model->liste(array('id' => $f3->get('SESSION.id')));
		$f3->set('liste', $res);
		$this->content = 'listProject';
	}

}

?>