/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$app.directive('direTive', function(){
   
    return {
        restrict: "E",
        scope: {
            message: '='
        },
        template: '<div id="flashmessage" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert navbar-fixed-top">'+
                '<div  class="alert danger fade in" role="alert">'+
                '<i class="glyphicon glyphicon"></i>'+
                'asdfasdfas'+
                '</div></div>',
        link: function(){
            
        }
    }
});

