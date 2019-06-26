<?php
    namespace Invest\Database;

    class DAO {
        
        private $table_name;

        private static $instance_of_class;

        private function __construct($table_name=NULL) {
            if (!empty($table_name)) {
                $this->table_name = $table_name;
            }
        }

        private static function getInstance(Connection(), $table_name = NULL) {
            if (!isset(self::$instance_of_class)) {
                try {
                    self::$instance_of_class = new DAO(Connection, $table_name); 
                } catch (Exception $e) {
                    echo "Erro ".$e->getMessage();
                }
            }

            return self::$instance_of_class;
        }

        public function setTableName($table_name) {
            if (!empty($table_name)) {
                $this->table_name = $table_name;
            }
        }

        private function buildInsert($array_data) {
            $sql = "";
            $fields = "";
            $values = "";

            foreach($array_data as $key => $value) {
                $fields .= $key . ' ';
                $values .= '? ';
            }

        $sql .= "INSERT INTO {$this->table_name} (" . $fields . ") VALUES("
        }


    }