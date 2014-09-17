angular.module('demo', []).controller('DemoCtrl', function ($scope) {
  $scope.listings = [];
  //$scope.selectedValue = null;
  $scope.getListing = function (id) {
     var i, res = [];
     $http({
             method: 'POST',
             url: link + '/ajax/vendorsList/',
             headers: {'Content-Type': 'application/x-www-form-urlencoded'}
          }).success(function(data){        
          var items = data;
          
            if (id !== $('#sitetypeid').val()) {
                that.products = [];
            }
            for (var i = 0; i < items.length; i++) {
               that.products.push(items[i]);
             }
             that.busy = false;
             $rootScope.$broadcast('updateItems', data);
         }).error(function(data, status, headers, config){

         }.bind(this));
     
     return res;
  };
});