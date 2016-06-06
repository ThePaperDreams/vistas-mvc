<?php 
require "Db.php";

abstract class ModeloBase{
	protected $pk;
	protected $tabla;
	protected $atributos;
	protected $_atributos;
	protected $conn;
	public $esNuevo = true;

	public function __construct(){
		$this->conn = new Db(DB_NAME, DB_USER, DB_PASS);
		$this->construirAtributos();
	}	

	private function construirAtributos(){
		foreach($this->atributos AS $nombre => $valor){
			$this->_atributos[$valor] = null;
		}
	}

	protected function insertar(){
		$attrs = $this->_atributos;
		unset($attrs['pk']);
		$cols = array_keys($attrs);
		$values = implode(', ',array_map(function($v){
			return "'$v'";
		}, $attrs));
		$consulta = "INSERT INTO $this->tabla (" . implode(', ', $cols) . ") VALUES($values)";
		$this->conn->consulta($consulta);
		$this->_atributos[$this->pk] = $this->conn->lastId();
		$this->esNuevo = false;
		return $this->conn->lastId() != null && $this->conn->lastId() != 0;
	}

	protected function consultar($criterios = []){
		$join = isset($criterios['join'])? $criterios['join'] : '';
		$where = isset($criterios['where'])? " WHERE " . $criterios['where'] : '';
		$order = isset($criterios['order'])? " ORDER BY " . $criterios['order'] : '';
		$limit = isset($criterios['limit'])? " LIMIT " . $criterios['limit'] : '';
		$consulta = "SELECT * FROM $this->tabla $join $where $order $limit";
		$this->conn->consulta($consulta);
		return $this->conn->fetchAll();
	}

	protected function actualizar(){
		$attrs = $this->_atributos;
		unset($attrs['pk']);
		$cols = array_keys($attrs);
		$values = implode(', ',array_map(function($k, $v){
			return "$k = '$v'";
		}, $cols, $attrs));
		$consulta = "UPDATE $this->tabla SET $values WHERE $this->pk = '" . $this->_atributos[$this->pk] . "'";
		$this->conn->consulta($consulta);
		return $this->conn->filas() > 0;
	}

	protected function borrar(){
		$consulta = "DELETE FROM  $this->tabla WHERE $this->pk = '" . $this->_atributos[$this->pk] . "'";
		$this->conn->consulta($consulta);
		return $this->conn->filas() > 0;
	}

	public function __set($n, $v){
		if(array_key_exists($n, $this->_atributos)){
			$this->_atributos[$n] = $v;
		}
	}

	public function setAtributos($atributos){
		foreach($atributos AS $key=>$val){
			if(array_key_exists($key, $this->_atributos)){
				$this->_atributos[$key] = $val;
			}
		}
	}

	public function __get($n){
		if(array_key_exists($n, $this->_atributos)){
			return $this->_atributos[$n];
		}
	}

}