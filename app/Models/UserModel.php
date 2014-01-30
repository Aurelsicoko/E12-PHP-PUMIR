<?php
class UserModel extends Model{
  
private $mapper;
  
	public function __construct(){
		parent::__construct();
		$this->mapper=$this->getMapper('users');
	}

	public function login ($params) {
		return $this->mapper->load('email = "'.$params['email'].'" AND password = "'.$params['password'].'"');
	}

	public function create () {
		$f3 = $this->f3;
		$this->mapper->copyFrom('POST');
	    $date = new DateTime();
	    $this->mapper->code = md5($date->getTimestamp().$this->mapper->email);
	    if ($this->mapper->password == $f3->get('POST.confirm') AND $this->mapper->password != "") {
	    	$this->mapper->save();
	   		return true;
	    }else{
	    	return false;
	    }
	}

	public function edit () {
		$f3 = $this->f3;
		$this->mapper->load('id = "'.$f3->get('SESSION.id').'"');
    	$this->mapper->copyTo('POST');
	}

	public function update () {
		$f3 = $this->f3;
		$infos = $this->mapper->load('id = "'.$f3->get('SESSION.id').'"');
	    $this->mapper->copyFrom('POST');
	    if ($this->mapper->password == $f3->get('POST.confirm')) {
	      if ($this->mapper->password == "") {
	        $this->mapper->password = $infos->password;
	        $this->mapper->save();
	      }else{
	        $this->mapper->save();
	      }
	      return true;
	    }else{
	      return false;
	    }
	}

	public function destroy () {
		$f3 = $this->f3;
		$this->mapper->erase('id = "'.$f3->get('SESSION.id').'"');
	}

}
?>