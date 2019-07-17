<?php declare(strict_types=1);

use Invest\Database\Query;
use PHPUnit\Framework\TestCase;
use Invest\Exceptions\DatabaseException;

final class QueryTest extends TestCase {
    
    /**
     * Test an insertion on the database
     */
    public function testInsert() {
        $q = new Query('CALL P_INSERT_USER("Rafael Campos Nunes", "rcamposnunes", "$2y$10$.1g6wdP.72zMxuyL3Y8iGOPAU1/onBmI53tizHPFNa45O4/.enNTG", 
                       "02086936290", "rcamposnunes@outlook.com", "45999211031", "1996-07-15", "100.00")');
        
        $this->assertEquals($q->execute(), 1);
    }

    /**
     * Test a select on the database
     */
    public function testSelect() {
        $q = new Query('SELECT * FROM TB_USER');
        
        $this->assertEquals($q->execute(), 1);
    }

    public function testDelete() {
        $q = new Query('DELETE t1 FROM TB_USER t1 JOIN TB_USER t2 
                        ON t1.USER_PK = t2.USER_PK AND t1.USER_LOGIN = "rcamposnunes"');
        
        $this->assertEquals($q->execute(), 1);
    }

    /**
     * Tests if the database binds and executes a SQL instruction correctly.
     */
    public function testBindParameters() {
        $q = new Query('SELECT * FROM TB_USER WHERE USER_NAME = :USER AND USER_PASSWORD = :PASS');

        $q->bind(array(':USER' => "ranu",
                       ':PASS' => "some password"));

        $this->assertEquals($q->execute(), 1);

        while ($row = $q->fetch()) {
            print_r($row);
        }
    }

    public function testException() {
        $q = new Query('CALL P_SOME_UNDEFINED_PROCEDURE');

        try {
            $q->execute();
        } catch (DatabaseException $e) {
            $this->assertEquals($e->getMessage(), "Error executing the query CALL P_SOME_UNDEFINED_PROCEDURE.\n\n:: SQLSTATE[42000]: Syntax error or access violation: 1305 PROCEDURE INVEST_DATABASE.P_SOME_UNDEFINED_PROCEDURE does not exist\n");
        }
    }
}
