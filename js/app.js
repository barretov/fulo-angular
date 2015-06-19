//URL de acesso ao servidor RESTful
SERVER_URL = "http://localhost:8082";

//Criação ao $app que é o modulo que representa toda a aplicação
var $app = angular.module('app', ['ngRoute']);

$app.config(['$routeProvider', '$httpProvider', function ($routeProvider, $httpProvider) {

        //Configura o route provider
        $routeProvider.
                when('/', {templateUrl: 'view/general/main.html'}).
                when('/contact', {templateUrl: 'view/general/contact.html'}).
                when('/user/new', {templateUrl: 'view/user/add.html', controller: 'userController'}).
                when('/user/newCustomer', {templateUrl: 'view/user/addCustomer.html', controller: 'userController'}).
                when('/user/editCustomer', {templateUrl: 'view/user/editCustomer.html', controller: 'userController'}).
                when('/user/:id', {templateUrl: 'view/user/edit.html', controller: 'userController'}).
                when('/user', {templateUrl: 'view/user/list.html', controller: 'userController'}).
                when('/user/contact', {templateUrl: 'view/user/contact.html', controller: 'userController'}).
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
            }
        });
    }]);

$app.run(['$rootScope', '$http', function ($rootScope, $http) {

        //Uma flag que define se o ícone de acesso ao servidor deve estar ativado
        $rootScope.showLoaderFlag = false;

        //Força que o ícone de acesso ao servidor seja ativado
        $rootScope.showLoader = function () {
            $rootScope.showLoaderFlag = true;
        }
        //Força que o ícone de acesso ao servidor seja desativado
        $rootScope.hideLoader = function () {
            $rootScope.showLoaderFlag = false;
        }

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

            $('#flashmessage').fadeIn().delay(1500).fadeOut('slow');

        }

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

    }]);

//We already have a limitTo filter built-in to angular,
//let's make a startFrom filter
$app.filter('startFrom', function () {

    return function (input, start) {

        if (input == null)
            return null;
        start = +start; //parse to int

        return input.slice(start);
    }
});