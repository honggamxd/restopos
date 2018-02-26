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
          <th class="center aligned middle aligned">Reference Number</th>
          <th class="center aligned middle aligned">Date</th>
          <th class="center aligned middle aligned">Quantity</th>
          <th class="center aligned middle aligned">Unit Cost</th>
          <th class="center aligned middle aligned">Total Cost</th>
          <th class="center aligned middle aligned">Remarks</th>
        </tr>
      </thead>
      <tbody ng-cloak>
        <tr ng-repeat="item_data in items">
          <td class="center aligned middle aligned">
            <a ng-href="/@{{item_data.reference_table}}/view/@{{item_data.reference_id}}">
              <span ng-if="item_data.reference_table=='purchase'">PO #: @{{item_data.reference_data.po_number}}</span>
              <span ng-if="item_data.reference_table=='issuance'">Issuance #:@{{item_data.reference_data.issuance_number}}</span>
            </a>
          </td>
          <td class="center aligned middle aligned">@{{item_data.date_}}</td>
          <td class="center aligned middle aligned">@{{item_data.quantity}}</td>
          <td class="center aligned middle aligned">@{{item_data.cost_price|currency:""}}</td>
          <td class="center aligned middle aligned">@{{item_data.quantity*item_data.cost_price|currency:""}}</td>
          <td class="center aligned middle aligned">@{{item_data.reference_data.comments}}</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>




@endsection

@section('modals')


@endsection

@push('scripts')
<script type="text/javascript">
  $('table').tablesort();
  $('.ui.checkbox').checkbox('enable');

  

  
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


  
</script>
@endpush