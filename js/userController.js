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
 * Controller of clients
 * @name userController
 * @author Victor Eduardo Barreto
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
    }

    /**
     * Method for load all users
     * @name loadAll
     * @author Victor Eduardo Barreto
     * @date Apr 3, 2015
     * @version 1.0
     */
    $scope.loadAll = function () {

        $scope.showLoader();

        $http.get($scope.server("/user")).success(function (data) {

            $scope.rows = data;
            $scope.hideLoader();
        });
    }

    /**
     * Method for load one user
     * @name loadRow
     * @author Victor Eduardo Barreto
     * @date Apr 3, 2015
     * @version 1.0
     */
    $scope.loadRow = function () {

        if ($routeParams.id != null) {

            $scope.showLoader();

            $http.get($scope.server("/user/" + $routeParams.id)).success(function ($data) {

                $scope.row = $data;
                $scope.hideLoader();
            });

        } else {

            $scope.row = {}
            $scope.row.sq_pessoa = null;
            $scope.hideLoader();
        }

    }

    /**
     * Method for add user
     * @name addUser
     * @author Victor Eduardo Barreto
     * @date May 13, 2015
     * @version 1.0
     */
    $scope.addUser = function () {

        $scope.showLoader();

        // validate passwords
        if ($scope.row.ds_re_senha === null || $scope.row.ds_senha === $scope.row.re_senha) {

            $http.post($scope.server("/addUser/"), $scope.row).success(function ($data) {

                // verify if email already exists.
                if ($data === "email-already") {

                    $scope.hideLoader();

                    $scope.showFlashmessage('alert-warning', 'Este email já está cadastrado.');

                } else {

                    $scope.hideLoader();

                    $location.path("/user");

                    $scope.showFlashmessage("alert-success", "Processo realizado com sucesso.");
                }
            });

        } else {
            $scope.showFlashmessage("alert-warning", "A senha não confere.");
        }
    }

    /**
     * Method for update user data access
     * @name updateDataAccess
     * @author Victor Eduardo Barreto
     * @date May 9, 2015
     * @version 1.0
     */
    $scope.updateDataAccess = function () {

        $scope.showLoader();

        // validate passwords
        if ($scope.user.ds_re_senha === null || $scope.user.ds_senha === $scope.user.re_senha) {

            $http.post($scope.server("/userUpDataAccess/"), $scope.user).success(function ($data) {

                $scope.hideLoader();

                $location.path("/");

                $scope.showFlashmessage("alert-success", "Processo realizado com sucesso.");
            });

        } else {
            $scope.showFlashmessage("alert-warning", "A senha não confere.");
        }
    }

    /**
     * Method for update data user
     * @name updateDataUser
     * @author Victor Eduardo Barreto
     * @date May 09, 2015
     * @version 1.0
     */
    $scope.updateDataUser = function ($form) {

        $scope.showLoader();

        // verify what data arrive and define the destiny.
        switch ($form) {

            case "user":
            {
                $param = $scope.user;
                $destination = "/";
                break;
            }
            case "admin":
            {
                $param = $scope.row;
                $destination = "/user";
                break;
            }
        }

        $http.post($scope.server("/userUpData/"), $param).success(function ($data) {

            // verify if email already exists.
            if ($data === "email-already") {

                $scope.hideLoader();

                $scope.showFlashmessage('alert-warning', 'Este email já está cadastrado.');

            } else {

                $scope.hideLoader();

                $location.path($destination);

                $scope.showFlashmessage("alert-success", "Processo realizado com sucesso.");
            }
        });

    }

    /**
     * Method for delete user
     * @name del
     * @author Victor Eduardo Barreto
     * @param {int} $sq_pessoa Identifier of person
     * @date Apr 12, 2015
     * @version 1.0
     */
    $scope.del = function ($sq_pessoa) {

        $http.delete($scope.server("/user/" + $sq_pessoa)).success(function ($result) {

            if ($result) {

                // if result is true, remove the row in the screen.
                $('#' + $sq_pessoa).fadeOut('slow');
                $scope.showFlashmessage("alert-success", "Processo realizado com sucesso.");
            }
        });
    }

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
    }

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

            $http.post($scope.server("/addCustomer/"), $scope.row).success(function ($data) {

                // verify if email already exists.
                if ($data === "email-already") {

                    $scope.hideLoader();

                    $scope.showFlashmessage('alert-warning', 'Este email já está cadastrado.');

                } else {

                    $scope.hideLoader();

                    $location.path("/");

                    $scope.showFlashmessage("alert-success", "Processo realizado com sucesso.");
                }
            });

        } else {
            $scope.showFlashmessage("alert-warning", "A senha não confere.");
        }
    }

});
