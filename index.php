<?php
require_once __DIR__ . '/Core/Core.php';

$core = new Core($_SERVER['REQUEST_URI']);
$core->findRoute();
$core->start();