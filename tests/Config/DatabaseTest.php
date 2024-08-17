<?php

declare(strict_types=1);


use PHPUnit\Framework\TestCase;
use src\Config\Database;
use src\Config\EnvLoader;

class DatabaseTest extends TestCase
{
   public static function setUpBeforeClass(): void
   {
       EnvLoader::load();
       parent::setUpBeforeClass();
   }

    public function testThatConnectionWithDbWorksCorrectlyWithValidCredentials(): void
    {
        $db = new Database();
        $this->assertNotNull($db->connection());
    }

    public function testThatConnectionFailsWithInvalidCredentials(): void
    {
        $host = 'localhost';
        $port = 5432;
        $dbname = 'main';
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
        self::expectException(PDOException::class);
        $db = new PDO($dsn, 'root', 'admin');
    }
}