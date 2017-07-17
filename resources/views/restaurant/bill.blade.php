@extends('layouts.main')

@section('title', 'Restaurant POS')

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

.bill-table{
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
  }
}

</style>
@endsection
@section('breadcrumb')
<a class="section hideprint" href="/restaurant">Restaurant</a>
<i class="right angle icon divider hideprint"></i>
<div class="active section hideprint">Bill</div>
@endsection
@section('content')
<img src="/assets/images/logo.png" style="margin-right: auto;margin-left: auto; width: 150px;display: block;">
<center>
Brgy. Malagos, Baguio District
<br>
Davao City, 8000
</center>
<br>
<table class="bill-table">
<tbody>
  <tr>
    <td style="width: 50%">Outlet:<span ng-cloak></span></td>
    <td>Date: <span ng-cloak>@{{bill.date_}}</span></td>
  </tr>
  <tr>
    <td>Check #: <span ng-cloak>@{{bill.id}}</td>
    <td>Time: <span ng-cloak>@{{bill.date_time}}</span></td>
  </tr>
  <tr>
    <td>Table #: <span ng-cloak>@{{bill.table_name}}</span></td>
    <td># of Pax: <span ng-cloak>@{{bill.pax}}</span></td>
  </tr>
</tbody>
</table>
<br>
<table class="bill-table">
<tbody>
  <thead>
    <tr>
      <th style="text-align: center;">ITEM</th>
      <th style="text-align: center;">QTY</th>
      <th style="text-align: right;">TOTAL</th>
    </tr>
  </thead>
  <tbody>
    <tr ng-repeat="items in bill_detail" ng-cloak>
      <td ng-bind="items.menu"></td>
      <td style="text-align: center;" ng-bind="items.quantity"></td>
      <td style="text-align: right;">@{{(items.price*items.quantity)|currency:""}}</td>
    </tr>
  </tbody>
</tbody>
<tfoot>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" style="text-align: right;">Sub-Total:</td>
    <td style="text-align: right;">@{{footer.sub_total|currency:""}}</td>
  </tr>
  <tr>
    <td colspan="2" style="text-align: right;">10% SC:</td>
    <td style="text-align: right;">@{{footer.sc|currency:""}}</td>
  </tr>
  <tr>
    <td colspan="2" style="text-align: right;">12% VAT:</td>
    <td style="text-align: right;">@{{footer.vat|currency:""}}</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" style="text-align: right;">TOTAL:</td>
    <td style="text-align: right;font-weight: bold;">@{{footer.total|currency:""}}</td>
  </tr>
</tfoot>
</table>
<br>
<table style="width: 100%">
  <tr>
    <td style="width: 50%;border-bottom: 1px solid black;">Server:</td>
    <td style="width: 50%;border-bottom: 1px solid black;">Cashier:</td>
  </tr>
  <tr>
    <td style="border-bottom: 1px solid black;" colspan="2">Guest Name:</td>
  </tr>
  <tr>
    <td style="border-bottom: 1px solid black;" colspan="2">Room Number:</td>
  </tr>
  <tr>
    <td style="border-bottom: 1px solid black;" colspan="2">Signature</td>
  </tr>
</table>
<a href="#" class="btn btn-primary hideprint" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> Print</a>
<a href="#" class="btn btn-danger hideprint" onclick="window.close()"><span class="glyphicon glyphicon-remove"></span> Close</a>
@endsection

@section('scripts')
<script type="text/javascript">
  var app = angular.module('main', []);
  app.controller('content-controller', function($scope,$http, $sce) {
    show_bill();
    shortcut.add("x",function() {
      window.close();
    });
    shortcut.add("esc",function() {
      window.close();
    });
    $(document).ready(function() {
      setTimeout(function(){ window.print(); }, 500);
    });
    function show_bill() {
      $http({
          method : "GET",
          url : "/restaurant/table/customer/bill/view/"+{{$id}},
      }).then(function mySuccess(response) {
          // console.log(response.data)
          $scope.bill = response.data.bill;
          $scope.bill_detail = response.data.bill_detail;
          $scope.footer = 
          {
            "sub_total": response.data.sub_total,
            "sc": response.data.sc,
            "vat": response.data.vat,
            "total": response.data.total,
          }
          ;
      }, function myError(response) {
          console.log(response.statusText);
      });
    }
  });
  angular.bootstrap(document, ['main']);
</script>
@endsection