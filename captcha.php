<?php

session_start();

//Array de fuentes a usar
$fonts = array('timesi','times','arial','consola','verdana');

//ruta de la fuente en el sistema
$fontRoute = 'c:\windows\fonts\\';//ruta principal de las fuentes

//tamaño del lienzo
$img_width = 300;
$img_height = 120;

//creación del lienzo
$img = imagecreatetruecolor($img_width, $img_height);

//colores
$black = imagecolorallocate($img, 0, 0, 0);
$white = imagecolorallocate($img, 255, 255, 255);
$green = imagecolorallocate($img, 0, 255, 0);
$blue  = imagecolorallocate($img, 0, 200, 250);

//función para generar color al azar
function rColor(){
	$color = array(rand(0,255),rand(0,255),rand(0,255));
	$color = implode($color);
	return $color;
}

//color de fondo
imagefill($img, 0, 0, $white);

//valores aleatorios para ubicar las lineas aleatorias
function n1(){	return rand( 33,90);}
function n2(){	return rand( 10,270);}
function n3(){	return rand( 200,280);}
function n5(){	return rand( 250,280);}

/*---------------------*/

//imagen de fondo en pixeles cambiantes de colores para dificultar la lectura
for($i=0;$i<1000;$i++) {
	$k = rand(3,296);
	$j = rand(3,116);
	for($m=2; $m>-3;$m--){
		for($n=-2;$n<3;$n++){
			imagesetpixel($img,($k+$m),($j), rColor());
			imagesetpixel($img,($k),($j+$n), rColor());
		}
	}
}

$randomWord =array();

//Generador de la palabra aleatoria
$separador = 80;
for($i=0; $i<rand(5,8);$i++){
	$font = $fontRoute.$fonts[rand(0,sizeof($fonts)-1)].'.ttf';
	$randomWord[] = $letter = randomLetter();
	imagettftext($img, rand(30,32), rand(-45,45), $img_width/2-$separador, $img_height/2+10, $black, $font, $letter);
	$separador-=26;
}

//Líneas aleatorias
switch(rand(3,5)){
	case 5:
		imageline($img, n1(), n1(), n3(), n1(), rColor());
		imagesetthickness($img, rand(1,3));
	case 4:
		imagearc($img, n2(), n2(), n2(), n1(),  n1(), n5(),rColor());
		imagesetthickness($img, rand(1,3));

	case 3:
		imageline($img, n1(), n1(), n3(), n1(), rColor());
		imagesetthickness($img, rand(1,3));

		imageline($img, n1(), n1(), n3(), n1(), rColor());
		imagesetthickness($img, rand(1,3));

		imageline($img, n1(), n1(), n3(), n1(), rColor());
		imagesetthickness($img, rand(1,3));
}


//almacenamiento en variable de sesion de la palabra que se muestra
$_SESSION['randomWord'] = implode($randomWord);

//función para generar una letra aleatoria
function randomLetter(){		
	$texto ="ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789";
	return $texto[rand(0,strlen($texto)-1)];
}
header("Content-type: image/png");
imagepng($img);
imagedestroy($img);
?>
