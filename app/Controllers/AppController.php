<?php

class AppController extends Controller {

	public function __construct(){
		parent::__construct();
		$this->tpl=array('sync'=>'layout.html');
	}

	public function home ($f3) {
		$this->content = 'app/index';
		$date = mktime (date("H"), date("i"), date("s"), date("n"), date("j")+1, date("Y") );
		$projects = $this->model->getProjects(array('date' => $date));
		$lego = $this->model->getLego(array('date' => $date));
		$f3->set('projects', $projects);
		$f3->set('lego', $lego);
	}

	public function singleProject ($f3) {
		$this->content = 'project/single';
		$project = $this->model->getProject(array('id' => $f3->get('PARAMS.id')));
		$vote = $this->model->getVote(array('id_project' => $f3->get('PARAMS.id'), 'id_user' => 1));
		$f3->set('project', $project);
		$f3->set('vote', $vote);
	}

	public function submitProject ($f3) {
		$this->content = 'project/submit';
	}

	public function singleUser ($f3) {
		$this->content = 'user/user';
		$user = $this->model->getUser(array('id' => 1));
		$f3->set('user', $user);
	}

	public function voteProject ($f3) {
		$project = $this->model->getProject(array('id' => $f3->get('PARAMS.id')));
		$user_vote = unserialize($project->user_vote);
		$user_vote["originality"] = (($user_vote["originality"]*$user_vote["vote"])+$f3->get('POST.originality'))/($user_vote["vote"]+1);
		$user_vote["difficulty"] = (($user_vote["difficulty"]*$user_vote["vote"])+$f3->get('POST.difficulty'))/($user_vote["vote"]+1);
		$user_vote["style"] = (($user_vote["style"]*$user_vote["vote"])+$f3->get('POST.style'))/($user_vote["vote"]+1);
		$this->model->voteProject(array("id_project" => $f3->get('PARAMS.id'), 'user_vote' => $user_vote));
		$this->model->addVote(array('id_user' => 1, 'id_project' => $f3->get('PARAMS.id')));
		$f3->reroute('/project/'.$f3->get('PARAMS.id'));
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
		$admin_vote = serialize(array('originality' => 0, 'difficulty' => 0, 'style' => 0, 'vote' => 0));
		$this->model->createProject(array('photos' => serialize($f3->get('photos')), 'id' => 1, 'user_vote' => $user_vote, 'admin_vote' => $admin_vote));
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
