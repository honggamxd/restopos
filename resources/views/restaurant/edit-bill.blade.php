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
@section('modals')
<div id="add-payment-modal" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Payment</h4>
      </div>
      <div class="modal-body">
        <form id="add-payment-form" ng-submit="add_payment()">
        {{ csrf_field() }}
        <div class="form-group">
          <label>Type of Settlement</label>
          <select class="form-control" ng-model="new_settlement_formdata.type" ng-options="item as item.label for item in settlements track by item.value">
            <option value="">Select Settlement</option>
          </select>
          <p class="help-block">@{{new_settlement_formerrors.type[0]}}</p>

          <label>Amount</label>
          <input class="form-control" ng-model="new_settlement_formdata.amount" type="number" step="0.01">
          <p class="help-block">@{{new_settlement_formerrors.amount[0]}}</p>
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="ui default button" data-dismiss="modal">Close</button>
        <button type="submit" class="ui primary button" form="add-payment-form" ng-disabled="submit" ng-class="{'loading':submit}">Submit</button>
      </div>
    </div>

  </div>
</div>
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
    <td ng-show="bill.type=='good_order'"># of Pax: <b ng-cloak>
      <input type="number" ng-model="bill.pax" min="0">
    </b></td>
    <td ng-show="bill.type=='good_order'"># of SC/PWD: <b ng-cloak>
      <input type="number" min="0" max="@{{bill.pax}}" ng-model="bill.sc_pwd" ng-change="change_sc_pwd()">
    </b></td>
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
      <td style="width: 50%;vertical-align: top;">
        <select ng-model="items.menu_data" ng-options="item as item.name for item in menu track by item.id">
        </select>
        <b ng-if="items.special_instruction != '' && items.special_instruction != null && bill.type=='good_order'"><br>(@{{items.special_instruction}})</b></td>
      <td style="text-align: center;vertical-align: top;">
        <input type="number" step="0.01" min="0" style="text-align: right;" ng-model="items.price">
      </td>
      <td style="text-align: center;vertical-align: top;">
        <input type="number" min="0" style="text-align: center;" ng-model="items.quantity">
      </td>
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
    <td ng-show="bill.type=='good_order'" style="text-align: right;">@{{gross_billing()|currency:""}}</td>
  </tr>
  <tr>
    <td ng-show="bill.type=='good_order'" colspan="3" style="text-align: right;">
      <div class="checkbox">
        <label><input type="checkbox" ng-model="has_room_service_charge" ng-change="toggle_room_service_charge()">Room Service Charge:</label>
      </div>
    </td>
    <td ng-show="bill.type=='good_order'" style="text-align: right;">@{{room_service_charge()|currency:""}}</td>
  </tr>
  <tr>
    <td ng-show="bill.type=='good_order'" colspan="3" style="text-align: right;">Discount:</td>
    <td ng-show="bill.type=='good_order'" style="text-align: right;">
      <input type="number" style="text-align: right;" ng-model="total_discount" min="0" step="0.01" max="@{{gross_billing()}}">
    </td>
  </tr>
  <tr>
    <td ng-show="bill.type=='good_order'" colspan="3" style="text-align: right;">Discounted Gross Billing:</td>
    <td ng-show="bill.type=='good_order'" style="text-align: right;">@{{discounted_gross_billing()|currency:""}}</td>
  </tr>
  <tr>
    <td ng-show="bill.type=='good_order'" colspan="3" style="text-align: right;">SC/PWD Discount:</td>
    <td ng-show="bill.type=='good_order'" style="text-align: right;">@{{sc_pwd_discount()|currency:""}}</td>
  </tr>
  <tr>
    <td ng-show="bill.type=='good_order'" colspan="3" style="text-align: right;">SC/PWD VAT Exemption:</td>
    <td ng-show="bill.type=='good_order'" style="text-align: right;">@{{sc_pwd_vat_exemption()|currency:""}}</td>
  </tr>
  <tr>
    <td ng-show="bill.type=='good_order'" colspan="3" style="text-align: right;">NET Billing:</td>
    <td ng-show="bill.type=='good_order'" style="text-align: right;font-weight: bold;">@{{net_billing()|currency:""}}</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr ng-repeat="(index,payment_data) in payments" ng-show="has_payment">
    <td ng-show="bill.type=='bad_order'" style="text-align: right;"></td>
    <td colspan="3" style="text-align: right;">
      <a href="javascipt:void(0)" ng-click="delete_settlement_confirmation(payment_data,index)" style="cursor: pointer" title="Delete Settlement">X</a>&nbsp;
      <select ng-model="payment_data.settlement_array" ng-options="item as item.label for item in settlements track by item.value" ng-change="change_settlement(this)">
      </select>
    :</td>
    <td style="text-align: right;font-weight: bold;">
      <input type="number" min="0" step="0.01" ng-model="payment_data.payment" style="text-align: right;width: 100px;">
    </td>
  </tr>
  <tr ng-show="has_payment">
    <td ng-show="bill.type=='bad_order'" style="text-align: right;"></td>
    <td colspan="3" style="text-align: right;">Remaining Balance:</td>
    <td style="text-align: right;font-weight: bold;">@{{remaining_balance()|currency:""}}</td>
  </tr>
  <tr ng-show="has_payment">
    <td ng-show="bill.type=='bad_order'" style="text-align: right;"></td>
    <td colspan="3" style="text-align: right;">Total Payments:</td>
    <td style="text-align: right;font-weight: bold;">@{{total_payments()|currency:""}}</td>
  </tr>
  <tr ng-show="has_payment">
    <td ng-show="bill.type=='bad_order'" style="text-align: right;"></td>
    <td colspan="3" style="text-align: right;">Change:</td>
    <td style="text-align: right;font-weight: bold;">@{{payment_change()|currency:""}}</td>
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
    <th ng-show="bill.type=='good_order'" colspan="2">
      <input type="text" ng-model="bill.guest_name">
    </th>
  </tr>
</table>
<div class="btn-group" role="group" aria-label="...">
  <button class="btn btn-primary" ng-click="save()">Save Changes</button>
  <button class="btn btn-success" ng-click="add_payment_form()">Add Payment</button>
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
    $scope.new_settlement_formdata = {
      type: null,
      amount: 0,
    };
    $scope.new_settlement_formerrors = {};
    $scope.footer = {};
    $scope.payments = {};
    $scope.formdata = {};
    $scope.bill_info = {!! json_encode($bill_info) !!};
    $scope.bill = {!! json_encode($bill_info['bill']) !!};
    $scope.bill_detail = {!! json_encode($bill_info['bill_detail']) !!};
    $scope.excess = {!! json_encode($payment_data['excess']) !!};
    $scope.payments = {!! json_encode($payment_data['result']) !!};
    $scope.has_payment = {!! json_encode($has_payment) !!};
    $scope.customer_data = {!! json_encode($customer_data) !!};
    $scope.settlements = {!! json_encode($settlements) !!};
    $scope.menu = {!! json_encode($menu) !!};
    $scope.total_discount = parseFloat($scope.bill.total_discount);
    $scope.items_has_sundry = function() {
      items_has_sundry = false;
      angular.forEach($scope.bill_detail, function(value, key) {
        if(value.menu_data.category.toUpperCase()=="SUNDRY"){
          items_has_sundry = true;
          $scope.bill.sc_pwd = 0;
        }
      });
      return items_has_sundry;
    }
    $scope.bill.sc_pwd = parseInt($scope.bill.sc_pwd);
    $scope.bill.pax = parseInt($scope.bill.pax);
    angular.forEach($scope.bill_detail, function(value, key) {
      $scope.bill_detail[key].quantity = parseInt($scope.bill_detail[key].quantity);
      $scope.bill_detail[key].price = parseFloat($scope.bill_detail[key].price);
    });

    angular.forEach($scope.payments, function(value, key) {
      $scope.payments[key].payment = parseFloat($scope.payments[key].payment);
    });

    $scope.change_sc_pwd = function() {
      $scope.bill.sc_pwd = $scope.items_has_sundry() ? 0 : $scope.bill.sc_pwd;
      if($scope.items_has_sundry()){
        $.notify('The number of SC/PWD Must be 0 if the items to bill has Sundries.','error');
      }
    }
    $scope.gross_billing = function() {
      gross_billing = 0;
      angular.forEach($scope.bill_detail, function(value, key) {
        gross_billing += (value.quantity * value.price)
      });
      return gross_billing;
    }
    $scope.has_room_service_charge = ($scope.bill.room_service_charge!=0);
    $scope.room_service_charge = function() {
      return ($scope.has_room_service_charge?$scope.gross_billing()*.1:0);
    }
    $scope.discounted_gross_billing = function() {
      return $scope.gross_billing()+$scope.room_service_charge()-$scope.total_discount; 
    }

    $scope.sc_pwd_discount = function() {
      return $scope.items_has_sundry() ? 0 :$scope.discounted_gross_billing()*$scope.bill.sc_pwd/$scope.bill.pax/1.12*.2;
    }

    $scope.sc_pwd_vat_exemption = function() {
      return $scope.items_has_sundry() ? 0 :$scope.discounted_gross_billing()*$scope.bill.sc_pwd/$scope.bill.pax/1.12*.12;
    }

    $scope.net_billing = function() {
      return $scope.discounted_gross_billing()-$scope.sc_pwd_discount()-$scope.sc_pwd_vat_exemption();
    }
    $scope.total_payments = function() {
      total_payments = 0;
      angular.forEach($scope.payments, function(value, key) {
        total_payments += value.payment
      });
      return total_payments;
    }
    $scope.remaining_balance = function() {
      remaining_balance = $scope.net_billing()-$scope.total_payments();
      remaining_balance = Math.round(remaining_balance * 100) / 100;
      return (remaining_balance<=0?0:remaining_balance);
    }
    $scope.payment_change = function() {
      payment_change = $scope.total_payments()-$scope.net_billing();
      return (payment_change<=0?0:payment_change);
    }

    $scope.change_settlement = function(data) {
      data.payment_data.settlement = data.payment_data.settlement_array.label;
    }
    $scope.save = function() {
      if($scope.remaining_balance()!=0){
        $.notify('Unable to modify, the remaining balance must be 0 in order to modify.','error');
        return false;
      }
      let bill = {};
      angular.copy($scope.bill,bill);
      bill.gross_billing = $scope.discounted_gross_billing();
      bill.total_item_amount = $scope.gross_billing();
      bill.room_service_charge = $scope.room_service_charge();
      bill.discounted_gross_billing = $scope.discounted_gross_billing();
      bill.sc_pwd_discount = $scope.sc_pwd_discount();
      bill.sc_pwd_vat_exemption = $scope.sc_pwd_vat_exemption();
      bill.net_billing = $scope.net_billing();
      bill.sales_net_of_vat_and_service_charge = $scope.discounted_gross_billing()/1.12*.9;
      bill.service_charge = $scope.discounted_gross_billing()/1.12*0.1;
      bill.vatable_sales = bill.sales_net_of_vat_and_service_charge+bill.service_charge;
      bill.output_vat = bill.vatable_sales*.12;
      bill.sales_inclusive_of_vat = bill.vatable_sales+bill.output_vat;
      bill.excess = $scope.payment_change();
      bill.total_discount = $scope.total_discount;
      bill = _.omit(bill,'check_number','is_paid','date_','date_time','server_id','table_name','reason_cancelled','type','deleted','deleted_by','deleted_comment','deleted_date','deleted_at','created_at','updated_at','restaurant_name','invoice_number','meal_type');
      let bill_detail = [];
      angular.copy($scope.bill_detail,bill_detail);
      angular.forEach(bill_detail, function(value, key) {
        bill_detail[key] = {
          id: value.id,
          quantity: value.quantity,
          price: value.price,
          restaurant_menu_id: value.menu_data['id'],
        }
      });

      let payments = [];
      angular.copy($scope.payments,payments);
      angular.forEach(payments, function(value, key) {
        payments[key] = {
          id: value.id,
          payment: value.payment,
          settlement_array: {
            value: value.settlement_array.value
          }
        }
      });
      $scope.formdata = {
        bill: bill,
        bill_detail: bill_detail,
        excess: $scope.excess,
        payments: payments,
      }
      $http({
        method: 'PUT',
        url: '/restaurant/bill/'+$scope.bill.id+'/edit',
        data: $.param($scope.formdata),
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      }).then(function(response) {
        $.notify('This Order Slip has been modified.');
        setTimeout(function(){
          // window.location = '/restaurant/bill/'+$scope.bill.id;
        }, 3000);
      }, function(rejection) {
        if (rejection.status != 422) {
          request_error(rejection.status);
        } else if (rejection.status == 422) {
          var errors = rejection.data;
          $scope.formerrors.sc_pwd = errors.sc_pwd;
          $scope.formerrors.pax = errors.pax;
          $scope.formerrors.server_id = errors['server_id.id'];
        }
        $scope.submit = false;
      });
      console.log($scope.formdata);
    }

    $scope.delete_settlement_confirmation = function(payment_data,index) {
      console.log(payment_data);
      console.log(index);
      alertify.confirm(
        'DELETE  <b style=" text-transform: uppercase;">'+payment_data.settlement+'</b>',
        'Are you sure you want to delete this  <b>'+payment_data.settlement+'</b> settlement?',
        function(){
          $scope.delete_settlement(payment_data,index);
        },
        function()
        {
          // alertify.error('Cancel')
        }
      );
    }
    
    $scope.delete_settlement = function(payment_data,index) {
      $http({
        method: 'DELETE',
        url: '/api/restaurant/table/customer/payment/delete/'+payment_data.id,
      })
      .then(function(response) {
        $.notify('The settlement '+payment_data.settlement+' has been deleted.');
        $scope.payments.splice(index,1);
      }, function(rejection) {
        var errors = rejection.data;
        if(rejection.status != 422){
          request_error(rejection.status);
        }else if(rejection.status == 422){        
            var errors = rejection.data;
            $scope.formerrors = errors;
            $scope.submit = false;
        }
      });
    }

    $scope.add_payment_form = function() {
      $('#add-payment-modal').modal('show');
      $scope.new_settlement_formdata = {
        type: null,
        amount: 0,
      }
      $scope.new_settlement_formerrors = {};
    }

    $scope.add_payment = function() {
      $http({
        method: 'POST',
        url: '/api/restaurant/table/customer/payment/add/'+$scope.bill.id,
        data: $.param($scope.new_settlement_formdata),
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      }).then(function(response) {
        $scope.has_payment = true;
        if(!angular.equals($scope.payments,[])){
          $scope.payments.push(response.data);
        }else{
          $scope.payments = [];
          $scope.payments[0] = response.data;
        }
        console.log($scope.payments);
        $('#add-payment-modal').modal('hide');
        $.notify('A new settlement has been added.');
      }, function(rejection) {
        if (rejection.status != 422) {
          request_error(rejection.status);
        } else if (rejection.status == 422) {
          var errors = rejection.data;
          $scope.new_settlement_formerrors = errors;
        }
        $scope.submit = false;
      });
    }

  });
  
</script>
@endpush