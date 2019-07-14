<?php declare(strict_types=1);

namespace Invest\Models;

interface Entity {
    /**
     * Fills the entity with data from the database. 
     * 
     * @param pk The primary key of the entity inside the database.
     */
    public function get(string $pk) : bool;

    /**
     * Saves the entity in the database
     */
    public function save() : bool;

    /**
     * Updates the data of the entity in the database 
     */
    public function update() : bool;

    /**
     * Deletes the entity from the database
     */
    public function delete() : bool;
}