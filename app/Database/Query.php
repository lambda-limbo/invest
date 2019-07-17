<?php declare(strict_types=1);

namespace Invest\Database;

use Invest\Database\Connection;
use Invest\Exceptions\DatabaseException;

use \PDO;
use \PDOException;

class Query {
    private $query;
    private $stmt;

    /**
     * Constructs an statement and prepare it.
     */
    public function __construct(string $query) {
        $this->query = $query;    
        $this->stmt = Connection::get()->prepare($this->query);
    }

    /**
     * Binds the array values to the statement created within this class. Only
     * here because of backwards compability.
     * 
     * @param $array Array of parameters to be bounded in the query
     */
    public function bind($parameters = array()) : void {
        // $array(
        //    ':LOGIN' => 'some_login'
        //    ':PASSWORD' => ''
        foreach ($parameters as $key => $value) {
            $this->stmt->bindParam($key, $value);
        }
    }

    /**
     * Executes the query
     */
    public function execute($parameters = array()) : bool {
        try {
            if (!empty($parameters)) {
                return $this->stmt->execute($parameters);
            }
            
            return $this->stmt->execute();
        } catch (PDOException $e) {
            $m = $e->getMessage();
            throw new DatabaseException("Error executing the query $this->query.\n\n:: $m\n");
        }
    }

    /**
     * 
     */
    public function fetch(int $style = PDO::FETCH_ASSOC) {
        return $this->stmt->fetch($style);
    }

    /**
     * 
     */
    public function fetchAll(int $style = PDO::FETCH_ASSOC) {
        return $this->stmt->fetchAll($style);
    }
}