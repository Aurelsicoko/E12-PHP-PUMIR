<?php

class AppController extends Controller {

	public function __construct(){
		parent::__construct();
		$this->tpl=array('sync'=>'layout.html');
	}

	// INDEX
	public function prehome ($f3){
		$this->tpl=array('sync'=>'app/prehome.html');
	}

	public function timeline ($f3){
		$this->tpl=array('sync'=>'timeline.html');
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

		$users = array();

		/* Get firstname and lastname information */
		for($i=0; $i<count($projects); $i++){
			$id_user = $projects[$i]->id_user;

			$user = $this->model->getUser(array('id' => $id_user));
			$users[$projects[$i]->id]['firstname'] = $user[0]->firstname;
			$users[$projects[$i]->id]['lastname'] = $user[0]->lastname;
		}
		/* - - - - - - - - - - - - - - - - - - -  */

		$f3->set('users', $users);
		$f3->set('projects', $projects);
	}

	public function getRecentProjects ($f3) {
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

		$f3->set('users', $users);
		$f3->set('projects', $projects);
		$this->tpl = array('async'=>'project/award.html');
	}

	public function getAward ($f3) {
		$projects = $this->model->getAward();

		$users = array();

		/* Get firstname and lastname information */
		for($i=0; $i<count($projects); $i++){
			$id_user = $projects[$i]->id_user;

			$user = $this->model->getUser(array('id' => $id_user));
			$users[$projects[$i]->id]['firstname'] = $user[0]->firstname;
			$users[$projects[$i]->id]['lastname'] = $user[0]->lastname;
		}
		/* - - - - - - - - - - - - - - - - - - -  */

		$f3->set('users', $users);
		$f3->set('projects', $projects);
		$this->tpl = array('async'=>'project/award.html');
	}

	public function getLastProjects ($f3){
		$date = mktime (0,0,0, date("n"), date("j")+1, date("Y"));
		$projects = $this->model->getLastProjects(array('date' => $date));

		$users = array();

		/* Get firstname and lastname information */
		for($i=0; $i<count($projects); $i++){
			$id_user = $projects[$i]->id_user;

			$user = $this->model->getUser(array('id' => $id_user));
			$users[$projects[$i]->id]['firstname'] = $user[0]->firstname;
			$users[$projects[$i]->id]['lastname'] = $user[0]->lastname;
		}
		/* - - - - - - - - - - - - - - - - - - -  */

		$f3->set('users', $users);
		$f3->set('projects', $projects);
		$this->tpl = array('async'=>'project/award.html');
	}

	// SESSION
	public function newSession ($f3){
		$this->content = 'session/new';
	}

	public function createSession ($f3) {
		$user = $this->model->login(array('email' => $f3->get('POST.email'), 'password' => md5($f3->get('POST.password'))));
		if(count($user) > 0 && $user != null){
			$f3->set('SESSION.authenticated', true);
			$f3->set('SESSION.user', array(	'id' => $user->id, 
											'firstname' => $user->firstname, 
											'lastname' => $user->lastname,
											'email' => $user->email,
											'admin' => $user->admin,
											'country' => $user->country,
											'city' => $user->city,
											'picture' => $user->picture 
										));
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
			$f3->set('POST.password', md5($f3->get('POST.password'))); 
			$date = new DateTime();
	    	$code = md5($date->getTimestamp().$f3->get('POST.email'));
			$user = $this->model->createUser(array('code' => $code, 'email' => $f3->get('POST.email')));

			$this->content = 'user/success';
		}
		else{
			$this->content = 'Mot de passe invalide';
		}
	}

	public function editUser ($f3){
		$firstname = $f3->get('POST.firstname');
		$lastname = $f3->get('POST.lastname');
		$email = $f3->get('POST.email');
		$city = $f3->get('POST.city');
		$country = $f3->get('POST.country');
		$password = $f3->get('POST.password');
		$confirm = $f3->get('POST.confirm');

		$user = $this->model->getUser(array('id' => $f3->get('SESSION.user')['id']));

		if($password == $confirm && $user[0]->password == md5($password)){
			$this->model->updateUser(array('id' => $user[0]->id, 'firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'city' => $city, 'country' => $country));
		}

		$f3->reroute('/profil');
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

	public function send ($f3){
		$this->content = 'app/contact';

		/*if(empty($f3->get('POST.name')) || empty($f3->get('POST.email')) || empty($f3->get('POST.message'))){
			$f3->set('send', 2);
		}
		else{
			$smtp = new SMTP('localhost', 25, 'tls', null, null);
	        $smtp->set('From','aurelsicoko@gmail.com');
	        $smtp->set('To', $f3->get('POST.name').' <'.$f3->get('POST.email').'>');
	        $smtp->set('Subject','LEGO Awards');

	        if($smtp->send($f3->get('POST.message')))  
	        	$f3->set('send', 1);
	        else
	        	$f3->set('send', 0);
		}*/
	}

	public function about ($f3){
		$this->content = 'app/about';
	}

	public function legal ($f3){
		$this->content = 'app/legal';
	}

	public function contact ($f3){
		$this->content = 'app/contact';
	}

	public function help ($f3){
		$this->content = 'app/help';
	}

	public function rules ($f3){
		$this->content = 'app/rules';
	}

}

?>
