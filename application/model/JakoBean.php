<?php
require "ModeloBase.php";

abstract class JakoBean extends ModeloBase{

	public function guardar(){
		if($this->esNuevo){
			return $this->insertar();
		} else {
			return $this->actualizar();
		}
	}

	public function eliminar(){
		return $this->borrar();
	}

	public function listarTodos($criterios = []){
		$resultados = $this->consultar($criterios);
		$registros = [];
		$claseInvocada = get_called_class();
		foreach($resultados AS $r){
			$nr = new $claseInvocada();
			$nr->setAtributos($r);
			$nr->esNuevo = false;
			$registros[] = $nr;
		}
		return $registros;
	}

	public function primer($criterios = []){
		$resultados = $this->consultar($criterios);
		$claseInvocada = get_called_class();
		$r = new $claseInvocada();
		$r->setAtributos($resultados[0]);
		$r->esNuevo = false;
		return $r;
	}

	public static function modelo($clase = __CLASS__){
		if(class_exists($clase)){
			return new $clase();
		} else {
			return null;
		}
	}
}