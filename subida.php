<?php

require ('funciones.php');
$nombre = $_SESSION['usuario'];

if (isset($_POST['botonEnviar'])) {
	if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK){
		list($width, $height, $type) = getimagesize($_FILES['imagen']['name']);

		if($width>1080 || $height>1980){
			print "La imagen es mayor de 1980x1080";
			exit();
		}		

		function fn_resize($image_resource_id,$width,$height,$target_width, $target_height) {   
			$target_layer=imagecreatetruecolor($target_width,$target_height);
			imagecopyresampled($target_layer,$image_resource_id,0,0,0,0,$target_width,$target_height, $width,$height);
			return $target_layer;
		}
		//extraer extension del fichero subido
		$rawName = explode(".",$_FILES['imagen']['name']);
		$fileExtension = $rawName[1];
		$nombre = 'img/'.$nombre;

		$allowedfileExtensions = array('jpg', 'gif', 'png');

		//filtro de extensiones permitidas
		if (in_array($fileExtension, $allowedfileExtensions)){

			if ($_FILES['imagen']['error'] != UPLOAD_ERR_OK) { // Se comprueba si hay un error al subir el archivo
				echo 'Error: ';
				switch ($_FILES['imagen']['error']) {
					case UPLOAD_ERR_INI_SIZE:
					case UPLOAD_ERR_FORM_SIZE: echo 'El fichero es demasiado grande'; break;
					case UPLOAD_ERR_PARTIAL: echo 'El fichero no se ha podido subir entero'; break;
					case UPLOAD_ERR_NO_FILE: echo 'No se ha podido subir el fichero'; break;
					default: echo 'Error indeterminado.';
				}
				exit();
			}

			// Si se ha podido subir el fichero se guarda
			if (is_uploaded_file($_FILES['imagen']['tmp_name']) === true) {




				$ubicacionFoto = $nombre.".".$fileExtension;

				$conexion = conectarDB();
				$sql = "UPDATE usuario SET ubicacionFoto=? WHERE usuario=?";
				$consulta  = $conexion->prepare($sql);
				$consulta->bindParam(1, $ubicacionFoto);
				$consulta->bindParam(2, $_SESSION['usuario']);
				$consulta->execute();

				$res =$consulta->rowCount();

				if($res > 0){
				}else{
					print "Error al guardar el nombre de la foto en la base de datos.";
					exit();
				}

				$file = $_FILES['imagen']['tmp_name']; 
				$source_properties = getimagesize($file);
				$image_type = $source_properties[2]; 

				//Check if file is jpg
				if( $image_type == IMAGETYPE_JPEG ) {   
					$image_resource_id = imagecreatefromjpeg($file);  
					$target_layer = fn_resize($image_resource_id,$source_properties[0],$source_properties[1],72, 96);
					imagejpeg($target_layer,$nombre . ".jpg");
				}
				//check if file is png
				elseif( $image_type == IMAGETYPE_PNG ) {
					$image_resource_id = imagecreatefrompng($file); 
					$target_layer = fn_resize($image_resource_id,$source_properties[0],$source_properties[1],72,96);
					imagepng($target_layer,$nombre . ".png");
				}

				header('location: login.php?accion=perfilUsuario');
			}
			else
				echo 'Error: posible ataque. Nombre: '.$_FILES['imagen']['name'];
		}
	}
}
?>
