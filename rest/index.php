<?php

# set cookies config.
ini_set('session.use_cookies', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.use_trans_sid', '0');

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
include_once './application/fulo/controller/login.php';

$app->run();
