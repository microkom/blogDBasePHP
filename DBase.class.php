<?php
/*
Clase BD - contendrá los siguientes campos:
�? Array de entradas.
�? Array de usuarios.
*/

Class BD{
	public $entrada = array();
	public $usuario = array();


	public function addEntrada($entrada) {
		$this->entrada[] = $entrada;
	}    
	
	public function getTotalEntradas() {   //Devuelve 
		$contador=0;
		foreach( $this->entrada as $value){
			$contador++;

		}       
		return $contador;
	}

	public function readEntradaIndice($indice) { //por �ndice
		return  $this->entrada[$indice];        
	}

	public function addUsuario($usuario) {
		$this->usuario[] = $usuario;
	}

	public function readUsuario($usuario) {
		return  $this->usuario[$usuario];
	}
}
?>
