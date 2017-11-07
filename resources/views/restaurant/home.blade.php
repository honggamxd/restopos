@extends('layouts.main')

@section('title', Session::get('users.user_data')->restaurant)

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
.modal-lg.order{
  width: 95vw;
}

#complete-order-table{
  width: 100%;
  border-collapse: collapse;
}
#complete-order-table tr td,#complete-order-table tr th{
  border: 1px solid black;
  padding: 0px 2px 0px 2px;
}

.order-table{
  width: 100%;
  border-collapse: collapse;
  margin: 0;
}



</style>
@endsection
@section('breadcrumb')
<div class="active section">{{Session::get('users.user_data')->restaurant}}</div>
@endsection
@section('content')
<div class="col-sm-12">
  <h1 style="text-align: center;">{{Session::get('users.user_data')->restaurant}}</h1>
  <div class="form-group">
      <!-- <div class="ui buttons"> -->
<!--         <input type="text" placeholder="Search Table">
        <button class="ui icon button" type="submit">
          <i class="search icon"></i>
        </button> -->
        <button type="button" class="ui icon primary button" ng-click="show_table()" data-balloon="Keyboard Shortcut: Alt + A" data-balloon-pos="down" data-balloon-length="fit"><i class="add icon"></i> Add Customer</button>
      <!-- </div> -->
  </div>
  <div class="table-responsive">
    <table class="ui unstackable sortable celled table" id="customer-table">
      <thead>
        <tr>
          <th class="center aligned">Table</th>
          <th class="center aligned">Guest Name</th>
          <th class="center aligned">Server</th>
          <th class="center aligned">Time</th>
          <th class="center aligned">Pax</th>
          <th class="center aligned">SC/PWD</th>
          <th class="center aligned">Total</th>
        </tr>
      </thead>
      <tbody>
        <tr ng-repeat="customer_data in table_customers" ng-class="{'warning':customer_data.has_cancellation_request==1}" ng-cloak>
          <td style="width: 30vw;" class="center aligned middle aligned">
          <p style="cursor: pointer;" ng-click="edit_table_customer(this)" data-balloon="Click to edit customer information." data-balloon-pos="up">
            @{{customer_data.table_name}}
          </p>
          </td>
          <td class="center aligned middle aligned" ng-bind="customer_data.guest_name"></td>
          <td class="center aligned middle aligned" ng-bind="customer_data.server_name"></td>
          <td class="center aligned middle aligned" ng-bind="customer_data.date_time"></td>
          <td class="center aligned middle aligned"><i class="fa fa-users" aria-hidden="true"></i> @{{customer_data.pax}}</td>
          <td class="center aligned middle aligned"><i class="fa fa-users" aria-hidden="true"></i> @{{customer_data.sc_pwd}}</td>
          <td class="center aligned middle aligned" ng-click="view_orders(this)" data-balloon="Click to View Orders or open a cancellation request." data-balloon-pos="up">
            <p>
              <a href="javascript:void(0);">@{{customer_data.total|currency:""}}</a>
            </p>
          </td>
          <td class="left aligned middle aligned" style="width: 28vw;">
            <div class="ui buttons" ng-if="!customer_data.has_order">
              <button class="ui inverted green button" ng-click="add_order(this)">
                <i class="fa fa-file-text-o" aria-hidden="true"></i> Order
              </button>
              <button class="ui inverted red button" ng-click="delete_table_customer(this)">
                <i class="fa fa-trash-o" aria-hidden="true"></i> Remove
              </button>
            </div>
            <div class="ui buttons" ng-if="customer_data.has_order">
              <div class="ui buttons">
                <button class="ui inverted green button" ng-click="add_order(this)" ng-if="!customer_data.has_billed_out">
                  <i class="fa fa-file-text-o" aria-hidden="true"></i> Order
                </button>
                <button class="ui inverted violet button" ng-click="bill_out(this)" ng-disabled="bill_out_submit" ng-class="{'loading':bill_out_submit}">
                  <i class="fa fa-calculator" aria-hidden="true"></i> Bill out
                </button>
                <button class="ui inverted brown button" ng-click="view_bills(this)" ng-if="customer_data.has_bill">
                  <i class="fa fa-list-alt" aria-hidden="true"></i> View Bills
                </button>
                <button class="ui inverted red button" ng-click="delete_table_customer(this)" ng-if="customer_data.has_paid == 1 && (customer_data.cancellation_order_status==0 || customer_data.cancellation_order_status==2)">
                  <i class="fa fa-trash-o" aria-hidden="true"></i> Remove
                </button>
                <button class="ui inverted green button" ng-click="settlement_cancelled_orders(this)" ng-if="customer_data.has_paid == 1 && (customer_data.cancellation_order_status==1)">
                  <i class="fa fa-window-close" aria-hidden="true"></i> Settle Cancelled Orders
                </button>
              </div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <small>* The highlighed row has a pending cancellation of orders request.</small>
  </div>  
</div>
@endsection

@section('modals')



<div id="add-table-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Customer</h4>
      </div>
      <div class="modal-body">
        <form id="add-table-form" ng-submit="add_table()">
        {{ csrf_field() }}
        <input type="hidden" name="restaurant_id" ng-model="formdata.restaurant_id">
        <label>Table:</label>
        <div class="form-group" ng-show="has_table">
          <select class="form-control" id="select-tablenumber" name="table_id" ng-model="formdata.table_id" ng-options="item as item.table_name_status for item in table track by item.id">
          </select>
        </div>
        <div class="form-group" ng-hide="has_table">
          <span class="form-control">No Available Table</span>
        </div>
        <div class="form-group">
          <label>Number of Pax</label>
          <input class="form-control" min="1" type="number" placeholder="Enter Number of Pax" name="pax" ng-model="formdata.pax" ng-blur="pax_changed(this)">
          <p class="help-block">@{{formerrors.pax[0]}}</p>
        </div>
        <div class="form-group">
          <label>Number of SC/PWD:</label>
          <input class="form-control" min="0" max="@{{formdata.pax}}" type="number" placeholder="Enter Number of SC/PWD" name="sc_pwd" ng-model="formdata.sc_pwd">
          <p class="help-block">@{{formerrors.sc_pwd[0]}}</p>
        </div>
        <div class="form-group">
          <label>Guest Name</label>
          <input class="form-control" type="text" placeholder="Enter Guest Name" name="pax" ng-model="formdata.guest_name">
          <p class="help-block">@{{formerrors.guest_name[0]}}</p>
        </div>

        <div class="form-group">
          <label>Server</label>
          <select class="form-control" ng-model="formdata.server_id" ng-options="item as item.name for item in server track by item.id">
          </select>
          <p class="help-block">@{{formerrors.server_id[0]}}</p>
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="ui default button" data-dismiss="modal">Close</button>
        <button type="submit" class="ui primary button" form="add-table-form" ng-disabled="submit" ng-show="has_table" ng-class="{'loading':submit}">Submit</button>
      </div>
    </div>

  </div>
</div>


<div id="edit-table-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Customer</h4>
      </div>
      <div class="modal-body">
        <form id="edit-table-form" ng-submit="edit_table(this)">

          <label>Table:</label>
          <div class="form-group" ng-show="has_table">
            <select class="form-control" id="select-tablenumber" name="table_id" ng-model="formdata.table_id" ng-options="item as item.table_name_status for item in table track by item.id">
            </select>
          </div>

          <div class="form-group" ng-hide="has_table">
            <span class="form-control">No Available Table</span>
          </div>
          <div class="form-group">
            <label>Number of Pax</label>
            <input class="form-control" min="1" type="number" placeholder="Enter Number of Pax" name="pax" ng-model="formdata.pax" ng-blur="pax_changed(this)">
            <p class="help-block">@{{formerrors.pax[0]}}</p>
          </div>
          <div class="form-group">
            <label>Number of SC/PWD:</label>
            <input class="form-control" min="0" max="@{{formdata.pax}}" type="number" placeholder="Enter Number of SC/PWD" name="sc_pwd" ng-model="formdata.sc_pwd">
            <p class="help-block">@{{formerrors.sc_pwd[0]}}</p>
          </div>
          <div class="form-group">
            <label>Guest Name</label>
            <input class="form-control" type="text" placeholder="Enter Guest Name" name="pax" ng-model="formdata.guest_name">
            <p class="help-block">@{{formerrors.guest_name[0]}}</p>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="ui default button" data-dismiss="modal">Close</button>
        <button type="submit" class="ui primary button" form="edit-table-form" ng-disabled="submit" ng-show="has_table" ng-class="{'loading':submit}">Submit</button>
      </div>
    </div>

  </div>
</div>


<div id="add-order-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-lg order">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Orders</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal ui form">
        {{ csrf_field() }}
        <input type="hidden" name="table_customer_id" ng-model="table_customer_id">
        <div class="row">
          <div class="col-sm-6">
            <div class="field">

            <div class="two fields">
              <div class="field">
                <label>Category</label>
                <select class="ui dropdown fluid" ng-change="category_change_menu_list(this)" ng-model="select_category" ng-init="select_category='all'">
                  <option value="all">All Menu</option>
                  @foreach($categories as $category)
                  <option value="{{$category}}">{{$category}}</option>
                  @endforeach
                </select>
              </div>
              <div class="field">
                <label>Category</label>
                <select class="form-control" ng-change="subcategory_change_menu_list(this)" ng-model="select_subcategory" ng-options="item for item in subcategories">
                <option value="">All Subcategories</option>
                </select>
              </div>
            </div>
              <div class="fields">
                <div class="twelve wide field">
                  <label>Menu Search</label>
                  <div class="ui fluid icon input focus">
                    <input type="text" placeholder="Search..." id="search-menu" ng-model="search_menu">
                    <i class="search icon"></i>
                  </div>
                </div>
                <div class="four wide field">
                  <label>&nbsp;</label>
<!--                   <button type="button" class="ui primary button fluid" onclick="$('#add-menu-modal').modal('show')">
                    Add Menu
                  </button> -->
                </div>
              </div>

            </div>

            <div class="table-responsive" style="height: 30vh;">
              <table class="ui compact table" id="menu-table">
                <thead>
                <tr>
                  <th class="center aligned">Menu</th>
                  <th class="center aligned">Price</th>
                  <th class="center aligned"></th>
                </tr>
                </thead>
                <tbody>
                  <tr ng-repeat="menu_data in menu">
                    <td class="left aligned middle aligned">@{{menu_data.name}}</td>
                    <td class="center aligned middle aligned">@{{menu_data.price|currency:""}}</td>
                    <td class="right aligned middle aligned">
                      <button type="button" class="ui positive button" ng-click="add_cart(this)" ng-disabled="add_cart_submit" ng-class="{'loading':add_cart_submit}"><i class="fa fa-arrow-right" aria-hidden="true"></i> Place Order</button>
                    </td>
                  </tr>
                </tbody>
                <tfoot>

                </tfoot>
              </table>
            </div>
          </div>
          <div class="col-sm-6">
            <p>Table #: <b>@{{table_name}}</b></p>
            <div class="table-responsive" style="height: 42.5vh;">
              <table class="ui compact table unstackable">
                <thead>
                  <tr>
                    <th class="center aligned" style="width: 100%">Orders</th>
                    <th class="center aligned">Quantity</th>
                    <th class="center aligned">Total</th>
                    <th class="center aligned"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr ng-repeat="cart_data in table_customer_cart" ng-init="table_customer_cart={};table_customer_total=''">
                    <td class="center aligned middle aligned">
                      <div style="width: 100%;cursor: pointer;" ng-click="toggle_special_instruction(this)" ng-init="cart_data.show_special_instruction=false">
                        @{{cart_data.name}}
                      </div>
                      <input type="text" name="" ng-model="cart_data.special_instruction" ng-change="add_special_instruction(this)" ng-if="cart_data.special_instruction != '' || cart_data.show_special_instruction">
                    </td>
                    <td class="center aligned middle aligned" ng-init="cart_data.update_quantity=true">
                      <div ng-hide="cart_data.show_update_quantity" ng-click="toggle_update_quantity(this)" style="width: 100%;cursor: pointer;">@{{cart_data.quantity}}</div>
                      <input style="width: 100px" type="number" ng-show="cart_data.show_update_quantity" ng-model="cart_data.quantity" ng-blur="update_quantity(this)" focus-me="cart_data.show_update_quantity">
                    </td>
                    <td class="right aligned middle aligned">@{{cart_data.total|currency:""}}</td>
                    <td class="right aligned middle aligned">
                      <button class="ui icon red inverted button" ng-click="remove_item_cart(this)">
                        <i class="trash outline icon"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <th class="right aligned" colspan="2">Total:</th>
                    <th class="right aligned">@{{table_customer_total|currency:""}}</th>
                    <th class="right aligned"></th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="ui default button" data-dismiss="modal">Close</button>
        <button type="button" class="ui primary button" ng-disabled="submit" ng-click="make_orders(this)" ng-class="{'loading':submit}">Confirm</button>
      </div>
    </div>

  </div>
</div>

<div id="view-list-order-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Orders of Table @{{table_name}}</h4>
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
                <a class="btn btn-primary" href="/restaurant/order/@{{order_data.id}}?print=1" target="_blank"><span class="glyphicon glyphicon-print"></span> Print</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" ng-if="(customer_data.has_billed_out==1&&customer_data.has_paid == 0) && customer_data.has_cancellation_request==0" ng-click="cancellation_orders('after',this)">Cancellation</button>
        <button type="button" class="btn btn-info" ng-if="customer_data.has_cancellation_request==1" ng-click="delete_cancellation_request(this)">Delete Request</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="view-order-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Order</h4>
      </div>
      <div class="modal-body">
        <h3 style="text-align: center;">FOOD ORDER @{{order.que_number}}</h3>
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
        <table class="order-table">
        <tbody>
          <thead>
            <tr>
              <th style="text-align: center;">ITEM</th>
              <th style="text-align: center;">QTY</th>
              <th style="text-align: right;">TOTAL</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="items in order_detail" ng-cloak>
              <td>@{{items.restaurant_menu_name}}<span ng-if="items.special_instruction != ''"><br>(@{{items.special_instruction}})</span></td>
              <td style="text-align: center;" ng-bind="items.quantity"></td>
              <td style="text-align: right;">@{{items.quantity*items.price|currency:""}}</td>
            </tr>
          </tbody>
        </tbody>
        </table>
        <br>
        <p>Server: <span ng-cloak>@{{order.server_name}}</span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" ng-hide="order.has_cancellation_request==1||customer_data.has_paid==1||customer_data.has_billed_out==1" ng-click="cancellation_orders('before',this)">Cancellation</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <a class="btn btn-primary" href="/restaurant/order/@{{order.id}}?print=1" target="_blank"><span class="glyphicon glyphicon-print"></span> Print</a>
      </div>
    </div>
  </div>
</div>


<div id="before-bill-out-cancellation-order-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cancellation of Orders</h4>
      </div>
      <div class="modal-body">
        <form id="before-bill-out-cancellation-order-form">      
          <table class="ui unstackable sortable compact table">
            <thead>
              <tr>
                <th>Menu</th>
                <th>Qty of orders</th>
                <th>Qty of cancellation</th>
              </tr>
            </thead>
            <tbody>
              <tr ng-repeat="item in order_detail">
                <td>@{{item.menu}}</td>
                <td>@{{item.quantity}}</td>
                <td><input type="number" ng-model="item.quantity_to_cancel"></td>
              </tr>
            </tbody>
          </table>
          <label>Message:</label>
          <div class="form-group">
            <textarea class="form-control" ng-model="formdata.reason_cancelled" required></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="ui default button" data-dismiss="modal">Close</button>
        <button type="submit" class="ui positive button" form="before-bill-out-cancellation-order-form" ng-click="cancel_orders('before',this)" ng-disabled="submit" ng-class="{'loading':submit}">Request for Cancellation</button>
      </div>
    </div>
  </div>
</div>


<div id="after-bill-out-cancellation-order-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cancellation of Orders</h4>
      </div>
      <div class="modal-body">
        <form id="after-bill-out-cancellation-order-form">
          <table class="ui compact table unstackable">
            <thead>
              <tr>
                <th class="center aligned" style="width: 100%">Item</th>
                <th class="center aligned">Qty</th>
                <th class="center aligned">Qty to cancel</th>
                <th class="right aligned">Price</th>
              </tr>
            </thead>
            <tbody>
              <tr ng-repeat="bill_preview_data in bill_preview.items">
                <td class="left aligned middle aligned">@{{bill_preview_data.name}}<span ng-if="bill_preview_data.special_instruction != ''"><br>(@{{bill_preview_data.special_instruction}})</span></td>
                <td class="center aligned middle aligned">@{{bill_preview_data.quantity}}</td>
                <td class="center aligned middle aligned">
                  <input style="width: 100px" type="number" min="0" ng-init="bill_preview_data.quantity_to_cancel=0" ng-model="bill_preview_data.quantity_to_cancel" value="@{{bill_preview_data.quantity_to_cancel}}">
                </td>
                <td class="right aligned middle aligned">@{{bill_preview_data.price|currency:""}}</td>
              </tr>
            </tbody>
          </table>
          <label>Message:</label>
          <div class="form-group">
            <textarea class="form-control" ng-model="formdata.reason_cancelled" required></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="ui default button" data-dismiss="modal">Close</button>
        <button type="submit" class="ui positive button" ng-click="cancel_orders('after',this)" form="after-bill-out-cancellation-order-form" ng-disabled="submit" ng-class="{'loading':submit}">Request for Cancellation</button>
      </div>
    </div>
  </div>
</div>


<div id="bill-preview-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Bill Summary</h4>
      </div>
      <div class="modal-body">
        <form class="ui form" id="make-bill-form" ng-submit="make_bill(this)">
          <div class="field">
            <div class="two fields">
              <div class="field">
                <label>Number of Pax:</label>
                <input type="number" ng-model="bill_preview.customer_data.pax" value="@{{bill_preview.customer_data.pax}}" min="0" max="@{{max}}" ng-change="compute_net_total()">
              </div> 
              <div class="field">
                <label>Number of SC/PWD:</label>
                <input type="number" min="0" max="@{{bill_preview.customer_data.pax}}" ng-model="bill_preview.customer_data.sc_pwd" value="@{{bill_preview.customer_data.sc_pwd}}" placeholder="Number of SC/PWD" ng-change="compute_net_total()">
              </div>
            </div>
          </div>
          <div class="field">
            <div class="two fields">
              <div class="field">
                <label>Percentage of Discount:</label>
                <div class="ui right labeled input">
                  <input type="number" min="0" max="100" placeholder="Percentage of Discount" ng-model="bill_preview.discount.percent_disount" ng-change="compute_net_total()">
                  <div class="ui label">
                    %
                  </div>
                </div>
              </div> 
              <div class="field">
                <label>Amount of Discount:</label>
                <input type="number" step="0.01" min="0" max="@{{bill_preview.total}}" placeholder="Amount of Discount" ng-model="bill_preview.discount.amount_disount" ng-change="compute_net_total()">
              </div>
            </div>
          </div>
        </form>
        
        <table class="ui compact table unstackable">
          <thead>
            <tr>
              <th class="center aligned" style="width: 100%">Item</th>
              <th class="center aligned">Qty</th>
              <th class="center aligned">Qty to bill</th>
              <th class="right aligned">Price</th>
              <th class="right aligned">Total</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="bill_preview_data in bill_preview.items">
              <td class="left aligned middle aligned">@{{bill_preview_data.name}}<span ng-if="bill_preview_data.special_instruction != ''"><br>(@{{bill_preview_data.special_instruction}})</span></td>
              <td class="center aligned middle aligned">@{{bill_preview_data.quantity}}</td>
              <td class="center aligned middle aligned">
                <input style="width: 100px" type="number" ng-init="bill_preview_data.quantity_to_bill = bill_preview_data.quantity" ng-model="bill_preview_data.quantity_to_bill" ng-change="bill_preview_total(this)" value="@{{bill_preview_data.quantity}}">
              </td>
              <td class="right aligned middle aligned">@{{bill_preview_data.price|currency:""}}</td>
              <td class="right aligned middle aligned">@{{ (bill_preview_data.quantity_to_bill*bill_preview_data.price)|currency:"" }}</td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="4" class="right aligned">DISCOUNT:</th>
              <th class="right aligned">@{{bill_preview.discount.total|currency:""}}</th>
            </tr>
            <tr>
              <th colspan="4" class="right aligned">Gross Total:</th>
              <th class="right aligned">@{{bill_preview.gross_billing|currency:""}}</th>
            </tr>
            <tr>
              <th colspan="4" class="right aligned">SC/PWD Discount:</th>
              <th class="right aligned">@{{bill_preview.discount.sc_pwd_discount|currency:""}}</th>
            </tr>
            <tr>
              <th colspan="4" class="right aligned">SC/PWD VAT Exemption:</th>
              <th class="right aligned">@{{bill_preview.discount.sc_pwd_vat_exemption|currency:""}}</th>
            </tr>
            <tr>
              <th colspan="4" class="right aligned">NET Total:</th>
              <th class="right aligned">@{{bill_preview.net_billing|currency:""}}</th>
            </tr>
          </tfoot>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="ui default button" data-dismiss="modal">Cancel</button>
        <button type="submit" class="ui primary button" form="make-bill-form" ng-model="bill_preview.table_customer_id" ng-disabled="submit" ng-hide="bill_preview.customer_data.has_cancellation_request==1" form="make-bill-form" ng-class="{'loading':submit}">Make Order Slip</button>
      </div>
    </div>
  </div>
</div>

<div id="view-bill-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Table #: @{{bill.table_name}} Bill</h4>
      </div>
      <div class="modal-body">
        <table class="ui unstackable compact table">
          <thead>
            <tr>
              <th class="center aligned">Check #</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="bill_data in bill">
              <td ng-bind="bill_data.check_number" class="center aligned" style="width: 40%"></td>
              <td style="text-align: center;">
                <div class="btn-group">
                  <a class="btn btn-primary" href="/restaurant/bill/@{{bill_data.id}}?print=1" target="_blank"><span class="glyphicon glyphicon-print"></span> Print</a>
                  <button class="btn btn-success" ng-click="add_payment(this)" ng-if="bill_data.is_paid == 0"><span class="glyphicon glyphicon-shopping-cart"></span> Payments</button>
                  <button class="btn btn-danger" ng-click="delete_bill(this)" ng-if="bill_data.is_paid == 0" ng-disabled="submit"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="payment-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Payment of Check #</h4>
      </div>
      <div class="modal-body" style="min-height: 50vh;">
        <div class="form-group">
          <label>Total:</label>
          <span class="form-control">@{{formdata.net_billing|currency:""}}</span>
        </div>
        <form class="form" id="make-payment-form">
          <div class="form-group">
            <label>Settlement:</label>
            <select name="settlement" id="settlement" multiple="" class="ui fluid dropdown" ng-model="formdata.settlement" ng-change="settlements_payment()">
              <option value="">Select Settlement</option>
              <option value="cash">{{settlements("cash")}}</option>
              <option value="credit">{{settlements("credit")}}</option>
              <option value="debit">{{settlements("debit")}}</option>
              <option value="cheque">{{settlements("cheque")}}</option>
              <option value="guest_ledger">{{settlements("guest_ledger")}}</option>
              <option value="send_bill">{{settlements("send_bill")}}</option>
              <option value="free_of_charge">{{settlements("free_of_charge")}}</option>
            </select>
          </div>
          <div class="form-group" ng-if="formdata.settlements_payment.cash">
            <label>{{settlements("cash")}}:</label>
            <input type="number" class="form-control" step="0.01" ng-model="formdata.settlements_amount.cash" ng-change="input_payment()">
          </div>
          <div class="form-group" ng-if="formdata.settlements_payment.credit">
            <label>{{settlements("credit")}}:</label>
            <input type="number" class="form-control" step="0.01" ng-model="formdata.settlements_amount.credit"  ng-change="input_payment()">
          </div>
          <div class="form-group" ng-if="formdata.settlements_payment.debit">
            <label>{{settlements("debit")}}:</label>
            <input type="number" class="form-control" step="0.01" ng-model="formdata.settlements_amount.debit"  ng-change="input_payment()">
          </div>
          <div class="form-group" ng-if="formdata.settlements_payment.cheque">
            <label>{{settlements("cheque")}}:</label>
            <input type="number" class="form-control" step="0.01" ng-model="formdata.settlements_amount.cheque"  ng-change="input_payment()">
          </div>
          <div class="form-group" ng-if="formdata.settlements_payment.guest_ledger">
            <label>{{settlements("guest_ledger")}}:</label>
            <input type="number" class="form-control" step="0.01" ng-model="formdata.settlements_amount.guest_ledger"  ng-change="input_payment()">
          </div>
          <div class="form-group" ng-if="formdata.settlements_payment.send_bill">
            <label>{{settlements("send_bill")}}:</label>
            <input type="number" class="form-control" step="0.01" ng-model="formdata.settlements_amount.send_bill"  ng-change="input_payment()">
          </div>
          <div class="form-group" ng-if="formdata.settlements_payment.free_of_charge">
            <label>{{settlements("free_of_charge")}}:</label>
            <input type="number" class="form-control" step="0.01" ng-model="formdata.settlements_amount.free_of_charge"  ng-change="input_payment()">
          </div>
          <div class="form-group">
            <label>Total Payment:</label>
            <span class="form-control">@{{total_payment|currency:""}}</span>
          </div>
          <div class="form-group">
            <label>Change:</label>
            <span class="form-control">@{{formdata.excess|currency:""}}</span>
          </div>

        </form>
      </div>
      <div class="modal-footer" ng-model="bill_id">
        <button type="button" class="ui default button" data-dismiss="modal">Cancel</button>
        <button type="button" class="ui primary button" ng-disabled="submit" ng-click="make_payment(this)" ng-if="valid_payment" ng-class="{'loading':submit}">Save Payment</button>
      </div>
    </div>
  </div>
</div>

<div id="settlement-cancelled-order-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Settle Cancelled Orders</h4>
      </div>
      <div class="modal-body" style="min-height: 50vh;">
        <form class="form" id="settlement-cancelled-order-form"  ng-submit="settle_cancelled_orders(this)">
          <table class="ui unstackable sortable celled table">
            <thead>
              <tr>
                <th class="center aligned middle aligned">Menu</th>
                <th class="center aligned middle aligned">Quantity of Cancelled</th>
                <th class="center aligned middle aligned">Amount</th>
                <th class="center aligned middle aligned">Settlement</th>
              </tr>
            </thead>
            <tbody>
              <tr ng-repeat="item in cancelled_orders">
                <td class="center aligned middle aligned" ng-bind="item.menu_name"></td>
                <td class="center aligned middle aligned" ng-bind="item.quantity"></td>
                <td class="right aligned middle aligned">@{{item.price*item.quantity|currency:""}}</td>
                <td class="center aligned middle aligned">
                  <select class="form-control" ng-model="item.settlement" required>
                    <option value="">Select Settlement</option>
                    <option value="cancelled">Cancelled / Void</option>
                    <option value="bad_order">BOD Charge</option>
                    <option value="staff_charge">Staff Charge</option>
                  </select>
                </td>
              </tr>
            </tbody>
          </table>
        </form>
      </div>
      <div class="modal-footer" ng-model="bill_id">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" form="settlement-cancelled-order-form" class="btn btn-primary" ng-disabled="submit">Save</button>
      </div>
    </div>
  </div>
</div>



<div id="add-menu-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Menu</h4>
      </div>
      <div class="modal-body">
        <form method="post" id="add-items-form" ng-submit="add_menu()">
        
        <div class="form-group">
          <label>Category</label>
          <select name="category" class="form-control" ng-model="formdata.category">
            <option value="">Select Category</option>
            @foreach ($categories as $category)
              <option value="{{$category}}">{{$category}}</option>
            @endforeach
          </select>
          <p class="help-block">@{{formerrors.category[0]}}</p>
        </div>

        <div class="form-group">
          <label>Subcategory</label>
          <input type="text" name="subcategory" placeholder="Subcategory" class="form-control" ng-model="formdata.subcategory">
          <p class="help-block">@{{formerrors.subcategory[0]}}</p>
        </div>

        <div class="form-group">
          <label>Name</label>
          <input type="text" name="name" placeholder="Name" class="form-control" ng-model="formdata.name">
          <p class="help-block">@{{formerrors.name[0]}}</p>
        </div>

        <div class="form-group">
          <label>Price</label>
          <input type="number" min="0" step="0.01" name="price" placeholder="Price" class="form-control" ng-model="formdata.price">
          <p class="help-block">@{{formerrors.price[0]}}</p>
        </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" ng-disabled="submit" form="add-items-form">Save</button>
      </div>
    </div>

  </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
  $('#customer-table').tablesort();
  $('#list-order-table').tablesort();
  $('#add-table-modal').on('shown.bs.modal', function () {
      $('#select-tablenumber').focus();
  });
  var app = angular.module('main', []);
  app.controller('content-controller', function($scope,$http, $sce, $window) {

    $scope.formdata = {};
    $scope.formdata.settlement = {};
    $scope.table = {};
    $scope.formdata._token = "{{csrf_token()}}";

    shortcut.add("Alt+A",function() {
      show_table();
      $scope.formdata.pax = '';
      $scope.formdata.pax = '';
      $scope.formdata.sc_pwd = '';
      $scope.formdata.guest_name = '';
      show_server();
      $('#add-table-modal').modal('show');
    });


    $scope.formdata = {};
    $scope.formdata.settlement = {};
    $scope.table = {};
    $scope.formdata.restaurant_id = {{Session::get('users.user_data')->restaurant_id}};


    $scope.add_table = function() {
      $scope.formerrors = {};
      $scope.submit = true;
      $http({
         method: 'POST',
         url: '/api/restaurant/table/customer/add',
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        console.log(response.data)
         show_table();
         $scope.submit = false;
         $("#add-table-modal").modal("hide");
         $.notify("A Customer has been added to the list.");
      }, function(rejection) {
        if(rejection.status == 500){
          error_505('Server Error, Try Again.');
        }else if(rejection.status == 422){        
           var errors = rejection.data;
           // console.log(errors.server_id);
           $scope.formerrors.pax = errors.pax;
           $scope.formerrors.server_id = errors['server_id.id'];
        }
         $scope.submit = false;
      });
    }


    $scope.pax_changed = function(data) {
      $scope.formdata.sc_pwd = 0;
    }

    $scope.table = {};
    $scope.has_table = false;
    function show_table(table_data='') {

      $http({
          method : "GET",
          url : "/api/restaurant/table/list/serve",
      }).then(function mySuccess(response) {
          $scope.table = response.data.result;
          if(table_data==''){
            $scope.formdata.table_id = $scope.table[0];
          }else{
            $scope.formdata.table_id = table_data;
          }
          // console.log($scope.formdata.table_id);
          if( typeof Object.keys($scope.table)[0] === 'undefined' ){
            $scope.has_table = false;
          }else{
            $scope.has_table = true;
          }
      }, function(rejection) {
        if(rejection.status == 500){
          error_505('Server Error, Try Again.');
        }else if(rejection.status == 422){
          console.log(rejection.statusText);
        }
      });
    }

    $scope.show_table = function() {
      // $scope.formdata.pax = "";
      // $scope.formdata.guest_name = "";
      $scope.formdata.pax = '';
      $scope.formdata.pax = '';
      $scope.formdata.sc_pwd = '';
      $scope.formdata.guest_name = '';

      $('#add-table-modal').modal('show');
      show_table();
      show_server();
    }

    $scope.table_customers = {};
    setInterval(function(){
      show_table_customers();
    }, 1000);
    function show_table_customers() {
      $http({
          method : "GET",
          url : "/api/restaurant/table/customer/list",
      }).then(function mySuccess(response) {
          if(angular.equals($scope.table_customers, {})){
            $scope.table_customers = response.data.result;
          }else if(angular.equals($scope.table_customers, response.data.result)){

          }else{
            $scope.table_customers = response.data.result;
          }
      }, function(rejection) {
        if(rejection.status == 500){
          // error_505('Server Error, Try Again.');
        }else if(rejection.status == 422){
          console.log(rejection.statusText);
        }
      });
    }

    $scope.delete_table_customer = function(data) {
      console.log(data.$parent.customer_data.id);
      $http({
         method: 'POST',
         url: '/api/restaurant/table/customer/remove/'+data.$parent.customer_data.id,
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        $.notify('A Customer has been removed from the list.','info');
      }, function(rejection) {
        if(rejection.status == 500){
          error_505('Server Error, Try Again.');
        }else if(rejection.status == 422){
          var errors = rejection.data;
        }
      });
    }

    $scope.table_name = "";

    $('#add-order-modal').on('shown.bs.modal', function () {
      $('#search-menu').focus();
    });

    $scope.add_order = function(data) {
      if(!data.$parent.customer_data.has_billed_out){
        $("#add-order-modal").modal("show");
        $scope.table_customer_id = data.$parent.customer_data.id;
        $scope.table_name = data.$parent.customer_data.table_name;
        show_cart(data.$parent.customer_data.id);
        show_menu();
      }else{
        $.notify("The customer has already billed out",'error');
      }
    }

    $scope.cancellation_orders = function(type,data) {
      if(type=='before'){
        console.log(data.order);
        console.log($scope.order_detail);
        $('#view-list-order-modal').modal('hide');
        $('#view-order-modal').modal('hide');
        $('#before-bill-out-cancellation-order-modal').modal('show');
      }else if(type=='after'){
        $('#view-list-order-modal').modal('hide');
        // console.log(data.$parent.customer_data);
        $http({
          method : "GET",
          url : "/api/restaurant/table/customer/bill/preview/"+data.$parent.customer_data.id,
        }).then(function mySuccess(response) {
          console.log(response.data);
          $scope.bill_preview = {};
          $scope.bill_preview.customer_data = {};
          $scope.bill_preview.items = response.data.result;
          $scope.bill_preview.total = response.data.total;
          $scope.bill_preview.customer_data = response.data.customer_data;
          $scope.bill_preview.discount = response.data.discount;
          $scope.bill_preview.gross_billing = response.data.gross_billing;
          $scope.bill_preview.net_billing = response.data.net_billing;
          $scope.bill_preview.table_customer_id = data.$parent.customer_data.id;
          $('#after-bill-out-cancellation-order-modal').modal('show');
        }, function(rejection) {
          if(rejection.status == 500){
            error_505('Server Error, Try Again.');
          }else if(rejection.status == 422){
            console.log(rejection.statusText); 
          }
        });
      }
    }

    $scope.delete_cancellation_request = function(data) {
      console.log(data.$parent.customer_data.id);
      var formdata = {
        id : data.$parent.customer_data.id
      };
      $http({
         method: 'POST',
         url: '/api/restaurant/orders/user-cancellations/delete/'+data.$parent.customer_data.id,
         data: $.param(formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        console.log(response.data);
        $.notify('Your request for cancellation of orders has been deleted.','info');
        $scope.customer_data.has_cancellation_request=0;
      }, function(rejection) {
        if(rejection.status == 500){
          error_505('Server Error, Try Again.');
        }else if(rejection.status == 422){
          var errors = rejection.data; 
          angular.forEach(errors, function(value, key) {
            $.notify(value[0],'error');
          });
        }
      });
    }
    $scope.cancel_orders = function(type,data) {
      $scope.submit = true;
      if(type=='before'){
        // console.log(data);
        var formdata = {
          restaurant_table_customer_id: data.table_customer_id,
          restaurant_order_id: data.order.id,
          items: data.order_detail,
          reason_cancelled: $scope.formdata.reason_cancelled
        };
        // console.log(formdata);
        $http({
           method: 'POST',
           url: '/api/restaurant/table/order/cancel/request/before',
           data: $.param(formdata),
           headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
        .then(function(response) {
          console.log(response.data);
          $.notify('A request for cancellations has been sent.');
          $('#before-bill-out-cancellation-order-modal').modal('hide');
          $scope.formdata.reason_cancelled = "";
          $scope.submit = false;
        }, function(rejection) {
          if(rejection.status == 500){
            error_505('Server Error, Try Again.');
          }else if(rejection.status == 422){
            var errors = rejection.data; 
            angular.forEach(errors, function(value, key) {
                $.notify(value[0],'error');
            });
          }
          $scope.submit = false;
        });
      }else if(type=='after'){
        data.bill_preview.reason_cancelled = $scope.formdata.reason_cancelled;
        $http({
           method: 'POST',
           url: '/api/restaurant/table/order/cancel/request/after',
           data: $.param(data.bill_preview),
           headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
        .then(function(response) {
          console.log(response.data);
          $.notify('A request for cancellations has been sent.');
          $('#after-bill-out-cancellation-order-modal').modal('hide');
          $scope.formdata.reason_cancelled = "";
          $scope.submit = false;
        }, function(rejection) {
          if(rejection.status == 500){
            error_505('Server Error, Try Again.');
          }else if(rejection.status == 422){
            var errors = rejection.data;
            angular.forEach(errors, function(value, key) {
                $.notify(value[0],'error');
            });
          }
          $scope.submit = false;
        });
      }

    }
    function show_cart(table_customer_id) {
      $scope.table_customer_cart = {};
      $scope.table_customer_total = "";
      $http({
          method : "GET",
          url : "/api/restaurant/table/order/cart/"+table_customer_id,
      }).then(function mySuccess(response) {
        $scope.table_customer_cart = response.data.cart;
        $scope.table_customer_total = response.data.total;
      }, function(rejection) {
        if(rejection.status == 500){
          error_505('Server Error, Try Again.');
        }else if(rejection.status == 422){
          console.log(rejection.statusText);
        }
      });
    }

    $scope.toggle_special_instruction = function(data) {
      var toggle = (data.cart_data.show_special_instruction?false:true);
      $scope.table_customer_cart["menu_"+data.cart_data.id].show_special_instruction = toggle;
    }
    $scope.toggle_update_quantity = function(data) {
      var toggle = (data.cart_data.show_update_quantity?false:true);
      $scope.table_customer_cart["menu_"+data.cart_data.id].show_update_quantity = toggle;
    }

    $scope.add_special_instruction = function(data) {
      // console.log(data.$parent.table_customer_id);
      $scope.formdata.special_instruction = data.cart_data.special_instruction;
      $scope.formdata.menu_id = data.cart_data.id;
      $http({
         method: 'POST',
         url: '/api/restaurant/table/order/cart/update/special_instruction/'+data.$parent.table_customer_id,
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        // console.log(response.data);
      }, function(rejection) {
        if(rejection.status == 500){
          error_505('Server Error, Try Again.');
        }else if(rejection.status == 422){
         var errors = rejection.data; 
        }
      });
    }
    $scope.update_quantity = function(data) {
      // console.log(data.$parent.table_customer_id);
      $scope.formdata.quantity = data.cart_data.quantity;
      $scope.formdata.menu_id = data.cart_data.id;
      $http({
         method: 'POST',
         url: '/api/restaurant/table/order/cart/update/quantity/'+data.$parent.table_customer_id,
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        // show_cart(data.$parent.table_customer_id);
        $scope.table_customer_cart = response.data.cart;
        $scope.table_customer_total = response.data.total;
        console.log(response.data);
      }, function(rejection) {
        if(rejection.status == 500){
          error_505('Server Error, Try Again.');
        }else if(rejection.status == 422){
           var errors = rejection.data;
        }
      });
    }

    $scope.category_change_menu_list = function() {
      show_menu($scope.select_category);
      $http({
          method : "GET",
          url : "/api/restaurant/menu/subcategory",
          params: {
            category: $scope.select_category
          },
      }).then(function mySuccess(response) {
          $scope.subcategories = response.data;
          console.log(response.data);
      }, function(rejection) {
        if(rejection.status == 500){
          error_505('Server Error, Try Again.');
        }else if(rejection.status == 422){ 
          console.log(rejection.statusText);
        }
      });
    }

    $scope.subcategory_change_menu_list = function() {
      show_menu($scope.select_category,$scope.select_subcategory);
    }

    $scope.menu = {};
    function show_menu(category='all',subcategory='all') {
      $http({
          method : "GET",
          url : "/api/restaurant/menu/list/orders",
          params: {
            category: category,
            subcategory: subcategory,
          },
      }).then(function mySuccess(response) {
          $scope.menu = response.data.result;
      }, function(rejection) {
        if(rejection.status == 500){
          error_505('Server Error, Try Again.');
        }else if(rejection.status == 422){
          console.log(rejection.statusText);
        }
      });
    }

    $scope.make_orders = function(data) {
      // console.log($scope.formdata);
      $scope.formdata.table_customer_cart = $scope.table_customer_cart;
      $scope.submit = true;
      $http({
        method: 'POST',
        url: '/api/restaurant/table/order/make/'+$scope.table_customer_id,
        data: $.param($scope.formdata),
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        console.log(response.data);
        $scope.submit = false;
        $("#add-order-modal").modal('hide');
        $('#view-order-modal').modal('show');
        show_order(response.data.id);
      }, function(rejection) {
        if(rejection.status == 500){
          error_505('Server Error, Try Again.');
        }else if(rejection.status == 422){
          var errors = rejection.data;
          $.notify(errors.table_customer_cart[0],'error');
        }
        $scope.submit = false; 
      }); 
    }
    $scope.preview_order = function(data) {
      console.log(data.order_data.id);
      $('#view-order-modal').modal('show');
      show_order(data.order_data.id);
    }

    function show_order(id) {
      $http({
        method : "GET",
        url : "/api/restaurant/table/order/view/"+id,
      }).then(function mySuccess(response) {
        // console.log(response.data);
        $scope.order = response.data.order;
        $scope.order_detail = response.data.order_detail;
        $scope.customer_data = response.data.customer_data;
      }, function(rejection) {
        if(rejection.status == 500){
          error_505('Server Error, Try Again.');
        }else if(rejection.status == 422){
          console.log(rejection.statusText);
        }
      });
    }
    $scope.add_cart = function(data) {
      $scope.add_cart_submit = true;
      
      console.log(data);
      // console.log(data.menu_data);
      $scope.formdata.menu_id = data.menu_data.id;
      $scope.formdata.table_customer_id = $scope.table_customer_id;
      $http({
         method: 'POST',
         url: '/api/restaurant/table/order/cart',
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
         // console.log(response.data);
         $scope.table_customer_cart = response.data.cart;
         $scope.table_customer_total = response.data.total;
         $.notify("Order has been placed.");
        $scope.add_cart_submit = false;
      }, function(rejection) {
        if(rejection.status == 500){
          error_505('Server Error, Try Again.');
        }else if(rejection.status == 422){
           var errors = rejection.data;
           $.notify(rejection.data.error,'error');
           $scope.formdata.date_payment = errors.date_payment; 
        }
        $scope.add_cart_submit = false;
      });
      
    }

    $scope.remove_item_cart = function(data) {
      // console.log(data);
      $scope.formdata.menu_id = data.cart_data.id;
      $scope.formdata.table_customer_id = data.$parent.table_customer_id;
      $http({
         method: 'POST',
         url: '/api/restaurant/table/order/remove',
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
         // console.log(response.data);
         $scope.table_customer_cart = response.data.cart;
         $scope.table_customer_total = response.data.total;
         $.notify("Order has been removed from the list.","info");
      }, function(rejection) {
        if(rejection.status == 500){
          error_505('Server Error, Try Again.');
        }else if(rejection.status == 422){
           var errors = rejection.data;
           $.notify(rejection.data.error,'error');
           $scope.formdata.date_payment = errors.date_payment;
        }
      });
    }

    $scope.orders = {};
    $scope.view_orders = function(data) {
      $http({
          method : "GET",
          url : "/api/restaurant/table/customer/orders/"+data.customer_data.id,
      }).then(function mySuccess(response) {
          $('#view-list-order-modal').modal('show');
          $scope.orders = response.data.result;
          $scope.table_name = data.customer_data.table_name;
          $scope.table_customer_id = data.customer_data.id;
          $scope.customer_data = data.customer_data;
      }, function(rejection) {
        if(rejection.status == 500){
          error_505('Server Error, Try Again.');
        }else if(rejection.status == 422){ 
          console.log(rejection.statusText);
        }
      });
    }

    $scope.bill_out = function(data) {
      // console.log(data.$parent.customer_data);
      if(data.$parent.customer_data.has_billed_out){
        // bill_preview
        $http({
          method : "GET",
          url : "/api/restaurant/table/customer/bill/preview/"+data.$parent.customer_data.id,
        }).then(function mySuccess(response) {
          console.log(response.data);
          $scope.bill_preview = {};
          $scope.bill_preview.customer_data = {};
          $scope.bill_preview.items = response.data.result;
          $scope.bill_preview.total = response.data.total;
          $scope.bill_preview.customer_data = response.data.customer_data;
          $scope.bill_preview.discount = response.data.discount;
          $scope.bill_preview.gross_billing = response.data.gross_billing;
          $scope.bill_preview.net_billing = response.data.net_billing;
          $scope.bill_preview.table_customer_id = data.$parent.customer_data.id;
          $scope.max = data.$parent.customer_data.pax;
          $('#bill-preview-modal').modal('show');
          if($scope.bill_preview.customer_data.has_cancellation_request=='1'){
            $.notify('Cannot make a bill, this customer has an existing cancellation request.','error');
          }
        }, function(rejection) {
          if(rejection.status == 500){
            error_505('Server Error, Try Again.');
          }else if(rejection.status == 422){
            console.log(rejection.statusText);
          }
        });
      }else{
        $scope.bill_out_submit = true;
        $http({
           method: 'POST',
           url: '/api/restaurant/table/customer/bill/preview/'+data.$parent.customer_data.id,
           data: $.param($scope.formdata),
           headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
        .then(function(response) {
          console.log(response.data);
          $scope.bill_out_submit = false;
          $scope.bill_preview = {};
          $scope.bill_preview.customer_data = {};
          $scope.bill_preview.items = response.data.result;
          $scope.bill_preview.total = response.data.total;
          $scope.bill_preview.customer_data = response.data.customer_data;
          $scope.bill_preview.discount = response.data.discount;
          $scope.bill_preview.gross_billing = response.data.gross_billing;
          $scope.bill_preview.net_billing = response.data.net_billing;
          $scope.bill_preview.table_customer_id = data.$parent.customer_data.id;
          $scope.max = data.$parent.customer_data.pax;
          $('#bill-preview-modal').modal('show');
        }, function(rejection) {
          if(rejection.status == 500){
            error_505('Server Error, Try Again.');
          }else if(rejection.status == 422){
           var errors = rejection.data;
           $scope.formdata.date_payment = errors.date_payment;
          }
           $scope.bill_out_submit = false; 
        });
      }
    }

    $scope.settlement_cancelled_orders = function(data) {
      console.log(data.$parent.customer_data);
      $http({
          method : "GET",
          url : "/api/restaurant/orders/cancellations/accept/"+data.$parent.customer_data.id,
      }).then(function mySuccess(response) {
          console.log(response.data);
          $scope.cancelled_orders = response.data.cancelled_orders;
          $scope.table_customer_id = response.data.table_customer_id;
          $("#settlement-cancelled-order-modal").modal("show");
      }, function(rejection) {
        if(rejection.status == 500){
          error_505('Server Error, Try Again.');
        }else if(rejection.status == 422){ 
          console.log(rejection.statusText);
        }
      });
    }

    $scope.settle_cancelled_orders = function(data) {
      console.log(data);
      $scope.submit = true;
      var formdata = {
        items: data.cancelled_orders,
        table_customer_id: data.table_customer_id
      };
      $http({
         method: 'POST',
         url: '/api/restaurant/orders/cancellations/settle/'+data.table_customer_id,
         data: $.param(formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        console.log(response.data);
        $.notify('Cancelled Orders has been settled.');
        $('#settlement-cancelled-order-modal').modal('hide');
        $scope.submit = false;
      }, function(rejection) {
        if(rejection.status == 500){
          error_505('Server Error, Try Again.');
        }else if(rejection.status == 422){
         var errors = rejection.data;
         angular.forEach(errors, function(value, key) {
             $.notify(value[0],'error');
         });
        }
       $scope.submit = false; 
      });
    }
    $scope.compute_net_total = function() {
      $scope.bill_preview.discount.total = ($scope.bill_preview.discount.amount_disount+($scope.bill_preview.total*$scope.bill_preview.discount.percent_disount*0.01));
      $scope.bill_preview.gross_billing = $scope.bill_preview.total - $scope.bill_preview.discount.total;
      $scope.bill_preview.discount.sc_pwd_discount = $scope.bill_preview.gross_billing*$scope.bill_preview.customer_data.sc_pwd/$scope.bill_preview.customer_data.pax/1.12*.2;
      $scope.bill_preview.discount.sc_pwd_vat_exemption = $scope.bill_preview.gross_billing*$scope.bill_preview.customer_data.sc_pwd/$scope.bill_preview.customer_data.pax/1.12*.12;
      $scope.bill_preview.net_billing = $scope.bill_preview.gross_billing-$scope.bill_preview.discount.sc_pwd_discount-$scope.bill_preview.discount.sc_pwd_vat_exemption;
      $scope.bill_preview.net_billing = $scope.bill_preview.gross_billing-$scope.bill_preview.discount.sc_pwd_discount-$scope.bill_preview.discount.sc_pwd_vat_exemption;
      console.log($scope.bill_preview);
    }

    $scope.make_bill = function(data) {
      console.log(data.bill_preview);
      // console.log(data.bill_preview.table_customer_id);
      $scope.submit = true;
      $scope.formdata = data.bill_preview;
      $http({
         method: 'POST',
         url: '/api/restaurant/table/customer/bill/make/'+data.bill_preview.table_customer_id,
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        $scope.submit = false;
        // console.log(response.data)
        $scope.bill = response.data.result;
        $scope.bill.table_name = response.data.table_name;
        $("#bill-preview-modal").modal("hide");
        $("#view-bill-modal").modal("show");
      }, function(rejection) {
        if(rejection.status == 500){
          error_505('Server Error, Try Again.');
        }else if(rejection.status == 422){
           var errors = rejection.data;
           $.notify(errors.items[0],'error');
        }
         $scope.submit = false; 
      });
    }

    $scope.bill_preview_total = function(data) {
      // console.log(data.$parent.bill_preview);
      data.$parent.bill_preview.total = 0;
      angular.forEach(data.$parent.bill_preview.items, function(value, key) {
        data.$parent.bill_preview.total = data.$parent.bill_preview.total + (Math.abs(value.quantity_to_bill)*value.price);
      });
      $scope.compute_net_total();
    }

    $scope.view_bills = function(data) {
      var id = data.$parent.$parent.customer_data.id;
      $http({
          method : "GET",
          url : "/api/restaurant/table/customer/bill/list/"+id,
      }).then(function mySuccess(response) {
          console.log(response.data);
          $scope.bill = response.data.result;
          $scope.bill.table_name = response.data.table_name;
          $("#view-bill-modal").modal("show");
      }, function(rejection) {
        if(rejection.status == 500){
          error_505('Server Error, Try Again.');
        }else if(rejection.status == 422){ 
          console.log(rejection.statusText);
        }
      });
    }

    $('#settlement').dropdown();
    $scope.total_payment = 0;
    $scope.add_payment = function(data) {
      console.log(data);
      $scope.formdata.settlements_amount = {
        cash: 0,
        credit: 0,
        debit: 0,
        cheque: 0,
        guest_ledger: 0,
        send_bill: 0,
        free_of_charge: 0
      };
      $scope.formdata.net_billing  = data.bill_data.net_billing  ;
      $scope.bill_id = data.bill_data.id;
      $("#payment-modal").modal("show");
      $('#settlement').dropdown('clear');
      $scope.formdata.settlement = {} ;
      $scope.formdata.excess = excess($scope);
      $scope.valid_payment = valid_payment($scope);
      $scope.formdata.settlements_payment = {};
      $scope.formdata.settlements_payment.cash = false;
      $scope.formdata.settlements_payment.credit = false;
      $scope.formdata.settlements_payment.debit = false;
      $scope.formdata.settlements_payment.cheque = false;
      $scope.formdata.settlements_payment.guest_ledger = false;
      $scope.formdata.settlements_payment.send_bill = false;
      $scope.formdata.settlements_payment.free_of_charge = false;
    }

    $scope.delete_bill = function function_name(data) {
      console.log(data.bill_data);
      alertify.prompt(
        'Check #: '+data.$parent.bill_data.check_number,
        'Reason to delete:',
        '',
        function(evt, value) {
          if(value.trim()!=""){
            var formdata = {};
            formdata.deleted_comment = value;
            formdata.bill_data = data.bill_data;
            $scope.submit = true;
            $http({
               method: 'POST',
               url: '/api/restaurant/table/customer/bill/delete/'+data.$parent.bill_data.id,
               data: $.param(formdata),
               headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
            .then(function(response) {
              console.log(response.data);
              $("#view-bill-modal").modal("hide");
              $scope.formdata.deleted_comment = '';
              $.notify('Check # '+data.$parent.bill_data.check_number+' has been deleted');
              $scope.submit = false;
            }, function(rejection) {
              if(rejection.status == 500){
                error_505('Server Error, Try Again.');
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
    $("#search-menu").autocomplete({
        source: "/api/restaurant/menu/search/name",
        select: function(event, ui) {
            var data = {};
            data.menu_data = {};
            data.menu_data.id = ui.item.id;
            $scope.add_cart(data);
            $scope.search_menu = '';
        }
    });

    $scope.make_payment = function(data) {
      // console.log(data.$parent.bill_id);
      $scope.submit = true;
      $http({
         method: 'POST',
         url: '/api/restaurant/table/customer/payment/make/'+data.$parent.bill_id,
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        $.notify('The Payment has been saved.');
        console.log(response.data);
        $scope.submit = false;
        $scope.bill = response.data.result;
        $scope.bill.table_name = response.data.table_name;
        $scope.formdata.settlement = {};
        $("#payment-modal").modal("hide");
      }, function(rejection) {
        if(rejection.status == 500){
          error_505('Server Error, Try Again.');
        }else if(rejection.status == 422){ 
         var errors = rejection.data;
        }
       $scope.submit = false;
      });
    }
    $scope.formdata.settlements_payment = {};
    $scope.formdata.settlements_amount = {
      cash: 0,
      credit: 0,
      debit: 0,
      cheque: 0,
      guest_ledger: 0,
      send_bill: 0,
      free_of_charge: 0
    };
    $scope.input_payment = function() {
      $scope.formdata.excess = excess($scope);
      $scope.valid_payment = valid_payment($scope);
    }

    $scope.edit_table_customer = function(data) {
      console.log(data.customer_data);
      $('#edit-table-modal').modal('show');
      $scope.formdata.customer_data = data.customer_data;
      $scope.formdata.pax = data.customer_data.pax;
      $scope.formdata.sc_pwd = data.customer_data.sc_pwd;
      $scope.formdata.guest_name = data.customer_data.guest_name;
      show_table(data.customer_data.table_data);
      // $scope.formdata.table_id = ;
    }

    $scope.edit_table = function(data) {
      $scope.submit = true;
      $http({
         method: 'PUT',
         url: '/api/restaurant/table/customer/update/'+data.formdata.customer_data.id,
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        console.log(response.data);
        $('#edit-table-modal').modal('hide');
        $scope.submit = false;
        $.notify("The information of customer has been updated.");
      }, function(rejection) {
        if(rejection.status == 500){
          error_505('Server Error, Try Again.');
        }else if(rejection.status == 422){ 
         var errors = rejection.data;
        }
         $scope.submit = false;
      });
    }

    
    function show_server() {
      $http({
          method : "GET",
          params: {
            restaurant_id: "{{Session::get('users.user_data')->restaurant_id}}"
          },
          url : "/api/restaurant/server/list",
      }).then(function mySuccess(response) {
          $scope.server = response.data.result;
          $scope.formdata.server_id = $scope.server[0];
      }, function(rejection) {
        if(rejection.status == 500){
          error_505('Server Error, Try Again.');
        }else if(rejection.status == 422){
          console.log(rejection.statusText); 
        }
      });
    }

    function excess($scope) {
      var excess = (
        $scope.formdata.settlements_amount.cash
      + $scope.formdata.settlements_amount.credit
      + $scope.formdata.settlements_amount.debit
      + $scope.formdata.settlements_amount.cheque
      + $scope.formdata.settlements_amount.guest_ledger
      + $scope.formdata.settlements_amount.send_bill
      + $scope.formdata.settlements_amount.free_of_charge
      ) - $scope.formdata.net_billing;
      return (excess>=0?excess:0);
    }

    function valid_payment(argument) {
      $scope.total_payment = (
        $scope.formdata.settlements_amount.cash
      + $scope.formdata.settlements_amount.credit
      + $scope.formdata.settlements_amount.debit
      + $scope.formdata.settlements_amount.cheque
      + $scope.formdata.settlements_amount.guest_ledger
      + $scope.formdata.settlements_amount.send_bill
      + $scope.formdata.settlements_amount.free_of_charge
      );
      return ($scope.total_payment>=$scope.formdata.net_billing?true:false);
    }
    $scope.settlements_payment = function(data) {
      $scope.formdata.settlements_payment = {};
      if($scope.formdata.settlement.includes('cash')){
        $scope.formdata.settlements_payment.cash = true;
      }else{
        $scope.formdata.settlements_payment.cash = false;
        $scope.formdata.settlements_amount.cash = 0;
        $scope.formdata.excess = excess($scope);
        $scope.valid_payment = valid_payment($scope);
      }
      if($scope.formdata.settlement.includes('credit')){
        $scope.formdata.settlements_payment.credit = true;
      }else{
        $scope.formdata.settlements_payment.credit = false;
        $scope.formdata.settlements_amount.credit = 0;
        $scope.formdata.excess = excess($scope);
        $scope.valid_payment = valid_payment($scope);
      }
      if($scope.formdata.settlement.includes('debit')){
        $scope.formdata.settlements_payment.debit = true;
      }else{
        $scope.formdata.settlements_payment.debit = false;
        $scope.formdata.settlements_amount.debit = 0;
        $scope.formdata.excess = excess($scope);
        $scope.valid_payment = valid_payment($scope);
      }
      if($scope.formdata.settlement.includes('cheque')){
        $scope.formdata.settlements_payment.cheque = true;
      }else{
        $scope.formdata.settlements_payment.cheque = false;
        $scope.formdata.settlements_amount.cheque = 0;
        $scope.formdata.excess = excess($scope);
        $scope.valid_payment = valid_payment($scope);
      }
      if($scope.formdata.settlement.includes('guest_ledger')){
        $scope.formdata.settlements_payment.guest_ledger = true;
      }else{
        $scope.formdata.settlements_payment.guest_ledger = false;
        $scope.formdata.settlements_amount.guest_ledger = 0;
        $scope.formdata.excess = excess($scope);
        $scope.valid_payment = valid_payment($scope);
      }
      if($scope.formdata.settlement.includes('send_bill')){
        $scope.formdata.settlements_payment.send_bill = true;
      }else{
        $scope.formdata.settlements_payment.send_bill = false;
        $scope.formdata.settlements_amount.send_bill = 0;
        $scope.formdata.excess = excess($scope);
        $scope.valid_payment = valid_payment($scope);
      }
      if($scope.formdata.settlement.includes('free_of_charge')){
        $scope.formdata.settlements_payment.free_of_charge = true;
      }else{
        $scope.formdata.settlements_payment.free_of_charge = false;
        $scope.formdata.settlements_amount.free_of_charge = 0;
        $scope.formdata.excess = excess($scope);
        $scope.valid_payment = valid_payment($scope);
      }
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