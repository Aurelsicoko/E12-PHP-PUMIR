<?php

class AppController extends Controller {

	public function __construct(){
		parent::__construct();
		$this->tpl=array('sync'=>'layout.html');
	}

	public function home ($f3) {
		$this->content = 'app/index';
		$projects = $this->model->getProjects();
		$f3->set('projects', $projects);
	}

	public function singleProject ($f3) {
		$this->content = 'project/single';
		$project = $this->model->getProject(array('id' => $f3->get('PARAMS.id')));
		$f3->set('project', $project);
	}

	public function submitProject ($f3) {
		$this->content = 'project/submit';
	}

	public function singleUser ($f3) {
		$this->content = 'user/user';
		$user = $this->model->getUser(array('id' => 1));
		$f3->set('user', $user);
	}

	public function createProject ($f3) {
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
		$user_vote = serialize(array('originality' => 0, 'difficulty' => 0, 'style' => 0, 'vote' => 0));
		$this->model->createProject(array('photos' => serialize($f3->get('photos')), 'id' => 1, 'user_vote' => $user_vote));
		$f3->reroute('/');
	}

	public function waitListProject ($f3) {
		$this->content = 'project/waitList';
		$projects = $this->model->waitListProject();
		$f3->set('projects', $projects);
	}

	public function validationProject ($f3) {
		$this->model->validationProject(array('id' => $f3->get('PARAMS.id')));
		$f3->reroute('/admin/project/waitList');
	}

	public function adminPreview ($f3) {
		$this->content = 'project/singleAdmin';
		$project = $this->model->adminPreview(array('id' => $f3->get('PARAMS.id')));
		$f3->set('project', $project);
	}

}

?>
