<?php
use Symfony\Component\Console\Application;

define('ROOT', dirname(__FILE__));
require_once(ROOT. '/components/Autoload.php');
require_once('vendor/autoload.php');

try {
    $api = new Application('Github parser application', 'v1');
    $api->add(new ApiController());
    $api->run();
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}