DROP DATABASE IF EXISTS `Svens_casino`;
CREATE DATABASE `Svens_casino`;
USE `Svens_casino`;

CREATE TABLE `gebruikers` (
    `gebruiker_id` INT AUTO_INCREMENT PRIMARY KEY,
    `gebruikersnaam` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `wachtwoord` VARCHAR(255) NOT NULL,
    `geboortedatum` DATE NOT NULL,
    `saldo` FLOAT DEFAULT NULL
);