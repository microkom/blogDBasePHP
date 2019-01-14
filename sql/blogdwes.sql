/*
AUTHOR: German Navarro Díaz.
*/
create database IF NOT EXISTS `blogdwes` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

use `blogdwes`;
create table IF NOT EXISTS usuario(
	`id` int AUTO_INCREMENT,
	`nombre` VARCHAR(100) not null,
	`usuario` VARCHAR(40) not null,
	`ubicacionFoto` VARCHAR(240) null,
	`pass` VARCHAR(256),
	PRIMARY KEY (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table IF NOT EXISTS entrada(
	`id` int AUTO_INCREMENT,
	`titulo` VARCHAR(250) not null,
	`texto` TEXT(65000) not null,
	`fechaHora` TIMESTAMP,
	`idUsuario` INT,
	PRIMARY KEY (id),
	FOREIGN KEY FK_idUsuario_idEntrada (idUsuario) REFERENCES usuario(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


create table IF NOT EXISTS comentario(
	`id` int AUTO_INCREMENT,
	`texto` VARCHAR(255) not null,
	`idUsuario` INT,
	`idEntrada` INT,
	`fechaHora` TIMESTAMP,
	PRIMARY KEY (`id`),
	FOREIGN KEY FK_idComent_idUsuario (`idUsuario`) REFERENCES usuario(`id`),
	FOREIGN KEY FK_idComent_idEntrada (idEntrada) REFERENCES entrada(id) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table IF NOT EXISTS favoritos(
	`id` int AUTO_INCREMENT,
	`idUsuario` INT,
	`idEntrada` INT,
	PRIMARY KEY (id),
	FOREIGN KEY FK_idFav_idUsuario (`idUsuario`) REFERENCES usuario(`id`) ON DELETE CASCADE,
	FOREIGN KEY FK_idFav_idEntrada (idEntrada) REFERENCES entrada(id) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `usuario` (`id`, `nombre`, `usuario`, `pass`) VALUES
(1, 'Pablo Ortega', 'paullenk', '$2y$10$bBHcalekCHoJ5x.lGEyVKeF9BSaOMH1zG0r/Dw8GX4u8PkYNVjrIi'),
(2, 'Matías S. Zavia', 'zavia', '$2y$10$bBHcalekCHoJ5x.lGEyVKeF9BSaOMH1zG0r/Dw8GX4u8PkYNVjrIi'),
(3, 'Carlos Zahumenszky', 'carlos', '$2y$10$bBHcalekCHoJ5x.lGEyVKeF9BSaOMH1zG0r/Dw8GX4u8PkYNVjrIi'),
(4, 'Jody Serrano', 'jodyserrano', '$2y$10$bBHcalekCHoJ5x.lGEyVKeF9BSaOMH1zG0r/Dw8GX4u8PkYNVjrIi'),
(5, 'Eduardo Marín', 'eduardomarin', '$2y$10$bBHcalekCHoJ5x.lGEyVKeF9BSaOMH1zG0r/Dw8GX4u8PkYNVjrIi'),
(6, 'xXx', 'xxmatadorxx', '$2y$10$bBHcalekCHoJ5x.lGEyVKeF9BSaOMH1zG0r/Dw8GX4u8PkYNVjrIi'),
(7, 'Troll Acido', 'troll-acido', '$2y$10$bBHcalekCHoJ5x.lGEyVKeF9BSaOMH1zG0r/Dw8GX4u8PkYNVjrIi');

INSERT INTO `entrada` (`id`, `titulo`, `texto`, `fechaHora`, `idUsuario`) VALUES
(1, 'La avispa verdugo es el insecto con la picadura más dolorosa del mundo, y este hombre ha decidido comprobarlo', 'Coyote Peterson ha vuelto. El presentador de Brave Wilderness es famoso por haberse dejado atacar por los insectos con la picadura más dolorosa del mundo. Hasta ahora Peterson creía que el aguijonazo más espantoso era el de la hormiga bala. Entonces le hablaron de la avispa verdugo.

 La avispa verdugo o avispa ahogadora (Polistes carnifex) es el miembro más grande de la familia Vespidae, con una longitud que ronda los 2,7cm. Pese a su tamaño, es una especie particularmente pacífica que solo ataca cuando se la provoca o si su colonia se ve seriamente amenazada. La picadura, sin embargo, se ha ganado una infame reputación en las regiones de Centroamérica y Latinoamérica donde vive por el espantoso dolor que causa. Algunos consideran la picadura de la avispa verdugo como la más dolorosa del planeta, y Coyote Peterson parece estar de acuerdo.

 El popular youtuber se deja picar por una Polistes carnifex en este vídeo y, desde luego, no parece agradable. Recordemos que el presentador ya ha experimentado en sus propias carnes la picadura de la hormiga bala, y la avispa tarántula. Ambas están en lo más alto de la escala del dolor creada por el entomólogo Justin O. Schmidt.

 ¿Es la picadura de la avispa verdugo la más dolorosa del mundo? Es difícil asegurarlo porque no hay una forma científica de medirlo. El dolor es una sensación que varía de individuo a individuo, y lo que sabemos sobre la picadura de estos insectos proviene de la experiencia directa y los relatos de personas que han sufrido sus ataques.

 La propia escala de Schmidt está elaborada en base a las descripciones que el naturalista hizo tras dejarse picar por diferentes insectos. Su intención original era determinar las propiedades hemolíticas del veneno de diferentes insectos, pero con el tiempo su escala del dolor se ha convertido en su trabajo más famoso. Más adelante, el entomólogo Christopher Starr amplió la lista por lo que a veces se la conoce como escala del dolor Starr.

 Tanto la avispa verdugo como la hormiga bala, la avispa tarántula, o la avispa guerrero (Synoeca septentrionalis) están en lo más alto de esta lista subjetiva. Schmidt describe la picadura de esta última como: “literalmente una tortura. Es como estar encadenado a un volcán en erupción... ¿Por qué demonios empecé esta lista?”. Para Coyote, sin embargo, no es peor que la de la hormiga bala. en su opinión, la peor de todas ellas es la de la avispa verdugo. Al menos lo será hasta que encuentre un insecto peor.', '2014-12-26 05:34:00', 3),
(2, 'Oppo patenta una cámara-periscopio con zoom de 10 aumentos para su próximo smartphone', 'Si estás planeando comprar un nuevo smartphone el año que viene, atento al Oppo F19. Una serie de imágenes filtradas por Slashleaks sugiere que el fabricante chino trabaja en un teléfono sin bordes, con tres cámaras traseras y un sistema de lentes con zoom de 10 aumentos que no tiene precedentes.

 Según una patente y un boceto de los que se ha hecho eco Pandadaily, la triple cámara del Oppo F19 tendrá dos grupos de lentes alineados en vertical (presumiblemente el de la cámara principal y el de la cámara gran angular) y un tercer grupo de lentes alineado en horizontal, con el que lograría un zoom sin pérdidas de 10 aumentos.

 Este sistema de cámara-periscopio es el mismo que Oppo presentó en 2017, pero con el doble de aumentos. Se trata de un zoom híbrido basado en un prisma óptico especial que refracta la imagen a través de un grupo de lentes hasta un sensor de alta resolución. Aunque el sensor está en perpendicular al teléfono, el grosor no es mayor que el de otros terminales.

 El Oppo F19 podría presentarse durante el Mobile World Congress de 2019, pero por ahora se desconocen sus especificaciones técnicas y su precio.', '2014-12-26 14:04:00', 2),
(3, 'El proyecto de crowdfunding más exitoso de la historia es un videojuego que ha recaudado más de 200 millones de dólares', 'Si el nombre Star Citizen no te suena de nada, alguna de las increíbles capturas, filtraciones y tráilers del juego que hemos ido publicando en los últimos años lo hará. Star Citizen es el simulador de vuelos y combates espaciales más ambicioso que existe, pero también el proyecto de crowdfunding que más éxito ha tenido: recaudó 200 millones de dólares de los fans y otros 46 millones de un inversor.

 Solo hay un pequeño problema: se esperaba para 2014, luego para 2016, y ahora sabemos que no llegará a las estanterías al menos hasta 2020.

 El juego de Cloud Imperium ha estado en desarrollo desde 2011 bajo la dirección de Chris Roberts, el también creador de Wing Commander. Todo empezó con una campaña inicial de Kickstarter que recaudó más de 2 millones de dólares de un objetivo de $500.000. El abrumador apoyo hizo que la compañía se replanteara el alcance del proyecto y decidiera crear un juego mucho más ambicioso.

 A día de hoy, Star Citizen ha recaudado $212.623.319 de los fans y va camino de convertirse en uno de los videojuegos con mayor presupuesto de la historia, por encima de títulos como Grand Theft Auto V y Call of Duty: Modern Warfare 2.

 El juego crea un extenso universo que el jugador puede explorar a su ritmo en primera persona. Son las decisiones del jugador las que convertirán Star Citizen en un simulador de vuelos, un first person shooter con combates interplanetarios, un juego de rol con misiones y progreso o una dimensión ficticia en la que puedes llevar una vida tranquila como transportista. A medida que crece su presupuesto, Cloud Imperium ha ido incluyendo nuevas capas de profundidad al juego, que destaca por sus mundos detallados y sus naves diseñadas a mano.

 A pesar de los constantes retrasos, Cloud Imperium ha sido particularmente transparente con la gestión del presupuesto de Star Citizen. Los fans que invirtieron dinero en el proyecto están al tanto de los avances y han podido participar en las versiones alfa del juego, que sorprenden por su nivel de detalle, su apertura sin precedentes y otros elementos como su banda sonora.

 La semana pasada, el magnate sudafricano Clive Calder compró un 10% de Cloud Imperium por 46 millones de dólares. Además de Star Citizen, la compañía trabaja en un juego en modo historia de un solo jugador basado en su universo multijugador. Se llama Squadron 42 y cuenta con las interpretaciones de estrellas de Hollywood como Mark Hamill, Gary Oldman, Gillian Anderson y Andy Serkis. La versión alfa no se espera al menos hasta la primera mitad de 2020.

 La versión alfa de Star Citizen, en cambio, se puede comprar para Windows por $45 con las naves Mustang Alpha o Aurora MR y un hangar de tu propiedad.', '2017-01-25 13:22:00', 1),
(4, 'Ni siquiera Macaulay Culkin sabía que la película en blanco y negro de Solo en casa era falsa', 'Una de las sagas clásicas para navidad es Home Alone, conocida en España como Solo en casa y en Latinoamérica como Mi pobre angelito. Uno de los momentos más míticos de la saga es cuando Kevin usa escenas de una película clásica de gángsters para defenderse. Esa película en realidad es falsa, y la estrella de Home Alone no lo sabía.

 El momento de los gángsters está presente en ambas películas. Kevin necesitó hacerse pasar por un adulto en algunos momentos claves de cada película, como engañar a los empleados de un hotel o pedir una pizza, y para ello decidió los diálogos de una película de gángsters en la que su protagonista terminaba disparando con un arma a su alrededor, lo que los personajes de Home Alone creían que era real. Al final, terminaba la oleada de balas con una frase que se hizo mítica: “Quédate con el cambio, gusano miserable”.

 Macaulay Culkin, el actor que dio vida a Kevin en la saga, creía que la película “retro” era real, y no está solo. Tanto los actores Seth Rogen como Chris Evans (el Capitán América) y otros también estaban seguros de que se trataba de un clásico de gángsters de hace muchas décadas.

 La escena forma parte de una película que titularon “Ángeles con almas sucias”, que se basa en una película de gángsters real de 1938 llamada “Ángeles con caras sucias”. Para Home Alone crearon sus propias escenas y las grabaron en apenas un día. Y si no recuerdas este clásico momento navideño, lo podrás ver a continuación, tanto en su versión para España como para Latinoamérica. ', '2018-07-04 06:22:00', 5),
 (5,'Piratear consolas en Japón ahora tiene penas de hasta cinco años de cárcel y 46.000 dólares','Japón ha aprobado recientemente una serie de cambios a su ley de prevención contra la competencia desleal. El más llamativo es que ahora incluye la modificación de consolas. Las penas por modificar los archivos de un juego ahora pueden ser de hasta 5 años de cárcel y 5 millones de yenes.

Según la página oficial de la Asociación de Copyright para Software y Computadoras, hacer modding en consolas o alterar los datos guardados de un juego suponen una violación de la Ley de Protección Contra la Competencia Desleal. Las penas varían según la magnitud de la modificación pero pueden alcanzar multas de hasta cinco millones de yenes (unos 46.000 dólares) y cinco años de prisión.

La noticia ha saltado en varias páginas japonesas de juegos como Hachima Kikou después de que un fabricante de periféricos llamado Cyber Gadget interrumpiera súbitamente las ventas de su herramienta para editar datos guardados que permitía hacer trampas o parchear los juegos.

La Asociación de Copyright para Software y Computadoras también menciona que el uso de códigos y claves no oficiales también es ilegal. LA página cita expresamente las subastas online de estos códigos o su puesta a disposición para descarga. Finalmente, la ley incluye los mods a consolas, sean con los fines que sean.','2019-01-07 09:37', 2),(6,'Por qué algunas personas se desmayan al ver sangre','Aunque no te guste, la sangre es una sustancia cotidiana hoy en día. Se tiene que extraer sangre para realizar pruebas médicas necesarias. También quizá tengas que limpiar sangre si tú o uno de tus familiares se corta o se cae. No obstante, hay algunas personas que no aguantan la presencia de sangre, y se terminan desmayando. 

¿Por qué se desmayan las personas en la presencia de sangre? La respuesta simple es que consideran la presencia de la sangre como una amenaza. De acuerdo con el psicólogo Christopher France de la Universidad de Ohio, este fenómeno se conoce como la fobia a la sangre y las inyecciones. Las personas que padecen esta fobia suele evitar la asistencia médica por la ansiedad que se produce cuando están en hospitales o consultas.

En una entrevista con Popular Science, France explica que al ver a la sangre caer goteando, las individuos experimentan una disminución en su frecuencia cardiaca y presión arterial. France afirma que esto reduce el flujo de sangre oxigenada al cerebro, lo cual provoca que la persona se desmaye. El desmayo asegura que más sangre llegue al cerebro.

Sin embargo, no está claro el porqué algunas personas se desmayan y otras no. France comenta que las personas que temen a la sangre y la agujas son más propensos a desmayarse. Existen otras teorías, pero ninguna se ha comprobado.

Una afirma que quizá la bajada en la tensión arterial minimiza la pérdida de sangre cuando una persona está herida. Otra teoría se basa en que nuestros cuerpos fueron diseñados para engañar a los predadores para que piensen que estamos muertos.

Considerando que la medicina moderna se basa cada vez más en las inyecciones, la fobia se ha convertido en un problema importante para los investigadores y los profesionales médicos. Se estima que entre un 3 y un 4% de la población general padece de esta fobia.','2019-01-05 13:41',4),(7,'Qué es la cara oculta de la Luna y por qué hasta ahora nadie había aterrizado en ella','No es la primera vez que China visita la Luna, pero la sonda china Chang’e-4 ha batido un récord muy particular. Ha sido la primera en posarse sobre su cara oculta, algo que nadie, ni siquiera las misiones Apolo, había logrado antes. ¿Qué es la cara oculta y por qué es tan complicado llegar a ella?

Desde la Tierra vemos alrededor del 59% de la superficie total de la Luna, pero se da la circunstancia de que siempre vemos el mismo 59%. La razón es que la Luna tarda exactamente lo mismo en dar una vuelta completa sobre sí misma que en recorrer una vuelta completa alrededor de la Tierra. Ambos movimientos están tan bien sincronizados que la cara oculta de la Luna fue una perfecta incógnita para el ser humano hasta que una sonda rusa, la Luna-3, la fotografió por primera vez en 1968. De hecho, la mayor parte de los nombres de esa cara son rusos precisamente porque Rusia fue la primera en observarla.

Desde entonces hemos podido fotografiar esa cara varias veces. La sonda Lunar Reconnaissance Orbiter de la NASA se ha encargado de cartografiarla en detalle, pero todos los exámenes se han hecho siempre desde la distancia. Por eso es tan importante que la sonda Chang’e-4 se haya posado allí. Es la primera vez que tenemos la oportunidad de observarla de cerca.

A menudo se habla de la cara oculta de la Luna como el lado oscuro, pero la expresión no es correcta porque esa mitad del satélite también recibe radiación solar. Se trata de una región muy accidentada que cuenta con menos grandes planicies (los llamados mares de la Luna) y muchos más impactos de meteorito.

¿Por qué nunca habíamos aterrizado antes?
Lo accidentado de su orografía es precisamente la primera razón por la que ni las misiones apolo ni ninguna de las sondas no tripuladas que llegaron después han elegido la cara oculta para posarse. Era mucho más seguro elegir alguna de las planicies y cráteres del lado visible.

El principal problema, no obstante, para un aterrizaje en la cara oculta de la Luna han sido y son las comunicaciones. La propia Luna bloquea las señales de radio que llegan desde la Tierra, por lo que el aterrizaje, que es una fase crítica, debía hacerse a ciegas y por sus propios medios. Aterrizar en la cara oculta de la Luna es casi tan difícil como hacerlo en Marte, donde nuestras señales tardan demasiado en llegar como para resultar efectivas.

Para solucionar este problema, la Agencia Espacial China puso un satélite en órbita alrededor de la Luna en mayo de 2018. Se trata del Queqiao, un satélite de comunicaciones de 425 kilos que orbita cerca del punto de Lagrange L2 del sistema Tierra-Luna. Desde esa ubicación, Queqiao sirve de repetidor entre el Centro de Control en Tierra y la sonda Chang’e y su rover. Es una solución sencilla, pero que nadie ha utilizado hasta ahora sencillamente porque era mucho más sencillo elegir la otra cara.','2019-01-4 8:26',3);


INSERT INTO `comentario` (`id`, `texto`, `idUsuario`, `idEntrada`, `fechaHora`) VALUES
(1, 'Es guapísima. También es el insecto que pone el huevo más grande. Si quiere que lo compruebe solo tiene que cambiar el dedo por otra parte del cuerpo.', 6, 1, '2018-12-26 08:42:00'),
(2, 'Es bastante bonita a pesar de su picoton... me recuerda a varias conocidas.', 7, 1, '2018-12-26 08:55:00'),
(3, 'Esto es lo maravilloso de estar en el mundo Android', 3, 2, '2018-12-26 15:50:00'),
(4, 'tener un montón de teléfonos que no se venden fuera de China? Oppo ya reanudó operaciones en España?, porque al continente americano de plano se fueron y no se les volvió a ver... ', 4, 2, '2018-12-26 17:30:00'),
(5, 'Yo le pillé manía a ese juego, muchos pesados de la PC Master Race dieron el coñazo largamente con el y encima me parto el ojete porque nunca sale, ni creo que lo haga y si algún día lo hace habrá dejado de importarle a nadie.\r\n\r\nDicho por un jugador (no ', 5, 3, '2018-05-05 15:33:00'),
(6, 'Esto va camino a convertirse en el Matrix del espacio. Lo van actualizando regularmente y creo que si acabara saliendo. Mientras tanto se puede jugar la Alfa. Tiempo al tiempo. ', 5, 3, '2018-06-06 14:33:00'),
(7, 'con razón nunca pude encontrarla ?...\r\n\r\ngusano miserable?!, me quedo con la traducción para latinoamerica...', 6, 4, '2015-08-29 23:44:00'),
(8, 'Que dato!!! Hoy podré dormir más tranquilo...\r\n\r\n', 6, 4, '2016-08-30 04:44:00');


CREATE USER 'userblog'@'%' IDENTIFIED BY 'passblog';
GRANT USAGE ON *.* TO 'userblog'@'%' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT ALL PRIVILEGES ON `blogdwes`.* TO 'userblog'@'%';