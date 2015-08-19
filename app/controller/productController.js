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
$app.controller('productController', function ($scope, $http, $location) {

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
//            var img = document.getElementById("img");
//            console.log(event.target.result);
//            img.src = event.target.result;

            $param = $scope.configParam($scope.row);

            $param.ds_image = event.target.result;

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
     * convertImgToBase64
     * @param  {String}   url
     * @param  {Function} callback
     * @param  {String}   [outputFormat='image/png']
     * @author HaNdTriX
     * @example
     convertImgToBase64('http://goo.gl/AOxHAL', function(base64Img){
     console.log('IMAGE:',base64Img);
     })
     */
    function convertImgToBase64(url, callback, outputFormat) {
        var img = new Image();
        img.crossOrigin = 'Anonymous';
        img.onload = function () {
            var canvas = document.createElement('CANVAS');
            var ctx = canvas.getContext('2d');
            canvas.height = this.height;
            canvas.width = this.width;
            ctx.drawImage(this, 0, 0);
            var dataURL = canvas.toDataURL(outputFormat || 'image/png');
            callback(dataURL);
            canvas = null;
        };
        img.src = url;
    }

    function nada() {

        var input = document.getElementById("file");
        var fReader = new FileReader();
        fReader.readAsDataURL(input.files[0]);

        fReader.onloadend = function (event) {
            var img = document.getElementById("img");
//            console.log(event.target.result);

            img.src = event.target.result;
        };
    }
    ;

});
