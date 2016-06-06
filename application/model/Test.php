<?php 
require "JakoBean.php";
class Test extends JakoBean{
	function __construct(){
		$this->tabla = "items";
		$this->pk = 'id';
		$this->atributos = ["id", "nombre", "des"];
		parent::__construct();
	}

	public static function modelo($c = __CLASS__){
		return parent::modelo($c);
	}
}