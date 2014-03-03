<?php

class AppController extends Controller {

	public function __construct(){
		parent::__construct();
		$this->tpl=array('sync'=>'layout.html');
	}

	// INDEX
	public function prehome ($f3){
		$this->content = 'app/prehome';
	}


	public function home ($f3) {
		$this->content = 'app/index';
		$date = mktime (date("H"), date("i"), date("s"), date("n"), date("j")+1, date("Y") );
		$projects = $this->model->getProjects(array('date' => $date));
		$users = array();

		/* Get firstname and lastname information */
		for($i=0; $i<count($projects); $i++){
			$id_user = $projects[$i]->id_user;

			$user = $this->model->getUser(array('id' => $id_user));
			$users[$projects[$i]->id]['firstname'] = $user[0]->firstname;
			$users[$projects[$i]->id]['lastname'] = $user[0]->lastname;
		}
		/* - - - - - - - - - - - - - - - - - - -  */
		
		$date = mktime (0,0,0, date("n"), date("j")+7, date("Y") );
		$lego = $this->model->getLego(array('date' => $date));
		$f3->set('users', $users);
		$f3->set('projects', $projects);
		$f3->set('lego', $lego);
	}

	// PROJECTS
	public function singleProject ($f3) {
		$project = $this->model->getProject(array('id' => $f3->get('PARAMS.id')));

		/* Get firstname and lastname information */
		$id_user = $project[0]->id_user;
		$user = $this->model->getUser(array('id' => $id_user));

		$users = array();
		$users[$project[0]->id]['id'] = $user[0]->id;
		$users[$project[0]->id]['firstname'] = $user[0]->firstname;
		$users[$project[0]->id]['lastname'] = $user[0]->lastname;
		/* - - - - - - - - - - - - - - - - - - -  */

		$vote = $this->model->getVote(array('id_project' => $f3->get('PARAMS.id'), 'id_user' => $f3->get('SESSION.user')['id']));
		
		$f3->set('users', $users);
		$f3->set('project', $project[0]);
		$f3->set('vote', $vote);
		
		if($project[0]->lego == 1){
			$this->content = 'project/lego';
		}else{
			$this->content = 'project/single';
		}
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
											'picture' => $user->picture 
										)
					);
			$f3->reroute('/home');
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
			$user = $this->model->createUser(array('code' => $code, 'email' => $f3->get('POST.email')));

			$this->content = 'user/success';
		}

		$this->content = 'Mot de passe invalide';
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
	public function facebookConnect($f3){
		require_once 'api/facebook.php';

		$facebook = new Facebook(array(
		  'appId'  => '351348715006194',
		  'secret' => '8f8b1a6fc1c1c2f9f2396952d61e611c',
		  'allowSignedRequest' => false,
          'cookie' => true
		));

		$user = $facebook->getUser();
		
		$params = array(
		  'scope' => 'email',
		);

		if($user){
			try{
				/* GET user profile information */
				$user_profile = $facebook->api('/me','GET');

				$firstname = $user_profile['first_name'];
				$lastname = $user_profile['last_name'];
				$email = $user_profile['email'];

				/* GET country of user */
				$client  = @$_SERVER['HTTP_CLIENT_IP'];
			    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
			    $remote  = $_SERVER['REMOTE_ADDR'];
			    $result  = "Unknown";

			    if(filter_var($client, FILTER_VALIDATE_IP)){
			        $ip = $client;
			    }
			    elseif(filter_var($forward, FILTER_VALIDATE_IP)){
			        $ip = $forward;
			    }
			    else{
			        $ip = $remote;
			    }

			    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));

			    if($ip_data && $ip_data->geoplugin_countryName != null){
			        $result = $ip_data->geoplugin_countryName;
			    }

			    $country = $result;
			    $city = null;

			    // Hack model's createUser function as we submit a form
			    $f3->set('POST.firstname', $firstname);
			    $f3->set('POST.lastname', $lastname);
			    $f3->set('POST.email', $email);
			    $f3->set('POST.country', $country);
			    $f3->set('POST.city', $city);
			    $f3->set('POST.password', 'facebook');
			    $f3->set('POST.confirm', 'facebook');

			    $date = new DateTime();
		    	$code = md5($date->getTimestamp().$f3->get('POST.email'));
				$user = $this->model->createUser(array('code' => $code, 'email' => $email));

				$this->createSession($f3);
			}
			catch(FacebookApiException $e){
				$f3->reroute($facebook->getLoginUrl($params));
			}   
		}
		else{
			$f3->reroute($facebook->getLoginUrl($params));
		}

	}

}

?>
