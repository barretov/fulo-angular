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

use fulo\controller\MasterController as MasterController;

/**
 * Method for add item in wish list
 * @name addWishList
 * @author Victor Eduardo Barreto
 * @var $app object Slim instance
 * @return bool Result of procedure
 * @date Alg 28, 2015
 * @version 1.0
 */
$app->post("/addWishList", function () {

    try {

        $business = MasterController::getPurchaseBusiness();

        echo json_encode($business->addWishList());
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for get items of wish list
 * @name getWishList
 * @author Victor Eduardo Barreto
 * @var $app object Slim instance
 * @return object Data of user wishlist
 * @date Alg 29, 2015
 * @version 1.0
 */
$app->get("/getWishList", function () {

    try {

        $business = MasterController::getPurchaseBusiness();

        echo json_encode($business->getWishList());
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for del items of wish list
 * @name delWishList
 * @author Victor Eduardo Barreto
 * @var $app object Slim instance
 * @return object Data of user wishlist
 * @date Alg 31, 2015
 * @version 1.0
 */
$app->post("/delWishList", function () {

    try {

        $business = MasterController::getPurchaseBusiness();

        echo json_encode($business->delWishList());
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for get fare value
 * @name getFareValue
 * @author Victor Eduardo Barreto
 * @var $app object Slim instance
 * @return object Data of product type
 * @date Oct 10, 2015
 * @version 1.0
 */
$app->post("/getFareValue", function () {

    try {
        $business = MasterController::getPurchaseBusiness();
        echo json_encode($business->getFareValue());
    } catch (Exception $ex) {
        throw $ex;
    }
});

/**
 * Method for buy
 * @name buy
 * @author Victor Eduardo Barreto
 * @var $app object Slim instance
 * @return object Data of product type
 * @date Dec 30, 2015
 * @version 1.0
 */
$app->post("/buy", function () {

    try {

        $business = MasterController::getPurchaseBusiness();

        echo json_encode($business->buy());
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for track order delivery
 * @name tracker
 * @author Victor Eduardo Barreto
 * @return object Products of orders
 * @date Jan 21, 2016
 * @version 1.0
 */
$app->get("/tracker", function () {

    try {

        $business = MasterController::getPurchaseBusiness();

        echo json_encode($business->tracker());
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 * Method for recive a paypal response
 * @name paypalResponse
 * @author Victor Eduardo Barreto
 * @return object Response of procedure
 * @date Feb 3, 2016
 * @version 1.0
 */
$app->get("/paypalResponse", function () {

    try {

        $business = MasterController::getPurchaseBusiness();

        echo json_encode($business->paypalResponse());
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 *
 */
$app->post("/addTracker", function () {

    try {

        $business = MasterController::getPurchaseBusiness();

        echo json_encode($business->addTracker());
    } catch (Exception $ex) {

        throw $ex;
    }
});

/**
 *Function for freeze order
 */
$app->post("/freezeOrder", function () {

    try {

        $business = MasterController::getPurchaseBusiness();
        echo json_encode($business->freezeOrder());
    } catch (Exception $ex) {
        throw $ex;
    }
});

/**
 * Funcrion for cancel order
 */
$app->post("/cancelOrder", function() {

	try {
		$business = MasterController::getPurchaseBusiness();
		echo json_encode($business->cancelOrder());
	} catch (Exception $ex) {
		throw $ex;
	}
});

/**
 * Funcrion for refund order
 */
$app->post("/refundOrder", function() {

	try {
		$business = MasterController::getPurchaseBusiness();
		echo json_encode($business->refundOrder());
	} catch (Exception $ex) {
		throw $ex;
	}
});
