@extends('layouts.main')

@section('title', 'Inventory')

@section('css')
<style type="text/css">
@media (min-width: 768px){
  .modal-lg {
    width: 760px;
  }  
}
@media (min-width: 992px){
  .modal-lg {
    width: 990px;
  }  
}

</style>
@endsection
@section('breadcrumb')
<div class="active section">Inventory</div>
@endsection
@section('content')
<div class="col-sm-12">
<h1 style="text-align: center;">Inventory</h1>
<br>
Category: <b>@{{item_data.category}}</b><br>
Item Name: <b>@{{item_data.item_name}}</b><br>
Unit: <b>@{{item_data.unit}}</b><br>
Current Cost Price: <b>@{{item_data.cost_price}}</b><br>
Begining Quantity: <b>@{{item_data.begining_quantity}}</b><br>
Current Quantity: <b>@{{item_data.current_quantity}}</b><br>

  <div class="table-responsive">
    <table class="ui sortable compact table unstackable" id="menu-table">
      <thead>
        <tr>
          <th class="center aligned middle aligned">Date</th>
          <th class="center aligned middle aligned">Quantity</th>
          <th class="center aligned middle aligned">Unit Cost</th>
          <th class="center aligned middle aligned">Total Cost</th>
          <th class="center aligned middle aligned">Remarks</th>
          <th class="center aligned middle aligned">Reference ID</th>
        </tr>
      </thead>
      <tbody ng-cloak>
        <tr ng-repeat="item_data in items">
          <td class="center aligned middle aligned">@{{item_data.date_}}</td>
          <td class="center aligned middle aligned">@{{item_data.quantity}}</td>
          <td class="center aligned middle aligned">@{{item_data.cost_price|currency:""}}</td>
          <td class="center aligned middle aligned">@{{item_data.quantity*item_data.cost_price|currency:""}}</td>
          <td class="center aligned middle aligned">@{{item_data.remarks}}</td>
          <td class="center aligned middle aligned"><a href="/@{{item_data.reference_table}}/view/@{{item_data.reference_id}}">@{{item_data.reference_id}}</a></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>




@endsection

@section('modals')


@endsection

@section('scripts')
<script type="text/javascript">
  $('table').tablesort();
  $('.ui.checkbox').checkbox('enable');

  

  var app = angular.module('main', ['ngSanitize']);
  app.controller('content-controller', function($scope,$http, $sce, $window) {
    show_items();
    function show_items() {
      $http({
          method : "GET",
          url : "/api/inventory/item/history/{{$id}}",
      }).then(function mySuccess(response) {
          // console.log(response.data.items);
          $scope.items = response.data.items;
          $scope.item_data = response.data.item_data;
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