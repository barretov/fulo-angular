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
 * Controller of clients
 * @name userController
 * @author Victor Eduardo Barreto
 * @param {Object} $scope Scope
 * @param {Object} $http  Http
 * @param {Object} $routeParams Route
 * @param {Object} $location Locatioin
 * @date Apr 3, 2015
 * @version 1.0
 */
$app.controller('userController', function ($scope, $http, $routeParams, $location) {

    /**
     * variables for pagination.
     */
    $scope.currentPage = 0;
    $scope.pageSize = 10;

    /**
     * Method for control the pagination
     * @name numberOfPages
     * @author Victor Eduardo Barreto
     * @date Apr 3, 2015
     * @version 1.0
     */
    $scope.numberOfPages = function () {
        return Math.ceil($scope.rows.length / $scope.pageSize);
    };

    /**
     * Method for load all users
     * @name loadAll
     * @author Victor Eduardo Barreto
     * @date Apr 3, 2015
     * @version 1.0
     */
    $scope.loadAll = function () {

        $scope.showLoader();

        // adjust param.
        $param = $scope.configParam();

        $http.get($scope.server("/user"), {params: $param}).success(function ($return) {

            // verify return data.
            $scope.securityReponse($return);

            $scope.rows = $return;
            $scope.hideLoader();
        });
    };

    /**
     * Method for load one user
     * @name loadRow
     * @author Victor Eduardo Barreto
     * @date Apr 3, 2015
     * @version 1.0
     */
    $scope.loadRow = function () {

        if ($routeParams.id !== null) {

            $scope.showLoader();

            // adjust parameters and add origin data.
            $param = $.extend($scope.origin, {sq_usuario: $routeParams.id});
//            $param = $scope.configParam();

            $http.get($scope.server("/userEdit"), {params: $param}).success(function ($return) {

                // verify return data.
                $scope.securityReponse($return);

                $scope.row = $return;
                $scope.hideLoader();
            });
        } else {

            $scope.row = {};
            $scope.row.sq_pessoa = null;
            $scope.hideLoader();
        }

    };

    /**
     * Method for add user
     * @name addUser
     * @author Victor Eduardo Barreto
     * @date May 13, 2015
     * @version 1.0
     */
    $scope.addUser = function () {

        $scope.showLoader();

        // adjust parameters and add origin data.
        $param = $.extend($scope.origin, $scope.row);

        // validate passwords
        if ($scope.row.ds_re_senha === null || $scope.row.ds_senha === $scope.row.re_senha) {

            $http.post($scope.server("/addUser"), $param).success(function ($return) {

                // verify return data.
                $scope.securityReponse($return);

                // verify if email already exists.
                if ($return === "email-already") {

                    $scope.hideLoader();

                    $scope.showFlashmessage('alert-warning', $scope.constant.MSG0002);

                } else {

                    $scope.hideLoader();

                    $location.path("/user");

                    $scope.showFlashmessage("alert-success", $scope.constant.MSG0001);
                }
            });
        } else {
            $scope.showFlashmessage("alert-warning", $scope.constant.MSG0003);
        }
    };

    /**
     * Method for update user data access
     * @name userUpAccess
     * @author Victor Eduardo Barreto
     * @date May 9, 2015
     * @version 1.0
     */     $scope.userUpAccess = function () {

        $scope.showLoader();

        // validate passwords
        if ($scope.user.ds_re_senha === null || $scope.user.ds_senha === $scope.user.re_senha) {

            // adjust parameters and add origin data.
            $param = $.extend($scope.origin, $scope.user);

            $http.post($scope.server("/userUpAccess"), $param).success(function ($return) {

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
     * @name updateDataUser
     * @author Victor Eduardo Barreto
     * @param {String} $form Define what form sended data
     * @date May 09, 2015
     * @version 1.0
     */
    $scope.updateDataUser = function ($form) {

        $scope.showLoader();

        // verify what form data was arrive and define the param and destiny.
        switch ($form) {

            case "user":
                {
                    $param = $.extend($scope.origin, $scope.user);
                    $destination = "/";
                }
                break;

            case "admin":
                {
                    $param = $.extend($scope.origin, $scope.row);
                    $destination = "/user";
                }
                break;
        }

        $http.post($scope.server("/updateDataUser"), $param).success(function ($return) {

            // verify return data.
            $scope.securityReponse($return);

            // verify if email already exists.
            if ($return === "email-already") {

                $scope.hideLoader();

                $scope.showFlashmessage('alert-warning', $scope.constant.MSG0002);

            } else {

                $scope.hideLoader();

                $location.path($destination);

                $scope.showFlashmessage("alert-success", $scope.constant.MSG0001);
            }
        });

    };

    /**
     * Method for delete user
     * @name del
     * @author Victor Eduardo Barreto
     * @param {int} $sq_pessoa Identifier of person
     * @date Apr 12, 2015
     * @version 1.0
     */
    $scope.del = function ($sq_pessoa) {

        // adjust parameters and add origin data.
        $param = $.extend($scope.origin, {sq_pessoa: $sq_pessoa});

        $http.delete($scope.server("/userDel"), {params: $param}).success(function ($return) {

            // verify return data.
            $scope.securityReponse($return);

            if ($return) {

                // if result is true, remove the row in the screen.
                $('#' + $sq_pessoa).fadeOut('slow');
                $scope.showFlashmessage("alert-success", $scope.constant.MSG0001);
            }
        });
    };

    /**
     * Method for tranfer data for exclusion modal
     * @name fireModal
     * @author Victor Eduardo Barreto
     * @param {array} $row Data of user
     * @date Apr 12, 2015
     * @version 1.0
     */
    $scope.fireModal = function ($row) {

        // set the variable pessoa in the scope.
        $scope.pessoa = $row;
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
        if ($scope.row.ds_re_senha === null || $scope.row.ds_senha === $scope.row.re_senha) {

            // adjust parameters and add origin data.
            $param = $.extend($scope.origin, $scope.row);

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
     * Method for get user profile
     * @name getProfiles
     * @author Victor Eduardo Barreto
     * @date Jun 19, 2015
     * @version 1.0
     */
    $scope.getProfiles = function () {

        $http.get($scope.server("/getProfiles"), {params: $scope.origin}).success(function ($return) {

            // verify return data.
            $scope.securityReponse($return);

            $scope.profiles = $return;

        });
    };

});
