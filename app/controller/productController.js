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
 * Controller of products
 * @name productController
 * @author Victor Eduardo Barreto
 * @param {Object} $scope Scope
 * @param {Object} $http  Http
 * @param {Object} $location Locatioin
 * @date Alg 18, 2015
 * @version 1.0
 */
$app.controller('productController', function ($scope, $rootScope, $http, $location, $routeParams) {

    /**
     * variables for pagination.
     */
    $scope.currentPage = 0;
    $scope.pageSize = 10;

    /**
     * Method for get products
     * @name getProducts
     * @author Victor Eduardo Barreto
     * @date Alg 18, 2015
     * @version 1.0
     */
    $scope.getProducts = function () {

        $scope.showLoader();

        // adjust parameters and add origin data.
        $param = $scope.configParam();

        $http.get($scope.server("/getProducts"), {params: $param}).success(function ($return) {

            // verify return data.
            $scope.checkResponse($return);

            $scope.rows = $return;
            $scope.hideLoader();
        });
    };

    /**
     * Method for get
     * @name getProduct
     * @author Victor Eduardo Barreto
     * @date Alg 18, 2015
     * @version 1.0
     */
    $scope.getProduct = function () {

        if ($routeParams.id === null) {

            $location.path("/error/systemError/");
        }

        $scope.showLoader();

        // adjust parameters and add origin data.
        $param = $scope.configParam({sq_product: $routeParams.id});

        $http.get($scope.server("/getProduct"), {params: $param}).success(function ($return) {

            // verify return data.
            $scope.checkResponse($return);

            $scope.row = $return;
            $scope.hideLoader();
        });
    };

    /* Method for get product detail
     * @name getProductDetail
     * @author Victor Eduardo Barreto
     * @date Alg 28, 2015
     * @version 1.0
     */
    $scope.getProductDetail = function () {

        $scope.showLoader();

        // adjust parameters and add origin data.
        $param = $scope.configParam({sq_product: $scope.row.sq_product});

        $http.get($scope.server("/getProductDetail"), {params: $param}).success(function ($return) {

            // verify return data.
            $scope.checkResponse($return);

            $scope.product = $return;

            $scope.hideLoader();
        });
    };

    /**
     * Method for get product types
     * @name getProductTypes
     * @author Victor Eduardo Barreto
     * @date Alg 19, 2015
     * @version 1.0
     */
    $scope.getProductTypes = function () {

        // adjust parameters and add origin data.
        $param = $scope.configParam();

        $http.get($scope.server("/getProductTypes"), {params: $param}).success(function ($return) {

            // verify return data.
            $scope.checkResponse($return);

            $scope.types = $return;

        });
    };

    /**
     * Method for add product
     * @name addProduct
     * @author Victor Eduardo Barreto
     * @date Alg 19, 2015
     * @version 1.0
     */
    $scope.addProduct = function () {

        $scope.showLoader();

        var input = document.getElementById("file");
        var fReader = new FileReader();

        fReader.readAsDataURL(input.files[0]);

        fReader.onloadend = function (event) {

            $scope.row.im_image = event.target.result;

            $param = $scope.configParam($scope.row);

            $http.post($scope.server("/addProduct"), $param).success(function ($return) {

                // verify return data.
                $scope.checkResponse($return);

                $scope.hideLoader();

                $location.path("/product/listProduct/");

                $scope.showFlashmessage("alert-success", $scope.constant.MSG0001);
            });

        };
    };

    /**
     * Method for up product
     * @name upProduct
     * @author Victor Eduardo Barreto
     * @date Alg 26, 2015
     * @version 1.0
     */
    $scope.upProduct = function () {

        $scope.showLoader();

        var input = document.getElementById("file");
        var fReader = new FileReader();

        fReader.readAsDataURL(input.files[0]);

        fReader.onloadend = function (event) {

            $scope.row.im_image = event.target.result;

            $param = $scope.configParam($scope.row);

            $http.post($scope.server("/upProduct"), $param).success(function ($return) {

                // verify return data.
                $scope.checkResponse($return);

                $scope.hideLoader();

                $location.path("/product/listProduct/");

                $scope.showFlashmessage("alert-success", $scope.constant.MSG0001);
            });

        };
    };

    /**
     * Method for activate product
     * @name activateProduct
     * @author Victor Eduardo Barreto
     * @param {int} $sq_product Product identifier
     * @date Alg 26, 2015
     * @version 1.0
     */
    $scope.activateProduct = function ($sq_product) {

        $scope.showLoader();

        $param = $scope.configParam({sq_product: $sq_product});

        $http.post($scope.server("/activateProduct"), $param).success(function ($return) {

            // verify return data.
            $scope.checkResponse($return);

            $scope.hideLoader();
        });
    };

    /**
     * Method for inactivate product
     * @name inactivateProduct
     * @author Victor Eduardo Barreto
     * @param {int} $sq_product Product identifier
     * @date Alg 26, 2015
     * @version 1.0
     */
    $scope.inactivateProduct = function ($sq_product) {

        $scope.showLoader();

        $param = $scope.configParam({sq_product: $sq_product});

        $http.post($scope.server("/inactivateProduct"), $param).success(function ($return) {

            // verify return data.
            $scope.checkResponse($return);

            $scope.hideLoader();
        });
    };

    /**
     * Method for get products by filter
     * @name getProductsByFilter
     * @author Victor Eduardo Barreto
     * @date Alg 27, 2015
     * @version 1.0
     */
    $scope.getProductsByFilter = function () {

        $scope.showLoader();

        // adjust parameters and add origin data.
        $param = $scope.configParam({sq_product_type: $routeParams.id});

        $http.get($scope.server("/getProductsByFilter"), {params: $param}).success(function ($return) {

            // verify return data.
            $scope.checkResponse($return);

            $scope.rows = $return;
            $scope.hideLoader();
        });
    };

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
        if (!$scope.user) {

            $('#modalLogin').modal('show');
            $scope.showFlashmessage("alert-warning", $scope.constant.MSG0007);
        } else {

            $scope.showLoader();

            $param = $scope.configParam({sq_product: $sq_product});

            $http.post($scope.server("/addWishList"), $param).success(function ($return) {

                // verify return data.
                $scope.checkResponse($return);

                // ajust quantity of itens and session.
                $scope.user.nu_wishlist++;
                sessionStorage.setItem('user', JSON.stringify($scope.user));

                $scope.hideLoader();
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

        $scope.showLoader();

        $param = $scope.configParam();

        $http.get($scope.server("/getWishList"), {params: $param}).success(function ($return) {

            // verify return data.
            $scope.checkResponse($return);

            $scope.rows = $return;
            $scope.hideLoader();
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

        $scope.showLoader();

        $param = $scope.configParam({sq_product: $sq_product});

        $http.post($scope.server("/delWishList"), $param).success(function ($return) {

            // verify return data.
            $scope.checkResponse($return);

            // ajust quantity of itens and session.
            $scope.user.nu_wishlist--;
            sessionStorage.setItem('user', JSON.stringify($scope.user));

            // remove row in list.
            $('#' + $sq_product).fadeOut();
            $scope.hideLoader();
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

        $scope.showLoader();

        // variable to use as flag, to continue the flow.
        var $continue = true;

        // verify if exists session data.
        if (sessionStorage.getItem('cart')) {

            // verify if already exists the new product in the cart.
            angular.forEach($scope.cart, function ($key) {

                if ($product.sq_product === $key.sq_product) {

                    $scope.hideLoader();
                    $scope.showFlashmessage("alert-warning", $scope.constant.MSG0006);
                    $continue = false;
                }
            });
        }

        if ($continue) {

            // insert product in variable cart.
            $rootScope.cart.push($product);

            // insert cart in session.
            sessionStorage.setItem('cart', JSON.stringify($scope.cart));

            // update variable through session.
            $rootScope.cart = JSON.parse(sessionStorage.getItem('cart'));

            // update icon cart value.
            $rootScope.cart.nu_cart = $scope.cart.length;

            // update total value.
            this.updateTotal();

            $scope.hideLoader();
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

        $scope.showLoader();

        angular.forEach($rootScope.cart, function ($key) {

            if ($sq_product === $key.sq_product) {

                $rootScope.cart.splice($key, 1);
            }
        });

        // insert cart in session.
        sessionStorage.setItem('cart', JSON.stringify($scope.cart));

        // update variable through session.
        $rootScope.cart = JSON.parse(sessionStorage.getItem('cart'));

        // update icon cart value.
        $rootScope.cart.nu_cart = $scope.cart.length;

        // update total value.
        this.updateTotal();

        $scope.hideLoader();
        $scope.showFlashmessage("alert-success", $scope.constant.MSG0001);

        $scope.hideLoader();
    };

    /**
     * Method for update total value products in cart
     * @name updateTotal
     * @author Victor Eduardo Barreto
     * @date Sep 1, 2015
     * @version 1.0
     */
    $scope.updateTotal = function () {

        // sum all products.
        angular.forEach($rootScope.cart, function ($key) {

            $rootScope.cart.nu_total = parseFloat($rootScope.cart.nu_total + $key.nu_value);
        });

    };

    /**
     * Method for show details of product
     * @name detailProduct
     * @author Victor Eduardo Barreto
     * @param {object} $row Product data
     * @date Sep 1, 2015
     * @version 1.0
     */
    $scope.detailProduct = function ($row) {

        $rootScope.row = $row;
        $location.path("/product/detailProduct");
    };

    /**
     * Method for get fare value
     * @name getFareValue
     * @author Victor Eduardo Barreto
     * @param {int} $nu_postcode Post code
     * @date Sep 17, 2015
     * @version 1.0
     */
    $scope.getFareValue = function () {

        // @Todo
        // fazer requisição para o servidor passando todos os produtos do carrinho.
        // receber o valor do frete para todos os produtos.

    };
});
