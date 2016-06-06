<?php 

class Db{
	private $conn = null;
	private $user;
	private $pass;
	private $db;
	private $host = "localhost";
	private $resultado = null;
	private $insertedId = null;
	private $filasAfectadas = null;

	public function __construct($db, $user, $pass){
		$this->db = $db;
		$this->user = $user;
		$this->pass = $pass;
	}

	public function __destruct(){		
		$this->desconectarse();
	}

	private function conectarse(){
		try {
			$this->conn = new PDO("mysql:host=$this->host;dbname=$this->db;", $this->user, $this->pass);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo "Error al establecer la conexión: " . $e->getMessage();
		}		
	}

	private function desconectarse(){
		$this->conn = null;
	}

	public function consulta($consulta, $params = []){
		$this->conectarse();

		$this->resultado = $this->conn->prepare($consulta);
		foreach($params AS $k=>$p){
			$this->resultado->bindParam($k + 1, $p);
		}
		try {			
			$this->resultado->execute();
		} catch (PDOException $e) {			
			echo $consulta . "<br>";
			echo $e->getMessage();
		}
		$this->insertedId = $this->conn->lastInsertId();
		$this->filasAfectadas = $this->resultado->rowCount();
		$this->desconectarse();
	}

	public function fetchAll(){
		return $this->resultado->fetchAll(PDO::FETCH_ASSOC);
	}

	public function fetch(){
		return $this->resultado->fetch(PDO::FETCH_ASSOC);
	}

	public function lastId(){
		return $this->insertedId;
	}

	public function filas(){
		return $this->filasAfectadas;
	}

}