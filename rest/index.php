<?php

/*
 * Init composer autoload
 */
require_once './vendor/autoload.php';

/*
 * Init the Slim Framework
 * @var $app Variable to recive Slim Framework
 */
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

/*
 * Include controllers
 * @var $directoryController Variable to recive files of controller directory
 */
$directoryController = \opendir("./application/fulo/controller/");

while (($file = readdir($directoryController)) !== false) {

    if (strpos($file, ".php")) {

        include_once("./application/fulo/controller/" . $file);
    }
}

closedir($directoryController);

/*
 * Generate constants
 * var $constant Variable to recive constants
 */
$constant = parse_ini_file('./application/config/constants.ini', true);

# merge constants for backend.
$constants = array_merge($constant['backend'], $constant['both']);

# set constants.
foreach ($constants as $key => $value) {
    define($key, $value);
}

$app->run();
