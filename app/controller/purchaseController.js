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
 * @param {Object} $rootScope root scope
 * @param {Object} $services services
 * @param {Object} $location location
 * @date Alg 18, 2015
 * @version 1.0
 */
 $app.controller('purchaseController', function ($scope, $rootScope, $http, $services, $window, $routeParams, $location) {

 	// TODO remove //
 	// Mandar o usuario para uma tela explicando o pedido feito.
 	// if arrived a paypal token, redirect user to page of confirmation order
 	if ($routeParams.token) {

 		$location.path("/purchase/confirmOrder");
 	}

    /**
     * Function for add item in wish list
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
        	$services.showFlashmessage("alert-warning", $scope.constant.MSG0007);
        } else {

        	$param = $scope.configParam({sq_product: $sq_product});

        	$http.post($scope.server("/addWishList"), $param).success(function ($return) {

                // verify return data.
                $services.checkResponse($return);

                // ajust quantity of itens and session.
                $rootScope.user.nu_wishlist++;
                sessionStorage.setItem('user', JSON.stringify($rootScope.user));

                $services.showFlashmessage("alert-success", $scope.constant.MSG0001);
            });
        }
    };

    /**
     * Function for get items of wish list
     * @name getWishList
     * @author Victor Eduardo Barreto
     * @date Alg 29, 2015
     * @version 1.0
     */
     $scope.getWishList = function () {

     	$param = $scope.configParam();

     	$http.get($scope.server("/getWishList"), {params: $param}).success(function ($return) {

            // verify return data.
            $services.checkResponse($return);

            $scope.rows = $return;
        });
     };

    /**
     * Function for del items of wish list
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
            $services.checkResponse($return);

            // ajust quantity of itens and session.
            $rootScope.user.nu_wishlist--;
            sessionStorage.setItem('user', JSON.stringify($rootScope.user));

            // remove row in list.
            $('#' + $sq_product).fadeOut();
        });
     };

    /**
     * Function for add item in cart
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

            		$services.showFlashmessage("alert-warning", $scope.constant.MSG0006);
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

            $services.showFlashmessage("alert-success", $scope.constant.MSG0001);
        }
    };

    /**
     * Function for del items of cart
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

        $services.showFlashmessage("alert-success", $scope.constant.MSG0001);
    };

    /**
     * Function for update total value products in cart
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
     * Function for get fare value
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
                $services.checkResponse($return);
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
     * Function for get data address by zip
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
            $services.checkResponse($return);
            $scope.address = {};
            $scope.address = $return;
        });
    };

    /**
     * Function for save data cart in session
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
     * Function for prepare the buy of itens in cart
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

                	$services.showFlashmessage("alert-warning", $scope.constant.MSG0012);
                } else {

                    // call the confirmation of order.
                    $('#modalConfirmation').modal('show');
                }
            } else {

            	$('#modalAddress').modal('show');
            	$services.showFlashmessage("alert-warning", $scope.constant.MSG0011);
            }
        } else {

        	$('#modalLogin').modal('show');
        	$services.showFlashmessage("alert-warning", $scope.constant.MSG0010);
        }
    };

    /**
     * Function for buy itens in cart
     * @name buy
     * @author Victor Eduardo Barreto
     * @date Dec 30, 2015
     * @version 1.0
     */
     $scope.buy = function () {

        // init variables.
        $scope.buyed = {};
        $scope.buyed.product = [];

        // transfer data for a new variable.
        angular.forEach($rootScope.cart, function ($key) {

        	$scope.buyed.product.push({sq_product: $key.sq_product, nu_quantity_buy: $key.nu_quantity_buy});
        });

        // transfer new data.
        $scope.buyed.nu_total = $scope.cart.nu_total;
        $scope.buyed.nu_farevalue = $scope.cart.nu_farevalue;

        $param = $scope.configParam($scope.buyed);

        $http.post($scope.server("/buy"), $param).success(function ($return) {

            // verify return data.
            $services.checkResponse($return);

            // clean cart.
            sessionStorage.removeItem('cart');

            // close modal.
            $('#modalConfirmation').modal('hide');

            jQuery(document).ready(function () {
            	jQuery('<div class="sa_payPal_overlay" style="visibility:visible;position:fixed; width:100%; height:100%; filter:progid:DXImageTransform.Microsoft.Gradient(GradientType=1, StartColorStr=\'#88ffffff\', EndColorStr=\'#88ffffff\'); background: rgba(255,255,255,0.8); top:0; left:0; z-index: 999999;"><div style=" background: #FFF; background-image: linear-gradient(top, #FFFFFF 45%, #E9ECEF 80%);background-image: -o-linear-gradient(top, #FFFFFF 45%, #E9ECEF 80%);background-image: -moz-linear-gradient(top, #FFFFFF 45%, #E9ECEF 80%);background-image: -webkit-linear-gradient(top, #FFFFFF 45%, #E9ECEF 80%);background-image: -ms-linear-gradient(top, #FFFFFF 45%, #E9ECEF 80%);background-image: -webkit-gradient(linear, left top,left bottom,color-stop(0.45, #FFFFFF),color-stop(0.8, #E9ECEF));display: block;margin: auto;position: fixed; margin-left:-220px; left:45%;top: 40%;text-align: center;color: #2F6395;font-family: Arial;padding: 15px;font-size: 15px;font-weight: bold;width: 530px;-webkit-box-shadow: 3px 2px 13px rgba(50, 50, 49, 0.25);box-shadow: rgba(0, 0, 0, 0.2) 0px 0px 0px 5px;border: 1px solid #CFCFCF;border-radius: 6px;"><img style="display:block;margin:0 auto 10px" src="https://www.paypalobjects.com/en_US/i/icon/icon_animated_prog_dkgy_42wx42h.gif"><h2>Aguarde alguns segundos.</h2> <p style="font-size:13px; color: #003171; font-weight:400">Você está sendo redirecionado para um ambiente seguro do PayPal<br /> para finalizar seu pagamento.</p><div style="margin:30px auto 0;"><img src="https://www.paypal-brasil.com.br/logocenter/util/img/logo_paypal.png"/></div></div></div>').appendTo('body');
            });

            // redirect for payment.
            $window.location.href = $return;
        });
    };

    /**
     * Function for get orders of user
     * @name getOrders
     * @author Victor Eduardo Barreto
     * @date Dec 31, 2015
     * @version 1.0
     */
     $scope.getOrdersByUser = function () {

     	$param = $scope.configParam();

     	$http.get($scope.server("/getOrdersByUser"), {params: $param}).success(function ($return) {

     		$services.checkResponse($return);

     		if ($return) {

     			$scope.orders = $return;
     		}
     	});
     };

    /**
     * Function for get orders
     * @name getOrders
     * @author Victor Eduardo Barreto
     * @date Jan 1, 2016
     * @version 1.0
     */
     $scope.getOrders = function () {

     	$param = $scope.configParam();

     	$http.get($scope.server("/getOrders"), {params: $param}).success(function ($return) {

     		$services.checkResponse($return);

     		if ($return) {

     			$scope.orders = $return;
     		}
     	});
     };

    /**
     * Function for get products of order
     * @name getProductsOrder
     * @author Victor Eduardo Barreto
     * @param {Integer} $sq_order Order number
     * @date Jan 1, 2016
     * @version 1.0
     */
     $scope.getProductsOrder = function ($sq_order) {

     	$param = $scope.configParam({sq_order: $sq_order});

     	$http.get($scope.server("/getProductsOrder"), {params: $param}).success(function ($return) {

     		$services.checkResponse($return);

     		if ($return) {

     			$scope.products = $return;

                // close all collapses.
                angular.forEach($('[id*=prod]'), function ($key) {

                	if ($('#' + $key.id).hasClass('in')) {

                		$('#' + $key.id).collapse(('hide'));
                	}
                });

                //open collapse.
                $('#prod' + $sq_order).collapse('show');
            }
        });
     };

    /**
     * Function for tracker order
     * @name tracker
     * @author Luis Fernando Meireles
     * @param {String} $nu_tracker description
     * @date Jan 21, 2016
     * @version 1.0
     */
     $scope.tracker = function ($nu_tracker) {

     	$param = $scope.configParam({nu_tracker: $nu_tracker});

     	$http.get($scope.server("/tracker"), {params: $param}).success(function ($return) {

     		$services.checkResponse($return);

     		$data = [];
     		$($return.table).find('tr:not(:eq(0))').each(function (i) {

     			if ($(this).find('td:eq(0)').attr('colspan') == 2) {

     				$data.push({
     					// 'data': $data[i - 1].data,
     					'local': $(this).find('td:eq(0)').text(),
     					'status': $(this).find('td:eq(1)').text(),
     				});

     			} else {

     				$data.push({
     					'data': $(this).find('td:eq(0)').text(),
     					'local': $(this).find('td:eq(1)').text(),
     					'status': $(this).find('td:eq(2)').text(),
     				});
     			}
     		});

            // close all collapses.
            angular.forEach($('[id*=track]'), function ($key) {

            	if ($('#' + $key.id).hasClass('in')) {

            		$('#' + $key.id).collapse(('hide'));
            	}
            });

            //open collapse.
            $('#track' + $nu_tracker).collapse('show');
            $scope.sro = $data;
        });
     };

     $scope.addTracker = function($nu_tracker, $sq_order) {

     	$param = $scope.configParam({sq_order: $sq_order, nu_tracker: $nu_tracker});


     	$http.post($scope.server("/addTracker"), $param).success(function ($return) {

     		$services.checkResponse($return);

     		angular.forEach($scope.orders, function ($key) {

     			if ($key.sq_order === $sq_order) {

     				$key.nu_tracker = $nu_tracker;
     				$key.sq_status = $scope.constant.NUMBER_TEN;
     				$key.ds_status = $scope.constant.ORDER_DISPATCHED;
     			}
     		});

     		$('#track' + $sq_order).collapse('hide');
     		$services.showFlashmessage("alert-success", $scope.constant.MSG0001);

     	});
     }

 	/**
 	 * Function for freeze order
 	 * @author Victor Eduardo Barreto
 	 * @return {bool} Result of request
 	 */
 	$scope.freezeOrder = function(sq_order) {

 		$param = $scope.configParam(sq_order);

 		$http.post($scope.server("/freezeOrder"), $param).success(function ($return) {
 		    $services.checkResponse($return);
 	        $services.showFlashmessage('alert-success', $scope.constant.MSG0001);
 		});
 	}

 	/**
 	 * function for cancel order
 	 * @author Victor Eduardo Barreto
 	 * @param {int} sq_order Identifyer of order
 	 * @return {bool} Result of request
 	 */
 	$scope.cancelOrder = function(sq_order) {

 		$param = $scope.configParam(sq_order);

 		$http.post($scope.server("/cancelOrder"), $param).success(function ($return){
 			$services.checkResponse($return);
 			$services.showFlashmessage('alert-success', $scope.constant.MSG0001);
 		});
 	}

 });
