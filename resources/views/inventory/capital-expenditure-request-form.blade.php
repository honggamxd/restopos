@extends('layouts.main')

@section('title', 'Capital Expenditure Request')

@section('css')
<style type="text/css">


</style>
@endsection
@section('breadcrumb')
<a class="section" href="{{route('inventory.capital-expenditure-request.list')}}">Capital Expenditure Requests</a>
<i class="right angle icon divider"></i>
<div class="active section" ng-if="edit_mode=='create' && (user_data.privilege == 'admin' || user_data.permissions.can_add_capital_expenditure_requests)" ng-cloak>Create Capital Expenditure Request</div>
<div class="active section" ng-if="edit_mode=='update'" ng-cloak>Edit Capital Expenditure Request</div>
<i class="divider">|</i>
<a class="section" href="{{route('inventory.capital-expenditure-request.create')}}" ng-if="edit_mode=='update' && (user_data.privilege == 'admin' || user_data.permissions.can_add_capital_expenditure_requests)">Create Capital Expenditure Requests</a>
<i class="divider" ng-if="edit_mode=='update' && (user_data.privilege == 'admin' || user_data.permissions.can_add_capital_expenditure_requests)">|</i>
<a class="section" href="{{route('inventory.capital-expenditure-request.settings')}}">Settings</a>
@endsection

@section('overlay-div')
<div ng-style="overlay_div" ng-cloak>
    <div style="margin-top: 10vh;text-align: center;padding-right: 20%;padding-left: 20%;">
        <span ng-if="sent_emails!=recipients.length">
            Sending Capital Expenditure Request to: @{{recipient.user.name}}<@{{recipient.user.email_address}}>
        </span>
        <span ng-if="sent_emails==recipients.length">
            Sent All Mails
        </span>
        <div class="progress" ng-if="mail_progress != ''">
            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
            aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:@{{mail_progress}}">
            @{{sent_emails}}/@{{recipients.length}} Sent
            </div>
        </div>
        <p ng-if="generated_form.form"><a ng-href="@{{generated_form.form}}">View Generated Capital Expenditure Request Form</a></p>
        <p><a href="{{route('inventory.capital-expenditure-request.create')}}">Create new Capital Expenditure Request</a></p>
  </div>
</div>
@endsection

@section('padded_content')
<form id="add-form" ng-submit="save_form()" class="form">
<div class="row">
    <div class="col-sm-12">
        <h2 style="text-align: center;">Capital Expenditure Request Header</h2>
        <br>
        <i>All fields marked with asterisk (*) are required field.</i>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="capital_expenditure_request_number"><small style="color:red">*</small> Budget Number:</label>
                    {{-- <input type="text" class="form-control" placeholder="Enter Budget Number" id="capital_expenditure_request_number" ng-model="formdata.capital_expenditure_request_number"> --}}
                    <input type="text" class="form-control" placeholder="Enter Budget Number" id="capital_expenditure_request_number" value="Auto Generated" readonly>
                    <p class="help-block" ng-cloak>@{{formerrors.capital_expenditure_request_number[0]}}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="purchase_request_number_formatted">PR Number:</label>
                    <span class="form-control" ng-bind="purchase_request_number_formatted"></span>
                    <p class="help-block" ng-cloak></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="budget_description">Budget Description:</label>
                    <input type="text" class="form-control" placeholder="Enter Budget Description" id="budget_description" ng-model="formdata.budget_description">
                    <p class="help-block" ng-cloak>@{{formerrors.budget_description[0]}}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="capital_expenditure_request_date"><small style="color:red">*</small> Date:</label>
                    <input type="text" class="form-control" placeholder="Enter Date Requested" id="capital_expenditure_request_date" ng-model="formdata.capital_expenditure_request_date" readonly>
                    <p class="help-block" ng-cloak>@{{formerrors.capital_expenditure_request_date[0]}}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="budget_amount">Budget mount:</label>
                    <input type="number" min="0" step="0.01" class="form-control" placeholder="Enter Budget mount" id="budget_amount" ng-model="formdata.budget_amount">
                    <p class="help-block" ng-cloak>@{{formerrors.budget_amount[0]}}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="department"><small style="color:red">*</small> Department:</label>
                    <input type="text" class="form-control" placeholder="Enter Department" id="department" ng-model="formdata.department">
                    <p class="help-block" ng-cloak>@{{formerrors.department[0]}}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="source_of_funds">Source of Funds:</label>
                    <textarea class="form-control" placeholder="Enter Source of Funds" id="source_of_funds" ng-model="formdata.source_of_funds"></textarea>
                    <p class="help-block" ng-cloak>@{{formerrors.source_of_funds[0]}}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="brief_project_description">Brief Project Description and Justification:</label>
                    <textarea class="form-control" placeholder="Enter Brief Project Description and Justification" id="brief_project_description" ng-model="formdata.brief_project_description"></textarea>
                    <p class="help-block" ng-cloak>@{{formerrors.brief_project_description[0]}}</p>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<hr>
<br>
<div class="row">
    <div class="col-sm-12">
        <h2 style="text-align: center;">Capital Expenditure Request Items</h2>
        <br>
        <div class="row">
            <div class="col-sm-12">
                <label ng-hide="edit_mode=='update'" ng-cloak>Search Purchase Request Number</label>
                <div class="ui icon input fluid" ng-hide="edit_mode=='update'" ng-cloak data-tooltip="Search for unapproved purchase requests with capex type of items" data-position="top right" data-inverted="">
                    <i class="search icon"></i>
                    <input type="text" placeholder="Search Purchase Request Number" id="search-purchase-request">
                </div>
            </div>
        </div>
        <br>
        <div class="table-responsive">
            <table class="ui single line unstackable table">
                <thead>
                <tr>
                    <th class="center aligned middle aligned">Category</th>
                    <th class="center aligned middle aligned">Subcategory</th>
                    <th class="center aligned middle aligned">Item</th>
                    <th class="center aligned middle aligned">Unit</th>
                    <th class="center aligned middle aligned">Quantity</th>
                    <th class="center aligned middle aligned">Unit Cost</th>
                    <th class="center aligned middle aligned"></th>
                </tr>
                </thead>
                <tbody ng-cloak>
                    <tr ng-repeat="(index,item) in items">
                        <td class="center aligned middle aligned">@{{item.inventory_item.category}}</td>
                        <td class="center aligned middle aligned">@{{item.inventory_item.subcategory}}</td>
                        <td class="center aligned middle aligned">@{{item.inventory_item.item_name}}</td>
                        <td class="center aligned middle aligned">@{{item.inventory_item.unit_of_measure}}</td>
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                <input type="number" min="1" placeholder="Quantity" ng-model="item.quantity">
                            </div>
                        </td>
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                <input type="number" min="0" step="0.01" placeholder="Unit Cost" ng-model="item.unit_price">
                            </div>
                        </td>
                        <td class="right aligned middle aligned"><button type="button" class="btn btn-danger" ng-click="delete_item(index)">&times;</button></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr ng-if="items | isEmpty">
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
</div>
<br>
<hr>
<br>
<div class="row">
    <div class="col-sm-12">
        <h2 style="text-align: center;">Capital Expenditure Request Routing Signature</h2>
        <br>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="requested_by_name"><small style="color:red">*</small> Requested By</label>
                    <input type="text" class="form-control" placeholder="Enter Name" id="requested_by_name" ng-model="formdata.requested_by_name">
                    <p class="help-block" ng-cloak>@{{formerrors.requested_by_name[0]}}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="requested_by_date"><small style="color:red">*</small> Date:</label>
                    <input type="text" class="form-control" placeholder="Enter Date" id="requested_by_date" ng-model="formdata.requested_by_date" readonly>
                    <p class="help-block" ng-cloak>@{{formerrors.requested_by_date[0]}}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="requested_by_position"><small style="color:red">*</small> Position:</label>
                    <input type="text" class="form-control" placeholder="Enter Position" id="requested_by_position" ng-model="formdata.requested_by_position">
                    <p class="help-block" ng-cloak>@{{formerrors.requested_by_position[0]}}</p>
                </div>
            </div>
        </div>
        <div class="row" ng-if="edit_mode=='update'">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="approved_by_1_name">Approved By</label>
                    <input type="text" class="form-control" placeholder="Enter Name" id="approved_by_1_name" ng-model="formdata.approved_by_1_name">
                    <p class="help-block" ng-cloak>@{{formerrors.approved_by_1_name[0]}}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="approved_by_1_date">Date:</label>
                    <input type="text" class="form-control" placeholder="Enter Date" id="approved_by_1_date" ng-model="formdata.approved_by_1_date" readonly>
                    <p class="help-block" ng-cloak>@{{formerrors.approved_by_1_date[0]}}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="approved_by_1_position">Position:</label>
                    <input type="text" class="form-control" placeholder="Enter Position" id="approved_by_1_position" ng-model="formdata.approved_by_1_position">
                    <p class="help-block" ng-cloak>@{{formerrors.approved_by_1_position[0]}}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="verified_as_funded_by_name">Verified as Funded By</label>
                    <input type="text" class="form-control" placeholder="Enter Name" id="verified_as_funded_by_name" ng-model="formdata.verified_as_funded_by_name">
                    <p class="help-block" ng-cloak>@{{formerrors.verified_as_funded_by_name[0]}}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="verified_as_funded_by_date">Date:</label>
                    <input type="text" class="form-control" placeholder="Enter Date" id="verified_as_funded_by_date" ng-model="formdata.verified_as_funded_by_date" readonly>
                    <p class="help-block" ng-cloak>@{{formerrors.verified_as_funded_by_date[0]}}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="verified_as_funded_by_position">Position:</label>
                    <input type="text" class="form-control" placeholder="Enter Position" id="verified_as_funded_by_position" ng-model="formdata.verified_as_funded_by_position">
                    <p class="help-block" ng-cloak>@{{formerrors.verified_as_funded_by_position[0]}}</p>
                </div>
            </div>
        </div>
        {{-- <div class="row" ng-if="edit_mode=='update'">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="approved_by_2_name">Approved By</label>
                    <input type="text" class="form-control" placeholder="Enter Name" id="approved_by_2_name" ng-model="formdata.approved_by_2_name">
                    <p class="help-block" ng-cloak>@{{formerrors.approved_by_2_name[0]}}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="approved_by_2_date">Date:</label>
                    <input type="text" class="form-control" placeholder="Enter Date" id="approved_by_2_date" ng-model="formdata.approved_by_2_date" readonly>
                    <p class="help-block" ng-cloak>@{{formerrors.approved_by_2_date[0]}}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="approved_by_2_position">Position:</label>
                    <input type="text" class="form-control" placeholder="Enter Position" id="approved_by_2_position" ng-model="formdata.approved_by_2_position">
                    <p class="help-block" ng-cloak>@{{formerrors.approved_by_2_position[0]}}</p>
                </div>
            </div>
        </div> --}}
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="recorded_by_name">Recorded By</label>
                    <input type="text" class="form-control" placeholder="Enter Name" id="recorded_by_name" ng-model="formdata.recorded_by_name">
                    <p class="help-block" ng-cloak>@{{formerrors.recorded_by_name[0]}}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="recorded_by_date">Date:</label>
                    <input type="text" class="form-control" placeholder="Enter Date" id="recorded_by_date" ng-model="formdata.recorded_by_date" readonly>
                    <p class="help-block" ng-cloak>@{{formerrors.recorded_by_date[0]}}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="recorded_by_position">Position:</label>
                    <input type="text" class="form-control" placeholder="Enter Position" id="recorded_by_position" ng-model="formdata.recorded_by_position">
                    <p class="help-block" ng-cloak>@{{formerrors.recorded_by_position[0]}}</p>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<hr>
<div class="row">
    <div class="col-sm-12">
        <div class="text-center">
            <div class="ui buttons">
                <button type="submit" class="ui primary button"  form="add-form" ng-disabled="submit" ng-class="{'loading':submit}" ng-hide="items | isEmpty">
                    <span class="glyphicon glyphicon-floppy-disk"></span> Save
                </button>
                <button type="button" class="ui red button" ng-if="edit_mode=='create'" ng-click="reset_form()">
                    <span class="glyphicon glyphicon-trash"></span> Cancel
                </button>
                <a href="javascript:history.back()" type="button" class="ui red button" ng-if="edit_mode=='update'" ng-cloak>
                    <span class="glyphicon glyphicon-trash"></span> Cancel
                </a>
            </div>
        </div>
    </div>
</div>
</form>

@endsection

@section('modals')

@endsection

@push('scripts')
<script type="text/javascript">
app.controller('content-controller', function($scope,$http, $sce, $window) {
    $scope.edit_mode = "{!! $edit_mode !!}";
    $scope.overlay_div = {
      'display': 'none'
    }
    $scope.recipients = [];
    $scope.recipient = {};
    $scope.sent_emails = 0;
    $scope.generated_form = {};
    $scope.user_data = user_data;

    if($scope.edit_mode=='create'){
        $scope.formdata = {};
        $scope.items = {};
        $scope.price_selection = {};
        $scope.formdata.requested_by_name = user_data.name;
        $scope.formdata.inventory_purchase_request_id = null;
        $scope.formdata.requested_by_position = "Department Head";
        $scope.formdata.approved_by_1_position = "Resort Manager";
        $scope.formdata.approved_by_2_position = "Managing Director";
        $scope.formdata.verified_as_funded_by_position = "Cash & Bank Section";
        $scope.formdata.recorded_by_position = "Finance Personel";
        $scope.formdata.capital_expenditure_request_date = moment().format("MM/DD/YYYY");
        $scope.formdata.requested_by_date = moment().format("MM/DD/YYYY");
        get_settings();
        // $scope.formdata.verified_as_funded_by_date = moment().format("MM/DD/YYYY");
        // $scope.formdata.recorded_by_date = moment().format("MM/DD/YYYY");
    }else{
        $scope.formdata = {!! isset($data) ? json_encode($data): '{}' !!};
        $scope.formdata.capital_expenditure_request_date = $scope.formdata.capital_expenditure_request_date ? moment($scope.formdata.capital_expenditure_request_date).format("MM/DD/YYYY") : null;
        $scope.formdata.requested_by_date = $scope.formdata.requested_by_date ? moment($scope.formdata.requested_by_date).format("MM/DD/YYYY") : null;
        $scope.formdata.verified_as_funded_by_date = $scope.formdata.verified_as_funded_by_date ? moment($scope.formdata.verified_as_funded_by_date).format("MM/DD/YYYY") : null;
        $scope.formdata.approved_by_1_date = $scope.formdata.approved_by_1_date ? moment($scope.formdata.approved_by_1_date).format("MM/DD/YYYY") : null;
        $scope.formdata.approved_by_2_date = $scope.formdata.approved_by_2_date ? moment($scope.formdata.approved_by_2_date).format("MM/DD/YYYY") : null;
        $scope.formdata.recorded_by_date = $scope.formdata.recorded_by_date ? moment($scope.formdata.recorded_by_date).format("MM/DD/YYYY") : null;
        $scope.items = {!! isset($data) ? json_encode($data['details']['data']) : '{}' !!};
        delete $scope.formdata.details;
    }
    $scope.formerrors = {};
    $scope.submit = false;
    $scope.loading = false;

    $scope.delete_item = function(index) {
        $scope.items.splice (index, 1);
    }

    $scope.close_overlay_div = function() {
      $scope.overlay_div = {
        'display': 'none',
      }
    }
    $scope.open_overlay_div = function() {
        $scope.overlay_div = {
            'z-index': '2000',
            'width': '100vw',
            'height': '100vh',
            'background-color': 'white',
            'position': 'fixed',
            'display': 'block'
        }
    }

    $scope.save_form = function() {
        if($scope.edit_mode == 'create'){
            $scope.add_form();
        }else{
            $scope.update_form();
        }
    }

    $scope.reset_form = function() {
        alertify.confirm(
            'RESET FORM',
            'Are you sure to delete this submission?',
            function(){
                $scope.$apply(function() {
                    $scope.formdata = {};
                    $scope.items = {};
                    $scope.formerrors = {};
                    $scope.formdata.inventory_purchase_request_id = null;
                });
            },
            function()
            {
                // alertify.error('Cancel')
            }
        );
    }
    
    $scope.add_form = function() {
        $scope.formerrors = {};
        $scope.submit = true;
        let items = {};
        angular.forEach($scope.items,function(value, key) {
            items[key] = {
                id: value.inventory_item_id,
                quantity: value.quantity,
                unit_cost: value.unit_price,
            };
        });
        $scope.formdata.items = items;
        $http({
            method: 'POST',
            url: route('api.inventory.capital-expenditure-request.store').url(),
            data: $.param($scope.formdata)
        }).then(function(response) {
            $scope.submit = false;
            $scope.mail_progress = '';
            $scope.sent_emails = 0;
            $scope.generated_form = response.data.capital_expenditure_request;
            $scope.recipients = response.data.recipients.data;
            $scope.formdata = {};
            $scope.formdata.capital_expenditure_request_number = response.data.capital_expenditure_request_number + 1;
            $scope.formdata.type_of_item_requested = 'operations';
            $scope.items = {};
            if($scope.recipients.length == 0){
                $.notify('Redirecting to print preview.','info');
                setTimeout(() => {
                    window.location.href = route('inventory.capital-expenditure-request.index',[$scope.generated_form.uuid]);
                }, 2000);
            }else{
                $scope.mail_progress = "0%";
                $scope.mail_users();
                $scope.open_overlay_div();
            }
            $.notify('Capital Expenditure Request has been generated.');
        }, function(rejection) {
            if (rejection.status != 422) {
                request_error(rejection.status);
            } else if (rejection.status == 422) {
                $.notify('Generation failed, please review the form.','error');
                var errors = rejection.data;
                $scope.formerrors = errors;
            }
            $scope.submit = false;
        });
    }

    $scope.update_form = function() {
        $scope.formerrors = {};
        $scope.submit = true;
        let items = {};
        angular.forEach($scope.items,function(value, key) {
            items[key] = {
                id: value.id,
                quantity: value.quantity,
                unit_cost: value.unit_price,
            };
        });
        $scope.formdata.items = items;
        $http({
            method: 'PUT',
            url: route('api.inventory.capital-expenditure-request.update',[$scope.formdata.id]).url(),
            data: $.param($scope.formdata)
        }).then(function(response) {
            $scope.submit = false;
            $.notify('Capital Expenditure Request has been updated.');
        }, function(rejection) {
            if (rejection.status != 422) {
                request_error(rejection.status);
            } else if (rejection.status == 422) {
                $.notify('Update failed, please review the form.','error');
                var errors = rejection.data;
                $scope.formerrors = errors;
            }
            $scope.submit = false;
        });
    }

    $scope.mail_users = function() {
        let user = $scope.recipients[$scope.sent_emails];
        $scope.recipient = user;
        console.log(user);
        if($scope.sent_emails==0){
            $scope.mail_progress = "0%";
        }
        $http({
            method: 'POST',
            url: route('api.inventory.capital-expenditure-request.notify.recipient',{uuid:$scope.generated_form.uuid,recipient:user.user_id}).url(),
            data: $.param(
                {
                    user:user,
                    form_type: "Capital Expenditure Request",
                    generated_form: $scope.generated_form,
                }
            )
        }).then(function(response) {
            $scope.submit = false;
            $scope.sent_emails++;
            if($scope.sent_emails!=$scope.recipients.length){
                $scope.mail_users();
            }
            let num = (($scope.sent_emails/$scope.recipients.length) * 100);
            $scope.mail_progress = num.toFixed(2) + "%"
        }, function(rejection) {
            if (rejection.status != 422) {
                request_error(rejection.status);
            } else if (rejection.status == 422) {
                $.notify('Generation failed, please review the form.','error');
                var errors = rejection.data;
                $scope.formerrors = errors;
            }
            $scope.submit = false;
        });
    }

    function get_settings() {
        $http({
            method: "GET",
            url: route('api.inventory.capital-expenditure-request.footer.get').url(),
        }).then(function mySuccess(response) {
            let footer = response.data.footer;
            $scope.formdata.verified_as_funded_by_name = footer.verified_as_funded_by_name;
            // $scope.formdata.approved_by_2_name = footer.approved_by_2_name;
            $scope.formdata.recorded_by_name = footer.recorded_by_name;
            $scope.formdata.verified_as_funded_by_date = moment().format("MM/DD/YYYY");
            // $scope.formdata.approved_by_2_date = moment().format("MM/DD/YYYY");
            $scope.formdata.recorded_by_date = moment().format("MM/DD/YYYY");
        }, function(rejection) {
            if (rejection.status != 422) {
                request_error(rejection.status);
            } else if (rejection.status == 422) {
                console.log(rejection.statusText);
            }
        });
    }


    $("#search-purchase-request").autocomplete({
        source: function(request, response)
        {
            $.ajax({
                url: route('api.inventory.purchase-request.list').url(),
                dataType: "json",
                data: {
                    searchString : request.term,
                    autocomplete : 1,
                    approved: 0,
                    capex: 1
                },
                success: function(data) {
                    response(data.result.data);
                }
            });
        },
        select: function(event, ui) {
            // $scope.searchString = "";
            $scope.$apply(function() {
                $scope.items = {};
                $scope.items = ui.item.details.data;
                $scope.formdata.inventory_purchase_request_id = ui.item.id;
                $scope.purchase_request_number_formatted = ui.item.purchase_request_number_formatted;
                $scope.formdata.department = ui.item.requesting_department;
            });
        }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
        .append( "<div> PR No. <b>" + item.purchase_request_number_formatted + "</b><br> <small> PR DATE: <b>" + item.purchase_request_date_formatted + "</b></small>" + "</div>" )
        .appendTo( ul );
    };

    $('#capital_expenditure_request_date,#date_needed').datepicker();
    $('#requested_by_date,#approved_by_1_date,#verified_as_funded_by_date,#approved_by_2_date,#recorded_by_date').datepicker();

    window.onbeforeunload = confirmExit;

    function confirmExit() {
      if ($scope.sent_emails!=$scope.recipients.length) return "Exporting is still in progress.";
    }
});
</script>
@endpush