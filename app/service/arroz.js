/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* global $app, $scope */

$app.service('services', ['$scope', function ($scope) {


    /**
     * Method for verify the response problems
     * @name checkResponse
     * @author Victor Eduardo Barreto
     * @param {obj} $response Data of response
     * @return Data of response
     * @date Jun 19, 2015
     * @version 1.0
     */
    teste = function ($response) {

        switch ($response) {

            case $scope.constant.ACCESS_DENIED:

                $rootScope.hideLoader();
                $rootScope.showFlashmessage('alert-danger', $rootScope.constant.MSG0004);
                $location.path("/");
                throw Error("Access Denied");
                break;

            case $rootScope.constant.EMAIL_ALREADY:

                $rootScope.hideLoader();
                $rootScope.showFlashmessage('alert-warning', $rootScope.constant.MSG0002);
                throw Error("Email Already");
                break;

            case $rootScope.constant.WISHLIST_ALREADY:

                $rootScope.hideLoader();
                $rootScope.showFlashmessage('alert-warning', $rootScope.constant.MSG0005);
                throw Error("Wishlist Already");
                break;

            case $rootScope.constant.WITHOUT_RESULT:

                $rootScope.hideLoader();
                $rootScope.showFlashmessage('alert-info', $rootScope.constant.MSG0008);
                break;

            default :

                return $response;
                break;
        }

        return false;
    }



    //entende que arroz é uma função ex: arroz();
//    return function(construct, destruct){
//        var $this = this;
//        function bootstrap(){
//           alert('init');
//           exemplo();
//           if(typeof construct === 'function'){
//               construct($this);
//           }
//        }
//        
//        this.exemplo = function(){
//            alert ('bola esquerda');
//        }
//        
//        function exemplo(){
//            alert('bola direuita');
//        }
//        //retorna o construtor
//        return bootstrap();
//    }
}]);

