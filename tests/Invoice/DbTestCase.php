<?php

declare(strict_types=1);

namespace Tests\Invoice;

use PDO;
use PHPUnit\Framework\TestCase;

class DbTestCase extends TestCase
{
    /**
     * @var PDO
     */
    protected $pdo;

    public function setUp()
    {
        if (!$this->pdo) {
            $this->pdo = new PDO(
                getenv('POSTGRES_DSN'),
                getenv('POSTGRES_USER'),
                getenv('POSTGRES_PASSWORD')
            );
        }

        $this->pdo->exec('DELETE FROM users');
    }
}
