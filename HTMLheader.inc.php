<?php
require_once "direcciones.inc.php";


//terminar la sesion del usuario
if(isset($_POST['logout'])){
	unset($_SESSION['usuario']);
	header('location: index.php');
}

//asignar la variable de sesion usuario a la variable $usuario
if(isset($_SESSION['usuario'])){
	$usuario = $_SESSION['usuario'];
	$show = "hidden";
	$show2 = "visible";
}else{
	$show2 = "hidden";
	$show = "visible";
}
if(isset($_SESSION['usuario']))
	$mostrarUsuario = $usuario;
else
	$mostrarUsuario ="";

$local = $_SERVER["PHP_SELF"];

function ubicacionFoto(){

	$idUsuario = consultaIdUsuarioDBporNick($_SESSION['usuario']);
	$conexion = conectarDB();
	$sql = ('SELECT ubicacionFoto FROM usuario WHERE id=?');
	$consulta = $conexion->prepare($sql);
	$consulta->bindParam(1,$idUsuario);
	$consulta->execute();

	$r = $consulta->fetch();

	$conextion = null;
	return $r['ubicacionFoto'];
}

if(isset($_SESSION['usuario']))
	$foto = ubicacionFoto();

//var_dump($foto);
if(!isset($foto)){
	$foto="img/default.jpg";
}

print <<<HERE

<header>
    <section class="header">
	<a href="$raiz">GERMAN NAVARRO</a><br> 
				<img src="$foto" alt="Foto de perfil" title="Foto de perfil" height="50" >
	
	</section>
    <div class="menu">
        <a href="$principal">Principal</a>
        <div class="$show2">
        <div class="menuEntrada">
            <a href="$todo">Entradas »</a>
            <div class="opcionesMenuEntrada">
                <a href="$crear">Crear Entrada</a>
                <a href="$borrar">Borrar Entrada</a><!--Solo las del usuario logueado-->
                <a href="$comentarios">Comentarios</a>
                
            </div>
        </div>
        </div>
        <div class="$show"><a href="$registro" >Registro</a></div>
        <div class="$show"><a href="$login" >Login</a></div>

        <div class="$show2 fright">
		  <form action="$local" method="post" >
		  <button id="salir" type="submit" name="logout" >Salir</button></form></div>
 <div  class="$show2 fright"><a href="$login" class="$show2 ">Entradas $mostrarUsuario</a></div>


HERE;


print '<div style="float:right;color:white;font-weight:500;padding:10px;">';
print '</div></div></header>';

?>