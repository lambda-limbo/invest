-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`TB_USER`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`TB_USER` (
  `USER_PK` INT NOT NULL,
  `USER_NAME` VARCHAR(100) NOT NULL,
  `USER_WALLET` DOUBLE NOT NULL,
  `USER_PASSWORD` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`USER_PK`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`TB_ADM`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`TB_ADM` (
  `ADM_PK` INT NOT NULL,
  `ADM_NAME` VARCHAR(100) NOT NULL,
  `ADM_PASSWORD` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`ADM_PK`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`TB_COMPANY`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`TB_COMPANY` (
  `COMPANY_PK` INT NOT NULL,
  `COMPANY_NAME` VARCHAR(100) NOT NULL,
  `COMPANY_INFO` VARCHAR(1000) NOT NULL,
  `COMPANY_SYMBOL` CHAR(10) NOT NULL,
  PRIMARY KEY (`COMPANY_PK`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`TB_STOCK`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`TB_STOCK` (
  `STOCK_MINIMIUM` DOUBLE NOT NULL,
  `STOCK_MAXIMUN` DOUBLE NOT NULL,
  `STOCK_OPENING` DOUBLE NOT NULL,
  `STOCK_PK` INT NOT NULL,
  `TB_COMPANY_COMPANY_PK` INT NOT NULL,
  `STOCK_VALUE` DOUBLE NOT NULL,
  `STOCK_CLOSE_VALUE` DOUBLE NULL,
  PRIMARY KEY (`STOCK_PK`),
  CONSTRAINT `fk_TB_STOCK_TB_COMPANY1`
    FOREIGN KEY (`TB_COMPANY_COMPANY_PK`)
    REFERENCES `mydb`.`TB_COMPANY` (`COMPANY_PK`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`TB_COMPANY_HISTORY`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`TB_COMPANY_HISTORY` (
  `COMPANY_HISTORY_PK` INT NOT NULL,
  `COMPANY_HISTORY_MINIMIUM` DOUBLE NOT NULL,
  `COMPANY_HISTORY_MAXIMIUM` DOUBLE NOT NULL,
  `COMPANY_PK` INT NOT NULL,
  `COMPANY_HISTORY_DATE` DATE NOT NULL,
  `COMPANY_HISTORY_OPENING_VALUE` DOUBLE NOT NULL,
  `COMPANY_HISTORY_CLOSE_VALUE` DOUBLE NOT NULL,
  PRIMARY KEY (`COMPANY_HISTORY_PK`, `COMPANY_PK`),
  CONSTRAINT `fk_TB_COMPANY_HISTORIC_TB_COMPANY1`
    FOREIGN KEY (`COMPANY_PK`)
    REFERENCES `mydb`.`TB_COMPANY` (`COMPANY_PK`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`TB_TRANSACTION`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`TB_TRANSACTION` (
  `USER_USER_CODIGO` INT NOT NULL,
  `STOCK_PK` INT NOT NULL,
  `TRANSACTION_DATE` DATE NOT NULL,
  `TRANSACTION_TYPE` VARCHAR(45) NOT NULL,
  `TRANSACTION_QUANTITY` INT NOT NULL,
  `TRANSACTION_TOTAL` DOUBLE ,
  PRIMARY KEY (`USER_USER_CODIGO`, `STOCK_PK`),
  CONSTRAINT `fk_TB_USER_has_TB_STOCK_TB_USER1`
    FOREIGN KEY (`USER_USER_CODIGO`)
    REFERENCES `mydb`.`TB_USER` (`USER_PK`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_TB_USER_has_TB_STOCK_TB_STOCK1`
    FOREIGN KEY (`STOCK_PK`)
    REFERENCES `mydb`.`TB_STOCK` (`STOCK_PK`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
