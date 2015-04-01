<?php

session_start();

require_once 'config.php';
require_once './Conexao.php';

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim(array(
    'debug' => true
        ));

$app->contentType("application/json");
$app->response->headers->set('Access-Control-Allow-Origin', '*');

$app->error(function ( Exception $e = null) use ($app) {
    echo '{"error":{"text":"' . $e->getMessage() . '"}}';
});

function formatJson ($obj)
{
    echo json_encode($obj);
}

//Includes
include("clientes.php");
include("employee.php");

$app->run();
