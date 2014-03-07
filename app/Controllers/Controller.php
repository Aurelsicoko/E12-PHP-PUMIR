<?php
class Controller{
  
protected $tpl;
protected $model;
protected $content;
protected $dir;

  protected function __construct(){
    $modelName=substr(get_class($this),0,strpos(get_class($this),'C')).'Model';
    if(class_exists($modelName)){
      $this->model=new $modelName();
    } 
  }
  
  public function afterroute($f3){
    $f3->set('content', 'app/Views/'.$this->content.'.html');
    $f3->set('load', 'app/Views/loadJS.html');

    $mimeTypes=array('html'=>'text/html','json'=>'application/json');
    $tpl=$f3->get('AJAX')?$this->tpl['async']:$this->tpl['sync'];
    $ext=substr($tpl,strrpos($tpl,'.')+1);
    $mime=$mimeTypes[$ext];
    echo View::instance()->render($tpl,$mime);
  }
}
?>
