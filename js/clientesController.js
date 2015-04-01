

//function clientesController($scope,$http,$routeParams,$location)
$app.controller('clientesController', function ($scope, $http, $routeParams, $location) {
    //lista de clientes
    $scope.rows = null;

    //um cliente
    $scope.row = null;

    //Pagination
    $scope.currentPage = 0;
    $scope.pageSize = 10;

    $scope.numberOfPages = function () {
        return Math.ceil($scope.rows.length / $scope.pageSize);
    }

    $scope.loadAll = function () {
        $scope.showLoader();
        $http.get($scope.server("/clientes")).success(function (data) {
            $scope.rows = data;
            $scope.hideLoader();
        });
    }

    $scope.loadRow = function () {
        if ($routeParams.id != null) {
            $scope.showLoader();
            $http.get($scope.server("/clientes/" + $routeParams.id)).success(function (data) {
                $scope.row = data;
                $scope.row.isUpdate = true;
                $scope.hideLoader();
            });
        }
        else
        {
            $scope.row = {}
            $scope.row.sq_pessoa = null;
            $scope.row.isUpdate = false;
            $scope.hideLoader();
        }
    }

    $scope.save = function () {
        $scope.showLoader();
        $http.post($scope.server("/clientes/" + $routeParams.id), $scope.row).success(function (data) {
            alert("Salvo com sucesso");
            $scope.row.isUpdate = true;
            $scope.hideLoader();
        });
    }

    $scope.del = function () {
        if (confirm("Deseja excluir " + $scope.row.sq_pessoa + "?")) {
            $http.delete($scope.server("/clientes/" + $routeParams.id)).success(function (s) {
                $scope.hideLoader();
                alert("Excluído com sucesso");
                $location.path("/clientes");
            });
        }

    }

});