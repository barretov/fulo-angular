<?php

use \fulo\business\UserBusiness as UserBusiness;

/**
 * get users.
 */
$app->get("/clientes", function () {

    $business = new UserBusiness();

    formatJson($business->getUsers());
});

$app->get("/clientes/:id", function ($sq_pessoa) {

    $business = new UserBusiness();

    formatJson($business->getUser($sq_pessoa));
});

$app->post("/clientes/:id", function ($id) {

    $business = new UserBusiness();

    $result = $business->addUser(json_decode(\Slim\Slim::getInstance()->request()->getBody()));

    formatJson($result);
});

$app->delete("/clientes/:id", function ($sq_pessoa) {

    $business = new UserBusiness();

    formatJson($business->delUser($sq_pessoa));
});
