<?php

# init the session.
//session_start();
# init composer autoload.
require_once './vendor/autoload.php';

# init the Slim Framework.
$app = new \Slim\Slim(array(
    'debug' => true
        )
);

$app->contentType("application/json");
$app->response->headers->set('Access-Control-Allow-Origin', '*');
$app->options('/(:name+)', function() use($app) {
    $response = $app->response();
    $app->response()->status(200);
    $response->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-authentication, X-client');
    $response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

$app->error(function ( Exception $e = null) use ($app) {
    echo '{"error":{"text":"' . $e->getMessage() . '"}}';
});

//Includes de configurações.
include_once './application/config/config.php';

# include de controllers.
include_once './application/fulo/controller/MasterController.php';
include_once './application/fulo/controller/user.php';
include_once './application/fulo/controller/login.php';
include_once './application/fulo/controller/domain.php';

$app->run();
