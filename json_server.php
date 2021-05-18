<?php


if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once __DIR__ . '/service/JsonRPCServer/JsonRPCServer.php';

$jsonServer = new JsonRPCServer();
$jsonServer->run();
