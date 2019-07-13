<?php declare(strict_types=1);

namespace Invest\Models;

use Invest\Database\Query;

class Company implements Entity {
    private $pk;
    public $name;
    public $info;
    public $symbol;
    
    public function __construct(string $symbol, string $name = "", string $info = "") {
        $this->name = $name;
        $this->info = $info;
        $this->symbol = $symbol;
    }
    
    /**
    * Fills the object with information, if existent, from the database
    */
    public function get(string $symbol) : bool {
        $q = new Query('SELECT * FROM TB_COMPANY WHERE COMPANY_SYMBOL = :SYMBOL');
        $r = $q->execute(array(':SYMBOL' => $symbol));
        
        if ($r) {
            $data = $q->fetch();
            
            $this->pk = $data['COMPANY_PK'];
            $this->name = $data['COMPANY_NAME'];
            $this->info = $data['COMPANY_INFO'];
            $this->symbol = $data['COMPANY_SYMBOL'];
        }
        
        return $r;
    }
    
    /**
    * Saves the object on the database and also downloads 20y+ of data from the ALPHAVANTAGE to insert
    * on the database.
    */
    public function save() : bool {
        $q = new Query('CALL P_INSERT_COMPANY(:NAME, :INFO, :SYMBOL)');
        $r = $q->execute(array(':NAME' => $this->name, ':INFO' => $this->info, ':SYMBOL' => $this->symbol));
        
        return $r;
    }
    
    /**
    * Updates the entity in the database with the information of this object.
    */
    public function update() : bool {
        $q = new Query('UPDATE TB_COMPANY SET COMPANY_NAME = :NAME, COMPANY_INFO = :INFO, COMPANY_SYMBOL = :SYMBOL WHERE COMPANY_PK = :PK');
        $r = $q->execute(array(':NAME' => $this->name, ':INFO' => $this->info, ':SYMBOL' => $this->symbol, ':PK' => $this->pk));
        
        return $r;
    }
    
    /**
    * Deletes the object inside the database and erase all information associated with this object.
    */
    public function delete() : bool {
        if (!isset($this->pk)) {
            $q = new Query('SELECT COMPANY_PK FROM TB_COMPANY WHERE COMPANY_SYMBOL = :SYMBOL');
            $q->execute(array(':SYMBOL' => $this->symbol));
            $data = $q->fetch();
            
            $this->pk = $data['COMPANY_PK'];
        }
        
        $q = new Query('DELETE FROM TB_COMPANY WHERE COMPANY_PK = :PK');
        $r = $q->execute(array(':PK' => $this->pk));
        
        if ($r) {
            $this->name = "";
            $this->info = "";
            $this->symbol = "";
        }
        
        return $r;
        
    }
}