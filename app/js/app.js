(function(){
  var link = $('#completePath').text();
  var srt = 0;
  var banner = { name: 'Azurite', price: 2.95 };
  var param = $.param({companyid: "533"});
  var param3 = $.param({companyid: "533", start:srt});
 
  var app = angular.module('siteListing', ['infinite-scroll']);

// *** Media Adder Popup  ***//
 app.controller('MeidaPopAdd', function($scope,$http) {
  $scope.todos = [];
  $scope.markAll = false;
console.log("event");
console.log(event);
$scope.isNew = false;


$scope.setit = function() {
  console.log($scope.todoText);
  $scope.todoText = "";
  $scope.isNew = true;
};





  $scope.isTodo = function(){
      return $scope.todos.length > 0;  
  }
  $scope.toggleEditMode = function(){
    console.log("puchla");
      $(event.target).closest('li').toggleClass('editing');
  };

  $scope.push = function(){
    alert("puchla");
     // $(event.target).closest('li').toggleClass('editing');
  };

  $scope.editOnEnter = function(todo){
      if(event.keyCode == 13 && todo.text){
          $scope.toggleEditMode();
      }
  };
    
  $scope.remaining = function() {
    var count = 0;
    angular.forEach($scope.todos, function(todo) {
      count += todo.done ? 0 : 1;
    });
    return count;
  };

  $scope.hasDone = function() {
      return ($scope.todos.length != $scope.remaining());
  }    
    
  $scope.itemText = function() {
      return ($scope.todos.length - $scope.remaining() > 1) ? "items" : "item";     
  };
      
  $scope.toggleMarkAll = function() {
      angular.forEach($scope.todos, function(todo) {
        todo.done =$scope.markAll;
      });
  };
  
  $scope.clear = function() {
    var oldTodos = $scope.todos;
    $scope.todos = [];
    angular.forEach(oldTodos, function(todo) {
      if (!todo.done) $scope.todos.push(todo);
    });
  };

});

// *** Listing with Scroll  ***//

  app.controller('DemoController', function($compile,$rootScope,$scope, Reddit) {
  $scope.reddit = new Reddit();

  $scope.toggle = function(id) {
  var param2 = $.param({siteid: id});
    $scope.message = param2;

  $rootScope.$broadcast('PullSlider', $scope.message);  
  };

  $scope.mysites = function() {
      console.log("this is fun");
  }; 
  
  $scope.$on("updateItems",function(d){
  $scope.items = d;
});
      
});

app.directive('customPopover', function ($compile) {
  console.log($compile)
    return {
        restrict: 'A',
        link: function (scope, el, attrs) {
            $(el).popover({
                trigger: 'click',
                  html : true, 
              content: function() {
               return $compile( $(".popover-content-custom").html()) (scope);
                  },
                title: function() {
                  return $(".popover-title-custom").html();
                    }            
                  });
        }
    };
});


// Reddit constructor function to encapsulate HTTP and pagination logic
app.factory('Reddit', function($http,$rootScope) {
  var Reddit = function() {
    this.items = [];
    this.products = [];
    this.busy = false;
    this.after = '';

  };

  Reddit.prototype.nextPage = function(id) {  
//      console.log(id);
        var that = this;

     if (this.busy) return;
     this.busy = true;

  var param = $.param({companyid: "533", start:srt});
  var param5 = $.param({type : $('#sitetypeid').val(), start:srt});
    srt = srt+30
    $http({
             method: 'POST',
             url: link + '/ajax/getlisting/',
             data: param5,
             headers: {'Content-Type': 'application/x-www-form-urlencoded'}
          }).success(function(data){        
          var items = data;
          
            if (id !== $('#sitetypeid').val()) {
                that.products = [];
//                console.log(id + ' sdfsdfsdffffff111 ' + $('#sitetypeid').val());
            }
            for (var i = 0; i < items.length; i++) {
               that.products.push(items[i]);
             }
             that.busy = false;
             $rootScope.$broadcast('updateItems', data);
         }).error(function(data, status, headers, config){

         }.bind(this));
    
  };

this.order = function(predicate, reverse) {
    that.product = orderBy(that.product, predicate, reverse);
};

  return Reddit;
});
  
       
  app.controller('DetailController', function($rootScope,$scope, $filter,$http){
      $rootScope.$on("PullSlider", function(event, message) {
        $scope.visible = true;
        
        $http({
            method: 'POST',
            url: link + '/ajax/getsitedetails/',
            data: message,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
         }).success(function(data){
            // With the data succesfully returned, call our callback
            $scope.pulldata = data;
        }).error(function(data, status, headers, config){
        });
        
      });  
  });
  
  
  app.controller('SiteController', function($rootScope,$scope,$filter,$http,fetchServer){
      var orderBy = $filter('orderBy');
     $http({
         method: 'POST',
         url: link + '/ajax/getlisting/',
         data: {'type' : $('#sitetypeid').val(),
            'start' : srt},
         headers: {'Content-Type': 'application/x-www-form-urlencoded'}
      }).success(function(data){
         // With the data succesfully returned, call our callback
         $scope.product = data.SiteListing;
     }).error(function(data, status, headers, config){
         
     });
          
            
$scope.order = function(predicate, reverse) {
    $scope.product = orderBy($scope.product, predicate, reverse);
};


$scope.nextPage = function() {
  fetchServer(function(items){
    $scope.items=items;
  })
};

$scope.GetMore = function() {
  fetchServer(function(items){      
    $scope.items=items;
  })
};

$scope.toggle = function() {
  $scope.message = "Child updated from parent controller";
  $rootScope.$broadcast('PullSlider', $scope.message);
  
};

  
});

})();
