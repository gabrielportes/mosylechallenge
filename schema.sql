CREATE DATABASE IF NOT EXISTS `mosylechallenge`
CHARACTER SET utf8
COLLATE utf8_general_ci;

USE `mosylechallenge`;

CREATE TABLE IF NOT EXISTS `users` (
    `iduser` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `drink_counter` INT(11) NOT NULL DEFAULT 0,
    `email` VARCHAR(255) NOT NULL,
    `password` CHAR(40) NOT NULL,
    `token` CHAR(40) NOT NULL,
    PRIMARY KEY(`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
