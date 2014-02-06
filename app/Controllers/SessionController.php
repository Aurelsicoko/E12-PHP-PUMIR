<?php
class SessionController extends Controller{

  public function __construct(){
    parent::__construct();
    $this->tpl=array('sync'=>'layout.html');
  }
  
  public function newSession ($f3) {
    $this->content = 'new';
  }

  public function create ($f3) {
    $res = $this->model->login(array('email' => $f3->get('POST.email'), 'password' => $f3->get('POST.password')));
    if (count($res) == 1) {
      $f3->set('SESSION.user', $res->firstname.' '.$res->lastname);
      $f3->set('SESSION.id', $res->id);
    }
    $f3->reroute('/');
  }

  public function destroy ($f3) {
    $f3->clear('SESSION');
    $f3->reroute('/');
  }  
  
}
?>