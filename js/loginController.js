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
 * Controller for login and logff in the system
 * @name loginController
 * @author Victor Eduardo Barreto
 * @param {object} $scope
 * @param {object} $rootScope
 * @param {object} $http
 * @param {string} $location
 * @date Apr 14, 2015
 * @version 1.0
 */
$app.controller('loginController', function ($scope, $rootScope, $http, $location) {

    /**
     * Method for login in the system
     * @name login
     * @author Victor Eduardo Barreto
     * @param {object} $param Data for login user
     * @date Apr 12, 2015
     * @version 1.0
     */
    $rootScope.login = function ($param) {

        // define $scope.row as $param, for get param of function or by variable $scope.row.
        $param = ($scope.row) ? $scope.configParam($scope.row) : $scope.configParam($param);

        $http.post($scope.server("/login"), $param).success(function ($return) {

            if ($return) {

                // if exists remove data user of the session browser.
                sessionStorage.removeItem('user');
                sessionStorage.removeItem('origin');

                // set user data in the session.
                sessionStorage.setItem('user', JSON.stringify($return));
                sessionStorage.setItem('origin', $return.origin);

                // set user data in the $rootScope variable.
                $rootScope.user = JSON.parse(sessionStorage.getItem('user'));
                $rootScope.origin = sessionStorage.getItem('origin');

                $scope.showFlashmessage("alert-success", "Processo realizado com sucesso.");

                // clear pass field.
                $('#modalLogin').modal('hide');
                $('#ds_password').val('');

                // if customer account was inactivated, show modal to inform.
                if ($rootScope.user.sq_status_user == $scope.constant.STATUS_INACTIVE) {

                    // apear modal to inform the customer.
                    $('#modalReactiveCustomer').modal('show');
                }

            } else {

                $scope.showFlashmessage("alert-danger", "Dados incorretos.");

                // clear pass field.
                $('#ds_password').val('');
            }
        });
    };

    /**
     * System of a down xD
     * @name logoff
     * @author Victor Eduardo Barreto
     * @date Apr 17, 2015
     * @version 1.0
     */
    $rootScope.logoff = function () {

        // adjust parameters and add origin data.
        $param = $scope.configParam($scope.user);

        $http.post($scope.server("/logoff"), $param).success(function ($return) {

            // verify return data.
            $scope.checkResponse($return);

            // remove user data of the session.
            $rootScope.user = sessionStorage.removeItem('user');
            $rootScope.origin = sessionStorage.removeItem('origin');

            $scope.showFlashmessage("alert-success", "Processo realizado com sucesso.");
            $('#modalLogoff').modal('hide');
            $location.path("/");

        });
    };

    /**
     * Get data of loged user in the session browser and set in the ariable $rootScope.user.
     */
    $rootScope.user = JSON.parse(sessionStorage.getItem('user'));
    $rootScope.origin = sessionStorage.getItem('origin');
});