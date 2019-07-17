<?php declare(strict_types=1);

use Invest\Models\Stock;
use Invest\Models\Company;

use Invest\Database\Query;

use PHPUnit\Framework\TestCase;

class StockTest extends TestCase {
    private $open = 30.42;
    private $close = 32.10;
    private $maximum = 33.20;
    private $minimum = 30.42;


    public function testInsert() {
        $c = new Company("IMAG", "NON EXIST", "This is the information about the IMAGINARY company");

        $this->assertEquals($c->save(), 1);

        $q = new Query('SELECT COMPANY_PK FROM TB_COMPANY WHERE COMPANY_SYMBOL = :SYMBOL');

        $this->assertEquals($q->execute(array('SYMBOL' => 'IMAG')), 1);

        $company_pk = (int) $q->fetch()['COMPANY_PK'];

        $s = new Stock($this->open, $this->close, $this->maximum, $this->minimum, $company_pk);

        $this->assertEquals($s->save(), 1);
    }

    public function testDelete() {
        $q = new Query('SELECT STOCK_PK FROM TB_STOCK 
                        WHERE STOCK_MINIMUM = :MINIMUM 
                        AND STOCK_MAXIMUM = :MAXIMUM
                        AND STOCK_OPEN_VALUE = :OPEN
                        AND STOCK_CLOSE_VALUE = :CLOSE');

        $r = $q->execute(array(':MINIMUM' => $this->minimum, ':MAXIMUM' => $this->maximum, ':OPEN' => $this->open, ':CLOSE' => $this->close));
 
        $this->assertEquals($r, 1);

        $stock_pk = $q->fetch()['STOCK_PK'];

        $s = new Stock();
        $s->get($stock_pk);

        $this->assertEquals($s->delete(), true);

        $c = new Company("IMAG");
        
        $this->assertEquals($c->delete(), 1);
    }
}