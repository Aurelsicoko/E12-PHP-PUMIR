<?php

class AuthAppController extends Controller {

	public function __construct(){
		parent::__construct();
		$this->tpl=array('sync'=>'layout.html');
	}

	public function beforeroute ($f3) {
		if ($f3->get('SESSION.authenticated') !== true) {
			$f3->reroute('/signup');
		}
	}

	// PROJECT
	public function submitProject ($f3) {
		$this->content = 'project/submit';
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
		$this->model->createProject(array('photos' => serialize($f3->get('photos')), 'id' => $f3->get('SESSION.user')['id'], 'user_vote' => $user_vote, 'admin_vote' => $admin_vote));
		$f3->reroute('/');
	}

	public function voteProject ($f3) {
		/* Verify if user doesn't vote for this project before */
		$user = $this->model->getIfUserVote(array('id_user' => $f3->get('SESSION.user')['id'], 'id_project' => $f3->get('PARAMS.id')));
		
		if(count($user) == 0){
			$project = $this->model->getProject(array('id' => $f3->get('PARAMS.id')));
			$user_vote = unserialize($project->user_vote);
			$user_vote["originality"] = (($user_vote["originality"]*$user_vote["vote"])+$f3->get('POST.originality'))/($user_vote["vote"]+1);
			$user_vote["difficulty"] = (($user_vote["difficulty"]*$user_vote["vote"])+$f3->get('POST.difficulty'))/($user_vote["vote"]+1);
			$user_vote["style"] = (($user_vote["style"]*$user_vote["vote"])+$f3->get('POST.style'))/($user_vote["vote"]+1);
			$this->model->voteProject(array("id_project" => $f3->get('PARAMS.id'), 'user_vote' => $user_vote));
			$this->model->addVote(array('id_user' => $f3->get('SESSION.user')['id'], 'id_project' => $f3->get('PARAMS.id')));
		}


		
		$f3->reroute('/project/'.$f3->get('PARAMS.id'));
	}


	// USERS
	public function singleUser($f3) {

		$id_user = $f3->get('SESSION.user')['id'];

		if(count($f3->get('PARAMS.id')) != 0){
			$id_user = $f3->get('PARAMS.id');
		}

		$this->content = 'user/user';
		$users = $this->model->getUser(array('id' => $id_user));
		$projects = $this->model->getProjects(array('id' => $id_user));
		$f3->set('projects', $projects);
		$f3->set('users', $users);
	}

	// SESSION
	public function destroySession ($f3) {
		$f3->set('SESSION.authenticated', false);
		$f3->set('SESSION.user', NULL);
    	$f3->reroute('/');
	}

	public function payment ($f3){
		/**
		* It's an personnal class in lib/stripe.php
		* Usable for all personne who have a stripe account
		**/
		Payment::instance()->pay("sk_test_AXDvxs10OzIjPEKjdWVF3WyZ", 2000, function ($err) use($f3) {
			if ($err) $f3->reroute('/');
			$this->model->validationProject(array('id' => $f3->get('POST.projectId')));
			$f3->reroute('/');
		});
	}

}

?>
