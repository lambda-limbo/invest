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
  `USER_LOGIN` VARCHAR(20) NOT NULL,
  `USER_CPF` VARCHAR(20) NOT NULL,
  `USER_EMAIL` VARCHAR(50) NOT NULL,
  `USER_DATE_OF_BIRTH` DATE NOT NULL,
  `USER_CELL_PHONE` VARCHAR(20) NOT NULL,
  `USER_ADM` INT,
  
  PRIMARY KEY (`USER_PK`))
ENGINE = InnoDB;



-- -----------------------------------------------------
-- Table `mydb`.`TB_ADM`
-- -----------------------------------------------------

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



DELIMITER //

CREATE PROCEDURE select_adm -- Mostrar codigo, nome, e email somente dos administradores, passar parâmetro 1
(IN codigo int)
BEGIN
  SELECT USER_PK, USER_NAME,USER_EMAIL FROM TB_USER WHERE
  USER_ADM = codigo ;
END;

//


DELIMITER //

CREATE PROCEDURE delete_user -- remover um usuario usando seu codigo
(IN codigo int)
BEGIN
IF EXISTS (SELECT USER_PK FROM TB_USER WHERE USER_PK = codigo)
THEN
DELETE FROM TB_USER WHERE USER_PK = codigo;
END IF;
END

//


DELIMITER //

CREATE PROCEDURE delete_user_name -- remover usuario usando login
(IN login varchar(20))
BEGIN
IF EXISTS (SELECT USER_LOGIN FROM TB_USER WHERE USER_LOGIN = login)
THEN
DELETE FROM TB_USER WHERE USER_LOGIN = login;
END IF;
END

//

DELIMITER //
CREATE PROCEDURE update_pass -- atualiza a senha
(codigo int, login varchar(20), senha varchar(255) , novaSenha varchar(255) )
BEGIN
IF EXISTS (SELECT USER_PASSWORD FROM TB_USER WHERE USER_PASSWORD = senha AND 
USER_LOGIN = login)
THEN
UPDATE TB_USER SET USER_PASSWORD = novaSenha WHERE USER_PK = codigo;
END IF;
END

//

DELIMITER //
CREATE PROCEDURE update_email -- atualiza o email
(codigo int, email varchar(50) )
BEGIN
IF EXISTS (SELECT USER_EMAIL FROM TB_USER WHERE USER_EMAIL = email AND 
USER_PK = codigo)
THEN
UPDATE TB_USER SET USER_EMAIL = email WHERE USER_PK = codigo;
END IF;
END

//

DELIMITER //
CREATE PROCEDURE update_wallet -- atualizar valor wallet
(codigo int, valor double)
BEGIN
IF EXISTS (SELECT USER_PK FROM TB_USER WHERE USER_PK = codigo) 
THEN
UPDATE TB_USER SET USER_WALLET = valor WHERE USER_PK = codigo;
END IF;
END

//

DELIMITER //
CREATE PROCEDURE update_phone -- atualiza o numero de celular
(codigo int, telefone varchar(20))
BEGIN
IF EXISTS (SELECT USER_PK FROM TB_USER WHERE USER_PK = codigo)
THEN
UPDATE TB_USER SET USER_CELL_PHONE = telefone WHERE USER_PK = codigo;
END IF;
END

//



DELIMITER //
CREATE PROCEDURE insert_user -- insere usuário
(codigo int, wallet double, login varchar(20), cpf varchar(20), email varchar(50),
senha varchar(255), adm int, telefone varchar(20), nasc date, nome varchar(100))
BEGIN
INSERT INTO TB_USER (USER_PK, USER_NAME, USER_WALLET, USER_PASSWORD, USER_LOGIN,
 USER_CPF, USER_EMAIL, USER_DATE_OF_BIRTH, USER_CELL_PHONE, USER_ADM) 
 VALUES 
 (codigo, nome, wallet, senha, login , cpf, email, nasc, telefone , adm);

END

//

