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

/* global $app */

/**
 * Controller of customers
 * @name customerController
 * @author Victor Eduardo Barreto
 * @param {Object} $scope Scope
 * @param {Object} $http  Http
 * @param {Object} $location Locatioin
 * @date Jul 13, 2015
 * @version 1.0
 */
$app.controller('customerController', function ($scope, $http, $location) {

    /**
     * Method for update user data access
     * @name upDataAccesss
     * @author Victor Eduardo Barreto
     * @date May 9, 2015
     * @version 1.0
     */
    $scope.upDataAccesss = function () {

        $scope.showLoader();

        // validate passwords
        if ($scope.user.ds_re_password === null || $scope.user.ds_password === $scope.user.re_password) {

            // adjust parameters and add origin data.
            $param = $scope.configParam($scope.user);

            $http.post($scope.server("/upDataAccesss"), $param).success(function ($return) {

                // verify return data.
                $scope.securityReponse($return);

                $scope.hideLoader();

                $location.path("/");

                $scope.showFlashmessage("alert-success", $scope.constant.MSG0001);
            });

        } else {
            $scope.showFlashmessage("alert-warning", $scope.constant.MSG0003);
        }
    };

    /**
     * Method for update data user
     * @name upUser
     * @author Victor Eduardo Barreto
     * @date May 09, 2015
     * @version 1.0
     */
    $scope.upUser = function () {

        $scope.showLoader();

        $param = $scope.configParam($scope.user);

        $http.post($scope.server("/upUser"), $param).success(function ($return) {

            // verify return data.
            $scope.securityReponse($return);

            // verify if email already exists.
            if ($return === "email-already") {

                $scope.hideLoader();

                $scope.showFlashmessage('alert-warning', $scope.constant.MSG0002);

            } else {

                // update data in session.
                sessionStorage.setItem('user', JSON.stringify($scope.user));

                $scope.hideLoader();

                $location.path("/");

                $scope.showFlashmessage("alert-success", $scope.constant.MSG0001);
            }
        });
    };

    /**
     * Method for add customer
     * @name addCustomer
     * @author Victor Eduardo Barreto
     * @date Jun 10, 2015
     * @version 1.0
     */
    $scope.addCustomer = function () {

        $scope.showLoader();

        // validate passwords
        if ($scope.row.ds_re_password === null || $scope.row.ds_password === $scope.row.re_password) {

            // adjust parameters and add origin data.
            $param = $scope.configParam($scope.row);

            $http.post($scope.server("/addCustomer/"), $param).success(function ($return) {

                // verify return data.
                $scope.securityReponse($return);

                // verify if email already exists.
                if ($return === "email-already") {

                    $scope.hideLoader();

                    $scope.showFlashmessage('alert-warning', $scope.constant.MSG0002);

                } else {

                    $scope.hideLoader();

                    // do the login.
                    $scope.login($scope.row);

                    $location.path("/");
                }
            });

        } else {
            $scope.showFlashmessage("alert-warning", $scope.constant.MSG0003);
        }
    };

    /**
     * Method for up address
     * @name upAddress
     * @author Victor Eduardo Barreto
     * @date Jul 29, 2015
     * @version 1.0
     */
    $scope.upAddress = function () {

        $scope.showLoader();

        // adjust parameters and add origin data.
        $param = $scope.configParam($scope.user);

        $http.post($scope.server("/upAddress"), $param).success(function ($return) {

            // verify return data.
            $scope.securityReponse($return);

            // insert current data in session and user variable.
            sessionStorage.setItem('user', JSON.stringify($scope.user));
            $scope.hideLoader();
            $scope.showFlashmessage('alert-success', $scope.constant.MSG0001);
            $location.path("/");
        });
    };

    /**
     * Method for add address
     * @name addAddress
     * @author Victor Eduardo Barreto
     * @date Jul 30, 2015
     * @version 1.0
     */
    $scope.addAddress = function () {

        $scope.showLoader();

        // adjust parameters and add origin data.
        $param = $scope.configParam($scope.user);

        $http.post($scope.server("/addAddress"), $param).success(function ($return) {

            // verify return data.
            $scope.securityReponse($return);

            $scope.hideLoader();
            sessionStorage.setItem('user', JSON.stringify($scope.user));
            $scope.showFlashmessage('alert-success', $scope.constant.MSG0001);
            $location.path("/");
        });
    };
});
