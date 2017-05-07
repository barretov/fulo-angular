<?php

/*
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

# start session.
// session_start();

# Init composer autoload
require_once './vendor/autoload.php';

/*
 * Init the Slim Framework
 * @var $app Variable to recive Slim Framework
 */
$app = new \Slim\Slim(['debug' => true]);

# Middlewares #
# Secret.
$app->add(new \fulo\middleware\Secret());

# ACL Middleware.
$app->add(new \fulo\middleware\Acl());

# Options.
$app->contentType("application/json");
$app->response->headers->set('Access-Control-Allow-Origin', '*');
$app->response->header('Access-Control-Allow-Methods', 'GET, POST');
// $app->response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');

//$app->options('/(:name+)', function() use($app) {
//    $response = $app->response();
//    $app->response()->status(200);
//    $response->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, X-authentication, X-client');
//    $response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
//});

$app->error(function (Exception $e = null) use ($app) {
	echo '{"error" : {"text" : "' . $e->getMessage() . '"}}';
});

/*
 * Include files of controller and middleware
 * @var $directories array Variable to recive url of files
 */
$directories = [
'./application/fulo/controller/',
];

# include files of directories.
foreach ($directories as $key => $directory) {

	$dir = \opendir($directory);

	while (($file = readdir($dir)) !== false) {
		if (strpos($file, ".php")) {
			include_once($directory . $file);
		}
	}
	closedir($dir);
}

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
