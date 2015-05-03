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
 * Set a better name for user business.
 */
use \fulo\business\UserBusiness as UserBusiness;

/**
 * Method for get users
 * @name get | user
 * @author Victor Eduardo Barreto
 * @return json Data of users
 * @date Apr 3, 2015
 * @version 1.0
 */
$app->get("/user", function () {

    try {

        $business = new UserBusiness();

        formatJson($business->getUsers());
    } catch (Exception $ex) {

        throw new $ex;
    }
});

/**
 * Method for get user
 * @name get | user
 * @author Victor Eduardo Barreto
 * @param int $sq_pessoa Identifier of user
 * @return json Data of user selected
 * @date Apr 3, 2015
 * @version 1.0
 */
$app->get("/user/:id", function ($sq_pessoa) {

    try {

        $business = new UserBusiness();

        formatJson($business->getUser($sq_pessoa));
    } catch (Exception $ex) {

        throw new $ex;
    }
});

/**
 * Method for save or update user
 * @name post | user
 * @author Victor Eduardo Barreto
 * @param json Data of user
 * @return bool Result of procedure
 * @date Apr 3, 2015
 * @version 1.0
 */
$app->post("/user/:id", function () {

    try {

        $business = new UserBusiness();

        $data = json_decode(\Slim\Slim::getInstance()->request()->getBody());

        # verify if is update.
        if ($data->isUpdate) {

            $result = $business->upUser($data);
        } else {

            $result = $business->addUser($data);
        }

        formatJson($result);
    } catch (Exception $ex) {

        throw new $ex;
    }
});

/**
 * Method for save or update user
 * @name delete | user
 * @author Victor Eduardo Barreto
 * @param int $sq_pessoa Identifier of user
 * @return bool Result of procedure
 * @date Apr 3, 2015
 * @version 1.0
 */
$app->delete("/user/:id", function ($sq_pessoa) {

    try {

        $business = new UserBusiness();

        formatJson($business->delUser($sq_pessoa));
    } catch (Exception $ex) {

        throw new $ex;
    }
});
