<?php

declare(strict_types=1);

namespace Tests\Invoice;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use PDO;
use PHPUnit\Framework\TestCase;

class DoctrineTestCase extends TestCase
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var PDO
     */
    protected $pdo;

    public function setUp()
    {
        $config = [
            'db_user' => getenv('POSTGRES_USER'),
            'db_password' => getenv('POSTGRES_PASSWORD'),
            'db_database' => getenv('POSTGRES_DB'),
            'db_host' => getenv('POSTGRES_HOST'),
        ];

        $paths = array(__DIR__ . "/../../src/Invoice/Adapter/Doctrine/config/mapping");
        $isDevMode = true;

        $dbParams = array(
            'driver'   => 'pdo_pgsql',
            'user'     => $config['db_user'],
            'password' => $config['db_password'],
            'dbname'   => $config['db_database'],
            'host'   => $config['db_host'],
        );

        $config = Setup::createYAMLMetadataConfiguration($paths, $isDevMode);
        $this->em = EntityManager::create($dbParams, $config);

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
