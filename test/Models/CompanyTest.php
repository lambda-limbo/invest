<?php

use Invest\Models\Company;

use PHPUnit\Framework\TestCase;

class CompanyTest extends TestCase {

    public function testCreate() {
        $c = new Company("SGPS3", "Springs", "This is the information about that company");

        $this->assertEquals($c->save(), 1);
    }

    public function testDelete() {
        $c = new Company("SGPS3");

        $this->assertEquals($c->delete(), 1);
    }
}