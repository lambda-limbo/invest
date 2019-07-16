<?php

use Invest\Models\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase {
    
    public function testInsert() {
        $t = new Transaction(date("Y-m-d"), "Compra", 3, 20.45, 1, 1);
        $this->assertEquals($t->save(), 1);
    }

    public function testDelete() { 
        $t = new Transaction();

        $this->assertEquals($t->delete(), 1);
    }
}