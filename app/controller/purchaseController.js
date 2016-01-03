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

/* global $app, angular */

/**
 * Controller of purchase
 * @name purchaseController
 * @author Victor Eduardo Barreto
 * @param {Object} $scope Scope
 * @param {Object} $http  Http
 * @param {Object} $location Locatioin
 * @param {Object} $rootScope root scope
 * @param {Object} $routeParams route
 * @date Alg 18, 2015
 * @version 1.0
 */
$app.controller('purchaseController', function ($scope, $rootScope, $http, $location, $routeParams) {

    /**
     * Method for add item in wish list
     * @name addWishList
     * @author Victor Eduardo Barreto
     * @param {int} $sq_product Product identifier
     * @date Alg 28, 2015
     * @version 1.0
     */
    $scope.addWishList = function ($sq_product) {

        // verify if user is loged.
        if (!$rootScope.user) {

            $('#modalLogin').modal('show');
            $scope.showFlashmessage("alert-warning", $scope.constant.MSG0007);
        } else {

            $param = $scope.configParam({sq_product: $sq_product});

            $http.post($scope.server("/addWishList"), $param).success(function ($return) {

                // verify return data.
                $scope.checkResponse($return);

                // ajust quantity of itens and session.
                $rootScope.user.nu_wishlist++;
                sessionStorage.setItem('user', JSON.stringify($rootScope.user));

                $scope.showFlashmessage("alert-success", $scope.constant.MSG0001);
            });
        }
    };

    /**
     * Method for get items of wish list
     * @name getWishList
     * @author Victor Eduardo Barreto
     * @date Alg 29, 2015
     * @version 1.0
     */
    $scope.getWishList = function () {

        $param = $scope.configParam();

        $http.get($scope.server("/getWishList"), {params: $param}).success(function ($return) {

            // verify return data.
            $scope.checkResponse($return);

            $scope.rows = $return;
        });
    };

    /**
     * Method for del items of wish list
     * @name delWishList
     * @author Victor Eduardo Barreto
     * @param {int} $sq_product Product identifier
     * @date Alg 31, 2015
     * @version 1.0
     */
    $scope.delWishList = function ($sq_product) {

        $param = $scope.configParam({sq_product: $sq_product});

        $http.post($scope.server("/delWishList"), $param).success(function ($return) {

            // verify return data.
            $scope.checkResponse($return);

            // ajust quantity of itens and session.
            $rootScope.user.nu_wishlist--;
            sessionStorage.setItem('user', JSON.stringify($rootScope.user));

            // remove row in list.
            $('#' + $sq_product).fadeOut();
        });
    };

    /**
     * Method for add item in cart
     * @name addCart
     * @author Victor Eduardo Barreto
     * @param {object} $product Product data
     * @date Alg 31, 2015
     * @version 1.0
     */
    $scope.addCart = function ($product) {

        // variable to use as flag, to continue the flow.
        var $continue = true;

        // verify if exists session data.
        if (sessionStorage.getItem('cart')) {

            // verify if already exists the new product in the cart.
            angular.forEach($scope.cart, function ($key) {

                if ($product.sq_product === $key.sq_product) {

                    $scope.showFlashmessage("alert-warning", $scope.constant.MSG0006);
                    $continue = false;
                }
            });
        }

        if ($continue) {

            // insert product in variable cart.
            $rootScope.cart.push($product);

            // Save data cart in session.
            this.saveCartSession();

            // update total value.
            this.updateTotal();

            $scope.showFlashmessage("alert-success", $scope.constant.MSG0001);
        }
    };

    /**
     * Method for del items of cart
     * @name delCart
     * @author Victor Eduardo Barreto
     * @param {int} $sq_product Product identifier
     * @date Alg 31, 2015
     * @version 1.0
     */
    $scope.delCart = function ($sq_product) {

        var $aux = 0;

        angular.forEach($rootScope.cart, function ($key) {

            if ($sq_product === $key.sq_product) {

                $rootScope.cart.splice($aux, 1);
            }

            $aux++;
        });

        // insert cart in session.
        this.saveCartSession();

        // update total value.
        this.updateTotal(true);

        $scope.showFlashmessage("alert-success", $scope.constant.MSG0001);
    };

    /**
     * Method for update total value products in cart
     * @name updateTotal
     * @author Victor Eduardo Barreto
     * @param bool $fare This flag tell if get fare value is necessary
     * @date Sep 1, 2015
     * @version 1.0
     */
    $scope.updateTotal = function ($fare) {

        $rootScope.cart.nu_total = 0;

        // insert cart in session.
        sessionStorage.setItem('cart', JSON.stringify($rootScope.cart));

        $scope.$watch($rootScope.cart, function () {

            // sum all products.
            angular.forEach($rootScope.cart, function ($key) {

                // multipli value per quantity.
                var $value = parseFloat($key.nu_value) * parseFloat($key.nu_quantity_buy);

                // sum all products.
                $rootScope.cart.nu_total = parseFloat($rootScope.cart.nu_total) + parseFloat($value);
            });

            if ($rootScope.cart.nu_farevalue) {

                // add value of fare value.
                $rootScope.cart.nu_total = parseFloat($rootScope.cart.nu_total) + parseFloat($rootScope.cart.nu_farevalue);
            }

            $rootScope.cart.nu_total = $rootScope.cart.nu_total.toFixed(2);
        });

        // if arrive tru in $flag, update fare value.
        $fare ? this.getFareValue() : '';
    };

    /**
     * Method for get fare value
     * @name getFareValue
     * @author Victor Eduardo Barreto
     * @param {int} $postcode Postcode number to get fare value
     * @date Sep 17, 2015
     * @version 1.0
     */
    $scope.getFareValue = function ($postcode) {

        // verify if user is loged.
        if ($rootScope.user) {

            // set postcode of user data.
            if ($rootScope.user.nu_postcode) {

                // init variables.
                $scope.row = {};
                $scope.row.product = [];
                $scope.row.nu_postcode = $rootScope.user.nu_postcode;
            }
        }

        // if arrive a new postcode, send to get fare value;
        if ($postcode) {

            $scope.row.nu_postcode = $postcode;
        }

        // init variable.
        $scope.row.product = [];

        angular.forEach($rootScope.cart, function ($key) {

            // if don't have quantity set number one.
            if (!$key.nu_quantity_buy) {

                $key.nu_quantity_buy = 1;
            }

            $scope.row.product.push({sq_product: $key.sq_product, nu_quantity_buy: $key.nu_quantity_buy});
        });

        $param = $scope.configParam($scope.row);

        // if dont have product in cart, dont send fare value request;
        if ($scope.row.product.length) {

            $http.post($scope.server("/getFareValue"), $param).success(function ($return) {

                // verify return data.
                $scope.checkResponse($return);
                $scope.fare = $return.fare_value.cServico;

                if ($return.fare_value.error) {

                    $scope.fare.error = $return.fare_value.error;
                }
            });
        }

        // clean variable of fare value in purchase summary.
        $rootScope.cart.nu_farevalue = '';

        // get address.
        this.getAddressByZip();
    };

    /**
     * Method for get data address by zip
     * @name getAddressByZip
     * @author Victor Eduardo Barreto
     * @date Nov 13, 2015
     * @version 1.0
     */
    $scope.getAddressByZip = function () {

        // adjust parameters and add origin data.
        $param = $scope.configParam({nu_postcode: $scope.row.nu_postcode});

        $http.get($scope.server("/getAddressByZip"), {params: $param}).success(function ($return) {

            // verify return data.
            $scope.checkResponse($return);
            $scope.address = {};
            $scope.address = $return;
        });
    };

    /**
     * Method for save data cart in session
     * @name saveCartSession
     * @author Victor Eduardo Barreto
     * @date Nov 17, 2015
     * @version 1.0
     */
    $scope.saveCartSession = function () {

        // insert cart in session.
        sessionStorage.setItem('cart', JSON.stringify($rootScope.cart));

        // update variable through session.
        $rootScope.cart = JSON.parse(sessionStorage.getItem('cart'));

        // update icon cart value.
        $rootScope.cart.nu_cart = $scope.cart.length;
    };

    /**
     * Method for prepare the buy of itens in cart
     * @name prepareBuy
     * @author Victor Eduardo Barreto
     * @date Nov 19, 2015
     * @version 1.0
     */
    $scope.prepareBuy = function () {

        // verify if user is loged.
        if ($rootScope.user) {

            // verify if user has address.
            if ($rootScope.user.ds_address) {

                // verify if zip code is the same of zip code registred in profile.
                if ($rootScope.user.nu_postcode !== $scope.row.nu_postcode) {

                    $scope.showFlashmessage("alert-warning", $scope.constant.MSG0012);
                } else {

                    // call the confirmation of order.
                    $('#modalConfirmation').modal('show');
                }
            } else {

                $('#modalAddress').modal('show');
                $scope.showFlashmessage("alert-warning", $scope.constant.MSG0011);
            }
        } else {

            $('#modalLogin').modal('show');
            $scope.showFlashmessage("alert-warning", $scope.constant.MSG0010);
        }
    };

    /**
     * Method for buy itens in cart
     * @name buy
     * @author Victor Eduardo Barreto
     * @date Dec 30, 2015
     * @version 1.0
     */
    $scope.buy = function () {

        // init variables.
        $scope.buy = {};
        $scope.buy.product = [];

        // transfer data for a new variable.
        angular.forEach($rootScope.cart, function ($key) {

            $scope.buy.product.push({sq_product: $key.sq_product, nu_quantity_buy: $key.nu_quantity_buy});
        });

        // transfer new data.
        $scope.row.nu_total = $scope.cart.nu_total;
        $scope.row.nu_farevalue = $scope.cart.nu_farevalue;

        $param = $scope.configParam($scope.buy);

        $http.post($scope.server("/buy"), $param).success(function ($return) {

            // verify return data.
            $scope.checkResponse($return);

            console.log($return);
        });
    };
});