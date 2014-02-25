<?php

namespace REST;

class award extends \REST\api{
  
private $dB;

  function __construct(){
    $f3 = \Base::instance();
    $this->dB=new \DB\SQL('mysql:host='.$f3->get('db_host').';port=3306;dbname='.$f3->get('db_server'),$f3->get('db_login'),$f3->get('db_password'));
  }
  
  function get($f3){
    $mapper=new \DB\SQL\Mapper($this->dB, 'projects');
    $f3->set('datas',$mapper->find(array('lego = 1')));
    $this->tpl='datas.json';
  }
  
  function post($f3){
    $f3->error(403);
    
  }
  
  function put($f3){

  }
  
  function delete($f3){

  }
  
  
  
}
?>