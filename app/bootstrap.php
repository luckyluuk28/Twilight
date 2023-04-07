<?php

// Load environment variables from .env file
function load_env($path) {
  $env = [];
  if (file_exists($path)) {
    $contents = file_get_contents($path);
    $lines = explode("\n", $contents);
    foreach ($lines as $line) {
      if ($line) {
        list($name, $value) = explode("=", $line, 2);
        $env[$name] = $value;
      }
    }
  }
  return $env;
}

$env = load_env(__DIR__ . '/.env');
foreach ($env as $name => $value) {
  putenv("$name=$value");
  $_ENV[$name] = $value;
}

// Other configuration and initialization code goes here
require_once __DIR__ . '/Http/router.php';