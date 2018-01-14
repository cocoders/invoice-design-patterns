<?php

declare(strict_types=1);

namespace Tests\Invoice;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Invoice\Adapter\Doctrine\Domain\User;
use Invoice\Adapter\Doctrine\Types\EmailType;
use PHPUnit\Framework\TestCase;

class DoctrineTestCase extends TestCase
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function setUp()
    {
        if (!$this->em) {
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
            if (!Type::hasType(EmailType::EMAIL_TYPE)) {
                Type::addType(EmailType::EMAIL_TYPE, EmailType::class);
            }
        }

        $tool = new SchemaTool($this->em);
        $classes = [
            $this->em->getClassMetadata(User::class)
        ];
        $tool->dropSchema($classes);
        $tool->createSchema($classes);
    }
}
