<?php
class Model{
  
private $dB;
protected $f3;

	protected function __construct(){
		$f3 = \Base::instance();
		$this->f3 = $f3;
		$this->dB=new DB\SQL('mysql:host='.$f3->get('db_host').';port=3306;dbname='.$f3->get('db_name'),$f3->get('db_login'),$f3->get('db_password'));
	}

	protected function getMapper($table){
		return new \DB\SQL\Mapper($this->dB,$table);
	}
  
}
?>