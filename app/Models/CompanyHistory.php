<?php

namespace Invest\Models;
include ('Company.php');
class CompanyHistory {
    private $minimium;
    private $maximum;
    private $history_date;
    private $opening_value;
    private $close_value;
    private $company;

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

    public function getHistory_date(){
        return $this->history_date;
    }
    public function setHistory_date($history_date){
        $this->history_date = $history_date;
    }

    public function getOpening_value(){
        return $this->opening_value;
    }
    public function setOpening_value($opening_value){
        $this->opening_value = $opening_value;
    }

    public function getClose_value(){
        return $this->close_value;
    }
    public function setClosevalue($close_value){
        $this->close_value = $close_value;
    }

    public function getCompany(){
        return $this->company;
    }
    public function setCompany($company){
        $this->company = $company;
    }
   	

	public function __construct($minimium, $maximum, $history_date, $opening_value, $close_value, $company){
		  $this->minimium = $minimium;
		  $this->maximum = $maximum;
		  $this->history_date = $history_date;
      $this->opening_value = $opening_value;
      $this->close_value = $close_value;
      $this->company = $company;

	}


  
}