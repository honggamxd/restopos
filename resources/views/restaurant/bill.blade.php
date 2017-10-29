@extends('layouts.main')

@section('title', 'Order Slip')

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
<h3 style="text-align: center;">ORDER SLIP</h3>
<table class="bill-table">
<tbody>
  <tr>
    <td style="width: 50%">Outlet: <b ng-cloak>@{{bill.restaurant_name}}</b></td>
    <td></td>
  </tr>
  <tr>
    <td>Check #: <b ng-cloak>@{{bill.check_number}}</b></td>
    <td>Date: <b ng-cloak>@{{bill.date_}}</b></td>
  </tr>
  <tr>
    <td>Table #: <b ng-cloak>@{{bill.table_name}}</b></td>
    <td>Time: <b ng-cloak>@{{bill.date_time}}</b></td>
  </tr>
  <tr>
    <td ng-show="bill.type=='good_order'"># of Pax: <b ng-cloak>@{{bill.pax}}</b></td>
    <td ng-show="bill.type=='good_order'"># of SC/PWD: <b ng-cloak>@{{bill.sc_pwd}}</b></td>
  </tr>
</tbody>
</table>
<br>
<table class="bill-table" ng-cloak>
<tbody>
  <thead>
    <tr>
      <th style="text-align: center;">ITEM</th>
      <th style="text-align: center;">PRICE</th>
      <th style="text-align: center;">QTY</th>
      <th style="text-align: center;" ng-show="bill.type=='bad_order'">SETTLEMENT</th>
      <th style="text-align: right;">TOTAL</th>
    </tr>
  </thead>
  <tbody>
    <tr ng-repeat="items in bill_detail" ng-cloak>
      <td style="width: 50%;vertical-align: top;">@{{items.menu}}<b ng-if="items.special_instruction != ''&&bill.type=='good_order'"><br>(@{{items.special_instruction}})</b></td>
      <td style="text-align: center;vertical-align: top;">@{{items.price|currency:""}}</td>
      <td style="text-align: center;vertical-align: top;" ng-bind="items.quantity"></td>
      <td style="text-align: center;vertical-align: top;" ng-show="bill.type=='bad_order'" ng-bind="items.settlement"></td>
      <td style="text-align: right;vertical-align: top;">@{{(items.price*items.quantity)|currency:""}}</td>
    </tr>
  </tbody>
</tbody>
<tfoot>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td ng-show="bill.type=='good_order'" colspan="3" style="text-align: right;">Gross Billing:</td>
    <td ng-show="bill.type=='good_order'" style="text-align: right;">@{{bill.total_item_amount|currency:""}}</td>
  </tr>
  <tr>
    <td ng-show="bill.type=='good_order'" colspan="3" style="text-align: right;">Discount:</td>
    <td ng-show="bill.type=='good_order'" style="text-align: right;">@{{bill.total_discount|currency:""}}</td>
  </tr>
  <tr>
    <td ng-show="bill.type=='good_order'" colspan="3" style="text-align: right;">Discounted Gross Billing:</td>
    <td ng-show="bill.type=='good_order'" style="text-align: right;">@{{bill.gross_billing|currency:""}}</td>
  </tr>
  <tr>
    <td ng-show="bill.type=='good_order'" colspan="3" style="text-align: right;">SC/PWD Discount:</td>
    <td ng-show="bill.type=='good_order'" style="text-align: right;">@{{bill.sc_pwd_discount|currency:""}}</td>
  </tr>
  <tr>
    <td ng-show="bill.type=='good_order'" colspan="3" style="text-align: right;">SC/PWD VAT Exemption:</td>
    <td ng-show="bill.type=='good_order'" style="text-align: right;">@{{bill.sc_pwd_vat_exemption|currency:""}}</td>
  </tr>
  <tr>
    <td ng-show="bill.type=='good_order'" colspan="3" style="text-align: right;">NET Billing:</td>
    <td ng-show="bill.type=='good_order'" style="text-align: right;font-weight: bold;">@{{bill.net_billing|currency:""}}</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr ng-repeat="payment_data in payments" ng-show="has_payment">
    <td ng-show="bill.type=='bad_order'" style="text-align: right;"></td>
    <td colspan="3" style="text-align: right;">@{{payment_data.settlement}}:</td>
    <td style="text-align: right;font-weight: bold;">@{{payment_data.payment|currency:""}}</td>
  </tr>
  <tr ng-show="has_payment">
    <td ng-show="bill.type=='bad_order'" style="text-align: right;"></td>
    <td colspan="3" style="text-align: right;">Change:</td>
    <td style="text-align: right;font-weight: bold;">@{{excess|currency:""}}</td>
  </tr>
</tfoot>
</table>
<br>
<table style="width: 100%">
  <tr>
    <td>Server:</td>
    <td>Cashier:</td>
  </tr>
  <tr>
    <td><b ng-cloak>@{{bill.server_name}}</b></td>
    <td><b ng-cloak>@{{bill.cashier_name}}</b></td>
  </tr>
  <tr>
    <td ng-show="bill.type=='good_order'" colspan="2">Guest Name:</td>
  </tr>
  <tr>
    <th ng-show="bill.type=='good_order'" colspan="2">@{{bill.guest_name}}</th>
  </tr>
  <tr>
    <td ng-show="bill.type=='good_order'" colspan="2">Room Number:</td>
  </tr>
  <tr>
    <td ng-show="bill.type=='good_order'" colspan="2"></td>
  </tr>
  <tr>
    <td ng-show="bill.type=='good_order'" style="border-bottom: 1px solid black;padding-top: 20px;" colspan="2">Signature</td>
  </tr>
</table>
<a href="javascript:void(0);" class="btn btn-primary hideprint" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> Print</a>
<a href="javascript:void(0);" class="btn btn-danger hideprint" onclick="window.close()"><span class="glyphicon glyphicon-remove"></span> Close</a>
@if(Session::get('users.user_data')->privilege=="restaurant_cashier")
@else
<a href="javascript:void(0);" class="btn btn-danger hideprint" ng-click="delete(this)" ng-if="bill.is_paid==1"><span class="glyphicon glyphicon-trash"></span> Delete</a>
@endif
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
    @if($print==1)
    $(document).ready(function() {
      setTimeout(function(){ window.print(); }, 500);
    });
    @endif
    $scope.footer = {};
    $scope.payments = {};
    $scope.formdata = {};
    $scope.formdata._token = "{{csrf_token()}}";
    function show_bill() {
      $http({
          method : "GET",
          url : "/api/restaurant/table/customer/bill/view/"+{{$id}},
      }).then(function mySuccess(response) {
          $scope.bill = response.data.bill;
          $scope.bill_detail = response.data.bill_detail;
          $scope.footer.sub_total =  response.data.sub_total;
          $scope.footer.sc =  response.data.sc;
          $scope.footer.vat =  response.data.vat;
          $scope.footer.total =  response.data.total;
      }, function myError(response) {
          console.log(response.statusText);
      });
    }
    $scope.delete = function(data){
      console.log(data.bill.id);
      alertify.prompt(
        'Check #: '+data.bill.check_number,
        'Reason to delete:',
        '',
        function(evt, value) {
          if(value.trim()!=""){
            var formdata = {};
            formdata.deleted_comment = value;
            formdata.bill_data = data.bill;
            $scope.submit = true;
            $http({
               method: 'POST',
               url: '/api/restaurant/table/customer/bill/delete/'+data.$parent.bill.id,
               data: $.param(formdata),
               headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
            .then(function(response) {
              console.log(response.data);
              $("#view-bill-modal").modal("hide");
              $scope.formdata.deleted_comment = '';
              alertify.success('Check # '+data.$parent.bill.check_number+' has been deleted');
              $scope.submit = false;
            }, function(rejection) {
              if(rejection.status == 500){
                error_505('Server Error, Try Again.');
              }else if(rejection.status == 422){ 
               var errors = rejection.data;
               angular.forEach(errors, function(value, key) {
                   alertify.error(value[0]);
               });
              }
             $scope.submit = false;
            });
          }else{
            alertify.error('Reason to delete message is required.');
          }
        },
        function() {
          
        });
    }
    $scope.has_payment = false;
    show_payment();
    function show_payment() {
      $http({
          method : "GET",
          url : "/api/restaurant/table/customer/payment/list/"+{{$id}},
      }).then(function mySuccess(response) {
          console.log(response.data.result)
          $scope.payments = response.data.result;
          if( typeof Object.keys($scope.payments)[0] === 'undefined' ){
            $scope.has_payment = false;
          }else{
            $scope.has_payment = true;
          }
          $scope.excess = response.data.excess;
      }, function myError(response) {
          console.log(response.statusText);
      });
    }

  });
  angular.bootstrap(document, ['main']);
</script>
@endsection