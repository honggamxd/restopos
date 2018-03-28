@extends('layouts.main')

@section('title', 'Request to Canvass')

@section('css')
<style type="text/css">


</style>
@endsection
@section('breadcrumb')
<a class="section" href="{{route('inventory.request-to-canvass.list')}}">Request to Canvasses</a>
<i class="right angle icon divider"></i>
<div class="active section" ng-if="edit_mode=='create'" ng-cloak>Create Request to Canvass</div>
<div class="active section" ng-if="edit_mode=='update'" ng-cloak>Edit Request to Canvass</div>
@endsection
@section('padded_content')
<form id="add-form" ng-submit="save_form()" class="form">
<div class="row">
    <div class="col-sm-12">
        <h2 style="text-align: center;">Request to Canvass Header</h2>
        <br>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="request_to_canvass_number">Request to Canvass Number:</label>
                    <input type="text" class="form-control" placeholder="Enter Request to Canvass Number" id="request_to_canvass_number" ng-model="formdata.request_to_canvass_number">
                    <p class="help-block" ng-cloak>@{{formerrors.request_to_canvass_number[0]}}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="inventory_request_to_canvass">PO Number:</label>
                    <input type="text" class="form-control" placeholder="Enter PO Number">
                    <p class="help-block" ng-cloak></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="requesting_department">Requesting Department:</label>
                    <input type="text" class="form-control" placeholder="Enter Requesting Department" id="requesting_department" ng-model="formdata.requesting_department">
                    <p class="help-block" ng-cloak>@{{formerrors.requesting_department[0]}}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="request_to_canvass_date">Date Requested:</label>
                    <input type="text" class="form-control" placeholder="Enter Date Requested" id="request_to_canvass_date" ng-model="formdata.request_to_canvass_date" readonly>
                    <p class="help-block" ng-cloak>@{{formerrors.request_to_canvass_date[0]}}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="reason_for_the_request">Reason for the Request:</label>
                    <input type="text" class="form-control" placeholder="Enter Reason for the Request" id="reason_for_the_request" ng-model="formdata.reason_for_the_request">
                    <p class="help-block" ng-cloak>@{{formerrors.reason_for_the_request[0]}}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="type_of_item_requested">Type of Item Requested:</label>
                    <select class="form-control" id="type_of_item_requested" ng-model="formdata.type_of_item_requested">
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
        <label ng-hide="edit_mode=='update'" ng-cloak>Search Item</label>
        <div class="ui icon input fluid" ng-hide="edit_mode=='update'" ng-cloak>
            <i class="search icon"></i>
            <input type="text" placeholder="Search" id="search-item" ng-model="search_item_name">
        </div>
        <br>
        <div class="table-responsive">
            <table class="ui single line unstackable table">
                <thead>
                <tr>
                    <th class="center aligned middle aligned">Quantity</th>
                    <th class="center aligned middle aligned">Unit of Measure</th>
                    <th class="center aligned middle aligned">Item</th>
                    <th class="center aligned middle aligned"><span ng-bind="formdata.vendor_1_name"></span></th>
                    <th class="center aligned middle aligned"><span ng-bind="formdata.vendor_2_name"></span></th>
                    <th class="center aligned middle aligned"><span ng-bind="formdata.vendor_3_name"></span></th>
                    <th class="center aligned middle aligned"></th>
                </tr>
                </thead>
                <tbody ng-cloak>
                    <tr ng-repeat="(index,item) in items" ng-if="edit_mode=='create'">
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                <input type="number" min="1" placeholder="Quantity" ng-model="item.quantity">
                            </div>
                        </td>
                        <td class="center aligned middle aligned">@{{item.unit_of_measure}}</td>
                        <td class="center aligned middle aligned" style="width: 100%">@{{item.item_name}}</td>
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
                    <label for="requested_by_name">Requested By:</label>
                    <input type="text" class="form-control" placeholder="Enter Requested By" id="requested_by_name" ng-model="formdata.requested_by_name">
                    <p class="help-block" ng-cloak>@{{formerrors.requested_by_name[0]}}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="noted_by_name">Noted By:</label>
                    <input type="text" class="form-control" placeholder="Enter Noted By" id="noted_by_name" ng-model="formdata.noted_by_name">
                    <p class="help-block" ng-cloak>@{{formerrors.noted_by_name[0]}}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="canvass_by_name">Canvass By:</label>
                    <input type="text" class="form-control" placeholder="Enter Canvass By" id="canvass_by_name" ng-model="formdata.canvass_by_name">
                    <p class="help-block" ng-cloak>@{{formerrors.canvass_by_name[0]}}</p>
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
        $scope.formdata.type_of_item_requested = 'operations';
        $scope.formdata.vendor_1_name = 'Vendor 1';
        $scope.formdata.vendor_2_name = 'Vendor 2';
        $scope.formdata.vendor_3_name = 'Vendor 3';
        $scope.items = {};
    }else{
        $scope.formdata = {!! isset($data) ? json_encode($data): '{}' !!};
        $scope.formdata.request_to_canvass_date = moment($scope.formdata.request_to_canvass_date.date).format("MM/DD/YYYY");
        $scope.items = {!! isset($data) ? json_encode($data['details']['data']) : '{}' !!};
        delete $scope.formdata.details;
    }
    $scope.formerrors = {};
    $scope.submit = false;
    $scope.loading = false;

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
                    $scope.formdata.type_of_item_requested = 'operations';
                    $scope.items = {};
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
            $.notify('Request to Canvass has been generated.');
            $scope.formdata = {};
            $scope.formdata.type_of_item_requested = 'operations';
            $scope.items = {};
            setTimeout(() => {
                window.location.href = route('inventory.request-to-canvass.index',[response.data.uuid]);
            }, 2000);
        }, function(rejection) {
            if (rejection.status != 422) {
                request_error(rejection.status);
            } else if (rejection.status == 422) {
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
                var errors = rejection.data;
                $scope.formerrors = errors;
            }
            $scope.submit = false;
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

    $('#request_to_canvass_date,#date_needed').datepicker();
});
</script>
@endpush