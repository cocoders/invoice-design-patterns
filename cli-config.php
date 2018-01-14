<?php include './vendor/autoload.php' ?>
<?php include './public/doctrine.php' ?>
<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

return ConsoleRunner::createHelperSet($entityManager);
