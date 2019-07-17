<?php declare(strict_types=1);

namespace Invest\Models;

use Invest\Models\User;
use Invest\Models\Stock;
use Invest\Database\Connection;
use Invest\Database\Query;

class Transaction implements Entity {
    private $pk;
    public $date;
    public $type;
    public $quantity;
    public $total;
    public $user;
    public $stock;

    public function __construct(date $date = null, 
                                string $type = null, 
                                int $quantity = null, 
                                double $total = null, 
                                int $user = null, 
                                int $stock = null) {
        if (isset($date)) {
            $this->date = $date;
        }
        if (isset($type)) {
            $this->type = $type;
        }
        if (isset($quantity)) {
            $this->quantity = $quantity;
        }
        if (isset($total)) {
            $this->total = $total;
        }
        if (isset($user)) {
            $this->user = $user;
        }
        if (isset($stock)) {
            $this->stock = $stock;
        }
    }

    public function get(string $user) : bool {
        $q = new Query('SELECT * FROM TB_TRANSACTION WHERE TRANSACTION_PK = :PK');
        $r = $q->execute(array(':PK' => $pk));

        if ($r) {
            $data = $q->fetch();

            $this->date = $data['TRANSACTION_DATE'];
            $this->type = $data['TRANSACTION_TYPE'];
            $this->quantity = $data['TRANSACTION_QUANTITY'];
            $this->total = $data['TRANSACTION_TOTAL'];
            $this->user = $data['USER_PK'];
            $this->stock = $data['STOCK_PK'];
        }

        return $r;
    }

    public function save() : bool {
        $q = new Query('CALL P_INSERT_TRANSACTION(:DATE, :TYPE, :QUANTITY, :TOTAL, :USER, :STOCK)');
        $r = $q->execute(array(':DATE' => $this->date, 
                               ':TYPE' => $this->type, 
                               ':QUANTITY' => $this->quantity, 
                               ':TOTAL' => $this->total, 
                               ':USER' => $this->user, 
                               ':STOCK' => $this->stock));

        return $r;
    }

    public function update() : bool {
        $q = new Query('UPDATE TB_TRANSACTION SET TRANSACTION_DATE = :DATE, 
                                                  TRANSACTION_TYPE = :TYPE, 
                                                  TRANSACTION_QUANTITY = :QUANTITY, 
                                                  TRANSACTION_TOTAL = :TOTAL, 
                                                  USER_PK = :USER, 
                                                  STOCK_PK = :STOCK WHERE TRANSACTION_PK = :PK');

        $r = $q->execute(array(':DATE' => $this->date, 
                               ':TYPE' => $this->type, 
                               ':QUANTITY' => $this->quantity, 
                               ':TOTAL' => $this->total, 
                               ':USER' => $this->user, 
                               ':STOCK' => $this->stock));

        return $r;
    }

    public function delete() : bool {
        if (isset($this->pk)) {
            $q = new Query("DELETE FROM TB_TRANSACTION WHERE TRANSACTION_PK = :PK");
            $r = $q->execute(array(':PK => $this->pk'));

            if ($r) {
                $this->date = "";
                $this->type = "";
                $this->quantity = "";
                $this->total = "";
                $this->user = "";
                $this->stock = "";
            }
        }
    }

}