@extends('layouts.main')

@section('title', 'Issuance')

@section('css')
<style type="text/css">


</style>
@endsection
@section('breadcrumb')
<div class="active section">Issuance</div>
@endsection
@section('content')
<h1 style="text-align: center;">Issuance</h1>
<div class="col-sm-9">
  <div class="row">
    <div class="col-sm-12">
      <label>Search Item</label>
      <div class="ui icon input fluid">
        <i class="search icon"></i>
        <input type="text" placeholder="Search" id="search-item-name" ng-model="search_item_name">
      </div> 
    </div>
  </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="table-responsive">
          <table class="ui single line unstackable table">
            <thead>
              <tr>
                <th class="center aligned middle aligned">Category</th>
                <th class="center aligned middle aligned">Item</th>
                <th class="center aligned middle aligned">Unit</th>
                <th class="center aligned middle aligned">Stocks</th>
                <th class="center aligned middle aligned">Qty</th>
                <th class="center aligned middle aligned"></th>
              </tr>
            </thead>
            <tbody ng-cloak>
              <tr ng-repeat="item in cart.items" ng-init="item.edit_quantity=false" ng-class="item.quantity == 0 ? 'error' : (item.valid == 0 ? 'warning' : '')">
                <td class="center aligned middle aligned">@{{item.category}}</td>
                <td class="center aligned middle aligned" style="width: 100%">@{{item.item_name}}</td>
                <td class="center aligned middle aligned">@{{item.unit}}</td>
                <td class="center aligned middle aligned">@{{item.stocks}}</td>
                <td class="center aligned middle aligned">
                  <div class="ui input" ng-show="item.edit_quantity">
                    <input type="number" placeholder="Cost Price" ng-model="item.quantity" ng-blur="update_cart(this)" focus-me="item.edit_quantity">
                  </div>
                  <p ng-hide="item.edit_quantity" ng-click="edit_quantity(this)" style="cursor: pointer;" data-balloon="Click to Edit" data-balloon-pos="left">@{{item.quantity}}</p>
                </td>
                <td class="right aligned middle aligned"><button type="button" class="btn btn-danger" ng-click="delete_item(this)">&times;</button></td>
              </tr>
            </tbody>  
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-3">
    <form ng-submit="make_issuance()">
      <div class="form-group">
        <label>Issuance #</label>
        <input type="text" name="" placeholder="Issuance #" class="form-control" ng-model="cart.info.issuance_number" ng-blur="add_info_cart(this)">
        <p class="help-block" ng-cloak>@{{formerrors.issuance_number[0]}}</p>
      </div>
      <div class="form-group">
        <label>Issuance To:</label>
        <!-- <input type="text" name="" placeholder="Issuance To:" class="form-control" ng-model="cart.info.issuance_to" ng-blur="add_info_cart(this)"> -->
        <select class="form-control" ng-model="cart.info.issuance_to" ng-change="add_info_cart(this)">          
          <option value="">Select Issuance To</option>
        @foreach($issuance_to as $issuance_to_data)
          <option value="{{$issuance_to_data->id}}">{{$issuance_to_data->name}}</option>
        @endforeach
        </select>
        <p class="help-block" ng-cloak>@{{formerrors.issuance_to[0]}}</p>
      </div>
      <div class="form-group">
        <label>Comments</label>
        <textarea placeholder="Comments" class="form-control" ng-model="cart.info.comments" ng-blur="add_info_cart(this)"></textarea>
        <p class="help-block" ng-cloak>@{{formerrors.comments[0]}}</p>
      </div>
      <div class="form-group">
        <label>Controls</label>
        <button type="submit" class="btn btn-primary btn-block" ng-disabled="submit"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
        <button type="button" class="btn btn-danger btn-block" ng-click="destroy_cart()"><span class="glyphicon glyphicon-trash"></span> Cancel</button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('modals')

@endsection

@section('scripts')
<script type="text/javascript">
  var app = angular.module('main', ['ngSanitize']);
  app.controller('content-controller', function($scope,$http, $sce, $window) {
    $scope.cart = {};
    $scope.formdata = {};
    show_cart();

    $( "#search-item-name" ).autocomplete({
      source: "/api/search/inventory/item/item_name/value",
      select: function( event, ui ) {
        add_items_cart(ui.item.id);
        $scope.search_item_name = "";
      }
    });

    $scope.edit_quantity = function(data) {
      data.item.edit_quantity = (data.item.edit_quantity?false:true);
    }


    $scope.update_cart = function(data) {
      data.item.edit_quantity = (data.item.edit_quantity?false:true);
      $http({
         method: 'PUT',
         url: '/api/issuance/cart/item/update/'+data.item.inventory_item_id,
         data: $.param({
          'quantity':data.item.quantity,
         }),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        // console.log(response.data);
        $scope.cart.items = response.data.items;
        $scope.cart.info = response.data.info;
        // show_cart();
      }, function(rejection) {
         var errors = rejection.data;
         console.log(errors);
         $scope.formerrors = errors;
      });
    }
    $scope.add_info_cart = function(data) {
      $http({
         method: 'POST',
         url: '/api/issuance/cart/info',
         data: $.param({
          'issuance_number':$scope.cart.info.issuance_number,
          'issuance_to':$scope.cart.info.issuance_to,
          'comments':$scope.cart.info.comments,
         }),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        // console.log(response.data);
      }, function(rejection) {
         var errors = rejection.data;
         console.log(errors);
         $scope.formerrors = errors;
      });
    }
    $scope.delete_item = function(data) {
      $http({
         method: 'DELETE',
         url: '/api/issuance/cart/item/delete/'+data.item.inventory_item_id,
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        // console.log(response.data);
        $scope.cart.items = response.data.items;
        $scope.cart.info = response.data.info;
        // show_cart();
      }, function(rejection) {
         var errors = rejection.data;
         console.log(errors);
         $scope.formerrors = errors;
      });
    }

    $scope.make_issuance = function() {
      if(isEmpty($scope.cart.items)){
        alertify.error("Cart is empty. Please add items in the cart.");
      }else{
      $scope.submit = true;
        $http({
           method: 'POST',
           url: '/api/issuance/make',
           data: $.param({
            'items':$scope.cart.items,
            'issuance_number':$scope.cart.info.issuance_number,
            'issuance_to':$scope.cart.info.issuance_to,
            'comments':$scope.cart.info.comments,
           }),
           headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
        .then(function(response) {
          $scope.submit = false;
          // console.log(response.data);
          $window.location.assign('/issuance/view/'+response.data);
        }, function(rejection) {
           var errors = rejection.data;
           console.log(errors);
           $scope.formerrors = errors;
           if($scope.formerrors.hasOwnProperty('items')){
             alertify.error($scope.formerrors.items[0]);
            
           }
          $scope.submit = false;
        });
      }
    }


    function add_items_cart(id) {
      $http({
         method: 'POST',
         url: '/api/issuance/cart/item/add/'+id,
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        // console.log(response.data);
        // show_cart();
        $scope.cart.items = response.data.items;
        $scope.cart.total = response.data.total;
        $scope.cart.info = response.data.info;
      }, function(rejection) {
         var errors = rejection.data;
         console.log(errors);
         $scope.formerrors = errors;
      });
    }


    function show_cart() {
      $http({
          method : "GET",
          url : "/api/issuance/cart",
      }).then(function mySuccess(response) {
          // console.log(response.data.items);
          $scope.cart.items = response.data.items;
          $scope.cart.info = response.data.info;
      }, function myError(rejection) {
          if(rejection.status != 422){
            request_error(rejection.status);
          }else if(rejection.status == 422){
            var errors = rejection.data;
          }
      });
    }

  });
  app.directive('focusMe', ['$timeout', '$parse', function ($timeout, $parse) {
      return {
          link: function (scope, element, attrs) {
              var model = $parse(attrs.focusMe);
              scope.$watch(model, function (value) {
                  if (value === true) {
                      $timeout(function () {
                          element[0].focus();
                      });
                  }
              });
              element.bind('blur', function () {
                  scope.$apply(model.assign(scope, false));
              });
          }
      };
  }]);
  angular.bootstrap(document, ['main']);
</script>
@endsection