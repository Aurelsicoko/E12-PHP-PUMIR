<?php

class AdminAppController extends Controller {

	public function __construct(){
		parent::__construct();
		$this->tpl=array('sync'=>'adminLayout.html');
	}

	// Policie
	public function beforeroute ($f3) {
		if ($f3->get('SESSION.authenticated') !== true OR $f3->get('SESSION.user.admin') !== 1) {
			$f3->reroute('/');
		}
	}

	// CRON
	public function CRON ($f3) {
		$lego = array(-0.1, 0);
		$date = mktime (0,0,0, date("n"), date("j")+7, date("Y") );
		$todayProjects = $this->model->todayProjects(array('date' => $date));
		for ($i = 0 ; $i<count($todayProjects) ; $i++) {
			$user_vote = unserialize($todayProjects[$i]->user_vote);
			$user_vote["originality"] = (($user_vote["originality"]*$user_vote["vote"])+$f3->get('POST.originality'))/($user_vote["vote"]+1);
			$user_vote["difficulty"] = (($user_vote["difficulty"]*$user_vote["vote"])+$f3->get('POST.difficulty'))/($user_vote["vote"]+1);
			$user_vote["style"] = (($user_vote["style"]*$user_vote["vote"])+$f3->get('POST.style'))/($user_vote["vote"]+1);
			$average = ($user_vote["originality"]+$user_vote["difficulty"]+$user_vote["style"])/3;
			if ($average > $lego[0]) {
				$lego[0] = $average;
				$lego[1] = $todayProjects[$i]->id;
			}
		}
		$this->model->setLego(array('id' => $lego[1]));
		$f3->reroute('/');
	}

	// PROJECT
	public function waitListProject ($f3) {
		$this->content = 'admin/moderation';
		$projects = $this->model->waitListProject();
		$f3->set('projects', $projects);
	}

	public function adminPreview ($f3) {
		$this->content = 'admin/preview';
		$project = $this->model->adminPreview(array('id' => $f3->get('PARAMS.id')));
		$f3->set('project', $project);
	}

	public function validationProject ($f3) {
		$this->model->validationProject(array('id' => $f3->get('PARAMS.id'), 'title' => $f3->get('POST.title'), 'description' => $f3->get('POST.description')));
		$admin_vote["originality"] = $f3->get('POST.originality');
		$admin_vote["difficulty"] = $f3->get('POST.difficulty');
		$admin_vote["style"] = $f3->get('POST.style');
		$this->model->voteProject(array('id' => $f3->get('PARAMS.id'), 'admin_vote' => $admin_vote, 'admin_id' => $f3->get('SESSION.user')['id']));
		$f3->reroute('/admin/project/waitList');
	}

	public function refuseProject ($f3) {
		$this->model->refuseProject(array('id' => $f3->get('PARAMS.id')));
		$f3->reroute('/admin/project/waitList');
	}

	// USER
	public function getUser ($f3) {
		$this->content = 'admin/user';
		$users = $this->model->getUser(array('id' => $f3->get('PARAMS.id')));
		$projects = $this->model->getUserProjects(array('id' => $f3->get('PARAMS.id')));
		$f3->set('users', $users);
		$f3->set('projects', $projects);
	}

	public function getUsers ($f3) {
		$this->content = 'admin/users';
		$users = $this->model->getUsers();
		$f3->set('users', $users);
	}

	// DASHBOARD
	public function dashboard() {
		$this->content = 'admin/dashboard';
	}

}

?>
