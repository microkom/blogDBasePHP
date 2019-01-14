<?php
require_once 'funciones.php';

print <<<HERE
	<aside class="side">
	<h2>ARCHIVO</h2>
	<ul>
HERE;

	//conexión a la base de datos
	$conexion =  conectarDB();

	//consulta para extraer los años de las entradas
	$sql="SELECT DISTINCT LEFT(fechaHora,4) as anyo from entrada ORDER BY fechaHora DESC;";
	$resultado=$conexion->prepare($sql);
	$resultado->execute();

	//años
	while($year = $resultado->fetch()){
		$anyo = $year['anyo'];

		//consulta para extraer los meses de las entradas usando loa años existentes
		$sql2="SELECT DISTINCT SUBSTR(fechaHora,6,2)fechaHora from entrada where fechaHora like '%$anyo%'";
		$resultado2=$conexion ->prepare($sql2);
		$resultado2->execute();
		print "<li>".$year['anyo'] ;
		//meses
		while($month = $resultado2->fetch()){
			$mes = $month['fechaHora'];			
			print "<ul ><li><a href=\"principal.php?year=$anyo&amp;month=$mes&amp;accion=archivo\">".month($mes)."</a></li></ul>"; 
		}
		print "</li>";
	}

	$conexion = null;

print "</ul> </aside>";

?>		