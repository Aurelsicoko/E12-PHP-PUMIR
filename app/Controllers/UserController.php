<?php
class UserController extends Controller{

  public function __construct(){
    parent::__construct();
    $this->tpl=array('sync'=>'layout.html');
  }
  
  public function loginPage ($f3) {
    $this->content = 'login';
  }

  public function login ($f3) {
    $res = $this->model->login(array('email' => $f3->get('POST.email'), 'password' => $f3->get('POST.password')));
    if (count($res) == 1) {
      $f3->set('SESSION.user', $res->firstname.' '.$res->lastname);
      $f3->set('SESSION.id', $res->id);
    }
    $f3->reroute('/');
  }

  public function logout ($f3) {
    $f3->clear('SESSION');
    $f3->reroute('/');
  }

  public function newAccount ($f3) {
    $this->content =  'newAccount';
  }

  public function create ($f3) {
    $res = $this->model->create();
    if ($res) {
      $f3->reroute('/login');
    }else{
      echo 'Fail creat profil';
    }
    $this->content =  'newAccount';

  }

  public function edit ($f3) {
    $this->model->edit();
    
    $this->content = 'editAccount';
  }

  public function update ($f3) {
    $res = $this->model->update();
    if ($res) {
      echo 'Profil updated';
    }else{
      echo 'Fail update';
    }
    $this->content = 'editAccount';
  }

  public function delete ($f3) {
    $this->content = 'destroyAccount';
  }

  public function destroy ($f3) {
    $this->model->destroy();
    $f3->clear('SESSION');
    $f3->reroute('/');
  }
  
  
  
  
  
}
?>