#!/usr/bin/env bash

bin/phpspec run -fpretty
bin/phpunit --bootstrap vendor/autoload.php tests