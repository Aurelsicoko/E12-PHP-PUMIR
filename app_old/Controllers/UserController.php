<?php

class UserController extends Controller {

	function __construct () {}

	function loginPage ($f3) {
		$f3->set('page', 'login');
	}

	function login ($f3) {
  	$model = new UserModel($f3);
	   $res = $model->login($f3, array('email' => $f3->get('POST.email'), 'password' => $f3->get('POST.password')));
		if (count($res) == 1) {
			$f3->set('SESSION.user', $res->firstname.' '.$res->lastname);
			$f3->set('SESSION.id', $res->id);
		}
		$f3->reroute('/');
  }

  	function logout ($f3) {
  		$f3->clear('SESSION');
  		$f3->reroute('/');
  	}

  	function newAccount ($f3) {
  		$f3->set('page', 'newAccount');
  	}

  	function create ($f3) {
  		$user = new DB\SQL\Mapper($f3->get('dB'),'users');
  		$user->copyFrom('POST');
  		$date = new DateTime();
  		$user->code = md5($date->getTimestamp().$user->email);
  		if ($user->password == $f3->get('POST.confirm')) {
  			$user->save();
  			$f3->reroute('/login');
  		}else{
  			echo 'Fail confirm';
  		}
  		$f3->set('page', 'newAccount');
  	}

  	function edit ($f3) {
  		$user = new DB\SQL\Mapper($f3->get('dB'),'users');
  		$user->load('id = "'.$f3->get('SESSION.id').'"');
  		$user->copyTo('POST');
  		$f3->set('page', 'editAccount');
  	}

  	function update ($f3) {
  		$user = new DB\SQL\Mapper($f3->get('dB'),'users');
  		$infos = $user->load('id = "'.$f3->get('SESSION.id').'"');
  		$user->copyFrom('POST');
  		if ($user->password == $f3->get('POST.confirm')) {
  			if ($user->password == "") {
  				$user->password = $infos->password;
  				$user->save();
  			}else{
  				$user->save();
  			}
  			echo 'Profil updated';
  		}else{
  			echo 'Fail confirm';
  		}
  		$f3->set('page', 'editAccount');
  	}

  	function delete ($f3) {
  		$f3->set('page', 'destroyAccount');
  	}

  	function destroy ($f3) {
  		$user = new DB\SQL\Mapper($f3->get('dB'),'users');
  		$user->erase('id = "'.$f3->get('SESSION.id').'"');
  		$f3->clear('SESSION');
  		$f3->reroute('/');
  	}

}

?>