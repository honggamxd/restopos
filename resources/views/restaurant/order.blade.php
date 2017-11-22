@extends('layouts.main')

@section('title', 'Orders')

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

.order-table{
  width: 100%;
  border-collapse: collapse;
  margin: 0;
}


@media print{
  .hideprint{
    display: none !important;
  }

  *{
    background-color: white !important;
    padding-top: 0px !important;
    margin-top: 0px !important;
  }

  #header{
    margin-top: 100px !important;
  }
}

</style>
@endsection
@section('breadcrumb')
<a class="section hideprint" href="/restaurant">Restaurant</a>
<i class="right angle icon divider hideprint"></i>
<div class="active section hideprint">Order</div>
@endsection
@section('content')
<h3 style="text-align: center;" id="header">FOOD ORDER @{{order.que_number}}</h3>
<table class="order-table">
<tbody>
  <tr>
    <td style="width: 50%" colspan="2">Outlet: <span ng-cloak>@{{order.restaurant_name}}</span></td>
  </tr>
  <tr>
    <td>Table #: <span ng-cloak>@{{order.table_name}}</span></td>
    <td>Date: <span ng-cloak>@{{order.date_}}</span></td>
  </tr>
  <tr>
    <td># of Pax: <span ng-cloak>@{{order.pax}}</span></td>
    <td>Time: <span ng-cloak>@{{order.date_time}}</span></td>
  </tr>
</tbody>
</table>
<br>
<table class="order-table"> 
<tbody>
  <thead>
    <tr>
      <th style="text-align: center;">ITEM</th>
      <th style="text-align: center;" ng-if="order.has_cancelled==1">OLD QTY</th>
      <th style="text-align: center;padding-left: 2px;padding-right: 2px;" ng-if="order.has_cancelled==1">CXLD</th>
      <th style="text-align: center;padding-left: 2px;padding-right: 2px;"><span ng-if="order.has_cancelled==1">NEW</span> QTY</th>
      <th style="text-align: right;padding-left: 2px;padding-right: 2px;">PRICE</th>
      <th style="text-align: right;">TOTAL</th>
    </tr>
  </thead>
  <tbody>
    <tr ng-repeat="items in order_detail" ng-cloak>
      <td style="width: 50%;vertical-align: top;">@{{items.restaurant_menu_name}}<span ng-if="items.special_instruction != ''"><br>(@{{items.special_instruction}})</span></td>
      <td style="text-align: center;vertical-align: top;" ng-if="order.has_cancelled==1">@{{items.quantity+items.cancelled_quantity}}</td>
      <td style="text-align: center;vertical-align: top;padding-left: 2px;padding-right: 2px;" ng-if="order.has_cancelled==1">@{{items.cancelled_quantity}}</td>
      <td style="text-align: center;vertical-align: top;padding-left: 2px;padding-right: 2px;" ng-bind="items.quantity"></td>
      <td style="text-align: right;vertical-align: top;padding-left: 2px;padding-right: 2px;">@{{items.price|currency:""}}</td>
      <td style="text-align: right;vertical-align: top;">@{{items.quantity*items.price|currency:""}}</td>
    </tr>
  </tbody>
</tbody>
</table>
<br>
<p>Server: <span ng-cloak>@{{order.server_name}}</span></p>
<a href="javascript:void(0);" class="btn btn-primary hideprint" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> Print</a>
<a href="javascript:void(0);" class="btn btn-danger hideprint" onclick="window.close()" data-balloon-pos="right" data-balloon="Can be closed by pressing the key X in the keyboard."><span class="glyphicon glyphicon-remove"></span> Close</a>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br><p style="text-align: center;">-</p>
@endsection

@section('scripts')
<script type="text/javascript">
  $('table').tablesort();
  shortcut.add("x",function() {
    window.close();
  });
  shortcut.add("esc",function() {
    window.close();
  });

  @if($print==1)
  $(document).ready(function() {
    setTimeout(function(){ window.print(); }, 1500);
  });
  @endif
  var app = angular.module('main', []);
  app.controller('content-controller', function($scope,$http, $sce) {
        $scope.order = {!! $order !!};
        $scope.order_detail = {!! $order_detail !!};
  });
  angular.bootstrap(document, ['main']);
</script>
@endsection