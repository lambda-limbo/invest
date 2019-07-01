<?php

namespace Invest\Models;

class User {
    private $name;
    private $wallet;
    private $password;
    private $login;
    private $cpf;
    private $email;
    private $date_birth;
    private $phone;
    private $admin;


    public function __construct($name, $wallet, $password, $login, $cpf, $email, $date_birth, $phone, $admin) {
        $this->name = $name;
        $this->wallet = $wallet;
        $this->password = $password;
        $this->login = $login;
        $this->cpf = $cpf;
        $this->email = $email;
        $this->date_birth = $date;
        $this->phone = $phone;
        $this->admin = $admin;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
   	}

    public function getWallet() {
        return $this->wallet;
   	}

    public function setWallet($wallet) {
        $this->wallet = $wallet;
   	}

    public function getPassword() {
        return $this->password;
   	}
    public function setPassword($password) {
        $this->password = $password;
   	}

   	public function getLogin() {
        return $this->login;
   	}

   	public function setLogin($login) {
        $this->login = $login;

   	public function getCPF() {
        return $this->cpf;
   	}
    
   	public function setCPF($cpf) {
        $this->cpf = $cpf;
    }

    public function getEmail() {
	   return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getDate_birth() {
	   return $this->email;
   	}

    public function setDate_birth($date) {
        $this->date_birth = $date;
    }

    public function getPhone() {
	   return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }
   	
    public function getAdmin() {
	   return $this->admin;
   	}

    public function setAdmin($admin) {
        $this->admin = $admin;
    }
}