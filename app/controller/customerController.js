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
 * @param {Object} $rootScope rootScope
 * @param {Object} $services services
 * @date Jul 13, 2015
 * @version 1.0
 */
$app.controller('customerController', function ($scope, $http, $location, $rootScope, $services) {

    /**
     * Method for update user data access
     * @name upAccess
     * @author Victor Eduardo Barreto
     * @date May 9, 2015
     * @version 1.0
     */
    $scope.upAccess = function () {

        // validate passwords
        if ($rootScope.user.ds_password === $rootScope.user.re_password) {

            // adjust parameters and add origin data.
            $param = $scope.configParam($rootScope.user);

            $http.post($scope.server("/upAccess"), $param).success(function ($return) {

                // verify return data.
                $services.checkResponse($return)

                $location.path("/");

                $services.showFlashmessage("alert-success", $scope.constant.MSG0001);

            });

        } else {
            $services.showFlashmessage("alert-warning", $scope.constant.MSG0003);
        }
    };

    /**
     * Method for update data user
     * @name upCustomer
     * @author Victor Eduardo Barreto
     * @date May 09, 2015
     * @version 1.0
     */
    $scope.upCustomer = function () {

        $param = $scope.configParam($rootScope.user);

        $http.post($scope.server("/upUser"), $param).success(function ($return) {

            // verify return data.
            $services.checkResponse($return);

            // update data in session.
            sessionStorage.setItem('user', JSON.stringify($rootScope.user));

            $location.path("/");

            $services.showFlashmessage("alert-success", $scope.constant.MSG0001);
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

        // validate passwords
        if ($scope.customer.ds_password === $scope.customer.re_password) {

            // adjust parameters and add origin data.
            $param = $scope.configParam($scope.customer);

            $http.post($scope.server("/addUser"), $param).success(function ($return) {

                // verify return data.
                $services.checkResponse($return);

                // do the login.
                $scope.login($scope.customer);

                $location.path("/");

            });

        } else {
            $services.showFlashmessage("alert-warning", $scope.constant.MSG0003);
        }
    };

    /**
     * Method for up address
     * @name upAddress
     * @author Victor Eduardo Barreto
     * @param {type} $data Form data
     * @date Jul 29, 2015
     * @version 1.0
     */
    $scope.upAddress = function ($data) {

        // adjust parameters and add origin data.
        $param = $scope.configParam($rootScope.user);

        $http.post($scope.server("/upAddress"), $param).success(function ($return) {

            // verify return data.
            $services.checkResponse($return);

            // insert current data in session and user variable.
            sessionStorage.setItem('user', JSON.stringify($rootScope.user));

            // verify if request arrived of form cart.
            if ($data) {

                // if modal addres is open. close.
                $('#modalAddress').modal('hide');
            } else {

                $services.showFlashmessage('alert-success', $scope.constant.MSG0001);
                $location.path("/");
            }
        });
    };

    /**
     * Method for get data address by zip
     * @name getAddressByZip
     * @author Victor Eduardo Barreto
     * @date Jul 31, 2015
     * @version 1.0
     */
    $scope.getAddressByZip = function () {

        // adjust parameters and add origin data.
        $param = $scope.configParam({nu_postcode: $rootScope.user.nu_postcode});

        $http.get($scope.server("/getAddressByZip"), {params: $param}).success(function ($return) {

            // verify return data.
            $services.checkResponse($return);

            // update user data.
            $rootScope.user.ds_city = $return.cidade;
            $rootScope.user.ds_neighborhood = $return.bairro;
            $rootScope.user.ds_address = $return.log_tipo_logradouro + " " + $return.logradouro;
            $rootScope.user.ac_state = $return.uf;
        });
    };

    /**
     * Method for apear sigin customer
     * @name modalSigin
     * @author Victor Eduardo Barreto
     * @date Dec 31, 2015
     * @version 1.0
     */
    $scope.modalSigin = function () {

        // close modal.
        $('#modalLogin').modal('hide');
        $location.path("/customer/addCustomer");
    };

});
