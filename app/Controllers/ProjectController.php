<?php

class ProjectController extends Controller {

	public function beforeroute ($f3) {
		$id = $f3->get('SESSION.id');
		if (!isset($id)){
			$f3->reroute('/login');
		}
		return;
	}

	public function __construct(){
	    parent::__construct();
	    $this->tpl=array('sync'=>'layout.html');
	}

	public function newProject ($f3) {
		$this->content = 'new';
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
		$user_vote = serialize(array('originality' => 0, 'difficulty' => 0, 'style' => 0, 'vote' => 0));
		$admin_vote = serialize(array('originality' => 5, 'difficulty' => 5, 'style' => 5, 'vote' => 1));

		$this->model->create(array('photos' => serialize($f3->get('photos')), 'begin' => $begin, 'last' => $last, 'id' => $f3->get('SESSION.id'), 'user_vote' => $user_vote, 'admin_vote' => $admin_vote ));
		$date = new DateTime();
		echo 'Projet Ajouté';
		$this->content = 'new';
	}

	public function liste ($f3) {
		$res = $this->model->liste(array('id' => $f3->get('SESSION.id')));
		$f3->set('liste', $res);
		$this->content = 'index';
	}

	public function view ($f3) {
		$res = $this->model->view(array('id' => $f3->get('PARAMS.id'), 'id_user' => $f3->get('SESSION.id')));
		$f3->set('project', $res[0]);
		$vote = count($res[1]);
		if ($vote == 1) {
			$f3->set('vote', $res[1][0]);
		}
		$this->content = 'show';
	}

	public function destroy ($f3) {
		$res = $this->model->destroy(array('id' => $f3->get('PARAMS.id'), 'id_user' => $f3->get('SESSION.id')));
		$photos = unserialize($res->photos);
		foreach ($photos as $key => $photo) {
			if( file_exists ( $photo)) unlink( $photo ) ;
		}
		$f3->reroute('/project');
	}

	public function vote ($f3) {
		$this->model->vote(array('id' => $f3->get('PARAMS.id'), 'id_user' => $f3->get('SESSION.id')));
		$f3->reroute('/project/view/'.$f3->get('PARAMS.id'));
	}

}

?>