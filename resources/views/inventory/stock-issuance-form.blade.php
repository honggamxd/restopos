@extends('layouts.main')

@section('title', 'Stock Issuance')

@section('css')
<style type="text/css">


</style>
@endsection
@section('breadcrumb')
<a class="section" href="{{route('inventory.stock-issuance.list')}}">Stock Issuances</a>
<i class="right angle icon divider"></i>
<div class="active section" ng-if="edit_mode=='create'" ng-cloak>Create Stock Issuance</div>
<div class="active section" ng-if="edit_mode=='update'" ng-cloak>Edit Stock Issuance</div>
<i class="divider">|</i>
<a class="section" href="{{route('inventory.stock-issuance.create')}}" ng-if="edit_mode=='update' && (user_data.privilege == 'admin' || user_data.permissions.can_add_stock_issuances)" ng-cloak>Create Stock Issuance</a>
<i class="divider" ng-if="edit_mode=='update' && (user_data.privilege == 'admin' || user_data.permissions.can_add_stock_issuances)">|</i>
<a class="section" href="{{route('inventory.stock-issuance.settings')}}">Settings</a>
@endsection

@section('overlay-div')
<div ng-style="overlay_div" ng-cloak>
    <div style="margin-top: 10vh;text-align: center;padding-right: 20%;padding-left: 20%;">
        <span ng-if="sent_emails!=recipients.length">
            Sending Stock Issuance to: @{{recipient.user.name}}<@{{recipient.user.email_address}}>
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
        <p ng-if="generated_form.form"><a ng-href="@{{generated_form.form}}">View Generated Stock Issuance Form</a></p>
        <p><a href="{{route('inventory.stock-issuance.create')}}">Create new Stock Issuance</a></p>
  </div>
</div>
@endsection

@section('padded_content')
<form id="add-form" ng-submit="save_form()" class="form">
<div class="row">
    <div class="col-sm-12">
        <h2 style="text-align: center;">Stock Issuance Header</h2>
        <br>
        <i>All fields marked with asterisk (*) are required field.</i>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="stock_issuance_number"><small style="color:red">*</small> Stock Issuance Number:</label>
                    {{-- <input type="text" class="form-control" placeholder="Enter Stock Issuance Number" id="stock_issuance_number" ng-model="formdata.stock_issuance_number"> --}}
                    <input type="text" class="form-control" placeholder="Enter Stock Issuance Number" id="stock_issuance_number" value="Auto Generated" readonly>
                    <p class="help-block" ng-cloak>@{{formerrors.stock_issuance_number[0]}}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="receiving_report_number_formatted">RR Number:</label>
                    <span class="form-control" ng-bind="receiving_report_number_formatted"></span>
                    <p class="help-block" ng-cloak></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="requesting_department"><small style="color:red">*</small> Requesting Department:</label>
                    <input type="text" class="form-control" placeholder="Enter Requesting Department" id="requesting_department" ng-model="formdata.requesting_department">
                    <p class="help-block" ng-cloak>@{{formerrors.requesting_department[0]}}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="stock_issuance_date"><small style="color:red">*</small> Date:</label>
                    <input type="text" class="form-control" placeholder="Enter Date Requested" id="stock_issuance_date" ng-model="formdata.stock_issuance_date" readonly>
                    <p class="help-block" ng-cloak>@{{formerrors.stock_issuance_date[0]}}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="request_chargeable_to"><small style="color:red">*</small> Request Chargeable To:</label>
                    <input type="text" class="form-control" placeholder="Enter Request Chargeable To" id="request_chargeable_to" ng-model="formdata.request_chargeable_to">
                    <p class="help-block" ng-cloak>@{{formerrors.request_chargeable_to[0]}}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="supplier_address"><small style="color:red">*</small> Supplier Address:</label>
                    <input type="text" class="form-control" placeholder="Enter Supplier Address" id="supplier_address" ng-model="formdata.supplier_address">
                    <p class="help-block" ng-cloak>@{{formerrors.supplier_address[0]}}</p>
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
        <h2 style="text-align: center;">Stock Issuance Items</h2>
        <br>
        <div class="row">
            <div class="col-sm-6">
                <label ng-hide="edit_mode=='update'" ng-cloak>Search Item</label>
                <div class="ui icon input fluid" ng-hide="edit_mode=='update'" ng-cloak>
                    <i class="search icon"></i>
                    <input type="text" placeholder="Search" id="search-item">
                </div>
            </div>
            <div class="col-sm-6">
                <label ng-hide="edit_mode=='update'" ng-cloak>Search Receiving Report Number</label>
                <div class="ui icon input fluid" ng-hide="edit_mode=='update'" ng-cloak>
                    <i class="search icon"></i>
                    <input type="text" placeholder="Search" id="search-receiving-report" ng-model="search_item_name">
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
                    <th class="center aligned middle aligned">Remarks</th>
                    <th class="center aligned middle aligned"></th>
                </tr>
                </thead>
                <tbody ng-cloak>
                    <tr ng-repeat="(index,item) in items" ng-if="edit_mode=='create' && receiving_report_number_formatted">
                        <td class="center aligned middle aligned">@{{item.inventory_item.category}}</td>
                        <td class="center aligned middle aligned">@{{item.inventory_item.subcategory}}</td>
                        <td class="center aligned middle aligned" style="width: 100%">@{{item.inventory_item.item_name}}</td>
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
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                <input type="text" placeholder="Remarks" ng-model="item.remarks">
                            </div>
                        </td>
                        <td class="right aligned middle aligned"><button type="button" class="btn btn-danger" ng-click="delete_item(index)">&times;</button></td>
                    </tr>
                    <tr ng-repeat="(index,item) in items" ng-if="edit_mode=='create' && !receiving_report_number_formatted">
                        <td class="center aligned middle aligned">@{{item.category}}</td>
                        <td class="center aligned middle aligned">@{{item.subcategory}}</td>
                        <td class="center aligned middle aligned" style="width: 100%">@{{item.item_name}}</td>
                        <td class="center aligned middle aligned">@{{item.unit_of_measure}}</td>
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
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                <input type="text" placeholder="Remarks" ng-model="item.remarks">
                            </div>
                        </td>
                        <td class="right aligned middle aligned"><button type="button" class="btn btn-danger" ng-click="delete_item(index)">&times;</button></td>
                    </tr>
                    <tr ng-repeat="(index,item) in items" ng-if="edit_mode=='update'">
                        <td class="center aligned middle aligned">@{{item.inventory_item.category}}</td>
                        <td class="center aligned middle aligned">@{{item.inventory_item.subcategory}}</td>
                        <td class="center aligned middle aligned" style="width: 100%">@{{item.inventory_item.item_name}}</td>
                        <td class="center aligned middle aligned">@{{item.inventory_item.unit_of_measure}}</td>
                        <td class="center aligned middle aligned">
                            <div class="ui input" ng-if="!formdata.approved_by_date">
                                <input type="number" min="1" placeholder="Quantity" ng-model="item.quantity">
                                {{-- <span ng-bind="item.quantity"></span> --}}
                            </div>
                            <div class="ui input" ng-if="formdata.approved_by_date">
                                {{-- <input type="number" min="1" placeholder="Quantity" ng-model="item.quantity"> --}}
                                <span ng-bind="item.quantity"></span>
                            </div>
                            <p class="help-block">@{{ formerrors['items.'+index+'.error'][0] }}</p>
                        </td>
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                <input type="number" min="0" step="0.01" placeholder="Unit Cost" ng-model="item.unit_price">
                            </div>
                        </td>
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                <input type="text" placeholder="Remarks" ng-model="item.remarks">
                            </div>
                        </td>
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
        <h2 style="text-align: center;">Stock Issuance Footer</h2>
        <br>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="issued_by_name"><small style="color:red">*</small> Stock Issued By:</label>
                    <div data-tooltip="Search for the name of the warehouse representative" data-position="bottom right" data-inverted="">
                        <input type="text" class="form-control" placeholder="Enter Stock Issued By" id="issued_by_name" ng-model="formdata.issued_by_name">
                    </div>
                    <p class="help-block" ng-cloak>@{{formerrors.issued_by_name[0]}}</p>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="received_by_name">Received By:</label>
                    <input type="text" class="form-control" placeholder="Enter Received By" id="received_by_name" ng-model="formdata.received_by_name">
                    <p class="help-block" ng-cloak>@{{formerrors.received_by_name[0]}}</p>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="posted_by_name">Posted By:</label>
                    <input type="text" class="form-control" placeholder="Enter Posted By" id="posted_by_name" ng-model="formdata.posted_by_name">
                    <p class="help-block" ng-cloak>@{{formerrors.posted_by_name[0]}}</p>
                </div>
            </div>
            <div class="col-sm-3" ng-if="edit_mode=='update'">
                <div class="form-group">
                    <label for="approved_by_name">Approved By: <a href="javascript:void(0);" ng-click="fill_approved_by()"><small data-tooltip="This will fill your name and date" data-position="right center" data-inverted="">Fill</small></a></label>
                    <input type="text" class="form-control" placeholder="Enter Approved By" id="approved_by_name" ng-model="formdata.approved_by_name">
                    <p class="help-block" ng-cloak>@{{formerrors.approved_by_name[0]}}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="issued_by_date">Date:</label>
                    <span class="form-control" ng-bind="formdata.issued_by_date" readonly></span>
                    <p class="help-block" ng-cloak>@{{formerrors.issued_by_date[0]}}</p>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="received_by_date">Date:</label>
                    <input type="text" class="form-control" placeholder="Enter Date" id="received_by_date" ng-model="formdata.received_by_date" readonly>
                    <p class="help-block" ng-cloak>@{{formerrors.received_by_date[0]}}</p>
                </div>
            </div>
            <div class="col-sm-3" ng-hide="edit_mode=='create'" ng-cloack>
                <div class="form-group">
                    <label for="approved_by_date">Date:</label>
                    <input type="text" class="form-control" placeholder="Enter Date" id="approved_by_date" ng-model="formdata.approved_by_date" readonly>
                    <p class="help-block" ng-cloak>@{{formerrors.approved_by_date[0]}}</p>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="posted_by_date">Date:</label>
                    <input type="text" class="form-control" placeholder="Enter Date" id="posted_by_date" ng-model="formdata.posted_by_date" readonly>
                    <p class="help-block" ng-cloak>@{{formerrors.posted_by_date[0]}}</p>
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
    $scope.generated_form = {}
    $scope.is_approved = false;
    $scope.user_data = user_data;

    if($scope.edit_mode=='create'){
        $scope.formdata = {};
        $scope.items = {};
        $scope.receiving_report_number_formatted = null;
        $scope.formdata.issued_by_date = moment().format("MM/DD/YYYY");
        $scope.formdata.stock_issuance_date = moment().format("MM/DD/YYYY");
        $scope.formdata.posted_by_date = moment().format("MM/DD/YYYY");
        $scope.formdata.received_by_date = moment().format("MM/DD/YYYY");
        $scope.formdata.issued_by_name = user_data.name;
        get_settings();
    }else{
        $scope.formdata = {!! isset($data) ? json_encode($data): '{}' !!};
        $scope.formdata.stock_issuance_date = $scope.formdata.stock_issuance_date ? moment($scope.formdata.stock_issuance_date).format("MM/DD/YYYY") : null;
        $scope.formdata.issued_by_date = $scope.formdata.issued_by_date ? moment($scope.formdata.issued_by_date).format("MM/DD/YYYY") : null;
        $scope.formdata.received_by_date = $scope.formdata.received_by_date ? moment($scope.formdata.received_by_date).format("MM/DD/YYYY") : null;
        $scope.formdata.approved_by_date = $scope.formdata.approved_by_date ? moment($scope.formdata.approved_by_date).format("MM/DD/YYYY") : null;
        if($scope.formdata.approved_by_date){
            $scope.is_approved = true;
        }else{
            $scope.is_approved = false;
        }
        $scope.formdata.posted_by_date = $scope.formdata.posted_by_date ? moment($scope.formdata.posted_by_date).format("MM/DD/YYYY") : null;
        $scope.items = {!! isset($data) ? json_encode($data['details']['data']) : '{}' !!};
        $scope.receiving_report_number_formatted = $scope.formdata.inventory_receiving_report ? $scope.formdata.inventory_receiving_report.receiving_report_number_formatted : null;
        delete $scope.formdata.details;
    }
    $scope.$watch('formdata["stock_issuance_date"]', function (newValue, oldValue, scope) {
        $scope.formdata.issued_by_date = newValue;
    });
    $scope.formerrors = {};
    $scope.submit = false;
    $scope.loading = false;

    $scope.delete_item = function(index) {
        if($scope.items instanceof Array){
            $scope.items.splice (index, 1);
        }else{
            delete $scope.items[index];
        }
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
            if(!$scope.formdata.is_approved && $scope.formdata.approved_by_name){
                alertify.confirm(
                    'Save Stock Issuance',
                    'After submitting, the items in the form will update its quantity. continue?',
                    function(){
                        $scope.update_form();
                    },
                    function()
                    {
                        // alertify.error('Cancel')
                    }
                );
            }else{
                $scope.update_form();
            }
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
                    $scope.receiving_report_number_formatted = null;
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
            let item_id = ($scope.receiving_report_number_formatted ? value.inventory_item_id : value.id);
            items[key] = {
                id: item_id,
                quantity: value.quantity,
                unit_cost: value.unit_price,
                remarks: value.remarks,
            };
        });
        $scope.formdata.items = items;
        $http({
            method: 'POST',
            url: route('api.inventory.stock-issuance.store').url(),
            data: $.param($scope.formdata)
        }).then(function(response) {
            $scope.submit = false;
            $scope.mail_progress = '';
            $scope.sent_emails = 0;
            $scope.generated_form = response.data.stock_issuance;
            $scope.recipients = response.data.recipients.data;
            $scope.formdata = {};
            $scope.formdata.stock_issuance_number = response.data.stock_issuance_number + 1;
            $scope.items = {};
            if($scope.recipients.length == 0){
                $.notify('Redirecting to print preview.','info');
                setTimeout(() => {
                    window.location.href = route('inventory.stock-issuance.index',[$scope.generated_form.uuid]);
                }, 2000);
            }else{
                $scope.mail_progress = "0%";
                $scope.mail_users();
                $scope.open_overlay_div();
            }
            $.notify('Stock Issuance has been generated.');
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
                inventory_item_id: value.inventory_item_id,
                quantity: value.quantity,
                key: key,
                unit_cost: value.unit_price,
                remarks: value.remarks,
            };
        });
        $scope.formdata.items = items;
        $http({
            method: 'PUT',
            url: route('api.inventory.stock-issuance.update',[$scope.formdata.id]).url(),
            data: $.param($scope.formdata)
        }).then(function(response) {
            $scope.submit = false;
            $scope.formdata.approved_by_date = response.data.approved_by_date ? moment(response.data.approved_by_date.date).format("MM/DD/YYYY") : null;
            $scope.formdata.is_approved = response.data.is_approved;
            $.notify('Stock Issuance has been updated.');
        }, function(rejection) {
            if (rejection.status != 422) {
                request_error(rejection.status);
            } else if (rejection.status == 422) {
                $.notify('Updating failed, please review the form.','error');
                var errors = rejection.data;
                $scope.formerrors = errors;
            }
            $scope.submit = false;
        });
    }

    $scope.fill_approved_by = function() {
        $scope.formdata.approved_by_date = moment().format("MM/DD/YYYY");
        $scope.formdata.approved_by_name = user_data.name;
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
            url: route('api.inventory.stock-issuance.notify.recipient',{uuid:$scope.generated_form.uuid,recipient:user.user_id}).url(),
            data: $.param(
                {
                    user:user,
                    form_type: "Stock Issuance",
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
            url: route('api.inventory.stock-issuance.footer.get').url(),
        }).then(function mySuccess(response) {
            let footer = response.data.footer;
            $scope.formdata.posted_by_name = footer.posted_by_name;
            $scope.formdata.posted_by_date = moment().format("MM/DD/YYYY");
        }, function(rejection) {
            if (rejection.status != 422) {
                request_error(rejection.status);
            } else if (rejection.status == 422) {
                console.log(rejection.statusText);
            }
        });
    }

    $("#search-item").autocomplete({
        source: function(request, response)
        {
            $.ajax({
                url: route('api.inventory.item.index').url(),
                dataType: "json",
                data: {
                    term : request.term,
                    autocomplete : 1
                },
                success: function(data) {
                    response(data.result.data);
                }
            });
        },
        select: function(event, ui) {
            $scope.searchString = "";
            if($scope.receiving_report_number_formatted){
                $scope.items = {};
                $scope.receiving_report_number_formatted = null;
                $scope.formdata.inventory_receiving_report_id = null;
            }
            $scope.$apply(function() {
                if(!$scope.items['item'+ui.item.id]){
                    ui.item.quantity = 1;
                    ui.item.unit_price = 0;
                    $scope.items['item'+ui.item.id] = ui.item;
                }
            });
        }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
        .append( "<div>" + item.item_name + "<br> <small> Category: <b>" + item.category + "</b></small>" +"<br> <small> Subcategory: <b>" + item.subcategory + "</b></small>" + "</div>" )
        .appendTo( ul );
    };


    $("#search-receiving-report").autocomplete({
        source: function(request, response)
        {
            $.ajax({
                url: route('api.inventory.receiving-report.list').url(),
                dataType: "json",
                data: {
                    searchString : request.term,
                    autocomplete : 1,
                    approved: 1
                },
                success: function(data) {
                    response(data.result.data);
                }
            });
        },
        select: function(event, ui) {
            $scope.$apply(function() {
                $scope.items = {};
                $scope.items = ui.item.details.data;
                $scope.formdata.inventory_receiving_report_id = ui.item.id;
                $scope.receiving_report_number_formatted = ui.item.receiving_report_number_formatted;
                $scope.formdata.supplier_address = ui.item.supplier_address;
                $scope.formdata.supplier_name = ui.item.supplier_name;
                $scope.formdata.request_chargeable_to = ui.item.request_chargeable_to;
                $scope.formdata.requesting_department = ui.item.requesting_department;
            });
        }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
        .append( "<div> RR No. <b>" + item.receiving_report_number_formatted + "</b><br> <small> RR DATE: <b>" + item.receiving_report_date_formatted + "</b></small>" + "</div>" )
        .appendTo( ul );
    };

    $('#stock_issuance_date,#date_to').datepicker();
    $('#received_by_date,#issued_by_date,#approved_by_date,#posted_by_date').datepicker();

    $("#issued_by_name").autocomplete({
        source: route('api.user.list').url() + "?fieldName=position&fieldValue=Warehouse Representative"
    });
    $("#received_by_name").autocomplete({
        source: route('api.user.list').url()
    });
    $("#posted_by_name").autocomplete({
        source: route('api.user.list').url()
    });

    window.onbeforeunload = confirmExit;

    function confirmExit() {
      if ($scope.sent_emails!=$scope.recipients.length) return "Exporting is still in progress.";
    }
});
</script>
@endpush