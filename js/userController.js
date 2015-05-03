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
    $scope.pageSize = 5;

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

                console.log($data);

                $scope.row = $data;
                $scope.row.isUpdate = true;
                $scope.hideLoader();
            });
        }
        else
        {
            $scope.row = {}
            $scope.row.sq_pessoa = null;
            $scope.row.isUpdate = false;
            $scope.hideLoader();
        }
    }

    /**
     * Method for save user
     * @name save
     * @author Victor Eduardo Barreto
     * @date Apr 3, 2015
     * @version 1.0
     */
    $scope.save = function () {

        $scope.showLoader();

        // validate passwords
        if ($scope.row.ds_senha === $scope.row.re_senha) {

            $http.post($scope.server("/user/" + $routeParams.id), $scope.row).success(function ($data) {

                console.log($data);

                // verify if email already exists.
                if ($data === "email-already") {

                    $scope.hideLoader();

                    $scope.showFlashmessage('alert-warning', 'Este email já está cadastrado.');

                } else {

                    $scope.row.isUpdate = true;

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

});