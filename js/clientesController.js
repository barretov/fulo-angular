$app.controller('clientesController', function ($scope, $http, $routeParams, $location) {

    //Pagination
    $scope.currentPage = 0;
    $scope.pageSize = 5;

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
            $scope.row.isUpdate = true;
            $scope.hideLoader();
            $location.path("/clientes");
        });
    }

    $scope.del = function ($sq_pessoa) {

        $http.delete($scope.server("/clientes/" + $sq_pessoa)).success(function ($result) {

            if ($result) {
                $('#' + $sq_pessoa).addClass('hidden');
            }
        });
    }

    $scope.fireModal = function ($row) {
        $scope.pessoa = $row;
    }

});