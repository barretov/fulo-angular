<?php

/*
 * Copyright (C) 2014
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

/**
 * User Controller
 */
use fulo\controller\MasterController as MasterController;

/**
 * Method for get users
 * @name user
 * @author Victor Eduardo Barreto
 * @return json Data of users
 * @date Apr 3, 2015
 * @version 1.0
 */
$app->get("/user", function () {

    try {

        $business = MasterController::getUserBusiness();

        $data = \Slim\Slim::getInstance()->request()->params();

        echo json_encode($business->getUsers($data));
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for get user
 * @name userEdit
 * @author Victor Eduardo Barreto
 * @param int $sq_pessoa Identifier of user
 * @return json Data of user selected
 * @date Apr 3, 2015
 * @version 1.0
 */
$app->get("/userEdit", function () {

    try {

        $business = MasterController::getUserBusiness();

        $data = \Slim\Slim::getInstance()->request()->params();

        echo json_encode($business->getUser($data));
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for add user
 * @name addUser
 * @author Victor Eduardo Barreto
 * @param json Data of user
 * @return bool Result of procedure
 * @date May 13, 2015
 * @version 1.0
 */
$app->post("/addUser", function () {

    try {

        $business = MasterController::getUserBusiness();

        $data = json_decode(\Slim\Slim::getInstance()->request()->getBody());

        echo json_encode($business->addUser($data));
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for save or update user
 * @name user/:id
 * @author Victor Eduardo Barreto
 * @param int $sq_pessoa Identifier of user
 * @return bool Result of procedure
 * @date Apr 3, 2015
 * @version 1.0
 */
$app->delete("/userDel", function () {

    try {

        $business = MasterController::getUserBusiness();

        $data = \Slim\Slim::getInstance()->request()->params();

        echo json_encode($business->delUser($data));
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for update data user
 * @name updateDataUser
 * @author Victor Eduardo Barreto
 * @param json Data of user
 * @return bool Result of procedure
 * @date Apr 3, 2015
 * @version 1.0
 */
$app->post("/updateDataUser", function () {

    try {

        $business = MasterController::getUserBusiness();

        $data = json_decode(\Slim\Slim::getInstance()->request()->getBody());

        echo json_encode($business->upUser($data));
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for update user data access
 * @name userUpAccess
 * @author Victor Eduardo Barreto
 * @param json Data access of user
 * @return bool Result of procedure
 * @date May 19, 2015
 * @version 1.0
 */
$app->post("/userUpAccess", function () {

    try {

        $business = MasterController::getUserBusiness();

        $data = json_decode(\Slim\Slim::getInstance()->request()->getBody());

        echo json_encode($business->upDataAccesss($data));
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for add customer
 * @name addCustomer
 * @author Victor Eduardo Barreto
 * @param json Data of user
 * @return bool Result of procedure
 * @date Jun 10, 2015
 * @version 1.0
 */
$app->post("/addCustomer/", function () {

    try {

        $business = MasterController::getUserBusiness();

        $data = json_decode(\Slim\Slim::getInstance()->request()->getBody());

        echo json_encode($business->addCustomer($data));
    } catch (Exception $ex) {

        throw $ex;
    }
});
