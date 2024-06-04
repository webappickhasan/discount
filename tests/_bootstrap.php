<?php

// This is global bootstrap for autoloading

use tad\FunctionMocker\FunctionMocker;

require_once __DIR__ . '/../vendor/autoload.php';

require_once '_support/extra.php';

FunctionMocker::init();
