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
 * Controller for login and logff in the system
 * @name loginController
 * @author Victor Eduardo Barreto
 * @date Apr 14, 2015
 * @version 1.0
 */
$app.controller('loginController', function ($scope, $http, $routeParams, $location) {

    /**
     * Method for login in the system
     * @name login
     * @author Victor Eduardo Barreto
     * @date Apr 12, 2015
     * @version 1.0
     */
    $scope.login = function () {

        $http.post($scope.server("/login"), $scope.row).success(function ($return) {

            if ($return) {

                $scope.showFlashmessage("alert-success", "Processo realizado com sucesso.");
                $('#ds_senha').val('');
                $('#modalLogin').modal('hide');

            } else {

                $scope.showFlashmessage("alert-danger", "Dados incorretos.");
                $('#ds_senha').val('');

            }

        });
    }

    /**
     * System of a down xD
     * @name logoff
     * @author Victor Eduardo Barreto
     * @date Apr 17, 2015
     * @version 1.0
     */
    $scope.logoff = function () {

        $http.post($scope.server("/logoff")).success(function ($return) {

            if ($return) {

                $scope.showFlashmessage("alert-success", "Processo realizado com sucesso.");
                $('#modalLogoff').modal('hide');

            } else {

                $scope.showFlashmessage("alert-danger", "Problemas encontrados.");
            }

        });
    }

    /**
     * Method for get session user data
     * @name session
     * @author Victor Eduardo Barreto
     * @date Apr 17, 2015
     * @version 1.0
     */
    $scope.session = function () {

        $http.get($scope.server("/session")).success(function ($return) {

            console.log($return);

            if ($return) {

                $scope.user = $return;
            }

        });
    }

});