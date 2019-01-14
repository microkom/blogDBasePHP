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
