<?php

class AppController extends Controller {

	public function __construct(){
		parent::__construct();
		$this->tpl=array('sync'=>'layout.html');
	}

	// INDEX
	public function home ($f3) {
		$this->content = 'app/index';
		$date = mktime (date("H"), date("i"), date("s"), date("n"), date("j")+1, date("Y") );
		$projects = $this->model->getProjects(array('date' => $date));
		$date = mktime (0,0,0, date("n"), date("j")+7, date("Y") );
		$lego = $this->model->getLego(array('date' => $date));
		$f3->set('projects', $projects);
		$f3->set('lego', $lego);
	}

	// PROJECTS
	public function singleProject ($f3) {
		$this->content = 'project/single';
		$project = $this->model->getProject(array('id' => $f3->get('PARAMS.id')));
		$vote = $this->model->getVote(array('id_project' => $f3->get('PARAMS.id'), 'id_user' => $f3->get('SESSION.user')['id']));
		$f3->set('project', $project);
		$f3->set('vote', $vote);
	}

	public function award ($f3) {
		$this->content = 'project/award';
		$projects = $this->model->getAward();
		$f3->set('projects', $projects);
	}

	// SESSION
	public function newSession ($f3){
		$this->content = 'session/new';
	}

	public function createSession ($f3) {
		$user = $this->model->login(array('email' => $f3->get('POST.email'), 'password' => $f3->get('POST.password')));
		if ($user) {
			$f3->set('SESSION.authenticated', true);
			$f3->set('SESSION.user', array(	'id' => $user->id, 
											'firstname' => $user->firstname, 
											'lastname' => $user->lastname,
											'email' => $user->email,
											'admin' => $user->admin,
											'country' => $user->country,
											'city' => $user->city,
											'picture' => $user->picture ));
			$f3->reroute('/');
		}else{
			$f3->reroute('/login');
		}
	}

	// USERS
	public function signup ($f3) {
		$this->content = 'user/new';
	}

	public function createUser ($f3){
		if ($f3->get('POST.password') == $f3->get('POST.confirm')) {
			$date = new DateTime();
	    	$code = md5($date->getTimestamp().$f3->get('POST.email'));
			$user = $this->model->createUser(array('code' => $code));
		}
		$f3->reroute('/');
	}

	public function confirmation ($f3) {
		$this->content = 'user/confirmation';
		$f3->set('code', $f3->get('PARAMS.code'));
	}

	public function validationAccount ($f3) {
		$user = $this->model->getUserByCode(array('code' => $f3->get('POST.code'), 'password' => $f3->get('POST.password')));
		if ($user) {
			$this->model->validationAccount(array('id' => $user->id));
			$f3->reroute('/login');
		}else{
			$f3->reroute('/');
		}
	}

	// FACEBOOK
	public function facebookConnect () {
	}

}

?>
