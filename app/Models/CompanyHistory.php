<?php declare(strict_types=1);

namespace Invest\Models;



class CompanyHistory implements Entity {
    private $pk;
    public $minimium;
    public $maximum;
    public $date;
    public $open;
    public $close;
    public $company;

    public function __construct($minimium, $maximum, $date, $open, $close, $company) {
        $this->minimium = $minimium;
        $this->maximum = $maximum;
        $this->date = $date;
        $this->open = $open;
        $this->close = $close;
        $this->company = $company;
    }


    public function get(string $pk) : bool {
        $q = new Query('SELECT * FROM TB_COMPANY_HISTORY WHERE COMPANY_HISTORY_PK = :PK');
		$r = $q->execute(array(':PK' => $pk));

		if ($r) {
			$data = $q->fetch();

			$this->pk = $data['COMPANY_HISTORY_PK'];
			$this->minimium = $data['COMPANY_HISTORY_MINIMIUM'];
			$this->maximium = $data['COMPANY_HISTORY_MAXIMIUM'];
			$this->open = $data['COMPANY_HISTORY_OPEN_VALUE'];
			$this->close = $data['COMPANY_HISTORY_CLOSE_VALUE'];
			$this->value = $data['COMPANY_HISTORY_VALUE'];
		}

		return $r;
    }

    public function save() : bool {
        
    }

    public function update() : bool {

    }

    public function delete() : bool {

    }
}