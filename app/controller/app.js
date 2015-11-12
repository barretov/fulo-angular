/* global angular */

//URL de acesso ao servidor RESTful
SERVER_URL = "http://fulo.rest";

//Criação ao $app que é o modulo que representa toda a aplicação
var $app = angular.module('app', ['ngRoute']);

$app.config(['$routeProvider', '$httpProvider', function ($routeProvider, $httpProvider) {

        //Configura o route provider
        $routeProvider.
                when('/', {templateUrl: 'app/view/general/main.html'}).
                when('/error/systemError', {templateUrl: 'app/view/error/systemError.html'}).
                when('/contact', {templateUrl: 'app/view/general/contact.html'}).
                when('/customer/addCustomer', {templateUrl: 'app/view/customer/addCustomer.html', controller: 'customerController'}).
                when('/customer/upCustomer', {templateUrl: 'app/view/customer/upCustomer.html', controller: 'customerController'}).
                when('/user/addUser', {templateUrl: 'app/view/user/addUser.html', controller: 'userController'}).
                when('/user/upUser/:id', {templateUrl: 'app/view/user/upUser.html', controller: 'userController'}).
                when('/user/listUser', {templateUrl: 'app/view/user/listUser.html', controller: 'userController'}).
                when('/product/listProduct', {templateUrl: 'app/view/product/listProduct.html', controller: 'productController'}).
                when('/product/addProduct', {templateUrl: 'app/view/product/addProduct.html', controller: 'productController'}).
                when('/product/upProduct/:id', {templateUrl: 'app/view/product/upProduct.html', controller: 'productController'}).
                when('/product/detailProduct', {templateUrl: 'app/view/product/detailProduct.html', controller: 'productController'}).
                when('/product/filterProduct/:id', {templateUrl: 'app/view/product/filterProduct.html', controller: 'productController'}).
                when('/product/listProductType', {templateUrl: 'app/view/product/listProductType.html', controller: 'productController'}).
                when('/product/addProductType', {templateUrl: 'app/view/product/addProductType.html', controller: 'productController'}).
                when('/product/upProductType/:id', {templateUrl: 'app/view/product/upProductType.html', controller: 'productController'}).
                when('/product/cart', {templateUrl: 'app/view/product/cart.html', controller: 'productController'}).
                when('/product/wishList', {templateUrl: 'app/view/product/wishList.html', controller: 'productController'}).
                otherwise({redirectTo: '/'});

        /*
         * Remove cors.
         * $httpProvider.defaults.headers.common = {};
         * $httpProvider.defaults.headers.put = {};
         * $httpProvider.defaults.headers.patch = {};
         */
        $httpProvider.defaults.headers.post = {};

        //configura o RESPONSE interceptor, usado para exibir o ícone de acesso ao servidor
        // e a exibir uma mensagem de erro caso o servidor retorne algum erro
//        $httpProvider.interceptors.push(function ($q, $rootScope) {
//
//            return function (promise) {
//
//                //Always disable loader
//                $rootScope.hideLoader();
//
//                return promise.then(function (response) {
//
//                    // do something on success
//                    return(response);
//                },
//                        function (response) {
//
//                            // do something on error
//                            $data = response.data;
//
//                            $error = $data.error;
//
//                            console.error($data);
//
//                            if ($error && $error.text)
//                                alert("ERROR: " + $error.text);
//                            else {
//
//                                if (response.status = 404)
//                                    alert("Erro ao acessar servidor. Página não encontrada. Veja o log de erros para maiores detalhes");
//                                else
//                                    alert("ERROR! See log console");
//                            }
//
//                            return $q.reject(response);
//                        });
//            };
//        });
    }]);

$app.run(['$rootScope', '$location', '$http', function ($rootScope, $location, $http) {

//        $rootScope.messages = [];

        //Uma flag que define se o ícone de acesso ao servidor deve estar ativado
        $rootScope.showLoaderFlag = false;

        //Força que o ícone de acesso ao servidor seja ativado
        $rootScope.showLoader = function () {
            $rootScope.showLoaderFlag = true;
        };

        //Força que o ícone de acesso ao servidor seja desativado
        $rootScope.hideLoader = function () {
            $rootScope.showLoaderFlag = false;
        };

        /**
         * Method for compose the flashmessages
         * @name showFlashmessage
         * @author Victor Eduardo Barreto
         * @param {string} $type Type of message
         * @param {string} $message Message to show
         * @date Apr 4, 2015
         * @version 1.0
         * Type of messages to send in the variable:
         * alert-success, alert-danger, alert-info, alert-warninfg
         */
        $rootScope.showFlashmessage = function ($type, $message) {

            switch ($type) {

                case "alert-success":
                    $rootScope.flashType = $type;
                    $rootScope.flashMsg = $message;
                    $rootScope.glyphicon = "glyphicon-ok-sign";
                    break;

                case "alert-danger":
                    $rootScope.flashType = $type;
                    $rootScope.flashMsg = $message;
                    $rootScope.glyphicon = "glyphicon-remove-sign";
                    break;

                case "alert-info":
                    $rootScope.flashType = $type;
                    $rootScope.flashMsg = $message;
                    $rootScope.glyphicon = "glyphicon-info-sign";
                    break;

                case "alert-warning":
                    $rootScope.flashType = $type;
                    $rootScope.flashMsg = $message;
                    $rootScope.glyphicon = "glyphicon-warning-sign";
                    break;
            }

            $('#flashmessage').fadeIn().delay(1500).fadeOut('fast');

        };

        /**
         * Method for compose the URL of server REST.
         * @name server
         * @author Victor Eduardo Barreto
         * @param {string} $url URL to complete address
         * @return {string} Complete address of server.
         * @date Apr 4, 2015
         * @version 1.0
         */
        $rootScope.server = function ($url) {
            return SERVER_URL + $url;
        };

        /**
         * Method for verify the response problems
         * @name checkResponse
         * @author Victor Eduardo Barreto
         * @param {obj} $response Data of response
         * @return Data of response
         * @date Jun 19, 2015
         * @version 1.0
         */
        $rootScope.checkResponse = function ($response) {

            switch ($response) {

                case $rootScope.constant.ACCESS_DENIED:

                    this.hideLoader();
                    this.showFlashmessage('alert-danger', $rootScope.constant.MSG0004);
                    $location.path("/");
                    throw Error("Access Denied");
                    break;

                case $rootScope.constant.EMAIL_ALREADY:

                    this.hideLoader();
                    this.showFlashmessage('alert-warning', $rootScope.constant.MSG0002);
                    throw Error("Email Already");
                    break;

                case $rootScope.constant.WISHLIST_ALREADY:

                    this.hideLoader();
                    this.showFlashmessage('alert-warning', $rootScope.constant.MSG0005);
                    throw Error("Wishlist Already");
                    break;

                case $rootScope.constant.WITHOUT_RESULT:

                    this.hideLoader();
                    this.showFlashmessage('alert-info', $rootScope.constant.MSG0008);
                    throw Error("Without Result");
                    break;

                case $rootScope.constant.PRODUCT_TYPE_BUSY:

                    this.hideLoader();
                    this.showFlashmessage('alert-info', $rootScope.constant.MSG0009);
                    throw Error("Product Type Busy");
                    break;

                default :

                    return $response;
                    break;
            }

            return false;
        };

        /**
         * Method configure origin parameters
         * @name configParam
         * @author Victor Eduardo Barreto
         * @param {obj} $data Data to send
         * @return Data with origin configured
         * @date Jul 8, 2015
         * @version 1.0
         */
        $rootScope.configParam = function ($data) {

            // if no arrived data, make a new object.
            (!$data) ? $data = {} : '';

            // inset secret and origin data.
            $data.secret = sessionStorage.getItem('secret');
            $data.origin = sessionStorage.getItem('origin');
            return $data;

        };

        /**
         * Method to get server secret and constants
         * @name getBasic
         * @author Victor Eduardo Barreto
         * @param {string} $return Object with secret and constants
         * @date Jul 8, 2015
         * @version 1.0
         */
        $http.get($rootScope.server("/getBasic")).success(function ($return) {

            // save secret in session.
            sessionStorage.setItem('secret', $return.secret);

            // save constants in variable.
            $rootScope.constant = $return.constants;

        });

        /**
         * Method to show or hide passowrd field and change button
         * @name showhidePass
         * @author Victor Eduardo Barreto
         * @date Alg 7, 2015
         * @version 1.0
         */
        $rootScope.showhidePass = function () {

            var $type = $('.password').prop('type');

            if ($type === 'password') {

                $('.password').prop('type', 'text');
                $('#switchPass').removeClass('glyphicon-eye-close');
                $('#switchPass').addClass('glyphicon-eye-open');

            } else {

                $('.password').prop('type', 'password');
                $('#switchPass').removeClass('glyphicon-eye-open');
                $('#switchPass').addClass('glyphicon-eye-close');
            }
        };

        /**
         * Adjust variable to cart, and get and save data in session.
         */
        if (sessionStorage.getItem('cart')) {

            $rootScope.cart = JSON.parse(sessionStorage.getItem('cart'));
            $rootScope.cart.nu_cart = $rootScope.cart.length;
        } else {
            $rootScope.cart = [];
        }

    }]);

//We already have a limitTo filter built-in to angular,
//let's make a startFrom filter
$app.filter('startFrom', function () {

    return function (input, start) {

        if (input == null)
            return null;
        start = +start; //parse to int

        return input.slice(start);
    };
});