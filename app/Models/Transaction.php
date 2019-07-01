<?php

namespace Invest\Models;

use Invest\Models\User;
use Invest\Models\Stock;

class Transaction {
    private $date;
    private $type;
    private $quantity;
    private $total;
    private $user;
    private $stock;

    public function __construct($date, $type, $quantity, $total, $user, $stock) {
        $this->date = $date;
        $this->type = $type;
        $this->quantity = $quantity;
        $this->total = $total;
        $this->user = $user;
        $this->stock = $stock;
    }

    public function getDate() {
        return $this->date;
    }
    public function setDate($date) {
        $this->date = $date;
    }

    public function getType() {
        return $this->type;
    }
    public function setType($type) {
        $this->type = $type;
    }

    public function getQuantity() {
        return $this->quantity;
    }
    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    public function getTotal() {
        return $this->total;
    }
    public function setTotal($total) {
        $this->total = $total;
    }

    public function getUser() {
        return $this->user;
    }
    public function setUser($user) {
        $this->user = $user;
    }

    public function getStock() {
        return $this->stock;
    }

    public function setStock($stock) {
        $this->stock = $stock;
    }
}