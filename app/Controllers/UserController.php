<?php
class UserController extends Controller{

  public function beforeroute ($f3) {
    $id = $f3->get('SESSION.id');
    if (!isset($id) AND $f3->get('PARAMS.action') != 'newAccount'){
      $f3->reroute('/login');
    }
    return;
  }

  public function __construct(){
    parent::__construct();
    $this->tpl=array('sync'=>'layout.html');
  }

  public function newAccount ($f3) {
    $this->content = 'new';
  }

  public function create ($f3) {
    $res = $this->model->create();
    if ($res) {
      $f3->reroute('/login');
    }else{
      echo 'Fail creat profil';
    }
    $this->content =  'new';
  }

  public function edit ($f3) {
    $res = $this->model->edit(array('id' => $f3->get('SESSION.id')));
    $f3->set('user', $res);
    $this->content = 'edit';
  }

  public function update ($f3) {
    $res = $this->model->update();
    if ($res) {
      echo 'Profil updated';
    }else{
      echo 'Fail update';
    }
    $this->content = 'edit';
  }

  public function delete ($f3) {
    $this->content = 'destroy';
  }

  public function destroy ($f3) {
    $this->model->destroy();
    $f3->clear('SESSION');
    $f3->reroute('/');
  }
  
}
?>