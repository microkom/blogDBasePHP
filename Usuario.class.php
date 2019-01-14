<?php
/*
Clase Usuario - contendrá los siguientes campos:
● user: email del usuario.
● nombre: nombre del usuario.
● pass: contraseña del usuario.
● rol: tipo de usuario, que podrá ser blogger o admin.
*/

Class Usuario{

    private $user;
    private $nombre;
    private $pass;
    private $rol;

    public function __construct($user, $nombre, $pass, $rol){
        $this->user = $user;
        $this->nombre = $nombre;
        $this->pass = $pass;
        $this->rol = $rol;
    }

    public function __set($atributo, $valor){
        $this->$atributo = $valor;
    }                    

    public function __get($atributo){
        return $this->$atributo;
    }
 
 
}

?>