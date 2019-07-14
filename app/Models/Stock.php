<?php declare(strict_types=1);

namespace Invest\Models;

use Invest\Models\Company;
use Invest\Database\Connection;
use Invest\Database\Query;

class Stock implements Entity {
	private $pk;
	public $minimium;
	public $maximum;
	public $opening_value;
	public $close_value;
	public $company;


	public function __construct(double $minimium = null, double $maximum = null, double $opening_value = null, double $close_value = null, int $company = null) {
		if (isset($minimium)) {
			$this->minimium = $minimium;
		}
		if (isset($maximum)) {
			$this->maximium = $maximum;
		}
		if (isset($opening_value)){
			$this->opening_value = $opening_value;
		}
		if (isset($close_value)){
			$this->close_value = $close_value;
		}
		if (isset($company)){
			$this->company = $company;
		}
	}
	
	public function getMinimium() {
		return $this->minimium;
	}

	public function get() : bool {
		$q = new Query('SELECT * FROM TB_STOCK WHERE TB_COMPANY_COMPANY_PK = :COMPANY');
		$r = $q->execute(array(' :COMPANY' => $this->company));

		if ($r) {
			$data = $q->fetch();

			$this->pk = $data['STOCK_PK'];
			$this->minimium = $data['STOCK_MINIMIUM'];
			$this->maximium = $data['STOCK_MAXIMIUM'];
			$this->opening_value = $data['STOCK_OPEN_VALUE'];
			$this->close_value = $data['STOCK_CLOSE_VALUE'];
		}

		return $r;
	}

	public function save() : bool {
		$query = 'CALL P_INSERT_'.'(:MINIMIUM, :MAXIMIUM, :OPENING_VALUE, :CLOSE_VALUE, :COMPANY)';

		$q = new Query($query);

		$r = $q->execute(array(':MINIMIUM' => $this->minimium,
							   ':MAXIMIUM' => $this->maximium,
							   ':OPENING_VALUE' => $this->opening_value,
							   ':CLOSE_VALUE' => $this->close_value,
							   ':COMPANY' => $this->company));

		return $r;
	}

	public function update() : bool {
		$q = new Query('UPDATE TB_STOCK SET STOCK_MINIMIUM = :MINIMIUM,
											STOCK_MAXIMIUM = :MAXIMIUM,
											STOCK_OPEN_VALUE  = :OPENING_VALUE,
											STOCK_CLOSE_VALUE = :CLOSE_VALUE
											TB_COMPANY_COMPANY_PK = :COMPANY WHERE STOCK_PK = :PK');

		$r = $q->execute(array(':MINIMIUM' => $this->minimium,
							   ':MAXIMIUM' => $this->maximium,
							   ':OPEN_VALUE' => $this->open_value,
							   'CLOSE_VALUE' => $this->close_value,
							   'COMPANY' => $this->company));

		return $r;
	}

	public function delete() : bool {
		$r = 0;

		if (isset($this->pk)) {
			$q = new Query('DELETE FROM TABLE TB_STOCK WHERE STOCK_PK = :PK');
			$r = $q->execute(array('PK' => $this->pk));

			if ($r) {
				$this->pk = "";
				$this->minimium = "";
				$this->maximium = "";
				$this->opening_value = "";
				$this->close_value = "";
				$this->company = "";
			}
		}

		return $r;
	}
	
}