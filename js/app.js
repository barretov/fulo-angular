/* global angular */

//URL de acesso ao servidor RESTful
SERVER_URL = "http://rest.local";

//Criação ao $app que é o modulo que representa toda a aplicação
var $app = angular.module('app', ['ngRoute']);

$app.config(['$routeProvider', '$httpProvider', function ($routeProvider, $httpProvider) {

        //Configura o route provider
        $routeProvider.
                when('/', {templateUrl: 'view/general/main.html'}).
                when('/contact', {templateUrl: 'view/general/contact.html'}).
                when('/customer/addCustomer', {templateUrl: 'view/customer/addCustomer.html', controller: 'customerController'}).
                when('/customer/upCustomer', {templateUrl: 'view/customer/upCustomer.html', controller: 'customerController'}).
                when('/user/addUser', {templateUrl: 'view/user/addUser.html', controller: 'userController'}).
                when('/user/upUser/:id', {templateUrl: 'view/user/upUser.html', controller: 'userController'}).
                when('/user/listUser', {templateUrl: 'view/user/listUser.html', controller: 'userController'}).
                otherwise({redirectTo: '/'});

        //configura o RESPONSE interceptor, usado para exibir o ícone de acesso ao servidor
        // e a exibir uma mensagem de erro caso o servidor retorne algum erro
        $httpProvider.interceptors.push(function ($q, $rootScope) {

            return function (promise) {

                //Always disable loader
                $rootScope.hideLoader();

                return promise.then(function (response) {

                    // do something on success
                    return(response);
                },
                        function (response) {

                            // do something on error
                            $data = response.data;

                            $error = $data.error;

                            console.error($data);

                            if ($error && $error.text)
                                alert("ERROR: " + $error.text);
                            else {

                                if (response.status = 404)
                                    alert("Erro ao acessar servidor. Página não encontrada. Veja o log de erros para maiores detalhes");
                                else
                                    alert("ERROR! See log console");
                            }

                            return $q.reject(response);
                        });
            };
        });
    }]);

$app.run(['$rootScope', '$location', '$http', function ($rootScope, $location, $http) {

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

                    //@TODO
                    merda();

                    break;

                case $rootScope.constant.EMAIL_ALREADY:

                    this.hideLoader();
                    this.showFlashmessage('alert-warning', $rootScope.constant.MSG0002);

                    //@TODO
                    merda();

                default :

                    return $response;
                    break;
            }

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
            (!$data) ? $data = {} : null;

            // inset secret and origin data.
            $data.secret = sessionStorage.getItem('secret');
            $data.origin = $rootScope.origin;
            return $data;

        };

        /**
         * Method to get server secret
         * @name getSecret
         * @author Victor Eduardo Barreto
         * @param {string} $return Object with secret
         * @date Jul 8, 2015
         * @version 1.0
         */
        $http.get($rootScope.server("/getSecret")).success(function ($return) {

            // save secret in session.
            sessionStorage.setItem('secret', $return);
        });

        /**
         * Method to get in the server and set constants for system
         * @name getConstants
         * @author Victor Eduardo Barreto
         * @param {obj} $return Object with constants
         * @date Jul 5, 2015
         * @version 1.0
         */
        $http.get($rootScope.server("/getConstants"), {params: $rootScope.configParam()}).success(function ($return) {

            $rootScope.constant = $return;
        });

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