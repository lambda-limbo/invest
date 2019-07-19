<?php

use Invest\Models\User;
use Invest\Database\Query;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase {

    public function testInsert() {
        $u = new User("sample", "pass123", "Rafael Campos Nunes", "02086936290", "rcamposnunes@outlook.com", "1996-07-15", "45999211031", "122000", "0");

        $this->assertEquals($u->save(), 1);
    }

    public function testGet() {
        $u = new User();
        $u->get('sample');

        $this->assertEquals($u->login, 'sample');
        $this->assertEquals($u->name, 'Rafael Campos Nunes');
        $this->assertEquals($u->cpf, '02086936290');
        $this->assertEquals($u->email, 'rcamposnunes@outlook.com');
        $this->assertEquals($u->birth, '1996-07-15');
    }

    public function testUpdate() {
        $u = new User();
        $u->get("sample");

        $u->name = 'Rafael';
        $u->cpf = '0';

        $this->assertEquals($u->update(), 1);

        $this->assertEquals($u->cpf, '0');
        $this->assertEquals($u->name, 'Rafael');
    }

    public function testDelete() { 
        $u = new User("sample");

        $this->assertEquals($u->delete(), 1);
    }
}