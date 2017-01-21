<?php
require_once __DIR__ . '/src/Core/Core.php';
use Core\Core;

$core = new Core();
$core->findRoute();
$core->executeAction();