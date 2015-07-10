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
 * Domain Controller
 */

/**
 * Alias for master controller
 */
use fulo\controller\MasterController as MasterController;

/**
 * Method for get domain profiles
 * @name getProfiles
 * @author Victor Eduardo Barreto
 * @return json Data of users
 * @date Jun 19, 2015
 * @version 1.0
 */
$app->get("/getProfiles", function () {

    try {

        $business = MasterController::getDomainBusiness();

        $data = \Slim\Slim::getInstance()->request()->params();

        echo json_encode($business->getProfiles($data));
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for get constants for frontend
 * @name getConstants
 * @author Victor Eduardo Barreto
 * @return json Data of users
 * @date Jul 5, 2015
 * @version 1.0
 */
$app->get("/getConstants", function () {

    try {

        $business = MasterController::getDomainBusiness();

        $data = \Slim\Slim::getInstance()->request()->params();

        echo json_encode($business->getConstants($data));
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for get secret
 * @name getSecret
 * @author Victor Eduardo Barreto
 * @return json Secret
 * @date Jul 8, 2015
 * @version 1.0
 */
$app->get("/getSecret", function () {

    try {

        $business = MasterController::getDomainBusiness();

        echo json_encode($business->getSecret());
    } catch (Exception $ex) {

        throw $ex;
    }
});

