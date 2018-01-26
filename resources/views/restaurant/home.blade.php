@extends('layouts.main')

@section('title', App\Restaurant::find(Auth::user()->restaurant_id)->name)

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
<div class="active section">{{App\Restaurant::find(Auth::user()->restaurant_id)->name}}</div>
@endsection
@section('content')
<div class="col-sm-12">
  <h1 style="text-align: center;">{{App\Restaurant::find(Auth::user()->restaurant_id)->name}}</h1>
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
          <th class="center aligned">Date</th>
          <th class="center aligned">Time</th>
          <th class="center aligned">Pax</th>
          <th class="center aligned">SC/PWD</th>
          <th class="center aligned">Total</th>
        </tr>
      </thead>
      <tbody ng-cloak>
        <tr ng-repeat="customer_data in table_customers" ng-class="{'warning':customer_data.has_cancellation_request==1}" ng-cloak>
          <td style="width: 30vw;" class="center aligned middle aligned">
          <p style="cursor: pointer;" ng-click="edit_table_customer(this)" data-balloon="Click to edit customer information." data-balloon-pos="up">
            @{{customer_data.table_name}}
          </p>
          </td>
          <td class="center aligned middle aligned" ng-bind="customer_data.guest_name"></td>
          <td class="center aligned middle aligned" ng-bind="customer_data.server_name"></td>
          <td class="center aligned middle aligned" ng-bind="customer_data.date"></td>
          <td class="center aligned middle aligned" ng-bind="customer_data.time"></td>
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
      <tfoot>
        <tr ng-if="table_customers | isEmpty">
          <td colspan="20" style="text-align: center;">
            <h1 ng-if="loading_table_customers">
              <img src="{{asset('assets/images/loading.gif')}}" style="height: 70px;">
              <br>
              LOADING
            </h1>
            <h1>
              <span ng-if="!loading_table_customers" ng-cloak>NO DATA</span>
              <span ng-if="loading_table_customers" ng-cloak></span>
            </h1>
          </td>
        </tr>
      </tfoot>
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
          <input class="form-control" type="number" placeholder="Enter Number of Pax" name="pax" ng-model="formdata.pax" ng-blur="pax_changed(this)">
          <p class="help-block">@{{formerrors.pax[0]}}</p>
        </div>
        <div class="form-group">
          <label>Number of SC/PWD:</label>
          <input class="form-control" min="0" type="number" placeholder="Enter Number of SC/PWD" name="sc_pwd" ng-model="formdata.sc_pwd">
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
            <option value="">Select Waiter/Waitress</option>
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
            <input class="form-control" type="number" placeholder="Enter Number of Pax" name="pax" ng-model="formdata.pax" ng-blur="pax_changed(this)">
            <p class="help-block">@{{formerrors.pax[0]}}</p>
          </div>
          <div class="form-group">
            <label>Number of SC/PWD:</label>
            <input class="form-control" min="0" type="number" placeholder="Enter Number of SC/PWD" name="sc_pwd" ng-model="formdata.sc_pwd">
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
              <div class="fields">
                <div class="twelve wide field">
                  <label>Menu Search</label>
                  <div class="ui fluid icon input focus">
                    <input type="text" placeholder="Search..." id="search-menu" ng-model="search_string" ng-keyup="search_menu()">
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
              <div class="two fields">
                <div class="field">
                  <label>Category</label>
                  <select id="select-category" class="ui dropdown fluid" ng-change="category_change_menu_list(this)" ng-model="select_category" ng-init="select_category='all'">
                    <option value="all">All Menu</option>
                    @foreach($categories as $category)
                    <option value="{{$category}}">{{$category}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="field">
                  <label>Subcategory</label>
                  <select id="select-subcategory" class="form-control" ng-change="subcategory_change_menu_list(this)" ng-model="select_subcategory" ng-options="item for item in subcategories">
                  <option value="">All Subcategories</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="table-responsive" style="height: 45vh;">
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
                  <tr ng-if="menu | isEmpty">
                    <td colspan="20" style="text-align: center;">
                      <h1 ng-if="loading">
                        <img src="{{asset('assets/images/loading.gif')}}" style="height: 70px;">
                        <br>
                        LOADING
                      </h1>
                      <h1 ng-if="!loading">NO DATA</h1>
                    </td>
                  </tr>
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
                    <th class="center aligned">Price</th>
                    <th class="center aligned">Total</th>
                    <th class="center aligned"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr ng-repeat="cart_data in table_customer_cart">
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
                    <td class="center aligned middle aligned" ng-init="cart_data.update_price=true">
                      <div ng-hide="cart_data.show_update_price" ng-click="toggle_update_price(this)" style="width: 100%;cursor: pointer;">@{{cart_data.price}}</div>
                      <input style="width: 100px" type="number" ng-show="cart_data.show_update_price" ng-model="cart_data.price" ng-blur="update_price(this)" focus-me="cart_data.show_update_price">
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
                  <tr ng-if="table_customer_cart | isEmpty">
                    <td colspan="20" style="text-align: center;">
                      <h1 ng-if="add_cart_submit">
                        <img src="{{asset('assets/images/loading.gif')}}" style="height: 70px;">
                        <br>
                        LOADING
                      </h1>
                      <h1 ng-if="!add_cart_submit">NO DATA</h1>
                    </td>
                  </tr>
                  <tr ng-if="!table_customer_cart | isEmpty">
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
        <button type="button" class="ui primary button" ng-disabled="submit" ng-click="confirm_food_order(this)" ng-class="{'loading':submit}">Make Food Order</button>
      </div>
    </div>

  </div>
</div>

<div id="confirm-food-order-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Confirm Orders</h4>
      </div>
      <div class="modal-body">
        <table class="order-table">
        <tbody>
          <thead ng-if="!(table_customer_cart | isEmpty)">
            <tr>
              <th style="text-align: center;">ITEM</th>
              <th style="text-align: center;">QTY</th>
              <th style="text-align: right;">PRICE</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="cart_data in table_customer_cart" ng-cloak>
              <td>@{{cart_data.name}}<span ng-if="cart_data.special_instruction != ''"><br>(@{{cart_data.special_instruction}})</span></td>
              <td style="text-align: center;" ng-bind="cart_data.quantity"></td>
              <td style="text-align: right;">@{{cart_data.price|currency:""}}</td>
            </tr>
          </tbody>
        <tfoot>
          <tr ng-if="table_customer_cart | isEmpty">
            <td colspan="20" style="text-align: center;">
              <h1>NO DATA</h1>
            </td>
          </tr>
        </tfoot>
        </table>
        <br>
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
        <button type="button" class="ui negative button" ng-if="(customer_data.has_billed_out==1&&customer_data.has_paid == 0) && customer_data.has_cancellation_request==0" ng-click="cancellation_orders('after',this)" ng-disabled="submit" ng-class="{'loading':submit}">Cancellation</button>
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
              <td>@{{items.restaurant_menu_name}}<span ng-if="items.special_instruction != '' && items.special_instruction != null"><br>(@{{items.special_instruction}})</span></td>
              <td style="text-align: center;" ng-bind="items.quantity"></td>
              <td style="text-align: right;">@{{items.quantity*items.price|currency:""}}</td>
            </tr>
          </tbody>
        <tfoot>
          <tr ng-if="order_detail | isEmpty">
            <td colspan="20" style="text-align: center;">
              <h1>NO DATA</h1>
            </td>
          </tr>
        </tfoot>
        </table>
        <br>
        <p>Server: <span ng-cloak>@{{order.server_name}}</span></p>
        <p ng-if="order.has_cancelled==1">Cancellation Message: <span ng-cloak>@{{order.cancellation_message}}</span></p>
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
            <tfoot>
              <tr ng-if="order_detail | isEmpty">
                <td colspan="20" style="text-align: center;">
                  <h1>NO DATA</h1>
                </td>
              </tr>
            </tfoot>
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
                  <input style="width: 100px" type="number" min="0" ng-model="bill_preview_data.quantity_to_cancel">
                </td>
                <td class="right aligned middle aligned">@{{bill_preview_data.price|currency:""}}</td>
              </tr>
            </tbody>
            <tfoot>
              <tr ng-if="bill_preview.items | isEmpty">
                <td colspan="20" style="text-align: center;">
                  <h1>NO DATA</h1>
                </td>
              </tr>
            </tfoot>
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
                <input type="number" ng-model="bill_preview.customer_data.pax" min="0" max="@{{max}}" ng-change="compute_net_total()">
              </div> 
              <div class="field">
                <label>Number of SC/PWD:</label>
                <input type="number" min="0" max="@{{bill_preview.customer_data.pax}}" ng-model="bill_preview.customer_data.sc_pwd" placeholder="Number of SC/PWD" ng-change="compute_net_total()">
              </div>
            </div>
          </div>
          <div class="field">
            <div class="two fields">
              <div class="field">
                <label>Percentage of Special Discount:</label>
                <div class="ui right labeled input">
                  <input type="number" min="0" max="100" placeholder="Percentage of Special Discount" ng-model="bill_preview.discount.percent_disount" ng-change="compute_net_total()">
                  <div class="ui label">
                    %
                  </div>
                </div>
              </div> 
              <div class="field">
                <label>Amount of Special Discount:</label>
                <input type="number" step="0.01" min="0" max="@{{bill_preview.total}}" placeholder="Amount of Special Discount" ng-model="bill_preview.discount.amount_disount" ng-change="compute_net_total()">
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
            <tr ng-repeat="bill_preview_data in bill_preview.items" ng-class="{'warning':bill_preview_data.category=='SUNDRY'}">
              <td class="left aligned middle aligned">@{{bill_preview_data.name}}<span ng-if="bill_preview_data.special_instruction != '' bill_preview_data.special_instruction != null"><br>(@{{bill_preview_data.special_instruction}})</span></td>
              <td class="center aligned middle aligned">@{{bill_preview_data.quantity}}</td>
              <td class="center aligned middle aligned">
                <input style="width: 100px" type="number" ng-init="bill_preview_data.quantity_to_bill = bill_preview_data.quantity" ng-model="bill_preview_data.quantity_to_bill" ng-change="bill_preview_total(this)">
              </td>
              <td class="right aligned middle aligned">@{{bill_preview_data.price|currency:""}}</td>
              <td class="right aligned middle aligned">@{{ (bill_preview_data.quantity_to_bill*bill_preview_data.price)|currency:"" }}</td>
            </tr>
            <tr>
              <th colspan="4" class="right aligned">TOTAL:</th>
              <th class="right aligned">@{{bill_preview.total|currency:""}}</th>
            </tr>
          </tbody>
          <tfoot>
            <tr ng-if="bill_preview.items | isEmpty">
              <td colspan="20" style="text-align: center;">
                <h1>NO DATA</h1>
              </td>
            </tr>
            <tr>
              <th colspan="4" class="right aligned">DISCOUNT:</th>
              <th class="right aligned">@{{bill_preview.discount.total|currency:""}}</th>
            </tr>
            <tr>
              <th colspan="4" class="right aligned" style="padding-top: 0;padding-bottom: 0">
                <div class="checkbox">
                  <label><input type="checkbox" ng-model="has_room_service_charge" ng-change="compute_net_total()">Room Service Charge:</label>
                </div>
              </th>
              <th class="right aligned">@{{bill_preview.discount.room_service_charge|currency:""}}</th>
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
        <small>* The highlighed rows are not subject for discount.</small>
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
          <tfoot>
            <tr ng-if="bill | isEmpty">
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

<div id="payment-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Payment of Check # @{{check_number}}</h4>
      </div>
      <div class="modal-body" style="min-height: 50vh;">
        <form class="form" id="make-payment-form">
          <div class="row">
            <div class="col-sm-4">
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
                  <option value="bod">{{settlements("bod")}}</option>
                  <option value="manager_meals">{{settlements("manager_meals")}}</option>
                  <option value="sales_office">{{settlements("sales_office")}}</option>
                  <option value="representation">{{settlements("representation")}}</option>
                  <option value="staff_charge">{{settlements("staff_charge")}}</option>
                  <option value="package_inclusion">{{settlements("package_inclusion")}}</option>
                </select>
              </div>
              <div class="form-group">
                <label>Invoice Number</label>
                <input type="text" class="form-control" ng-model="formdata.invoice_number">
                <p class="help-block">@{{formerrors.invoice_number[0]}}</p>
              </div>
            </div>
            <div class="col-sm-4">
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
              <div class="form-group" ng-if="formdata.settlements_payment.staff_charge">
                <label>{{settlements("staff_charge")}}:</label>
                <input type="number" class="form-control" step="0.01" ng-model="formdata.settlements_amount.staff_charge"  ng-change="input_payment()">
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group" ng-if="formdata.settlements_payment.send_bill">
                <label>{{settlements("send_bill")}}:</label>
                <input type="number" class="form-control" step="0.01" ng-model="formdata.settlements_amount.send_bill"  ng-change="input_payment()">
              </div>
              <div class="form-group" ng-if="formdata.settlements_payment.free_of_charge">
                <label>{{settlements("free_of_charge")}}:</label>
                <input type="number" class="form-control" step="0.01" ng-model="formdata.settlements_amount.free_of_charge"  ng-change="input_payment()">
              </div>
              <div class="form-group" ng-if="formdata.settlements_payment.bod">
                <label>{{settlements("bod")}}:</label>
                <input type="number" class="form-control" step="0.01" ng-model="formdata.settlements_amount.bod"  ng-change="input_payment()">
              </div>
              <div class="form-group" ng-if="formdata.settlements_payment.manager_meals">
                <label>{{settlements("manager_meals")}}:</label>
                <input type="number" class="form-control" step="0.01" ng-model="formdata.settlements_amount.manager_meals"  ng-change="input_payment()">
              </div>
              <div class="form-group" ng-if="formdata.settlements_payment.sales_office">
                <label>{{settlements("sales_office")}}:</label>
                <input type="number" class="form-control" step="0.01" ng-model="formdata.settlements_amount.sales_office"  ng-change="input_payment()">
              </div>
              <div class="form-group" ng-if="formdata.settlements_payment.representation">
                <label>{{settlements("representation")}}:</label>
                <input type="number" class="form-control" step="0.01" ng-model="formdata.settlements_amount.representation"  ng-change="input_payment()">
              </div>
              <div class="form-group" ng-if="formdata.settlements_payment.package_inclusion">
                <label>{{settlements("package_inclusion")}}:</label>
                <input type="number" class="form-control" step="0.01" ng-model="formdata.settlements_amount.package_inclusion"  ng-change="input_payment()">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label>Total:</label>
                <span class="form-control">@{{formdata.net_billing|currency:""}}</span>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label>Total Payment:</label>
                <span class="form-control">@{{total_payment|currency:""}}</span>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label>Change:</label>
                <span class="form-control">@{{formdata.excess|currency:""}}</span>
              </div>
            </div>
          </div>

        </form>
      </div>
      <div class="modal-footer" ng-model="bill_id">
        <button type="button" class="ui default button" data-dismiss="modal">Cancel</button>
        <button type="submit" class="ui primary button" ng-disabled="submit" form="make-payment-form" ng-click="make_payment(this)" ng-if="valid_payment" ng-class="{'loading':submit}">Save Payment</button>
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
                    <!-- <option value="bad_order">BOD Charge</option> -->
                    <!-- <option value="staff_charge">Staff Charge</option> -->
                  </select>
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr ng-if="cancelled_orders | isEmpty">
                <td colspan="20" style="text-align: center;">
                  <h1>NO DATA</h1>
                </td>
              </tr>
            </tfoot>
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

@push('scripts')
<script type="text/javascript">
  var csrf_token = "{{csrf_token()}}";
  var restaurant_id = {{Auth::user()->restaurant_id}};
</script>
<script type="text/javascript" src="{{asset('assets/js/restaurant.js')}}"></script>
@endpush