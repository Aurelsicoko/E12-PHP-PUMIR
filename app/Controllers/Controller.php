<?php
class Controller{
  
protected $tpl;
protected $model;
protected $content;
protected $dir;

  protected function __construct(){
    $modelName=substr(get_class($this),0,strpos(get_class($this),'C')).'Model';
    $this->dir=substr(get_class($this),0,strpos(get_class($this),'C'));
    if(class_exists($modelName)){
      $this->model=new $modelName();
    } 
  }
  
  public function afterroute($f3){
    
    $f3->set('content', 'app/Views/'.$this->dir.'/'.$this->content.'.html');
    if($f3->get('AJAX')){
      echo View::instance()->render($this->tpl['async']);
    }else{
      echo View::instance()->render($this->tpl['sync']);
    } 
  }
}
?>