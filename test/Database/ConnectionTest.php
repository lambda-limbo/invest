<?php declare(strict_types=1);

use Invest\Database\Connection;
use PHPUnit\Framework\TestCase;

final class ConnectionTest extends TestCase {
    /**
     * Tests if the database returns an instance of the connection
     */
    public function testDatabaseConnection() {
        $c = Connection::get();

        $this->assertNotNull($c);

        Connection::close();
    }

    /**
     * Tests if the singleton returns the same instance or not
     */
    public function testSingletonInstance() {
        $c0 = Connection::get();
        $c1 = Connection::get();

        $this->assertEquals($c0, $c1);

        Connection::close();
    }
}
