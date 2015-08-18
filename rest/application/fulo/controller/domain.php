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

        echo json_encode($business->getProfiles());
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for get secret and constants
 * @name getBasic
 * @author Victor Eduardo Barreto
 * @return json Secret and constants
 * @date Jul 8, 2015
 * @version 1.0
 */
$app->get("/getBasic", function () {

    try {

        $business = MasterController::getDomainBusiness();

        echo json_encode($business->getBasic());
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for get postal data by zip code
 * @name getAddressByZip
 * @author Victor Eduardo Barreto
 * @return Object Data address
 * @date Jul 31, 2015
 * @version 1.0
 */
$app->get("/getAddressByZip", function () {

    try {

        $business = MasterController::getDomainBusiness();

        echo json_encode($business->getAddressByZip());
    } catch (Exception $ex) {

        throw $ex;
    }
});
