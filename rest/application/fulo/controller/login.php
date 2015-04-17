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
 * Set a better name for login business.
 */
use \fulo\business\LoginBusiness as LoginBusiness;

/**
 * Method login in the system
 * @name post | login
 * @author Victor Eduardo Barreto
 * @param json Data of user
 * @return bool Result of procedure
 * @date Apr 17, 2015
 * @version 1.0
 */
$app->post("/login", function () {

    try {

        $business = new LoginBusiness();

        $data = json_decode(\Slim\Slim::getInstance()->request()->getBody());

        $result = $business->prepareLogin($data);

        formatJson($result);
    } catch (Exception $ex) {

        throw new $ex;
    }
});

/**
 * Method for logoff of system
 * @name post Logoff
 * @author Victor Eduardo Barreto
 * @return bool Result of procedure
 * @date Apr 17, 2015
 * @version 1.0
 */
$app->post("/logoff", function () {

    try {

        # verify if current ip is the same wich did the login.
        if ($_SESSION['user']['no_ip'] === $_SERVER['REMOTE_ADDR']) {

            unset($_SESSION['user']);

            formatJson(true);
        } else {

            formatJson(false);
        }
    } catch (Exception $ex) {

        throw new $ex;
    }
});

/**
 * Method for get session data
 * @name get | session
 * @author Victor Eduardo Barreto
 * @return json Data of users
 * @date Apr 3, 2015
 * @version 1.0
 */
$app->get("/session", function () {

    try {

        # verify if current ip is the same wich did the login.
        if (@$_SESSION['user']['no_ip'] === $_SERVER['REMOTE_ADDR']) {

            formatJson($_SESSION['user']);
        } else {

            formatJson(false);
        }
    } catch (Exception $ex) {

        throw new $ex;
    }
});
