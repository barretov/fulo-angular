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
 * @name getUsers
 * @author Victor Eduardo Barreto
 * @return json User datas
 * @date Apr 3, 2015
 * @version 1.0
 */
$app->get("/getUsers", function () {

    try {

        $business = MasterController::getUserBusiness();

        echo json_encode($business->getUsers());
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for get user
 * @name getUser
 * @author Victor Eduardo Barreto
 * @param int $sq_pessoa Identifier of user
 * @return json User data selected
 * @date Apr 3, 2015
 * @version 1.0
 */
$app->get("/getUser", function () {

    try {

        $business = MasterController::getUserBusiness();

        echo json_encode($business->getUser());
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for add user
 * @name addUser
 * @author Victor Eduardo Barreto
 * @param json User data
 * @return bool Result of procedure
 * @date May 13, 2015
 * @version 1.0
 */
$app->post("/addUser", function () {

    try {

        $business = MasterController::getUserBusiness();

        echo json_encode($business->addUser());
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for update data of user
 * @name upUser
 * @author Victor Eduardo Barreto
 * @param json User data
 * @return bool Result of procedure
 * @date Apr 3, 2015
 * @version 1.0
 */
$app->post("/upUser", function () {

    try {

        $business = MasterController::getUserBusiness();

        echo json_encode($business->upUser());
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for update user data access
 * @name upAccess
 * @author Victor Eduardo Barreto
 * @param json Data access of user
 * @return bool Result of procedure
 * @date May 19, 2015
 * @version 1.0
 */
$app->post("/upAccess", function () {

    try {

        $business = MasterController::getUserBusiness();

        echo json_encode($business->upAccess());
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for inactivate user
 * @name inactivateUser
 * @author Victor Eduardo Barreto
 * @return bool Result of procedure
 * @date Jul 23, 2015
 * @version 1.0
 */
$app->post("/inactivateUser", function () {

    try {

        $business = MasterController::getUserBusiness();

        echo json_encode($business->inactivateUser());
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for activate user
 * @name activateUser
 * @author Victor Eduardo Barreto
 * @return bool Result of procedure
 * @date Jul 23, 2015
 * @version 1.0
 */
$app->post("/activateUser", function () {

    try {

        $business = MasterController::getUserBusiness();

        echo json_encode($business->activateUser());
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for update address
 * @name upAddress
 * @author Victor Eduardo Barreto
 * @return bool Result of procedure
 * @date Jul 29, 2015
 * @version 1.0
 */
$app->post("/upAddress", function () {

    try {

        $business = MasterController::getUserBusiness();

        echo json_encode($business->upAddress());
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for add address
 * @name addAddress
 * @author Victor Eduardo Barreto
 * @return bool Result of procedure
 * @date Jul 30, 2015
 * @version 1.0
 */
$app->post("/addAddress", function () {

    try {

        $business = MasterController::getUserBusiness();

        echo json_encode($business->addAddress());
    } catch (Exception $ex) {

        throw $ex;
    }
});
