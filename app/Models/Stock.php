<?php

namespace Invest\Models;
use Invest\Models\Company;
class Stock {
	private $minimium;
	private $maximum;
	private $opening;
	private $value;
	private $close_value;
	private $company;


	public function __construct($minimium, $maximum, $opening, $value, $close_value, $company){
		$this->minimium = $minimium;
		$this->maximum = $maximum;
		$this->opening = $opening;
		$this->value = $value;
		$this->close_value = $close_value;
		$this->company = $company;
	}
	public function getMinimium(){
		return $this->minimium;
   	}
   	public function setMinimium($minimium){
       	$this->minimium = $minimium;
	}

	public function getMaximum(){
		return $this->maximum;
   	}
   	public function setMaximum($maximum){
       	$this->maximum = $maximum;
	}

	public function getOpening(){
		return $this->opening;
   	}
   	public function setOpening($opening){
       	$this->opening = $opening;
	}

	public function getValue(){
		return $this->value;
   	}
   	public function setValue($value){
       	$this->value = $value;
	}

	public function getClose_value(){
		return $this->close_value;
   	}
   	public function setClose_value($close_value){
       	$this->close_value = $close_value;
	}

	public function getCompany(){
		return $this->company;
   	}
   	public function setCompany($company){
       	$this->company = $company;
	}
}