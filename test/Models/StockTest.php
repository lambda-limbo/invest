<?php declare(strict_types=1);

use Invest\Models\Stock;
use Invest\Models\Company;
use Invest\Database\Query;

use PHPUnit\Framework\TestCase;

class StockTest extends TestCase {

    public function testInsert() {
        $c = new Company("Springs", "This is the information about that company", "SGPS3");
        
        $this->assertEquals($c->save(), 1);

        $s = new Stock(30.42, 45.30, 38.22, 43.20, 42.00, 1);

        $this->assertEquals($s->save(), 1);
    }

    public function testDelete() {
        $s = new Stock();
        $s->get("SGPS3");

        $this->assertEquals($s->delete(), 1);
    }
}