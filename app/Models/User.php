<?php declare(strict_types=1);

namespace Invest\Models;

use Invest\Database\Query;

class User implements Entity {
    private $pk;
    public $name;
    public $wallet;
    public $password;
    public $login;
    public $cpf;
    public $email;
    public $birth;
    public $phone;
    public $admin;
    
    
    public function __construct($login, $password = "",  $name = "", $cpf = "", $email = "", $birth = "", $phone = "", $wallet = "", $admin = "") {
        $this->login = $login;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->name = $name;
        $this->cpf = $cpf;
        $this->email = $email;
        $this->birth = $birth;
        $this->phone = $phone;
        $this->wallet = $wallet;
        $this->admin = $admin;
    }
    
    public function get(string $pk) : bool {
        $q = new Query('SELECT * FROM TB_USER WHERE USER_LOGIN = :LOGIN');
        $r = $q->execute(array(':LOGIN' => $this->login));

        if ($r) {
            $data = $q->fetch();

            $this->pk = $data['USER_PK'];
            $this->password = $data['USER_PASSWORD'];
            $this->name = $data['USER_NAME'];
            $this->cpf = $data['USER_CPF'];
            $this->email = $data['USER_EMAIL'];
            $this->birth = $data['USER_BIRTH'];
            $this->phone = $data['USER_PHONE'];
            $this->wallet = $data['USER_WALLET'];
            $this->admin = $data['USER_ADM'];
        }

        return $r;
    }

    public function save() : bool {
        $type = "";
        
        if ($this->admin) {
            $type = "ADMIN";
        } else {
            $type = "USER";
        }
        
        $query = 'CALL P_INSERT_'. $type . '(:NAME, :LOGIN, :PASSWORD, :CPF, :EMAIL, :PHONE, :BIRTH, :WALLET)';
        
        $q = new Query($query);
        $r = $q->execute(array(':NAME' => $this->name, 
                               ':LOGIN' => $this->login, 
                               ':PASSWORD' => $this->password, 
                               ':CPF' => $this->cpf, 
                               ':EMAIL' => $this->email, 
                               ':PHONE' => $this->phone, 
                               ':BIRTH' => $this->birth, 
                               ':WALLET' => $this->wallet));

        return $r;
    }

    public function update() : bool {
        $q = new Query('UPDATE TB_USER SET USER_NAME = :NAME, 
                                           USER_LOGIN = :LOGIN, 
                                           USER_PASSWORD = :PASSWORD,
                                           USER_CPF = :CPF,
                                           USER_EMAIL = :EMAIL,
                                           USER_PHONE = :PHONE,
                                           USER_BIRTH = :BIRTH,
                                           USER_WALLET = :WALLET WHERE USER_PK = :PK');

        $r = $q->execute(array(':NAME' => $this->name, 
                            ':LOGIN' => $this->login, 
                            ':PASSWORD' => $this->password, 
                            ':CPF' => $this->cpf, 
                            ':EMAIL' => $this->email, 
                            ':PHONE' => $this->phone, 
                            ':BIRTH' => $this->birth, 
                            ':WALLET' => $this->wallet));

        return $r;
    }

    public function delete() : bool {
        $r = 0;

        if (!isset($this->pk)) { 
            $q = new Query('SELECT USER_PK FROM TB_USER WHERE USER_LOGIN = :LOGIN');
            $q->execute(array(':LOGIN' => $this->login));
            $data = $q->fetch();
            var_dump($data);
            $this->pk = $data['USER_PK'];
        }
        
        $q = new Query('DELETE FROM TABLE TB_USER WHERE USER_PK = :PK');
        $r = $q->execute(array('PK' => $this->pk));

        if ($r) {
            $this->pk = "";
            $this->login = "";
            $this->password = "";
            $this->name = "";
            $this->cpf = "";
            $this->email = "";
            $this->birth = "";
            $this->phone = "";
            $this->wallet = "";
            $this->admin = "";
        }

        return $r;
    }
}