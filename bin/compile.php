#!/usr/bin/env php
<?php

$rootDir   = realpath(__DIR__ . '/..');
$pharPath  = $rootDir . "/bin/pho.phar";
$pharFlags = FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME;

$stub = <<<EOD
#!/usr/bin/env php
<?php

Phar::mapPhar();

require_once 'phar://pho.phar/vendor/autoload.php';
require_once 'phar://pho.phar/src/pho.php';

__HALT_COMPILER();
EOD;

// create phar file
$phar = new Phar($pharPath, $pharFlags, 'pho.phar');
$phar->buildFromDirectory($rootDir, '/[src|vendor]\/.+\.php$/');
$phar->setStub($stub);
