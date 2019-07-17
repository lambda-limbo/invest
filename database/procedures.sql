--
-- Procedures file
-- 
USE `INVEST_DATABASE`;


--
-- @brief Deletes an user given the primary key value
--
DELIMITER //
CREATE  PROCEDURE P_DELETE_USER (IN PK_CODE INT)
BEGIN
    IF EXISTS (SELECT USER_PK FROM TB_USER WHERE USER_PK = PK_CODE)
    THEN
        DELETE FROM TB_USER WHERE USER_PK = PK_CODE;
    END IF;
END
//


--
-- @brief Creates an user on the database
--
DELIMITER //
CREATE  PROCEDURE P_INSERT_USER(IN NAME VARCHAR(100), IN LOGIN VARCHAR(20), IN PASSWORD VARCHAR(255), IN CPF VARCHAR(20), IN EMAIL VARCHAR(50),
					            IN PHONE VARCHAR(20), IN BIRTH DATE, WALLET DOUBLE)
BEGIN
    INSERT INTO TB_USER (USER_NAME, USER_LOGIN, USER_PASSWORD, USER_CPF, USER_EMAIL, USER_PHONE, USER_BIRTH, USER_WALLET, USER_ADM)
    VALUES (NAME, LOGIN, PASSWORD, CPF, EMAIL, PHONE, BIRTH, WALLET, 0);
END
//

--
-- @brief Creates an user on the database with administrative privileges
--
DELIMITER //
CREATE  PROCEDURE P_INSERT_ADM(IN NAME VARCHAR(100), IN LOGIN VARCHAR(20), IN PASSWORD VARCHAR(255), IN CPF VARCHAR(20), IN EMAIL VARCHAR(50),
					           IN PHONE VARCHAR(20), IN BIRTH DATE, WALLET DOUBLE)
BEGIN
    INSERT INTO TB_USER (USER_NAME, USER_LOGIN, USER_PASSWORD, USER_CPF, USER_EMAIL, USER_PHONE, USER_BIRTH, USER_WALLET, USER_ADM)
    VALUES (NAME, LOGIN, PASSWORD, CPF, EMAIL, PHONE, BIRTH, WALLET, 1);
END
//

--
-- @brief Select an specific user with a given primary key.
--
DELIMITER //
CREATE  PROCEDURE P_SELECT_USER(IN PK_CODE INT)
BEGIN
  SELECT * FROM TB_USER WHERE USER_PK = PK_CODE;
END
//


-- 
-- @brief Changes the email of a given user primary key
--
DELIMITER //
CREATE  PROCEDURE P_UPDATE_EMAIL(IN PK_CODE INT, IN EMAIL VARCHAR(50))
BEGIN
    IF EXISTS (SELECT USER_EMAIL FROM TB_USER WHERE USER_EMAIL = EMAIL AND
               USER_PK = PK_CODE)
    THEN
        UPDATE TB_USER SET USER_EMAIL = EMAIL WHERE USER_PK = PK_CODE;
    END IF;
END
//

--
-- @brief Changes the password of a given user primary key
--
DELIMITER //
CREATE PROCEDURE P_UPDATE_PASS(IN PK_CODE INT, IN LOGIN VARCHAR(20), IN PASSWORD VARCHAR(255),
                               IN NEW_PASSWORD VARCHAR(255))
BEGIN
    IF EXISTS (SELECT USER_PASSWORD FROM TB_USER WHERE USER_PASSWORD = PASSWORD AND
               USER_LOGIN = LOGIN)
    THEN
        UPDATE TB_USER SET USER_PASSWORD = NEW_PASSWORD WHERE USER_PK = PK_CODE;
    END IF;
END
//

--
-- @brief Changes the phone of a given user primary key
--
DELIMITER //
CREATE PROCEDURE P_UPDATE_PHONE(IN PK_CODE INT, IN PHONE VARCHAR(20))
BEGIN
    IF EXISTS (SELECT USER_PK FROM TB_USER WHERE USER_PK = PK_CODE)
    THEN
        UPDATE TB_USER SET USER_CELL_PHONE = PHONE WHERE USER_PK = PK_CODE;
    END IF;
END
//

--
-- @brief Updates the wallet of the user
--
DELIMITER //
CREATE PROCEDURE P_UPDATE_WALLET(IN PK_CODE INT, IN VALUE DOUBLE)
BEGIN
    IF EXISTS (SELECT USER_PK FROM TB_USER WHERE USER_PK = PK_CODE)
    THEN
        UPDATE TB_USER SET USER_WALLET = VALUE WHERE USER_PK = PK_CODE;
    END IF;
END
//

--
-- @brief Insert a stock on the database
--
DELIMITER //
CREATE  PROCEDURE P_INSERT_STOCK(IN STOCK_MINIMUM DOUBLE, IN STOCK_MAXIMUM DOUBLE, 
                                 IN STOCK_OPEN_VALUE DOUBLE, IN STOCK_CLOSE_VALUE DOUBLE, 
                                 IN FK_COMPANY_PK INT)
                            
BEGIN
    INSERT INTO TB_STOCK (STOCK_MINIMUM, STOCK_MAXIMUM, STOCK_OPEN_VALUE, STOCK_CLOSE_VALUE, FK_COMPANY_PK)
    VALUES (STOCK_MINIMUM, STOCK_MAXIMUM, STOCK_OPEN_VALUE, STOCK_CLOSE_VALUE, FK_COMPANY_PK);
END
//

--
-- @brief Selects  the transactions for report page of current user
--
DELIMITER //
CREATE PROCEDURE P_REPORT(IN CODIGO INT)
BEGIN
  SELECT
    T.TRANSACTION_DATE AS DATA,
    T.TRANSACTION_TOTAL AS VALOR,
    C.COMPANY_NAME AS NOME
  FROM
    TB_COMPANY C, TB_TRANSACTION T, TB_STOCK S
  WHERE 
    (S.STOCK_PK = T.STOCK_PK)
    AND (C.COMPANY_PK = S.TB_COMPANY_COMPANY_PK)
    AND T.USER_PK = CODIGO;
END
//


--
-- @brief Insert company
--
DELIMITER //
CREATE PROCEDURE P_INSERT_COMPANY(IN NOME VARCHAR(100), IN INFO VARCHAR(100), IN SYMBOL VARCHAR(8))
BEGIN
    INSERT INTO TB_COMPANY (COMPANY_NAME, COMPANY_INFO, COMPANY_SYMBOL) 
    VALUES (NOME, INFO, SYMBOL);
END
//

--
-- @brief select the value in wallet
--
DELIMITER //
CREATE PROCEDURE P_SELECT_USER_WALLET (IN CODIGO INT)
BEGIN
    SELECT USER_WALLET FROM TB_USER WHERE USER_PK = CODIGO;
END
//

--
-- @brief select the purchase total value
--
DELIMITER //
CREATE PROCEDURE P_SUM_BUY (IN PK_CODE INT)
BEGIN
    SELECT SUM(TRANSACTION_TOTAL)  AS VALOR FROM TB_TRANSACTION WHERE USER_PK = PK_CODE AND TRANSACTION_TYPE = 'Compra';
END
//

--
-- @brief select the sale total value 
--
DELIMITER //
CREATE  PROCEDURE P_SUM_SELL(IN PK_CODE INT)
BEGIN
    SELECT SUM(TRANSACTION_TOTAL) AS VALOR FROM TB_TRANSACTION WHERE USER_PK = PK_CODE AND TRANSACTION_TYPE = 'Venda';
END
//

