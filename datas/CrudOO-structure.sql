-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema CrudOO
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema CrudOO
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `CrudOO` DEFAULT CHARACTER SET utf8 ;
USE `CrudOO` ;

-- -----------------------------------------------------
-- Table `CrudOO`.`theuser`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `CrudOO`.`theuser` (
  `idtheuser` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `thelogin` VARCHAR(80) NOT NULL,
  `thepwd` CHAR(64) NOT NULL COMMENT 'sha-256',
  PRIMARY KEY (`idtheuser`),
  UNIQUE INDEX `thelogin_UNIQUE` (`thelogin` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CrudOO`.`thesection`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `CrudOO`.`thesection` (
  `idthesection` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `thetitle` VARCHAR(100) NOT NULL,
  `thedesc` VARCHAR(1000) NULL,
  PRIMARY KEY (`idthesection`),
  UNIQUE INDEX `thetitle_UNIQUE` (`thetitle` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CrudOO`.`thestudent`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `CrudOO`.`thestudent` (
  `idthestudent` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `thename` VARCHAR(80) NOT NULL,
  `thesurname` VARCHAR(80) NOT NULL,
  PRIMARY KEY (`idthestudent`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CrudOO`.`thesection_has_thestudent`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `CrudOO`.`thesection_has_thestudent` (
  `thesection_idthesection` INT UNSIGNED NOT NULL,
  `thestudent_idthestudent` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`thesection_idthesection`, `thestudent_idthestudent`),
  INDEX `fk_thesection_has_thestudent_thestudent1_idx` (`thestudent_idthestudent` ASC),
  INDEX `fk_thesection_has_thestudent_thesection_idx` (`thesection_idthesection` ASC),
  CONSTRAINT `fk_thesection_has_thestudent_thesection`
    FOREIGN KEY (`thesection_idthesection`)
    REFERENCES `CrudOO`.`thesection` (`idthesection`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_thesection_has_thestudent_thestudent1`
    FOREIGN KEY (`thestudent_idthestudent`)
    REFERENCES `CrudOO`.`thestudent` (`idthestudent`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- update cascade
ALTER TABLE `CrudOO`.`thesection_has_thestudent` DROP FOREIGN KEY `fk_thesection_has_thestudent_thesection`; ALTER TABLE `CrudOO`.`thesection_has_thestudent` ADD CONSTRAINT `fk_thesection_has_thestudent_thesection` FOREIGN KEY (`thesection_idthesection`) REFERENCES `CrudOO`.`thesection`(`idthesection`) ON DELETE CASCADE ON UPDATE NO ACTION; ALTER TABLE `CrudOO`.`thesection_has_thestudent` DROP FOREIGN KEY `fk_thesection_has_thestudent_thestudent1`; ALTER TABLE `CrudOO`.`thesection_has_thestudent` ADD CONSTRAINT `fk_thesection_has_thestudent_thestudent1` FOREIGN KEY (`thestudent_idthestudent`) REFERENCES `CrudOO`.`thestudent`(`idthestudent`) ON DELETE CASCADE ON UPDATE NO ACTION;