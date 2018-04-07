@extends('layouts.main')

@section('title', 'Receiving Report')

@section('css')
<style type="text/css">


</style>
@endsection
@section('breadcrumb')
<a class="section" href="{{route('inventory.receiving-report.list')}}">Receiving Reports</a>
<i class="right angle icon divider"></i>
<div class="active section" ng-if="edit_mode=='create'" ng-cloak>Create Receiving Report</div>
<div class="active section" ng-if="edit_mode=='update'" ng-cloak>Edit Receiving Report</div>
@endsection
@section('padded_content')
<form id="add-form" ng-submit="save_form()" class="form">
<div class="row">
    <div class="col-sm-12">
        <h2 style="text-align: center;">Receiving Report Header</h2>
        <br>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="receiving_report_number">Receiving Report Number:</label>
                    <input type="text" class="form-control" placeholder="Enter Receiving Report Number" id="receiving_report_number" ng-model="formdata.receiving_report_number">
                    <p class="help-block" ng-cloak>@{{formerrors.receiving_report_number[0]}}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="purchase_order_number_formatted">PR Number:</label>
                    <span class="form-control" ng-bind="purchase_order_number_formatted"></span>
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
                    <label for="receiving_report_date">Date & Time Received:</label>
                    <input type="text" class="form-control" placeholder="Enter Date Requested" id="receiving_report_date" ng-model="formdata.receiving_report_date" readonly>
                    <p class="help-block" ng-cloak>@{{formerrors.receiving_report_date[0]}}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="request_chargeable_to">Reason Chargeable To:</label>
                    <input type="text" class="form-control" placeholder="Enter Reason Chargeable To" id="request_chargeable_to" ng-model="formdata.request_chargeable_to">
                    <p class="help-block" ng-cloak>@{{formerrors.request_chargeable_to[0]}}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="supplier_address">Supplier Address:</label>
                    <input type="text" class="form-control" placeholder="Enter Supplier Address" id="supplier_address" ng-model="formdata.supplier_address">
                    <p class="help-block" ng-cloak>@{{formerrors.supplier_address[0]}}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="supplier_name">Supplier Name:</label>
                    <input type="text" class="form-control" placeholder="Enter Supplier Name" id="supplier_name" ng-model="formdata.supplier_name">
                    <p class="help-block" ng-cloak>@{{formerrors.supplier_name[0]}}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="supplier_contact_number">Supplier Contact Number:</label>
                    <input type="text" class="form-control" placeholder="Enter Supplier Address" id="supplier_contact_number" ng-model="formdata.supplier_contact_number">
                    <p class="help-block" ng-cloak>@{{formerrors.supplier_contact_number[0]}}</p>
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
        <h2 style="text-align: center;">Receiving Report Items</h2>
        <br>
        <label ng-hide="edit_mode=='update'" ng-cloak>Search Purchase Order Number</label>
        <div class="ui icon input fluid" ng-hide="edit_mode=='update'" ng-cloak>
            <i class="search icon"></i>
            <input type="text" placeholder="Search" id="search-item" ng-model="search_item_name">
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
                    <tr ng-repeat="(index,item) in items" ng-if="edit_mode=='create'">
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
                    <tr ng-repeat="(index,item) in items" ng-if="edit_mode=='update'">
                        <td class="center aligned middle aligned">@{{item.inventory_item.category}}</td>
                        <td class="center aligned middle aligned">@{{item.inventory_item.subcategory}}</td>
                        <td class="center aligned middle aligned" style="width: 100%">@{{item.inventory_item.item_name}}</td>
                        <td class="center aligned middle aligned">@{{item.inventory_item.unit_of_measure}}</td>
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                {{-- <input type="number" min="1" placeholder="Quantity" ng-model="item.quantity"> --}}
                                <span ng-bind="item.quantity"></span>
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
        <h2 style="text-align: center;">Receiving Report Footer</h2>
        <br>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="received_by_name">Received By:</label>
                    <input type="text" class="form-control" placeholder="Enter Received By" id="received_by_name" ng-model="formdata.received_by_name">
                    <p class="help-block" ng-cloak>@{{formerrors.received_by_name[0]}}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="checked_by_name">Checked By:</label>
                    <input type="text" class="form-control" placeholder="Enter Checked By" id="checked_by_name" ng-model="formdata.checked_by_name">
                    <p class="help-block" ng-cloak>@{{formerrors.checked_by_name[0]}}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="posted_by_name">Posted By:</label>
                    <input type="text" class="form-control" placeholder="Enter Posted By" id="posted_by_name" ng-model="formdata.posted_by_name">
                    <p class="help-block" ng-cloak>@{{formerrors.posted_by_name[0]}}</p>
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
        $scope.items = {};
        $scope.purchase_order_number_formatted = null;
    }else{
        $scope.formdata = {!! isset($data) ? json_encode($data): '{}' !!};
        $scope.formdata.receiving_report_date = moment($scope.formdata.receiving_report_date).format("MM/DD/YYYY hh:mm:ss a");
        $scope.items = {!! isset($data) ? json_encode($data['details']['data']) : '{}' !!};
        $scope.purchase_order_number_formatted = $scope.formdata.inventory_purchase_order.purchase_order_number_formatted;
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
                id: value.inventory_item_id,
                quantity: value.quantity,
                unit_cost: value.unit_price,
                remarks: value.remarks,
            };
        });
        $scope.formdata.items = items;
        $http({
            method: 'POST',
            url: route('api.inventory.receiving-report.store').url(),
            data: $.param($scope.formdata)
        }).then(function(response) {
            $scope.submit = false;
            $.notify('Receiving Report has been generated.');
            $scope.formdata = {};
            $scope.items = {};
            setTimeout(() => {
                window.location.href = route('inventory.receiving-report.index',[response.data.uuid]);
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
                unit_cost: value.unit_price,
                remarks: value.remarks,
            };
        });
        $scope.formdata.items = items;
        $http({
            method: 'PUT',
            url: route('api.inventory.receiving-report.update',[$scope.formdata.id]).url(),
            data: $.param($scope.formdata)
        }).then(function(response) {
            $scope.submit = false;
            $.notify('Receiving Report has been updated.');
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
                url: route('api.inventory.purchase-order.list').url(),
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
            // $scope.searchString = "";
            $scope.$apply(function() {
                $scope.items = ui.item.details.data;
                $scope.formdata.inventory_purchase_order_id = ui.item.id;
                $scope.purchase_order_number_formatted = ui.item.purchase_order_number_formatted;
                $scope.formdata.supplier_address = ui.item.supplier_address;
                $scope.formdata.supplier_name = ui.item.supplier_name;
                $scope.formdata.requesting_department = ui.item.requesting_department;
            });
        }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
        .append( "<div> PO No. <b>" + item.purchase_order_number_formatted + "</b><br> <small> PO DATE: <b>" + item.purchase_order_date_formatted + "</b></small>" + "</div>" )
        .appendTo( ul );
    };

    $('#receiving_report_date,#date_to').datetimepicker({
      timeFormat: "hh:mm:ss tt"
    });
});
</script>
@endpush