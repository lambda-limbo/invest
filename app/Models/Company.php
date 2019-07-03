<?php

namespace Invest\Models;

use Invest\Models\CompanyHistory;

class Company {
    private $name;
    private $info
    private $symbol;

    public function __construct($name, $info, $symbol) {
        $this->name = $name;
        $this->info = $info;
        $this->symbol = $symbol;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getInfo() {
        return $this->info;
    }

    public function setInfo($info) {
        $this->info = $info;
    }

    public function getSymbol() {
        return $this->name;
    }

    public function setSymbol($symbol) {
        $this->symbol = $symbol;
    }
}