<?php declare(strict_types=1);

namespace Invest\Models;

use Invest\Models\Company;
use Invest\Database\Connection;
use Invest\Database\Query;

class Stock implements Entity {
	private $pk;
	public $minimum;
	public $maximum;
	public $open;
	public $close;
	public $company;

	/**
	 * Constructs a new Stock object
	 */
	public function __construct(float $open = null, 
								float $close = null,
								float $maximum = null, 
								float $minimum = null,
								int $company = null) {
		
		if (isset($open)){
			$this->open = $open;
		}
		if (isset($close)){
			$this->close = $close;
		}
		if (isset($minimum)) {
			$this->minimum = $minimum;
		}
		if (isset($maximum)) {
			$this->maximum = $maximum;
		}
		if (isset($company)) {
			$this->company = $company;
		}
	}

	/**
	 * Fills the Stock object given a PK
	 */
	public function get(string $pk) : bool {
		$q = new Query('SELECT * FROM TB_STOCK WHERE STOCK_PK = :PK');
		$r = $q->execute(array(':PK' => $pk));

		if ($r) {
			$data = $q->fetch();

			$this->pk = $data['STOCK_PK'];
			$this->minimum = $data['STOCK_MINIMUM'];
			$this->maximum = $data['STOCK_MAXIMUM'];
			$this->open = $data['STOCK_OPEN_VALUE'];
			$this->close = $data['STOCK_CLOSE_VALUE'];
		}

		return $r;
	}

	/**
	 * Persists the Stock object on the database
	 */
	public function save() : bool {
		$q = new Query('CALL P_INSERT_STOCK(:MINIMUM, 
											:MAXIMUM, 
											:OPEN_VALUE, 
											:CLOSE_VALUE, 
											:COMPANY)');

		$r = $q->execute(array(':MINIMUM' => $this->minimum,
							   ':MAXIMUM' => $this->maximum,
							   ':OPEN_VALUE' => $this->open,
							   ':CLOSE_VALUE' => $this->close,
							   ':COMPANY' => $this->company));

		return $r;
	}

	/**
	 * Updates the Stock object on the database.
	 */
	public function update() : bool {
		$q = new Query('UPDATE TB_STOCK SET STOCK_MINIMUM = :MINIMUM,
											STOCK_MAXIMUM = :MAXIMUM,
											STOCK_OPEN_VALUE  = :OPEN_VALUE,
											STOCK_CLOSE_VALUE = :CLOSE_VALUE,
											TB_COMPANY_COMPANY_PK = :COMPANY WHERE STOCK_PK = :PK');

		$r = $q->execute(array(':MINIMUM' => $this->minimum,
							   ':MAXIMUM' => $this->maximum,
							   ':OPEN_VALUE' => $this->open_value,
							   ':CLOSE_VALUE' => $this->close,
							   ':COMPANY' => $this->company,
							   ':PK' => $this->pk));

		return $r;
	}

	/**
	 * Deletes the Stock object from the database and erase the object fields.
	 */
	public function delete() : bool {
		if (isset($this->pk)) {
			$q = new Query('DELETE FROM TB_STOCK WHERE STOCK_PK = :PK');
			$r = $q->execute(array(':PK' => $this->pk));

			if ($r) {
				$this->pk = "";
				$this->minimum = "";
				$this->maximum = "";
				$this->open = "";
				$this->close = "";
				$this->company = "";
			}

			return $r;
		}

		return false;
	}
	
}