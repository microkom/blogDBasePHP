<?php
/*
Clase Entrada - contendrá los siguientes campos:
● idEntrada: número identificativo.
● fechaHora: timestamp de la creación del comentario.
● titulo: título de la entrada.
● usuario: id del usuario que creó la entrada.
● comentarios: array con los comentarios de la entrada
*/

Class Entrada{
	private $idEntrada;
	private $fechaHora;
	private $titulo;
	private $usuario;
	private $texto;
	private $comentario = array();



	public function __construct($idEntrada, $fechaHora, $titulo, $usuario, $texto, $comentario=array()){
		$this->idEntrada = $idEntrada;
		$this->fechaHora = $fechaHora;
		$this->titulo = $titulo;
		$this->usuario = $usuario;
		$this->texto = $texto;
		$this->comentario = $comentario;

	}

	public function __set($atributo, $valor){
		$this->$atributo = $valor;
	}                    

	public function __get($atributo){
		return $this->$atributo;
	}

	public function imprimirEntradaResumida(){  //llamado con accion=mostrar

		$textoString = "<article><h3><a href=\"principal.php?idEntrada=$this->idEntrada&amp;accion=mostrar\">$this->titulo</a></h3>";

		//Fraccionamiento del articulo para mostrar las 30 primeras palabras
		$textoEntrada = explode(' ', $this->texto);
		$textoEntrada = array_slice($textoEntrada,0,30);
		$textoEntrada = implode(' ', $textoEntrada);

		//mostrar texto del artículo
		$textoString .= "<p>$textoEntrada";

		//mostrar texto del artículo
		$textoString .= "...<a href=\"./principal.php?idEntrada=$this->idEntrada&amp;accion=mostrar\">Leer mas.</a></p>";

		//mostrar usuario, fecha hora, contador comentarios
		$textoString .= '<div><div> '.$this->_mostrarUsuarioFechaHora().'</div><div> <a href="./principal.php?idEntrada='.$this->idEntrada.'&amp;accion=mostrarComentario">Comentarios: '.$this->_contadorComentarios().'</a></div></div><br>';

		$textoString .= $this->_mostrarEditarBorrarEntrada();
		$textoString .= "</article>";
		return $textoString;
	}

	public function _mostrarUsuarioFechaHora(){
		return $this->usuario.' ' . dateTime($this->fechaHora);
	}

	public function imprimirTituloEntrada(){

		$textoString = "<!-- imprimirTituloEntrada()-->";
		//título del artículo
		$textoString .= "<article>";
		$textoString .= "<h3><a href=\"./principal.php?idEntrada=$this->idEntrada&amp;accion=mostrar\">$this->titulo</a></h3>";

		//mostrar texto del artículo
		$textoString .=  "...<a href=\"./principal.php?idEntrada=$this->idEntrada&amp;accion=mostrar\">Leer mas.</a><br><br>";

		//mostrar usuario, fecha hora, contador comentarios
		$textoString .=  '<div><div> '.$this->_mostrarUsuarioFechaHora().'</div><div> <a href="./principal.php?idEntrada='.$this->idEntrada.'&amp;accion=mostrarComentario">Comentarios: '.$this->_contadorComentarios().'</a></div></div><br>';

		$textoString .=  $this->_mostrarEditarBorrarEntrada();

		$textoString .= "</article>";
		return $textoString;
	}

	public function _contadorComentarios(){
		$comments  =$this->comentario ;
		if($comments>0)
			return sizeof($comments);
		else
			return 0;
	}

	//Solo si el usuario esta logueado se mostraran estas opciones
	public function _mostrarEditarBorrarEntrada(){

		$texto = "";
		$idUsuario = consultaIdUsuarioDB($this->idEntrada);
		$nickUsuarioObjetoEntrada = consultaNickUsuarioDB($idUsuario);

		//muestra las imagenes de edicion, borrar y favoritos en las entradas
		if(isset($_SESSION['usuario'])){
			if(favoritoLeer($this->idEntrada, $idUsuario)){
				$heart = "images/redHeart.svg";
				$link = "principal.php?idEntrada=$this->idEntrada&amp;idUsuario=$idUsuario&amp;accion=favoritoQuitar";
			}else{
				$heart = "images/grayHeart.svg";
				$link = "principal.php?idEntrada=$this->idEntrada&amp;idUsuario=$idUsuario&amp;accion=favoritoGuardar";
			}

			if(strtolower($_SESSION['usuario']) === strtolower($nickUsuarioObjetoEntrada)){
				$texto .= '
        <a href="principal.php?idEntrada='.$this->idEntrada.'&amp;accion=editarEntrada">
        <img src="images/edit_small.png" alt="Editar Entrada" title="Editar Entrada"></a>
        <a href="principal.php?idEntrada='.$this->idEntrada.'&amp;accion=borrarEntradaWarning">
        <img src="images/bin_small.png" alt="Borrar Entrada" title="Borrar Entrada"></a>
		';}
			$texto .= '<p><a href="'.$link.'"><img src="'.$heart.'" height="30" alt="Favorito" title="Favorito"></a></p>
		';
		}
		return $texto;
	}

	public function _mostrarComentarioCompleto(){

		$textoString ="";

		if(count($this->comentario)>0)
			$textoString .= "<h3>COMENTARIOS</h3>";

		//muestra cada comentario
		foreach($this->comentario as $commentario){

			//autor del comentario + texto del comentario
			$textoString .=  "<ul><li>".$commentario->autor.":<br>".$commentario->texto."<br>";   

			//fechahora del comentario
			$textoString .=  "<h5>".$commentario->fechaHora."</h5> ";

			$nickUsuarioObjetoEntrada = consultaNickUsuarioDB(consultaIdUsuarioDB($this->idEntrada));

			if(isset($_SESSION['usuario']) &&  strtolower($_SESSION['usuario']) === strtolower($nickUsuarioObjetoEntrada)){
				$textoString .= 
					'<a href="principal.php?idEntrada='.$this->idEntrada. '&amp;idComentario=' .$commentario->idComentario. '&amp;accion=editarComentario">
					<img src="images/edit_small.png" alt="Editar Comentario" title="Editar Comentario"></a>
					<a href="principal.php?idEntrada='.$this->idEntrada.'&amp;idComentario='.$commentario->idComentario.'&amp;accion=borrarComentarioWarning">
					<img src="images/bin_small.png" alt="Borrar Comentario" title="Borrar Comentario"></a></li></ul>';

			}else{
				$textoString .='</li></ul>';
			}
		}
		$textoString .=  "<br>";
		return $textoString;
	}

	public function setIdEntrada($id){
		$this->idEntrada = $id;
	}
	public function setFechaHora($fechaHora){
		$this->fechaHora= $fechaHora;
	}
	public function setTitulo($titulo){
		$this->titulo = $titulo;
	}
	public function setUsuario($usuario){
		$this->usuario = $usuario;
	}
	public function setComentario($comentario){
		$this->comentario[] = $comentario;
	}

	public function getIdEntrada(){
		return $this->idEntrada;
	}
	public function getFechaHora(){
		return $this->fechaHora;
	}
	public function getTitulo(){
		return $this->titulo;
	}
	public function getUsuario(){
		return $this->usuario;
	}
	public function getComentario(){
		return $this->comentario;
	}
	public function getUnComentario($idComentario){
		foreach($this->comentario as $comentario){
			if(strcasecmp($comentario->idComentario, $idComentario)==0)
				return $comentario;
		}

	}


	public function __toString(){
		return "IdEntrada: ".$this->idEntrada." <br>
		FechaHora: ".$this->fechaHora." <br>
		Título: ".$this->titulo." <br>
		Usuario: ".$this->usuario." <br>
		Comentario: ";//.retCom()."<br>";
	}
	function retCom(){
		$valor="";
		foreach($this->comentario as $valor){
			$valor .= $valor." ";
		}        
		return $valor;    
	}

}
?>