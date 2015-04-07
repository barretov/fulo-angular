<?php

session_start();

require_once './vendor/autoload.php';

$app = new \Slim\Slim(array(
    'debug' => true
        )
);

$app->contentType("application/json");
$app->response->headers->set('Access-Control-Allow-Origin', '*');
$app->error(function ( Exception $e = null) use ($app) {
    echo '{"error":{"text":"' . $e->getMessage() . '"}}';
});

function formatJson ($obj)
{
    echo json_encode($obj);
}

//Includes de configurações.
include_once './application/config/config.php';

# include de controllers.
include_once './application/fulo/controller/clientes.php';

$app->run();
