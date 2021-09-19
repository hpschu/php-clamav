<?php
declare(strict_types=1);

// Log errors to /dev/stdout for debugging
ini_set('log_errors', '1');
ini_set('error_log', '/dev/stdout');

$dir = __DIR__ . '/src';
require_once __DIR__ . '/bootstrap.php';
bootstrap($dir);

\PhpClamav\App::init();