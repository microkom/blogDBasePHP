<?php
session_start();
require_once 'AllClasses.php'; //no borrar


function _borrarComentario($idComentario) {   //por idEntrada

	foreach( $this->comentario as $key => $objetoComentario){
		if($objetoComentario->idComentario === (string)$idComentario){
			unset($this->comentario[$key]);
			return true;
		}
	}

}

function borrarComentario($idComentario){

	$conexion = conectarDB();
	$sql = "DELETE from comentario WHERE id=?";
	$consulta  = $conexion->prepare($sql);
	$consulta->bindParam(1, $idComentario);
	$res = $consulta->execute();

	$sql = null;
	$conextion = null;

	if($res)
		return "Comentario borrado.";
	else
		return "Fallo al borrar el comentario.";
}


function borrarComentarioWarning($idEntrada,$idComentario){

	$objetoEntrada = readUnaEntradaDB($idEntrada);
	$objetoComentario = $objetoEntrada->getUnComentario($idComentario);

	$comentarioBorrar = $objetoComentario->texto;

	$textoString = "Se va a proceder al borrado del comentario :<br><br>
    <h5> $comentarioBorrar </h5> <br> <br> Confirma el borrado?  
    <a href=\"principal.php?idEntrada=$idEntrada&amp;accion=borrarComentario&amp;idComentario=$idComentario\">Borrar</a> 
    <a href=\"login.php\">Anular</a>";
	return $textoString;

}


function borrarEntradaWarning($idEntrada){

	$objetoEntrada = readUnaEntradaDB($idEntrada);
	return "Se va a proceder al borrado de la entrada con título: <h5>$objetoEntrada->titulo</h5> Confirma el borrado de dicha entrada?  
        <a href=\"principal.php?idEntrada=$idEntrada&amp;accion=borrarEntrada\"> Borrar</a> <a href=\"login.php\">Anular</a>";

}

function borrarEntrada($idEntrada){

	$conexion = conectarDB();
	$sql = "DELETE from entrada WHERE id=?";
	$consulta  = $conexion->prepare($sql);
	$consulta->bindParam(1, $idEntrada);
	$res = $consulta->execute();

	$sql = null;
	$conextion = null;

	if($res)
		return "Entrada borrada.";
	else
		return "Fallo al borrar la entrada.";
}

function _borrarEntrada($idEntrada) {   //por idEntrada

	foreach( $this->entrada as $key => $objetoEntrada){
		if(strcasecmp($objetoEntrada->idEntrada, $idEntrada)===0){
			unset($this->entrada[$key]);
			return true;
		}
	}

}

function busqueda($busqueda, $tipo) {   //por string
	$idEntradaArr = array();
	$found =false;

	//busca 1 o más palabras en el array que recibe
	if($tipo === 1){
		//busca frases completas
		$busquedaArr = explode(' ', $busqueda);//separar el texto en un array
		$busquedaArr = array_filter($busquedaArr); //limpiar los posibles espacios dobles
		$busqueda = implode(' ', $busquedaArr);//pasar a string el array
	}

	$conexion = conectarDB();
	$sql = "SELECT id from entrada WHERE texto like CONCAT('%', :texto ,'%') or titulo like  CONCAT('%', :titulo ,'%')";
	$consulta  = $conexion->prepare($sql);
	$consulta->bindValue(":texto", $busqueda);
	$consulta->bindValue(":titulo", $busqueda);
	$res = $consulta->execute();

	$sql = null;
	$conextion = null;

	while($data = $consulta->fetch()){ 
		$id = $data['id'];
		$idEntradaArr[] = $id;
		$found = true;
	}

	if($found)
		return $idEntradaArr;
	else
		return false;
}


function conectarDB(){
	try {
		$dsn = "mysql:host=localhost;dbname=blogdwes;charset=utf8";
		$dbh = new PDO($dsn, 'root');
	} catch (PDOException $e){
		print "Error de Conexión: ".$e->getMessage();
	}
	return $dbh;
}

//Devuelve el nick del usuario usando el idUsuario
function consultaNickUsuarioDB($idUsuario){
	$conexion = conectarDB();
	$sql = "SELECT usuario from usuario WHERE id=?";
	$consultaUsuario = $conexion->prepare($sql);
	$consultaUsuario->bindParam(1, $idUsuario);
	$consultaUsuario->execute();

	while($reg = $consultaUsuario->fetch()){
		$usuario = $reg['usuario'];
	}
	$sql = null;
	$conextion = null;
	return $usuario;
}

//Devuelve el id del usuario usando el nick
function consultaIdUsuarioDBporNick($nick){
	$conexion = conectarDB();
	$sql = "SELECT id from usuario WHERE usuario=?";
	$consultaIdUsuario = $conexion->prepare($sql);
	$consultaIdUsuario->bindParam(1, $nick);
	$consultaIdUsuario->execute();

	while($reg = $consultaIdUsuario->fetch()){
		$idUsuario = $reg['id'];
	}
	$sql = null;
	$conextion = null;
	return $idUsuario;
}

//Devuelve el Id del usuario usando el idEntrada
function consultaIdUsuarioDB($idEntrada){
	$conexion = conectarDB();
	$sql = "SELECT idUsuario FROM entrada WHERE id =?";
	$consultaUsuario = $conexion->prepare($sql);
	$consultaUsuario->bindParam(1, $idEntrada);
	$consultaUsuario->execute();

	while($reg = $consultaUsuario->fetch()){
		$idUsuario = $reg['idUsuario'];
	}
	$sql = null;
	$conextion = null;
	return $idUsuario;
}


function consultaComentariosDB($idEntrada){

	$conexion = conectarDB();

	//consultar comentarios de la entrada
	$sql = "SELECT c.id  , c.texto , c.idUsuario  , c.fechaHora as fh  FROM comentario c, entrada e WHERE e.id=c.idEntrada AND c.idEntrada=?";
	$consultaComentario = $conexion->prepare($sql);
	$consultaComentario->bindParam(1, $idEntrada);
	$consultaComentario->execute();

	$comentarioArr = null;
	$comentarioArr = array();

	while ($comentario = $consultaComentario->fetch()){

		$idComentario = $comentario['id'];
		$textoComent = $comentario['texto'];
		$fhComent = $comentario['fh'];
		$idUsuario = $comentario['idUsuario'];

		//crea objeto comentario para agregar al array
		$comentario = new Comentario($idComentario, $idEntrada, $fhComent,  consultaNickUsuarioDB($idUsuario), $textoComent);

		//construye array de comentarios con los comentarios que hay
		$comentarioArr[] = $comentario;							
	}
	$sql = null;
	$conextion = null;
	return $comentarioArr;
}

function comentarios($idEntrada){

	$objetoEntrada = readUnaEntradaDB($idEntrada);

	$textoString ="";

	//título del artículo
	$textoString .= "<h3>$objetoEntrada->titulo</h3>";
	$textoString .=  "...<a href=\"./principal.php?idEntrada=$objetoEntrada->idEntrada&amp;accion=mostrar\">Leer mas.</a>";

	//Usuario y fecha update
	$textoString .=  "<table class=\"w100\"  ><tr><td><h4>".$objetoEntrada->_mostrarUsuarioFechaHora()."</h4></td></tr><tr><td>";

	$textoString .=  $objetoEntrada->_mostrarComentarioCompleto();
	$textoString .=  "</td></tr></table>";

	$textoString .=  '<a href="principal.php?idEntrada='.$objetoEntrada->idEntrada.'&amp;accion=comentar">Agregar Comentario</a>';

	return $textoString;
}

function comentar($idEntrada){

	//crea un objeto con el id de la entrada desde la base de datos
	$objetoEntrada = readUnaEntradaDB($idEntrada);

	if(isset($_SESSION['usuario'])){

		$textoString ="";
		$textoString .= "<form action='principal.php' method='post'>";
		//$comentario = $objetoEntrada->getComentario();
		$textoString .=  "<h3>AÑADIR COMENTARIO</h3>";
		$textoString .=  "Se va a añadir un comentario. Los comentarios pueden ser moderados por el administrador del blog<br><br><h3>$objetoEntrada->titulo</h3>";
		$textoString .=  '
		<label for="autordd">Nombre</label>
		<br>
		<input type="text"  size="20" value="'.$_SESSION['usuario'].'" disabled>
		<input type="hidden" name="autor" size="20" value="'.$_SESSION['usuario'].'" >';

		//Texto del articulo
		$textoString .=  "<br><br><label for=\"texto\">Comentario</label><br>
     	<textarea rows=\"16\" cols=\"50\" name=\"texto\" ></textarea><br>";
		$fechaHora = date('Y-m-d H:i');

		$textoString .= "<div>".dateTime(date('Y-m-d H:i'))."</div>
		<input type=\"hidden\" name=\"fechaHora\" value=\"$fechaHora\">
		<input type=\"hidden\" name=\"idEntrada\" value=\"$idEntrada\">
		<br>
		<input type=\"submit\" name=\"guardarNuevoCommentario\" value=\"Guardar\"></form>
		<br>
		<br>";

		$textoString .=  $objetoEntrada->_mostrarComentarioCompleto();
		return $textoString;
	}else{
		header("location:login.php");
	}

}

function crearEntrada(){

	
	print '<!--crearEntrada()-->';
	print '<h2 class="width100">CREACION DE ENTRADAS</h2>';

	print "<form action=".$_SERVER['PHP_SELF']." method=\"post\">";

	print <<<HERE

    <label  for="titulo">Titulo</label><br>
    <input   class="width100" type="text"  name="titulo" >
    <br><br>
    <label for="texto">Articulo</label><br>
    <textarea class="w100" name="texto"></textarea>

HERE;

	$fechaHora = date('Y-m-d H:i');

	//autor y fecha
	
	print $_SESSION['usuario']."   ". dateTime($fechaHora)." <br>  <br>  
    <input type=\"hidden\" name=\"fechaHora\" value=\"$fechaHora\">
    <input type=\"hidden\" name=\"usuario\" value=\"".consultaIdUsuarioDBporNick($_SESSION['usuario'])."\">
    <input type=\"hidden\" name=\"id\" value=\"".ultimoIdEntradasDB()."\">
         
                <input type=\"submit\" name=\"crearEntrada\" value=\"Guardar\">
            </br>
    </form>";

}

//formato fecha-hora: dia, mes, año, hora, minutos
function dateTime($fechaHora){    

	$anyo = substr($fechaHora,0,4); 
	$mes = substr($fechaHora,5,2);
	$dia = substr($fechaHora,8,2);
	$hora = substr($fechaHora,11,2);
	$min = substr($fechaHora,14,2);

	return $dia."/".$mes."/".$anyo." ".$hora.":".$min;
}

function editarComentario($idEntrada, $idComentario){

	$textoString = "";
	$textoString .= "<form action=".$_SERVER['PHP_SELF']." method=\"post\">";

	$objetoEntrada =  readUnaEntradaDB($idEntrada);
	$comentarioArr = $objetoEntrada->comentario;

	foreach($comentarioArr as $comentario ){
		if(strcasecmp ($comentario->idComentario, $idComentario)==0){
			$objetoComentario = $comentario;
		}
	}

	$textoString .=  "<h3>$objetoEntrada->titulo</h3>Editar Comentario<br><br>";

	$textoString .=  "<label for='autor'>Autor</label><br><input type='text' size='50'  name='autor' value='$objetoComentario->autor' disabled>";

	$texto=  $objetoComentario->texto;//nl2br agrega <br/>
	$texto = br2nl($texto);

	//Texto del Comentario
	$textoString .=  '<br><br><label for="texto">Comentario</label><br><textarea rows="22" cols="70" name="texto" >'.$texto.'</textarea><br>';

	$fechaHora = date('Ymdhis');

	//autor y fecha
	$textoString .=  "<table class=\"w100\"  ><tr><td class=\"right\"> <h5>".dateTime($objetoEntrada->fechaHora)."</h5></td> </tr>

        <input type=\"hidden\" name=\"fechaHora\" value=\"$fechaHora\">
        <input type=\"hidden\" name=\"idEntrada\" value=\"$idEntrada\">
        <input type=\"hidden\" name=\"idComentario\" value=\"$objetoComentario->idComentario\">

        <tr><td><input type=\"submit\" name=\"guardarCommentarioEditado\" value=\"Guardar\"></td></tr>
        </table></form>";

	return $textoString;

}

function editarEntrada($idEntrada){

	print "<form action=".$_SERVER['PHP_SELF']." method=\"post\">";

	$objetoEntrada =  readUnaEntradaDB($idEntrada);
	print "<label for='titulo'>Titulo</label><br>
    <input type='text' size='50'  name='titulo' value='$objetoEntrada->titulo'>";

	$texto =  ($objetoEntrada->texto);//nl2br agrega <br/>
	$texto = br2nl($texto);

	//Texto del articulo
	print "<br><br><label for=\"articulo\">Articulo</label><br>
     <textarea rows=\"22\" cols=\"70\" name=\"texto\">$texto</textarea><br>";

	$fechaHora = date('Ymdhis');
	//autor y fecha
	print "<table class=\"w100\"  ><tr><td>";
	print "<h5>".$objetoEntrada->usuario."</h5> </td><td class=\"right\"> <h5>".dateTime($objetoEntrada->fechaHora)."</h5></td></tr>

    <input type=\"hidden\" name=\"fechaHora\" value=\"$fechaHora\">
    <input type=\"hidden\" name=\"idEntrada\" value=\"$idEntrada\">
    <tr><td><input type=\"submit\" name=\"guardar\" value=\"Guardar\"></td></tr>
    </table></form>";

	print $objetoEntrada->_mostrarComentarioCompleto();
}

//comprueba si existe el usuario recibido por parámetro en la base de datos
function existeUsuario($usuario, $pass) {
	$conexion = conectarDB();

	$sql = "SELECT pass FROM usuario WHERE usuario =:usuario  ";

	$consultaUsuario = $conexion->prepare($sql);
	$consultaUsuario->bindValue(':usuario',$usuario);
	$consultaUsuario->execute();

	$res = $consultaUsuario->fetch(PDO::FETCH_ASSOC);

	$sql = null;
	$conextion = null;

	$salt = substr($res['pass'], 0, 29);
	$verified = crypt($pass, $salt); 

	if($verified ==  ($res['pass']))
		return $usuario;
	else
		return false;

}

function favoritoLeer($idEntrada, $idUsuario){

	$idUsuario = consultaIdUsuarioDBporNick($_SESSION['usuario']);
	//print $_SERVER['HTTP_REFERER']."<br>";
	$conexion = conectarDB();
	$sql = "SELECT id FROM favoritos WHERE idEntrada=? and idUsuario=?";
	$consulta  = $conexion->prepare($sql);
	$consulta->bindParam(1, $idEntrada);
	$consulta->bindParam(2, $idUsuario);
	$consulta->execute();

	$res =$consulta->rowCount();

	$sql = null;
	$conextion = null;

	if($res>0){
		return true;	
	}else{
		return false;	
	}

}

function favoritoGuardar($idEntrada, $idUsuario){

	$conexion = conectarDB();
	$sql = "INSERT INTO favoritos (idEntrada, idUsuario) VALUES (?,?)";
	$consulta  = $conexion->prepare($sql);
	$consulta->bindParam(1, $idEntrada);
	$consulta->bindParam(2,  consultaIdUsuarioDBporNick($_SESSION['usuario']));
	$consulta->execute();

	$res =$consulta->rowCount();

	$sql = null;
	$conextion = null;
	header('Location:'.$_SERVER['HTTP_REFERER']);
	/*	if($res>0)

		else
			return false;	*/
}
function favoritoListar(){

	$idUsuario = consultaIdUsuarioDBporNick($_SESSION['usuario']);
	$conexion = conectarDB();
	$sql = "SELECT idEntrada FROM favoritos WHERE idUsuario=?";
	$consulta  = $conexion->prepare($sql);
	$consulta->bindParam(1, $idUsuario);
	$consulta->execute();

	//$r = consulta->rowCount();
	$idArr = array();
	while($res = $consulta->fetch()){
		$idArr[] = $res['idEntrada'];
	}
	$sql = null;
	$conextion = null;
	$texto = "";
	//var_dump($idArr);
	foreach($idArr as $id){
		$entrada = readUnaEntradaDB($id);
		$texto .= $entrada->imprimirTituloEntrada();
	}
	if(sizeof($idArr) > 0)
		return $texto;
	else
		return "<article><p>No hay Favoritos guardados</p></article>"."<script> window.setTimeout(function(){window.location.href = 'login.php'; }, 700)</script>"; ;
}
function favoritoQuitar($idEntrada, $idUsuario){

	$conexion = conectarDB();
	$sql = "DELETE FROM favoritos WHERE idEntrada=? and idUsuario=?";
	$consulta  = $conexion->prepare($sql);
	$consulta->bindParam(1, $idEntrada);
	$consulta->bindParam(2, consultaIdUsuarioDBporNick($_SESSION['usuario']));
	$consulta->execute();

	$res =$consulta->rowCount();

	$sql = null;
	$conextion = null;

	header('Location:'.$_SERVER['HTTP_REFERER']);
	/*if($res>0)
		header('Location:'.$_SERVER['HTTP_REFERER']);
	else
		return false;	*/
}

function imprimirEntrada($idEntrada){

	$textoString ="";
	$objetoEntrada = readUnaEntradaDB($idEntrada);

	if(isset($_SESSION['usuario'])) $textoString .= "<aside>";
	$textoString .= $objetoEntrada->_mostrarEditarBorrarEntrada();
	if(isset($_SESSION['usuario'])) $textoString .= "</aside>";
	$textoString .= "<h3>$objetoEntrada->titulo</h3>";

	//Texto del articulo
	$textoObjetoEnt =  nl2br($objetoEntrada->texto);
	$textoString .= $textoObjetoEnt." <br>";

	//mostrar usuario, fecha hora, contador comentarios
	$textoString .= '<br><div><div> '.$objetoEntrada->_mostrarUsuarioFechaHora().'</div></div><br>';
	$textoString .= $objetoEntrada->_mostrarComentarioCompleto();
	$textoString .= '<a href="principal.php?idEntrada='.$idEntrada.'&amp;accion=comentar">Agregar Comentario</a>';

	return $textoString; 
}

//Conversión de meses en número a palabra
function month($month){
	switch($month){
		case 1:  return "Enero";break;
		case 2:  return "Febrero";break;
		case 3:  return "Marzo";break;
		case 4:  return "Abril";break;
		case 5:  return "Mayo";break;
		case 6:  return "Junio";break;
		case 7:  return "Julio";break;
		case 8:  return "Agosto";break;
		case 9:  return "Septiembre";break;
		case 10:  return "Octubre";break;
		case 11:  return "Noviembre";break;
		case 12:  return "Diciembre";break;
	}
}

function mostrarArchivo( $year, $month){

	$textoString ="";
	$textoString .= "<h2>ARCHIVO</h2><ul><li><h3>$year</h3><ul><li><h3>".month($month)."</h3>";

	$idArr = _mostrarArchivoDBgetIdEntradas($year, $month);

	foreach($idArr as $idEntrada){

		$objetoEntrada = readUnaEntradaDB($idEntrada);

		//título del artículo
		$textoString .= "<ul><li><h4><a href=\"./principal.php?idEntrada=$objetoEntrada->idEntrada&amp;accion=mostrar\">$objetoEntrada->titulo</a></h4>";

		//mostrar usuario, fecha hora, contador comentarios
		$textoString .= '<div><div> '.$objetoEntrada->_mostrarUsuarioFechaHora().'</div><div> <a href="./principal.php?idEntrada='.$objetoEntrada->idEntrada.'&amp;accion=mostrarComentario">Comentarios: '.$objetoEntrada->_contadorComentarios().'</a></div></div><br>';

		$textoString .= $objetoEntrada->_mostrarEditarBorrarEntrada();

		$textoString .= " </li></ul> ";

	}
	$textoString .= "</li></ul></li></ul>";
	return $textoString;
}

//extrae de la base de datos las idEntradas que coinciden con el año y mes recibido por parámetro
function _mostrarArchivoDBgetIdEntradas($year, $month){

	$conexion = conectarDB();

	//consultar comentarios de la entrada
	$sql = "SELECT id  from entrada WHERE SUBSTR(fechaHora, 1, 4) = ? and SUBSTR(fechaHora,6,2) =?";
	$idEntrada = $conexion->prepare($sql);
	$idEntrada->bindParam(1, $year);
	$idEntrada->bindParam(2, $month);
	$idEntrada->execute();

	$idArr = array();

	while ($resultado = $idEntrada->fetch()){
		$idArr[]= $resultado['id'];
	}

	$sql = null;
	$conextion = null;
	return $idArr;
}

function mostrarBusqueda($idEntradaArr){

	if($idEntradaArr !== false){
		$textoString = "";
		foreach($idEntradaArr as $idEntrada){

			$objetoEntrada = readUnaEntradaDB($idEntrada);
			if($objetoEntrada->idEntrada === $idEntrada){

				//título del artículo
				$textoString .= "<ul><li><h4><a href=\"./principal.php?idEntrada=$idEntrada&amp;accion=mostrar\">$objetoEntrada->titulo</a></h4>";


				//mostrar usuario, fecha hora, contador comentarios
				$textoString .= '
				<div>

				<div> '.$objetoEntrada->_mostrarUsuarioFechaHora().'</div>
				<div><a href="./principal.php?idEntrada='.$idEntrada.'&amp;accion=mostrarComentario">Comentarios: '.$objetoEntrada->_contadorComentarios().'</a>
				</div>

				</div>
				<br>';

				$textoString .= $objetoEntrada->_mostrarEditarBorrarEntrada();

				$textoString .= " </li></ul>";

			}
		}
		return $textoString;
	}else{
		return "No hay conincidencias con la búsqueda. 
					<script> window.setTimeout(function(){window.location.href = 'index.php'; }, 700)</script>";

	}
}

function perfilUsuario(){

	$idUsuario = consultaIdUsuarioDBporNick($_SESSION['usuario']);
	$conexion = conectarDB();
	$sql = ('SELECT * FROM usuario WHERE id=?');
	$consulta = $conexion->prepare($sql);
	$consulta->bindParam(1,$idUsuario);
	$consulta->execute();

	$r = $consulta->fetch();

	return "
		<article>

			<aside >
				<img src=\"".$r['ubicacionFoto']."\"  >
			</aside>
			<h3>PERFIL USUARIO</h3>
			<p>
				<div>Nombre: ".$r['nombre']."</div><br>
				<div>Usuario: ".$r['usuario']."</div>
			</p>
			<form action=\"subida.php\" method=\"post\" enctype=\"multipart/form-data\">
			<p>Subir Foto:</p>
			<input type=\"file\" name=\"imagen\" id=\"imagen\"><br>
			<input type=\"submit\" name=\"botonEnviar\" value=\"Enviar\">
		</form>

		</article>";


	$conexion = null;

}
//Devuele el contenido de una entrada usando un id de entrada
function readUnaEntradaDB($idEntrada){
	//realizar conexión a DB
	$conexion = conectarDB();

	//consulta de una entrada en la base de datos
	$sql = "SELECT e.id, e.titulo, e.texto, e.fechaHora, u.nombre FROM entrada e, usuario u WHERE e.idUsuario=u.id and e.id=?";
	$consultaEntrada = $conexion->prepare($sql);
	$consultaEntrada->bindParam(1, $idEntrada);
	$consultaEntrada->execute();

	while ($registro = $consultaEntrada->fetch()) {

		$idEntrada = $registro['id'];
		$fh = $registro['fechaHora'];
		$titulo = $registro['titulo'];
		$usuario = $registro['nombre'];	
		$texto = $registro['texto'];

		//crea objeto del tipo entrada 
		$entrada = new Entrada ($idEntrada, $fh, $titulo, $usuario, $texto, consultaComentariosDB($idEntrada));

	}
	$conexion = null;
	return $entrada;
}

//Devuele el contenido de una entrada usando un id de usuario
function readEntradasPorUsuarioDB($idUsuario){
	//realizar conexión a DB
	$conexion = conectarDB();

	$entradaArr = new BD();

	//consulta de una entrada en la base de datos
	$sql = "SELECT e.id, e.titulo, e.texto, e.fechaHora, u.nombre FROM entrada e, usuario u WHERE e.idUsuario=u.id and u.usuario=:idU";
	$consultaEntrada = $conexion->prepare($sql);
	$consultaEntrada->bindValue(":idU", $idUsuario);
	$consultaEntrada->execute();

	while ($registro = $consultaEntrada->fetch()) {//var_dump($registro );exit();
		$idEntrada = $registro['id'];
		$fh = $registro['fechaHora'];
		$titulo = $registro['titulo'];
		$usuario = $registro['nombre'];	
		$texto = $registro['texto'];

		//crea objeto del tipo entrada 
		$entrada = new Entrada ($idEntrada, $fh, $titulo, $usuario, $texto, consultaComentariosDB($idEntrada));

		$entradaArr->addEntrada($entrada);

	}
	$conexion = null;


	return $entradaArr;
}


function quitarAcentos($texto){
	$from = array(
		'á','À','Á','Â','Ã','Ä','Å',
		'ß','Ç',
		'È','É','Ê','Ë','é',
		'Ì','Í','Î','Ï','Ñ','í',
		'Ò','Ó','Ô','Õ','Ö','ó',
		'Ù','Ú','Û','Ü','ú');

	$to = array(
		'a','A','A','A','A','A','A',
		'B','C',
		'E','E','E','E','é',
		'I','I','I','I','N','i',
		'O','O','O','O','O','o',
		'U','U','U','U','u');

	$textoNuevo = str_replace($from, $to, $texto);
	return $textoNuevo;
}

function todosLasEntradas(){
	$textoString ="";

	//realizar conexión a DB
	$conexion = conectarDB();

	//consulta de una entrada en la base de datos
	$sql = "SELECT e.id, e.titulo, e.texto, e.fechaHora, u.nombre FROM entrada e, usuario u WHERE e.idUsuario=u.id ";
	$consultaEntrada = $conexion->prepare($sql);
	$consultaEntrada->execute();

	while ($registro = $consultaEntrada->fetch()) {

		$idEntrada = $registro['id'];
		$fh = $registro['fechaHora'];
		$titulo = $registro['titulo'];
		$usuario = $registro['nombre'];	
		$texto = $registro['texto'];

		//crea objeto del tipo entrada 
		$entrada = new Entrada ($idEntrada, $fh, $titulo, $usuario, $texto, consultaComentariosDB($idEntrada));

		$textoString .= $entrada->imprimirEntradaResumida();

	}
	$conexion = null;

	return $textoString;
}

function todosLosComentarios(){
	$textoString ="";

	//realizar conexión a DB
	$conexion = conectarDB();

	//consulta de una entrada en la base de datos
	$sql = "SELECT e.id, e.titulo, e.texto, e.fechaHora, u.nombre FROM entrada e, usuario u WHERE e.idUsuario=u.id ";
	$consultaEntrada = $conexion->prepare($sql);
	$consultaEntrada->execute();

	while ($registro = $consultaEntrada->fetch()) {

		$idEntrada = $registro['id'];
		$fh = $registro['fechaHora'];
		$titulo = $registro['titulo'];
		$usuario = $registro['nombre'];	
		$texto = $registro['texto'];

		//crea objeto del tipo entrada 
		$entrada = new Entrada ($idEntrada, $fh, $titulo, $usuario, $texto, consultaComentariosDB($idEntrada));
		$textoString .=  comentarios($entrada->idEntrada);
	}
	$conexion = null;

	return $textoString;	
}

function ultimoIdEntradasDB(){
	$conexion = conectarDB();

	$sql = "SELECT MAX(id) as lastId from entrada  ";
	$consultaUsuario = $conexion->prepare($sql);
	$consultaUsuario->execute();

	while($reg = $consultaUsuario->fetch()){
		$lastId = $reg['lastId'];
	}
	$sql = null;
	$conextion = null;
	return $lastId+1;
}

//quita los <br /> del texto almacenado al mostrarlos
function br2nl($texto){
	return preg_replace('(<br />)', " ", $texto);
}
?>