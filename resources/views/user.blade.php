@extends('layouts.main')

@section('title', 'Users')

@section('css')
<style type="text/css">
.ui.striped.table > tr:nth-child(2n), .ui.striped.table tbody tr:nth-child(2n) {
    background-color: rgba(0, 0, 50, 0.06);
}
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
<div class="active section">Users</div>
@endsection
@section('content')
<div class="col-sm-12">
  <div class="form-group">
    <button class="ui primary button" ng-click="add_user_form()">Add User</button>
  </div>
  <div class="table-responsive">
    <table class="ui unstackable celled table" id="customer-table">
      <thead>
        <tr>
          <th class="center aligned">Name</th>
          <th class="center aligned">Username</th>
          <th class="center aligned">Email Address</th>
          <th class="center aligned">Privilege</th>
          <th class="center aligned">Outlet/Position</th>
        </tr>
      </thead>
      <tbody ng-cloak>
        <tr ng-repeat="user in users.data" ng-hide="user.id=={{Auth::user()->id}}">
          <td class="center aligned middle aligned">@{{user.name}}</td>
          <td class="center aligned middle aligned">@{{user.username}}</td>
          <td class="center aligned middle aligned">@{{user.email_address}}</td>
          <td class="center aligned middle aligned">
            <span>@{{user.str_privilege}}</span>
          </td>
          <td class="center aligned middle aligned">
            <span ng-if="user.restaurant && user.privilege!='inventory_user'">@{{user.restaurant.name}}</span>
            <span ng-if="user.privilege=='inventory_user' || user.privilege=='admin'">@{{user.position}}</span>
          </td>
          <td class="center aligned middle aligned">
            <div class="ui buttons">
              <button class="ui primary button" ng-click="edit_user(this)" data-balloon="Click Edit the Privilege of the User" data-balloon-pos="down">
                <i class="glyphicon glyphicon-edit" aria-hidden="true"></i>
              </button>
              <button class="ui negative button" ng-click="delete_user(this)" data-balloon="Click Delete User" data-balloon-pos="down">
                <i class="glyphicon glyphicon-trash" aria-hidden="true"></i>
              </button>
            </div>
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr ng-if="users.data | isEmpty">
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
@endsection

@section('modals')


<div id="add-user-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add User</h4>
      </div>
      <div class="modal-body">
        <form id="add-user-form" ng-submit="add_user()">
        {{ csrf_field() }}

        <div class="form-group">
          <label>Privilege:</label>
          <select name="restaurant_id" placeholder="Outlet" class="form-control" ng-model="formdata.privilege">
            <option value="restaurant_cashier">Restaurant Cashier</option>
            <option value="restaurant_admin">Restaurant Admin</option>
            <option value="restaurant_waiter">Restaurant Waiter</option>
            <option value="admin">Admin</option>
            <option value="inventory_user">Inventory User</option>
          </select>
          <p class="help-block">@{{formerrors.privilege[0]}}</p>
        </div>


        <div class="form-group" ng-hide="formdata.privilege=='admin' || formdata.privilege=='inventory_user'">
          <label>Outlet:</label>
          <select name="restaurant_id" placeholder="Outlet" class="form-control" ng-model="formdata.restaurant_id">
            <option value="">Select Outlet</option>
            @foreach($restaurants as $restaurant)
              <option value="{{$restaurant->id}}">{{$restaurant->name}}</option>
            @endforeach
          </select>
          <p class="help-block">@{{formerrors.restaurant_id[0]}}</p>
        </div>

        <div class="form-group" ng-if="formdata.privilege=='admin' || formdata.privilege=='inventory_user'">
          <label>Position</label>
          <select class="form-control" ng-model="formdata.position" ng-options="item for item in positions">
            <option value="">Select Position</option>
          </select>
          <p class="help-block">@{{formerrors.position[0]}}</p>
        </div>

        <div class="form-group" ng-if="formdata.privilege=='inventory_user'">
          <label>Permissions</label>
          <a href="javascript:void(0)" class="form-control" ng-click="permission_form()">View Permissions</a>
          <p class="help-block">@{{formerrors.position[0]}}</p>
        </div>

        <div class="form-group">
          <label>Name</label>
          <input class="form-control" type="text" placeholder="Enter Name" name="pax" ng-model="formdata.name">
          <p class="help-block">@{{formerrors.name[0]}}</p>
        </div>

        <div class="form-group">
          <label>Email Address</label>
          <input class="form-control" type="email" placeholder="Enter Email Address" name="pax" ng-model="formdata.email_address">
          <p class="help-block">@{{formerrors.email_address[0]}}</p>
        </div>


        <div class="form-group">
          <label>Username</label>
          <input class="form-control" type="text" placeholder="Enter Username" name="pax" ng-model="formdata.username">
          <p class="help-block">@{{formerrors.username[0]}}</p>
        </div>

        <div class="form-group">
          <label>Password</label>
          <input class="form-control" type="text" placeholder="Enter Password" name="pax" ng-model="formdata.password" ng-init="formdata.password='password123'">
          <!-- <small>* This is the default password, the user must change his password after account creation.</small> -->
          <p class="help-block">@{{formerrors.password[0]}}</p>
        </div>

        <div class="form-group">
          <div class="checkbox">
            <label><input type="checkbox" ng-model="formdata.allow_edit_info">Allow User to Edit Information</label>
          </div>
        </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="ui default button" data-dismiss="modal">Cancel</button>
        <button type="submit" class="ui primary button" form="add-user-form" ng-disabled="submit" ng-class="{'loading':submit}">Save</button>
      </div>
    </div>

  </div>
</div>

<div id="edit-user-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">edit User</h4>
      </div>
      <div class="modal-body">
        <form id="edit-user-form" ng-submit="update_user()">
        {{ csrf_field() }}

        <div class="form-group">
          <label>Email Address</label>
          <input class="form-control" type="email" placeholder="Enter Email Address" name="pax" ng-model="formdata.email_address">
          <p class="help-block">@{{formerrors.email_address[0]}}</p>
        </div>
        <div class="form-group">
          <label>Validity of the User:</label><br>
          <div class="ui toggle checkbox">
              <input type="checkbox" name="public" ng-model="formdata.is_valid">
              <label ng-if="formdata.is_valid">Valid</label>
              <label ng-if="!formdata.is_valid">Invalid</label>
            </div>
        </div>

        <div class="form-group">
          <label>Privilege:</label>
          <select name="restaurant_id" placeholder="Outlet" class="form-control" ng-model="formdata.privilege">
            <option value="restaurant_cashier">Restaurant Cashier</option>
            <option value="restaurant_admin">Restaurant Admin</option>
            <option value="admin">Admin</option>
            <option value="inventory_user">Inventory User</option>
          </select>
          <p class="help-block">@{{formerrors.privilege[0]}}</p>
        </div>

        <div class="form-group" ng-if="formdata.privilege=='admin' || formdata.privilege=='inventory_user'">
          <label>Position</label>
          <select class="form-control" ng-model="formdata.position" ng-options="item for item in positions">
            <option value="">Select Position</option>
          </select>
          <p class="help-block">@{{formerrors.position[0]}}</p>
        </div>

        <div class="form-group" ng-if="formdata.privilege=='inventory_user'">
          <label>Permissions</label>
          <a href="javascript:void(0)" class="form-control" ng-click="permission_form()">View Permissions</a>
          <p class="help-block">@{{formerrors.position[0]}}</p>
        </div>

        <div class="form-group" ng-hide="formdata.privilege=='admin' || formdata.privilege=='inventory_user'">
          <label>Outlet:</label>
          <select name="restaurant_id" placeholder="Outlet" class="form-control" ng-model="formdata.restaurant_id">
            <option value="">Select Outlet</option>
            @foreach($restaurants as $restaurant)
              <option value="{{$restaurant->id}}">{{$restaurant->name}}</option>
            @endforeach
          </select>
          <p class="help-block">@{{formerrors.restaurant_id[0]}}</p>
        </div>

        <div class="form-group">
          <label>Replace Password</label>
          <input class="form-control" type="text" placeholder="Enter Password" name="pax" ng-model="formdata.password">
          <!-- <small>* This is the default password, the user must change his password after account creation.</small> -->
          <p class="help-block">@{{formerrors.password[0]}}</p>
        </div>

        <div class="form-group">
          <div class="checkbox">
            <label><input type="checkbox" ng-model="formdata.allow_edit_info" ng-checked="formdata.allow_edit_info==1">Allow User to Edit Information</label>
          </div>
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="ui default button" data-dismiss="modal">Cancel</button>
        <button type="submit" class="ui primary button" form="edit-user-form" ng-disabled="submit" ng-class="{'loading':submit}">Save</button>
      </div>
    </div>

  </div>
</div>

<div id="permission-modal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Permissions</h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="ui unstackable celled table">
            <thead>
              <tr>
                <th class="center aligned middle aligned">Module</th>
                <th class="center aligned middle aligned">Accessing</th>
                <th class="center aligned middle aligned">Adding</th>
                <th class="center aligned middle aligned">Updating</th>
                <th class="center aligned middle aligned">Deleting</th>
                <th class="center aligned middle aligned">Approving</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="center aligned middle aligned">Items</td>
                <td class="center aligned middle aligned">
{{--                   <div class="ui toggle checkbox">
                      <input type="checkbox" ng-model="permissions.can_view_items">
                      <label ng-if="permissions.can_view_items">Enable</label>
                      <label ng-if="!permissions.can_view_items">Disable</label>
                  </div> --}}
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_items">
                      <input type="checkbox" ng-model="permissions.can_add_items">
                      <label ng-if="permissions.can_add_items">Enable</label>
                      <label ng-if="!permissions.can_add_items">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_items">
                      <input type="checkbox" ng-model="permissions.can_edit_items">
                      <label ng-if="permissions.can_edit_items">Enable</label>
                      <label ng-if="!permissions.can_edit_items">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_items">
                      <input type="checkbox" ng-model="permissions.can_delete_items">
                      <label ng-if="permissions.can_delete_items">Enable</label>
                      <label ng-if="!permissions.can_delete_items">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">

                </td>
              </tr>
              <tr>
                <td class="center aligned middle aligned">Purchase Request</td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox">
                      <input type="checkbox" ng-model="permissions.can_view_purchase_requests">
                      <label ng-if="permissions.can_view_purchase_requests">Enable</label>
                      <label ng-if="!permissions.can_view_purchase_requests">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_purchase_requests">
                      <input type="checkbox" ng-model="permissions.can_add_purchase_requests">
                      <label ng-if="permissions.can_add_purchase_requests">Enable</label>
                      <label ng-if="!permissions.can_add_purchase_requests">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_purchase_requests">
                      <input type="checkbox" ng-model="permissions.can_edit_purchase_requests">
                      <label ng-if="permissions.can_edit_purchase_requests">Enable</label>
                      <label ng-if="!permissions.can_edit_purchase_requests">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_purchase_requests">
                      <input type="checkbox" ng-model="permissions.can_delete_purchase_requests">
                      <label ng-if="permissions.can_delete_purchase_requests">Enable</label>
                      <label ng-if="!permissions.can_delete_purchase_requests">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_purchase_requests">
                      <input type="checkbox" ng-model="permissions.can_approve_purchase_requests">
                      <label ng-if="permissions.can_approve_purchase_requests">Enable</label>
                      <label ng-if="!permissions.can_approve_purchase_requests">Disable</label>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="center aligned middle aligned">Request to Canvass</td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox">
                      <input type="checkbox" ng-model="permissions.can_view_request_to_canvasses">
                      <label ng-if="permissions.can_view_request_to_canvasses">Enable</label>
                      <label ng-if="!permissions.can_view_request_to_canvasses">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_request_to_canvasses">
                      <input type="checkbox" ng-model="permissions.can_add_request_to_canvasses">
                      <label ng-if="permissions.can_add_request_to_canvasses">Enable</label>
                      <label ng-if="!permissions.can_add_request_to_canvasses">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_request_to_canvasses">
                      <input type="checkbox" ng-model="permissions.can_edit_request_to_canvasses">
                      <label ng-if="permissions.can_edit_request_to_canvasses">Enable</label>
                      <label ng-if="!permissions.can_edit_request_to_canvasses">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_request_to_canvasses">
                      <input type="checkbox" ng-model="permissions.can_delete_request_to_canvasses">
                      <label ng-if="permissions.can_delete_request_to_canvasses">Enable</label>
                      <label ng-if="!permissions.can_delete_request_to_canvasses">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">

                </td>
              </tr>
              <tr>
                <td class="center aligned middle aligned">Capital Expenditure Request</td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox">
                      <input type="checkbox" ng-model="permissions.can_view_capital_expenditure_requests">
                      <label ng-if="permissions.can_view_capital_expenditure_requests">Enable</label>
                      <label ng-if="!permissions.can_view_capital_expenditure_requests">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_capital_expenditure_requests">
                      <input type="checkbox" ng-model="permissions.can_add_capital_expenditure_requests">
                      <label ng-if="permissions.can_add_capital_expenditure_requests">Enable</label>
                      <label ng-if="!permissions.can_add_capital_expenditure_requests">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_capital_expenditure_requests">
                      <input type="checkbox" ng-model="permissions.can_edit_capital_expenditure_requests">
                      <label ng-if="permissions.can_edit_capital_expenditure_requests">Enable</label>
                      <label ng-if="!permissions.can_edit_capital_expenditure_requests">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_capital_expenditure_requests">
                      <input type="checkbox" ng-model="permissions.can_delete_capital_expenditure_requests">
                      <label ng-if="permissions.can_delete_capital_expenditure_requests">Enable</label>
                      <label ng-if="!permissions.can_delete_capital_expenditure_requests">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_capital_expenditure_requests">
                      <input type="checkbox" ng-model="permissions.can_approve_capital_expenditure_requests">
                      <label ng-if="permissions.can_approve_capital_expenditure_requests">Enable</label>
                      <label ng-if="!permissions.can_approve_capital_expenditure_requests">Disable</label>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="center aligned middle aligned">Purchase Order</td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox">
                      <input type="checkbox" ng-model="permissions.can_view_purchase_orders">
                      <label ng-if="permissions.can_view_purchase_orders">Enable</label>
                      <label ng-if="!permissions.can_view_purchase_orders">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_purchase_orders">
                      <input type="checkbox" ng-model="permissions.can_add_purchase_orders">
                      <label ng-if="permissions.can_add_purchase_orders">Enable</label>
                      <label ng-if="!permissions.can_add_purchase_orders">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_purchase_orders">
                      <input type="checkbox" ng-model="permissions.can_edit_purchase_orders">
                      <label ng-if="permissions.can_edit_purchase_orders">Enable</label>
                      <label ng-if="!permissions.can_edit_purchase_orders">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_purchase_orders">
                      <input type="checkbox" ng-model="permissions.can_delete_purchase_orders">
                      <label ng-if="permissions.can_delete_purchase_orders">Enable</label>
                      <label ng-if="!permissions.can_delete_purchase_orders">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_purchase_orders">
                      <input type="checkbox" ng-model="permissions.can_approve_purchase_orders">
                      <label ng-if="permissions.can_approve_purchase_orders">Enable</label>
                      <label ng-if="!permissions.can_approve_purchase_orders">Disable</label>
                  </div>
                </td>
              </tr>
              <tr>
                <td class="center aligned middle aligned">Receiving Report</td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox">
                      <input type="checkbox" ng-model="permissions.can_view_receiving_reports">
                      <label ng-if="permissions.can_view_receiving_reports">Enable</label>
                      <label ng-if="!permissions.can_view_receiving_reports">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_receiving_reports">
                      <input type="checkbox" ng-model="permissions.can_add_receiving_reports">
                      <label ng-if="permissions.can_add_receiving_reports">Enable</label>
                      <label ng-if="!permissions.can_add_receiving_reports">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_receiving_reports">
                      <input type="checkbox" ng-model="permissions.can_edit_receiving_reports">
                      <label ng-if="permissions.can_edit_receiving_reports">Enable</label>
                      <label ng-if="!permissions.can_edit_receiving_reports">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_receiving_reports">
                      <input type="checkbox" ng-model="permissions.can_delete_receiving_reports">
                      <label ng-if="permissions.can_delete_receiving_reports">Enable</label>
                      <label ng-if="!permissions.can_delete_receiving_reports">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">

                </td>
              </tr>
              <tr>
                <td class="center aligned middle aligned">Stock Issuance</td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox">
                      <input type="checkbox" ng-model="permissions.can_view_stock_issuances">
                      <label ng-if="permissions.can_view_stock_issuances">Enable</label>
                      <label ng-if="!permissions.can_view_stock_issuances">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_stock_issuances">
                      <input type="checkbox" ng-model="permissions.can_add_stock_issuances">
                      <label ng-if="permissions.can_add_stock_issuances">Enable</label>
                      <label ng-if="!permissions.can_add_stock_issuances">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_stock_issuances">
                      <input type="checkbox" ng-model="permissions.can_edit_stock_issuances">
                      <label ng-if="permissions.can_edit_stock_issuances">Enable</label>
                      <label ng-if="!permissions.can_edit_stock_issuances">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_stock_issuances">
                      <input type="checkbox" ng-model="permissions.can_delete_stock_issuances">
                      <label ng-if="permissions.can_delete_stock_issuances">Enable</label>
                      <label ng-if="!permissions.can_delete_stock_issuances">Disable</label>
                  </div>
                </td>
                <td class="center aligned middle aligned">
                  <div class="ui toggle checkbox" ng-if="permissions.can_view_stock_issuances">
                      <input type="checkbox" ng-model="permissions.can_approve_stock_issuances">
                      <label ng-if="permissions.can_approve_stock_issuances">Enable</label>
                      <label ng-if="!permissions.can_approve_stock_issuances">Disable</label>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="ui default button" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


@endsection

@push('scripts')
<script type="text/javascript">
  
  app.controller('content-controller', function($scope,$http, $sce, $window) {
    show_users();
    get_settings();
    initialize_user_permissions();
    $scope.positions = [];
    function show_users() {
      $scope.loading = true;
      $http({
        method : "GET",
        url : "/api/users",
      }).then(function mySuccess(response) {
        $scope.loading = false;
        $scope.users = response.data.result;
      }, function myError(rejection) {
        $scope.loading = false;
        if(rejection.status != 422){
            request_error(rejection.status);
          }else if(rejection.status == 422){
            var errors = rejection.data;
          }
      });
    }
    function initialize_user_permissions() {
      $scope.permissions = {};
      $scope.permissions = {
        can_view_items: true,
        can_add_items: true,
        can_edit_items: true,
        can_delete_items: true,
        can_view_purchase_requests: true,
        can_add_purchase_requests: true,
        can_edit_purchase_requests: true,
        can_delete_purchase_requests: true,
        can_approve_purchase_requests: true,
        can_view_request_to_canvasses: true,
        can_add_request_to_canvasses: true,
        can_edit_request_to_canvasses: true,
        can_delete_request_to_canvasses: true,
        can_view_capital_expenditure_requests: true,
        can_add_capital_expenditure_requests: true,
        can_edit_capital_expenditure_requests: true,
        can_delete_capital_expenditure_requests: true,
        can_approve_capital_expenditure_requests: true,
        can_view_purchase_orders: true,
        can_add_purchase_orders: true,
        can_edit_purchase_orders: true,
        can_delete_purchase_orders: true,
        can_approve_purchase_orders: true,
        can_view_receiving_reports: true,
        can_add_receiving_reports: true,
        can_edit_receiving_reports: true,
        can_delete_receiving_reports: true,
        can_view_stock_issuances: true,
        can_add_stock_issuances: true,
        can_edit_stock_issuances: true,
        can_delete_stock_issuances: true,
        can_approve_stock_issuances: true,
      };
    }
    function get_settings() {
        $http({
            method: "GET",
            url: route('api.user.settings.get').url(),
        }).then(function mySuccess(response) {
            let positions = response.data.positions;
            angular.forEach(positions, function(value, key) {
              $scope.positions.push(value);
            });
        }, function(rejection) {
            if (rejection.status != 422) {
                request_error(rejection.status);
            } else if (rejection.status == 422) {
                console.log(rejection.statusText);
            }
        });
    }
    $scope.loading = true;
    $scope.formdata = {
      privilege:'restaurant_cashier',
      position: null,
    }
    $scope.add_user_form = function() {
      $('#add-user-modal').modal('show');
      initialize_user_permissions();
      $scope.formdata = {
        privilege:'restaurant_cashier',
        position: null,
      }
    }

    $scope.add_user = function(){
      $scope.formerrors = {};
      $scope.submit = true;
      $scope.formdata.permissions = $scope.permissions;
      $http({
         method: 'POST',
         url: '/api/users/add',
         data: $.param($scope.formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        console.log(response.data)
         show_users();
         $scope.submit = false;
         $("#add-user-modal").modal("hide");
         $('#add-user-form')[0].reset();
      }, function(rejection) {
         var errors = rejection.data;
         // console.log(errors.server_id);
         $scope.formerrors = errors;
         $scope.submit = false;
      });
      
    }

    $scope.edit_user = function(data) {
      // console.log(data);
      $scope.formdata = data.user;
      $scope.permissions = data.user.permissions ? data.user.permissions : {};
      // console.log($scope.permissions);
      $scope.formdata.restaurant_id = (data.user.restaurant_id.toString()=='0'?'':data.user.restaurant_id.toString());
      $('#edit-user-modal').modal('show');
    }

    $scope.update_user = function(data) {
      var formdata = {
        id: $scope.formdata.id,
        privilege: $scope.formdata.privilege,
        restaurant_id: $scope.formdata.restaurant_id,
        allow_edit_info: $scope.formdata.allow_edit_info,
        password: $scope.formdata.password,
        is_valid: $scope.formdata.is_valid,
        email_address: $scope.formdata.email_address,
        position: $scope.formdata.position,
        permissions: $scope.permissions
      };
      $scope.formerrors = {};
      $scope.submit = true;
      $http({
         method: 'POST',
         url: '/api/users/edit/'+formdata.id,
         data: $.param(formdata),
         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
      })
      .then(function(response) {
        console.log(response.data)
         $("#edit-user-modal").modal("hide");
         $.notify('The User '+$scope.formdata.username+' has been updated.');
         $scope.formdata = {};
         $scope.formerrors = {};
         $scope.submit = false;
         $scope.users = response.data.result;
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

    $scope.delete_user = function(data) {
      alertify.confirm(
        'DELETE  <b style=" text-transform: uppercase;">'+data.user.username+'</b>',
        'Are you sure you want to delete this  <b>'+data.user.username+'</b> user?',
        function(){
          var formdata = {
            id: data.user.id,
            privilege: data.user.privilege,
            restaurant_id: data.user.restaurant_id,
          };
          $scope.formerrors = {};
          $scope.submit = true;
          $http({
             method: 'POST',
             url: '/api/users/delete/'+formdata.id,
             data: $.param(formdata),
             headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
          })
          .then(function(response) {
             $.notify('The user '+data.user.username+' has been deleted.');
             $scope.users = response.data.result;
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
        },
        function()
        {
          // alertify.error('Cancel')
        }
      );
    }
    $scope.permission_form = function() {
      $('#permission-modal').modal('show');
    }
    $scope.$watch('permissions.can_view_items', function (newValue, oldValue, scope) {
      $scope.permissions.can_add_items = newValue;
      $scope.permissions.can_edit_items = newValue;
      $scope.permissions.can_delete_items = newValue;
    });
    $scope.$watch('permissions.can_view_purchase_requests', function (newValue, oldValue, scope) {
      $scope.permissions.can_add_purchase_requests = newValue;
      $scope.permissions.can_edit_purchase_requests = newValue;
      $scope.permissions.can_delete_purchase_requests = newValue;
      $scope.permissions.can_approve_purchase_requests = newValue;
    });
    $scope.$watch('permissions.can_view_request_to_canvasses', function (newValue, oldValue, scope) {
      $scope.permissions.can_add_request_to_canvasses = newValue;
      $scope.permissions.can_edit_request_to_canvasses = newValue;
      $scope.permissions.can_delete_request_to_canvasses = newValue;
    });
    $scope.$watch('permissions.can_view_capital_expenditure_requests', function (newValue, oldValue, scope) {
      $scope.permissions.can_add_capital_expenditure_requests = newValue;
      $scope.permissions.can_edit_capital_expenditure_requests = newValue;
      $scope.permissions.can_delete_capital_expenditure_requests = newValue;
      $scope.permissions.can_approve_capital_expenditure_requests = newValue;
    });
    $scope.$watch('permissions.can_view_purchase_orders', function (newValue, oldValue, scope) {
      $scope.permissions.can_add_purchase_orders = newValue;
      $scope.permissions.can_edit_purchase_orders = newValue;
      $scope.permissions.can_delete_purchase_orders = newValue;
      $scope.permissions.can_approve_purchase_orders = newValue;
    });
    $scope.$watch('permissions.can_view_receiving_reports', function (newValue, oldValue, scope) {
      $scope.permissions.can_add_receiving_reports = newValue;
      $scope.permissions.can_edit_receiving_reports = newValue;
      $scope.permissions.can_delete_receiving_reports = newValue;
    });
    $scope.$watch('permissions.can_view_stock_issuances', function (newValue, oldValue, scope) {
      $scope.permissions.can_add_stock_issuances = newValue;
      $scope.permissions.can_edit_stock_issuances = newValue;
      $scope.permissions.can_delete_stock_issuances = newValue;
      $scope.permissions.can_approve_stock_issuances = newValue;
    });

  });


  
</script>
@endpush