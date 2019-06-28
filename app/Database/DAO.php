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

            $sql .= "INSERT INTO {$this->table_name} (" . $fields . ") VALUES(" . $values . ")";  
        
            return trim($sql); 
        }

        private function buildUpdate($array_data, $array_condition){
            $sql = "";
            $fields = "";
            $condition = "";

            foreach($aray_data as $key => $value) {
                $fields .= $key . '=? ';
            }

            foreach($array_condition as $key => $value) {
                $condition .= $key . '? AND ';
            }

            $sql .= "UPDATE {$this->$table_name} SET " . $fields . " WHERE " . $condition;

            return trim($sql);
        }

        private function buildDelete($array_condition) {
            $sql = "";
            $fields = "";

            foreach($array_condition as $key => $value) {
                $fields .= $key . '? AND ';
            }

            $fields = (substr($fields, -4) == 'AND ') ? trim(substr($fields, 0, (strlen($fields) - 4))) {
                $fields;
            }

            $sql .= "DELETE FROM {$this->table_name} WHERE " . $fields; 

            return trim($sql);
        }

        public function insert($array_data) {
            try {
                $sql = $this->buildInsert($array_data);

                $stm = $this->Connection()->prepare($sql);

                $count = 1;

                foreach($array_data as $value) {
                    $stm->bindValue($count, $value);
                    $count++;
                }

                $return = $stm->execute();

                return $return;
            } catch (PDOException $e) {
                echo "Erro: " . $e->getMessage();
            }
        }

        public function update($array_data, $array_condition) {
            try {
                $sql = $this->buildUpdate($array_data, $array_condition);

                $stm = $this->Connection()->prepare($sql);

                $count = 1;

                foreach ($aray_data as $value) {
                    $stm->bindValue($count, $value);
                    $count++;
                }

                foreach($array_condition as $value) {
                    $stm->bindValue($count, $value);
                    $count++;
                }

                $return = $stm->execute();

                return $return;
            } catch (PDOException $e) {
                echo "Erro: " . $e->getMessage();
            }
        }

        public function delete($array_condition) {
            try {
                $sql = $this->buildDelete($array_condition);

                $stm = $this->Connection()->prepare($sql);

                $count = 1;

                foreach($array_condition as $value) {
                    $stm->bindValue($count, $value);
                    $count++;
                }

                $return = $stm->execute();

                return $return;
            } catch (PDOException $e) {
                echo "Erro: " . $e->getMessage();
            }
        }

        public function getSQLGeneric($sql, $array_params = null, $fetch_all = TRUE) {
            try {
                $stm = $this->Connection()->prepare($sql);

                if (!empty($array_params)) {
                    $count = 1;

                    foreach($array_params as $value) {
                        $stm->bindValue($count, $value);
                        $count++;
                    }
                }

                $stm->execute();

                if ($fetch_all) {
                    $data = $stm->fetchAll(PDO::FETCH_OBJ);
                } else {
                    $data = $stm->fetch(PDO::FETCH_OBJ);
                }

                return $data;
            } catch (PDOException $e) {
                echo "Erro : " . $e->getMessage();
            }
        }

    }