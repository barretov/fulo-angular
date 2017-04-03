/**
 * Variable to init module services
 * @author Victor Eduardo Barreto
 * @type angular.module.angular-1_3_6_L1749.moduleInstance
 * @date Jan 16, 2016
 */
var $service = angular.module('moduleServices', ['ngResource']);

/**
 * Service for services of program
 * @name services
 * @author Victor Eduardo Barreto
 * @param {object} $rootScope Object root
 * @param {object} $location Object to redirect
 * @date Jan 16, 2016
 * @version 1.0
 */
$service.service('$services', function ($rootScope, $location) {

    /**
     * Method for verify the response problems
     * @name checkResponse
     * @author Victor Eduardo Barreto
     * @param {obj} $response Data of response
     * @return Data of response
     * @date Jun 19, 2015
     * @version 1.0
     */
    this.checkResponse = function ($response) {

        switch ($response) {

            case $rootScope.constant.ACCESS_DENIED:

                this.showFlashmessage('alert-danger', $rootScope.constant.MSG0004);
                $location.path("/");
                throw Error("Access Denied");
                break;

            case $rootScope.constant.EMAIL_ALREADY:

                this.showFlashmessage('alert-warning', $rootScope.constant.MSG0002);
                throw Error("Email Already");
                break;

            case $rootScope.constant.WISHLIST_ALREADY:

                this.showFlashmessage('alert-warning', $rootScope.constant.MSG0005);
                throw Error("Wishlist Already");
                break;

            case $rootScope.constant.PRODUCT_TYPE_BUSY:

                this.showFlashmessage('alert-info', $rootScope.constant.MSG0009);
                throw Error("Product Type Busy");
                break;

            case $rootScope.constant.ERROR:

                $location.path("/error/systemError");
                throw Error("Generic Error");
                break;

                //@TODO
            case $rootScope.constant.ERROR_PAYMENT:

                //@TODO SWITCH PARA ERRO DE PAGAMENTO.
                $location.path("/error/systemError");
                this.showFlashmessage('alert-info', $rootScope.constant.MSG0009);
                throw Error("Payment Error");
                break;

            default :

                return $response;
                break;
        }

        return false;

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
    this.showFlashmessage = function ($type, $message) {

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

        // generate a randomic id for flashmessage.
        var $aux = Math.floor(Math.random() * 2 + 1);

        $('#flashmessage').prepend('<div id="' + $aux + '" class="alert ' + $rootScope.flashType + ' fade in" role="alert"><i class="glyphicon ' + $rootScope.glyphicon + '"></i> ' + $rootScope.flashMsg + '</div>');

        $('#' + $aux).delay(1000);

        $('#' + $aux).fadeOut(function () {
            $('#' + $aux).remove();
        });
    };
});

