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
  .spacing{
    width: 30px;
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
    <td><div class="spacing">&nbsp;</div></td>
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
      <th style="text-align: right;"><div class="spacing">&nbsp;</div></th>
    </tr>
  </thead>
  <tbody>
    <tr ng-repeat="items in bill_detail" ng-cloak>
      <td style="width: 50%;vertical-align: top;">@{{items.menu}}<b ng-if="items.special_instruction != '' && items.special_instruction != null && bill.type=='good_order'"><br>(@{{items.special_instruction}})</b></td>
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
  <tr ng-hide="bill.room_service_charge==0">
    <td ng-show="bill.type=='good_order'" colspan="3" style="text-align: right;">Room Service Charge:</td>
    <td ng-show="bill.type=='good_order'" style="text-align: right;">@{{bill.room_service_charge|currency:""}}</td>
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
    <td><div class="spacing">&nbsp;</div></td>
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
<div class="btn-group" role="group" aria-label="...">
  <a href="javascript:void(0);" class="btn btn-info hideprint" ng-click="order_history()"><span class="glyphicon glyphicon-list-alt"></span> Order History</a>
  <a href="javascript:void(0);" class="btn btn-success hideprint" ng-click="show_invoice_number_logs()"><span class="glyphicon glyphicon-edit"></span> Invoice Number Logs</a>
  <a href="javascript:void(0);" class="btn btn-info hideprint" ng-click="prompt_edit_invoice()"><span class="glyphicon glyphicon-edit"></span> Edit Invoice Number</a>
  <a href="javascript:void(0);" class="btn btn-primary hideprint" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> Print</a>
  <a href="javascript:void(0);" class="btn btn-danger hideprint" onclick="window.close()" data-balloon-pos="right" data-balloon="Can be closed by pressing the key X in the keyboard."><span class="glyphicon glyphicon-remove"></span> Close</a>
  <a ng-href="/restaurant/bill/@{{bill.id}}/edit" class="btn btn-success hideprint" ng-hide="customer_data == null" ng-cloak><span class="glyphicon glyphicon-edit"></span> Modify Order Slip</a>

@if(Auth::user()->privilege=="restaurant_cashier")
@else
<a href="javascript:void(0);" class="btn btn-danger hideprint" ng-click="delete(this)" ng-if="bill.is_paid==1&&bill.deleted_at==null"><span class="glyphicon glyphicon-trash"></span> Delete</a>
@endif
</div>
@endsection

@section('modals')
<div id="order-history-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Orders History</h4>
      </div>
      <div class="modal-body">
        <table class="ui unstackable sortable compact table" id="list-order-table">
          <thead>
            <tr>
              <th class="center aligned" width="100%">Order #</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="order_data in orders">
              <td class="center aligned"><p style="cursor: pointer;" data-balloon="Click to View" data-balloon-pos="up" ng-bind="order_data.que_number" ng-click="preview_order(this)"></p></td>
              <td>
                <a class="btn btn-primary" href="javascript:void(0);" ng-click="open_food_order(order_data.id)"> View Food Order</a>
              </td>
            </tr>
          </tbody>
          <tfoot>
            <tr ng-if="orders | isEmpty">
              <td colspan="20" style="text-align: center;">
                <h1>NO DATA</h1>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div id="invoice-number-logs-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Invoice Number Logs</h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Date Modified</th>
                <th style="text-align: center;">Invoice Number</th>
                <th style="text-align: center;">User</th>
              </tr>
            </thead>
            <tbody>
              <tr ng-repeat="item in invoice_number_logs">
                <td>@{{item.created_at}}</td>
                <td style="text-align: center;">@{{item.invoice_number}}</td>
                <td style="text-align: center;">@{{item.user_data.username}}</td>
              </tr>
            </tbody>
            <tfoot>
              <tr ng-if="invoice_number_logs | isEmpty">
                <td colspan="20" style="text-align: center;">
                  <h1 ng-if="loading">
                    <img src="{{asset('assets/images/loading.gif')}}" style="height: 70px;">
                    <br>
                    LOADING
                  </h1>
                  <h1>
                    <span ng-if="!loading" ng-cloak>NO DATA</span>
                    <span ng-if="loading" ng-cloak></span>
                  </h1>
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="ui default button" data-dismiss="modal">Close</button>
        <button type="submit" class="ui primary button" form="add-table-form" ng-disabled="submit" ng-show="has_table" ng-class="{'loading':submit}">Submit</button>
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
  
  app.controller('content-controller', function($scope,$http, $sce) {
    shortcut.add("x",function() {
      window.close();
    });
    shortcut.add("esc",function() {
      window.close();
    });
    @if($print==1)
    $(document).ready(function() {
      setTimeout(function(){ window.print(); }, 1000);
    });
    @endif

    $scope.open_food_order = function(id) {
      window.open('/restaurant/order/'+id,
      'newwindow', 
      'width=500,height=600');
      return false;
    }
    $scope.footer = {};
    $scope.payments = {};
    $scope.formdata = {};
    $scope.loading = true;
    $scope.invoice_number_logs = {};
    $scope.bill_info = {!! json_encode($bill_info) !!};
    $scope.bill = {!! json_encode($bill_info['bill']) !!};
    $scope.bill_detail = {!! json_encode($bill_info['bill_detail']) !!};
    $scope.excess = {!! json_encode($payment_data['excess']) !!};
    $scope.payments = {!! json_encode($payment_data['result']) !!};
    $scope.has_payment = {!! json_encode($has_payment) !!};
    $scope.customer_data = {!! json_encode($customer_data) !!};
    $scope.orders = {!! json_encode($orders) !!};

    $scope.order_history = function() {
      $('#order-history-modal').modal('show');
    }

    $scope.prompt_edit_invoice = function() {
      alertify.prompt(
        'Edit Invoice Number',
        '',
        $scope.bill.invoice_number,
        function(evt, value){
          $http({
             method: 'PUT',
             url: '/api/restaurant/table/customer/bill/view/'+$scope.bill.id,
             data: $.param({invoice_number:value}),
             headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
          })
          .then(function(response) {
            console.log(response.data);
            $.notify('The Invoice number is now ' + value);
            $scope.bill.invoice_number = value;
          }, function(rejection) {
            if(rejection.status != 422){
              request_error(rejection.status);
            }else if(rejection.status == 422){ 
             var errors = rejection.data;
            }
           $scope.submit = false;
          });
        },
        function(){
          //cancel
        });
    }

    $scope.show_invoice_number_logs = function() {
      $('#invoice-number-logs-modal').modal('show');
      $scope.invoice_number_logs = {};
      $scope.loading = true;
      $http({
        method: "GET",
        url: "/api/restaurant/table/customer/bill/invoice-number-logs/" + $scope.bill.id,
      }).then(function mySuccess(response) {
        $scope.loading = false;
        $scope.invoice_number_logs = response.data;
      }, function(rejection) {
        if (rejection.status != 422) {
          request_error(rejection.status);
        } else if (rejection.status == 422) {
          console.log(rejection.statusText);
        }
        $scope.loading = false;
      });
    }
    $scope.delete = function(data){
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
              $.notify('Check # '+data.$parent.bill.check_number+' has been deleted');
              $scope.bill.deleted_at = true;
              $scope.submit = false;
            }, function(rejection) {
              if(rejection.status != 422){
                request_error(rejection.status);
              }else if(rejection.status == 422){ 
               var errors = rejection.data;
               angular.forEach(errors, function(value, key) {
                   $.notify(value[0],'error');
               });
              }
             $scope.submit = false;
            });
          }else{
            $.notify('Reason to delete message is required.','error');
          }
        },
        function() {
          
        });
    }
  });
  
</script>
@endpush