<?php

declare(strict_types=1);

use src\Config\EnvLoader;
use PHPUnit\Framework\TestCase;

class EnvLoaderTest extends TestCase
{
    public function testThatEnvLoaderReturnsEnvParamCorrectly()
    {
        EnvLoader::load();
        $this->assertEquals( 'main', $_ENV['DB_NAME']);
    }
}