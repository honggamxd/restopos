@extends('layouts.main')

@section('title', 'Request to Canvass')

@section('css')
<style type="text/css">


</style>
@endsection
@section('breadcrumb')
<a class="section" href="{{route('inventory.request-to-canvass.list')}}">Request to Canvasses</a>
<i class="right angle icon divider"></i>
<div class="active section" ng-if="edit_mode=='create' && (user_data.privilege == 'admin' || user_data.permissions.can_add_request_to_canvasses)" ng-cloak>Create Request to Canvass</div>
<div class="active section" ng-if="edit_mode=='update'" ng-cloak>Edit Request to Canvass</div>
<i class="divider">|</i>
<a class="section" href="{{route('inventory.request-to-canvass.create')}}" ng-if="edit_mode=='update' && (user_data.privilege == 'admin' || user_data.permissions.can_add_request_to_canvasses)" ng-cloak>Create Request to Canvass</a>
<i class="divider" ng-if="edit_mode=='update' && (user_data.privilege == 'admin' || user_data.permissions.can_add_request_to_canvasses)">|</i>
<a class="section" href="{{route('inventory.request-to-canvass.settings')}}">Settings</a>
@endsection
@section('padded_content')
<form id="add-form" ng-submit="save_form()" class="form">
<div class="row">
    <div class="col-sm-12">
        <h2 style="text-align: center;">Request to Canvass Header</h2>
        <br>
        <i>All fields marked with asterisk (*) are required field.</i>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="request_to_canvass_number"><small style="color:red">*</small> Request to Canvass Number:</label>
                    {{-- <input type="text" class="form-control" placeholder="Enter Request to Canvass Number" id="request_to_canvass_number" ng-model="formdata.request_to_canvass_number"> --}}
                    <input type="text" class="form-control" placeholder="Enter Request to Canvass Number" id="request_to_canvass_number" value="Auto Generated" readonly>
                    <p class="help-block" ng-cloak>@{{formerrors.request_to_canvass_number[0]}}</p>
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
                    <label for="requesting_department"><small style="color:red">*</small> Requesting Department:</label>
                    <input type="text" class="form-control" placeholder="Enter Requesting Department" id="requesting_department" ng-model="formdata.requesting_department">
                    <p class="help-block" ng-cloak>@{{formerrors.requesting_department[0]}}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="request_to_canvass_date"><small style="color:red">*</small> Date Requested:</label>
                    <input type="text" class="form-control" placeholder="Enter Date Requested" id="request_to_canvass_date" ng-model="formdata.request_to_canvass_date" readonly>
                    <p class="help-block" ng-cloak>@{{formerrors.request_to_canvass_date[0]}}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="reason_for_the_request"><small style="color:red">*</small> Reason for the Request:</label>
                    <input type="text" class="form-control" placeholder="Enter Reason for the Request" id="reason_for_the_request" ng-model="formdata.reason_for_the_request">
                    <p class="help-block" ng-cloak>@{{formerrors.reason_for_the_request[0]}}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="type_of_item_requested"><small style="color:red">*</small> Type of Item Requested:</label>
                    <select class="form-control" id="type_of_item_requested" ng-model="formdata.type_of_item_requested">
                        <option value="">Select type of items requested</option>
                        <option value="operations">Operations</option>
                        <option value="capex">Capex</option>
                    </select>
                    <p class="help-block" ng-cloak>@{{formerrors.type_of_item_requested[0]}}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="vendor_1_name">Vendors:</label>
                    <input type="text" class="form-control" placeholder="Enter Vendor Name" ng-model="formdata.vendor_1_name">
                    <p class="help-block" ng-cloak>@{{formerrors.vendor_1_name[0]}}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="vendor_2_name">Vendors:</label>
                    <input type="text" class="form-control" placeholder="Enter Vendor Name" ng-model="formdata.vendor_2_name">
                    <p class="help-block" ng-cloak>@{{formerrors.vendor_2_name[0]}}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="vendor_3_name">Vendors:</label>
                    <input type="text" class="form-control" placeholder="Enter Vendor Name" ng-model="formdata.vendor_3_name">
                    <p class="help-block" ng-cloak>@{{formerrors.vendor_3_name[0]}}</p>
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
        <h2 style="text-align: center;">Request to Canvass Items</h2>
        <br>
        <div class="row">
            <div class="col-sm-6">
                <label ng-hide="edit_mode=='update'" ng-cloak>Search Item</label>
                <div class="ui icon input fluid" ng-hide="edit_mode=='update'" ng-cloak>
                    <i class="search icon"></i>
                    <input type="text" placeholder="Search" id="search-item" ng-model="search_item_name">
                </div>
            </div>
            <div class="col-sm-6">
                <label ng-hide="edit_mode=='update'" ng-cloak>Search Purchase Request Number</label>
                <div class="ui icon input fluid" ng-hide="edit_mode=='update'" ng-cloak>
                    <i class="search icon"></i>
                    <input type="text" placeholder="Search" id="search-purchase-request">
                </div>
            </div>
        </div>
        <br>
        <div class="table-responsive">
            <table class="ui single line unstackable table">
                <thead>
                <tr>
                    <th class="center aligned middle aligned">Item</th>
                    <th class="center aligned middle aligned">Unit of Measure</th>
                    <th class="center aligned middle aligned">Quantity</th>
                    <th class="center aligned middle aligned"><span ng-bind="formdata.vendor_1_name"></span><br>Unit Price</th>
                    <th class="center aligned middle aligned"><span ng-bind="formdata.vendor_2_name"></span><br>Unit Price</th>
                    <th class="center aligned middle aligned"><span ng-bind="formdata.vendor_3_name"></span><br>Unit Price</th>
                    <th class="center aligned middle aligned"></th>
                </tr>
                </thead>
                <tbody ng-cloak>
                    <tr ng-repeat="(index,item) in items" ng-if="edit_mode=='create' && purchase_request_number_formatted">
                        <td class="center aligned middle aligned" style="width: 100%">@{{item.inventory_item.item_name}}</td>
                        <td class="center aligned middle aligned">@{{item.inventory_item.unit_of_measure}}</td>
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                <input type="number" min="1" placeholder="Quantity" ng-model="item.quantity">
                            </div>
                        </td>
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                <input type="number" min="0" step="0.01" placeholder="Unit Cost" ng-model="item.vendor_1_unit_price">
                            </div>
                        </td>
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                <input type="number" min="0" step="0.01" placeholder="Unit Cost" ng-model="item.vendor_2_unit_price">
                            </div>
                        </td>
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                <input type="number" min="0" step="0.01" placeholder="Unit Cost" ng-model="item.vendor_3_unit_price">
                            </div>
                        </td>
                        <td class="right aligned middle aligned"><button type="button" class="btn btn-danger" ng-click="delete_item(index)">&times;</button></td>
                    </tr>
                    <tr ng-repeat="(index,item) in items" ng-if="edit_mode=='create' && !purchase_request_number_formatted">
                        <td class="center aligned middle aligned" style="width: 100%">@{{item.item_name}}</td>
                        <td class="center aligned middle aligned">@{{item.unit_of_measure}}</td>
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                <input type="number" min="1" placeholder="Quantity" ng-model="item.quantity">
                            </div>
                        </td>
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                <input type="number" min="0" step="0.01" placeholder="Unit Cost" ng-model="item.vendor_1_unit_price">
                            </div>
                        </td>
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                <input type="number" min="0" step="0.01" placeholder="Unit Cost" ng-model="item.vendor_2_unit_price">
                            </div>
                        </td>
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                <input type="number" min="0" step="0.01" placeholder="Unit Cost" ng-model="item.vendor_3_unit_price">
                            </div>
                        </td>
                        <td class="right aligned middle aligned"><button type="button" class="btn btn-danger" ng-click="delete_item(index)">&times;</button></td>
                    </tr>
                </tbody>
                <tbody ng-cloak>
                    <tr ng-repeat="(index,item) in items" ng-if="edit_mode=='update'">
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                <input type="number" min="1" placeholder="Quantity" ng-model="item.quantity">
                            </div>
                        </td>
                        <td class="center aligned middle aligned">@{{item.inventory_item.unit_of_measure}}</td>
                        <td class="center aligned middle aligned" style="width: 100%">@{{item.inventory_item.item_name}}</td>
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                <input type="number" min="0" step="0.01" placeholder="Unit Cost" ng-model="item.vendor_1_unit_price">
                            </div>
                        </td>
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                <input type="number" min="0" step="0.01" placeholder="Unit Cost" ng-model="item.vendor_2_unit_price">
                            </div>
                        </td>
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                <input type="number" min="0" step="0.01" placeholder="Unit Cost" ng-model="item.vendor_3_unit_price">
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
        <h2 style="text-align: center;">Request to Canvass Footer</h2>
        <br>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="requested_by_name"><small style="color:red">*</small> Requested By:</label>
                    <input type="text" class="form-control" placeholder="Enter Requested By" id="requested_by_name" ng-model="formdata.requested_by_name">
                    <p class="help-block" ng-cloak>@{{formerrors.requested_by_name[0]}}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="noted_by_name">Noted By:</label>
                    <div data-tooltip="Search for the name of the department head" data-position="bottom right" data-inverted="">
                        <input type="text" class="form-control" placeholder="Enter Noted By" id="noted_by_name" ng-model="formdata.noted_by_name">
                    </div>
                    <p class="help-block" ng-cloak>@{{formerrors.noted_by_name[0]}}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="canvass_by_name">Canvass By:</label>
                    <div data-tooltip="Search for the name of a purchaser" data-position="bottom right" data-inverted="">
                        <input type="text" class="form-control" placeholder="Enter Canvass By" id="canvass_by_name" ng-model="formdata.canvass_by_name">
                    </div>
                    <p class="help-block" ng-cloak>@{{formerrors.canvass_by_name[0]}}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="requested_by_date">Date:</label>
                    <span class="form-control" ng-bind="formdata.requested_by_date" readonly></span>
                    <p class="help-block" ng-cloak>@{{formerrors.requested_by_date[0]}}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="noted_by_date">Date:</label>
                    <input type="text" class="form-control" placeholder="Enter Date" id="noted_by_date" ng-model="formdata.noted_by_date" readonly>
                    <p class="help-block" ng-cloak>@{{formerrors.noted_by_date[0]}}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="canvass_by_date">Date:</label>
                    <input type="text" class="form-control" placeholder="Enter Date" id="canvass_by_date" ng-model="formdata.canvass_by_date" readonly>
                    <p class="help-block" ng-cloak>@{{formerrors.canvass_by_date[0]}}</p>
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
    if($scope.edit_mode=='create'){
        $scope.formdata = {};
        $scope.formdata.requested_by_name = user_data.name;
        $scope.formdata.type_of_item_requested = null;
        $scope.formdata.vendor_1_name = 'Vendor 1';
        $scope.formdata.vendor_2_name = 'Vendor 2';
        $scope.formdata.vendor_3_name = 'Vendor 3';
        $scope.formdata.request_to_canvass_date = moment().format("MM/DD/YYYY");
        $scope.formdata.requested_by_date = moment().format("MM/DD/YYYY");
        // $scope.formdata.noted_by_date = moment().format("MM/DD/YYYY");
        // $scope.formdata.canvass_by_date = moment().format("MM/DD/YYYY");
        $scope.items = {};
        get_settings();
    }else{
        $scope.formdata = {!! isset($data) ? json_encode($data): '{}' !!};
        $scope.formdata.request_to_canvass_date = moment($scope.formdata.request_to_canvass_date.date).format("MM/DD/YYYY");
        $scope.items = {!! isset($data) ? json_encode($data['details']['data']) : '{}' !!};
        delete $scope.formdata.details;
    }
    $scope.formerrors = {};
    $scope.submit = false;
    $scope.loading = false;
    $scope.user_data = user_data;

    $scope.delete_item = function(index) {
        delete $scope.items[index];
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
                    $scope.formdata.type_of_item_requested = null;
                    $scope.items = {};
                    $scope.formerrors = {};
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
                id: value.id,
                quantity: value.quantity,
                vendor_1_unit_price: value.vendor_1_unit_price,
                vendor_2_unit_price: value.vendor_2_unit_price,
                vendor_3_unit_price: value.vendor_3_unit_price,
            };
        });
        $scope.formdata.items = items;
        $http({
            method: 'POST',
            url: route('api.inventory.request-to-canvass.store').url(),
            data: $.param($scope.formdata)
        }).then(function(response) {
            $scope.submit = false;
            $.notify('Redirecting to print preview.','info');
            $.notify('Request to Canvass has been generated.');
            $scope.formdata = {};
            $scope.formdata.type_of_item_requested = null;
            $scope.formdata.request_to_canvass_number = response.data.request_to_canvass_number + 1;
            $scope.items = {};
            setTimeout(() => {
                window.location.href = route('inventory.request-to-canvass.index',[response.data.uuid]);
            }, 2000);
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
                vendor_1_unit_price: value.vendor_1_unit_price,
                vendor_2_unit_price: value.vendor_2_unit_price,
                vendor_3_unit_price: value.vendor_3_unit_price,
            };
        });
        $scope.formdata.items = items;
        $http({
            method: 'PUT',
            url: route('api.inventory.request-to-canvass.update',[$scope.formdata.id]).url(),
            data: $.param($scope.formdata)
        }).then(function(response) {
            $scope.submit = false;
            $.notify('Request to Canvass has been updated.');
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

    $scope.$watch('formdata["request_to_canvass_date"]', function (newValue, oldValue, scope) {
        $scope.formdata.requested_by_date = newValue;
    });

    function get_settings() {
        $http({
            method: "GET",
            url: route('api.inventory.request-to-canvass.footer.get').url(),
        }).then(function mySuccess(response) {
            let footer = response.data.footer;
            $scope.formdata.noted_by_name = footer.noted_by_name;
            $scope.formdata.noted_by_date = moment().format("MM/DD/YYYY");
            $scope.formdata.canvass_by_name = footer.canvass_by_name;
            $scope.formdata.canvass_by_date = moment().format("MM/DD/YYYY");
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
            $scope.$apply(function() {
                if($scope.purchase_request_number_formatted){
                    $scope.purchase_request_number_formatted = null;
                    $scope.formdata.inventory_purchase_request_id = null;
                    $scope.items = {};
                }
                if(!$scope.items['item'+ui.item.id]){
                    ui.item.quantity = 1;
                    ui.item.vendor_1_unit_price = 0;
                    ui.item.vendor_2_unit_price = 0;
                    ui.item.vendor_3_unit_price = 0;
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

    $("#search-purchase-request").autocomplete({
        source: function(request, response)
        {
            $.ajax({
                url: route('api.inventory.purchase-request.list').url(),
                dataType: "json",
                data: {
                    searchString : request.term,
                    autocomplete : 1,
                    approved: 0
                },
                success: function(data) {
                    response(data.result.data);
                }
            });
        },
        select: function(event, ui) {
            $scope.searchString = "";
            $scope.$apply(function() {
                $scope.items = {};
                $scope.items = ui.item.details.data;
                angular.forEach($scope.items,function(value, key) {
                    value.vendor_1_unit_price = 0;
                    value.vendor_2_unit_price = 0;
                    value.vendor_3_unit_price = 0;
                });
                $scope.formdata.inventory_purchase_request_id = ui.item.id;
                $scope.purchase_request_number_formatted = ui.item.purchase_request_number_formatted;
                $scope.formdata.reason_for_the_request = ui.item.reason_for_the_request;
                $scope.formdata.type_of_item_requested = ui.item.type_of_item_requested;
                $scope.formdata.requesting_department = ui.item.requesting_department;
            });
        }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
        .append( "<div> PR No. <b>" + item.purchase_request_number_formatted + "</b><br> <small> PR DATE: <b>" + item.purchase_request_date_formatted + "</b></small>" + "</div>" )
        .appendTo( ul );
    };

    $('#request_to_canvass_date,#date_needed').datepicker();
    $('#noted_by_date,#canvass_by_date').datepicker();

    $("#noted_by_name").autocomplete({
        source: route('api.user.list').url() + "?fieldName=position&fieldValue=Department Head"
    });
    $("#canvass_by_name").autocomplete({
        source: route('api.user.list').url() + "?fieldName=position&fieldValue=Purchasing"
    });
    $("#requested_by_name").autocomplete({
        source: route('api.user.list').url()
    });
});
</script>
@endpush