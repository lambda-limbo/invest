<?php

use Invest\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase {

    public function testInsert() {
        $u = new User("sample", "pass123", "Rafael Campos Nunes", "02086936290", "rcamposnunes@outlook.com", "1996-07-15", "45999211031", "122000", "0");

        $this->assertEquals($u->save(), 1);
    }

    public function testDelete() { 
        $u = new User("sample");

        $this->assertEquals($u->delete(), 1);
    }
}