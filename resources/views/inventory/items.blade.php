@extends('layouts.main')

@section('title', 'Items')

@section('css')
<style type="text/css">


</style>
@endsection
@section('breadcrumb')
<a class="section" href="{{route('inventory.item.index')}}">Items</a>
@endsection
@section('content')
<h1 style="text-align: center;">Items</h1>
<div class="col-sm-12">
    <div class="row">
        <div class="col-sm-12">
            <label>Search Item</label>
            <div class="ui left icon input action fluid">
                <i class="search icon"></i>
                <input type="text" placeholder="Search" id="search-item-name" ng-model="search_item_name">
                <button type="button" class="ui primary button" ng-click="add_item_form()">Add Item</button>
            </div> 
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="ui single line unstackable table">
                    <thead>
                        <tr>
                            <th class="center aligned middle aligned">Category</th>
                            <th class="center aligned middle aligned">Subcategory</th>
                            <th class="center aligned middle aligned">Item</th>
                            <th class="center aligned middle aligned">Unit</th>
                            <th class="center aligned middle aligned">Qty</th>
                            <th class="right aligned middle aligned">Average Cost</th>
                        </tr>
                    </thead>
                    <tbody ng-cloak>
                        <tr ng-repeat="item in items" ng-init="item.edit_quantity=false" ng-class="item.quantity == 0 ? 'error' : (item.valid == 0 ? 'warning' : '')">
                            <td class="center aligned middle aligned">@{{item.category}}</td>
                            <td class="center aligned middle aligned">@{{item.subcategory}}</td>
                            <td class="center aligned middle aligned">@{{item.item_name}}</td>
                            <td class="center aligned middle aligned">@{{item.unit_of_measure}}</td>
                            <td class="center aligned middle aligned">@{{item.total_quantity}}</td>
                            <td class="right aligned middle aligned">@{{item.average_cost|currency:""}}</td>
                            <td class="center aligned middle aligned"></td>
                            <td class="right aligned middle aligned"><button type="button" class="btn btn-danger" ng-click="delete_item(this)">&times;</button></td>
                        </tr>
                    </tbody>  
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('modals')
<div id="add-item-modal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Item</h4>
            </div>
            <div class="modal-body">
                <form id="add-item-form" ng-submit="add_item()">
                    
                    <div class="form-group">
                        <label for="category">Category</label>
                        <input class="form-control" type="text" placeholder="Enter Category" ng-model="formdata.category">
                        <p class="help-block">@{{formerrors.category[0]}}</p>
                    </div>
                    <div class="form-group">
                        <label for="subcategory">Subcategory</label>
                        <input class="form-control" type="text" placeholder="Enter Subcategory" ng-model="formdata.subcategory">
                        <p class="help-block">@{{formerrors.subcategory[0]}}</p>
                    </div>
                    <div class="form-group">
                        <label for="item_name">Item Name</label>
                        <input class="form-control" type="text" placeholder="Enter Item Name" ng-model="formdata.item_name">
                        <p class="help-block">@{{formerrors.item_name[0]}}</p>
                    </div>
                    <div class="form-group">
                        <label for="unit_of_measure">Unit of Measure</label>
                        <input class="form-control" type="text" placeholder="Enter Unit of Measure" ng-model="formdata.unit_of_measure">
                        <p class="help-block">@{{formerrors.unit_of_measure[0]}}</p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="ui default button" data-dismiss="modal">Close</button>
                <button type="submit" class="ui primary button" form="add-item-form" ng-disabled="submit" ng-class="{'loading':submit}">Submit</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    app.controller('content-controller', function($scope,$http, $sce, $window) {
        $scope.formdata = {};
        $scope.formerrors = {};
        $scope.submit = false;
        $scope.loading = true;
        
        $scope.show_items =  _.debounce(function(url_string) {
            url_string = (typeof url_string !== 'undefined') && url_string !== "" ? url_string : route('api.inventory.item.index').url();
            $scope.items = {};
            $scope.loading = true;
            $http({
                method: "GET",
                url: url_string,
                params: {
                    searchString: $scope.searchString
                }
            }).then(function mySuccess(response) {
                $scope.items = response.data.result.data;
                $scope.pages = $sce.trustAsHtml(response.data.pages);
                $scope.loading = false;
            }, function(rejection) {
                $scope.loading = false;
                if (rejection.status != 422) {
                    request_error(rejection.status);
                } else if (rejection.status == 422) {
                    console.log(rejection.statusText);
                }
            });
        },250);

        $scope.show_items();
        
        $scope.add_item_form = function() {
            $scope.formdata = {};
            $('#add-item-modal').modal('show');
        }

        $scope.add_item = function() {
            $scope.formerrors = {};
            $scope.submit = true;
            $http({
                method: 'POST',
                url: route('api.inventory.item.store').url(),
                data: $.param($scope.formdata)
            }).then(function(response) {
                $scope.submit = false;
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

        $scope.edit_item_form = function(data) {
            $scope.formdata = {};
            angular.copy(data, $scope.formdata);
            $('#edit-item-modal').modal('show');
        }

        $scope.edit_item = function() {
            $scope.formerrors = {};
            $scope.submit = true;
            $http({
                method: 'PUT',
                url: route('api.inventory.item.update',[$scope.formdata.id]).url(),
                data: $.param($scope.formdata)
            }).then(function(response) {
                $scope.submit = false;
                show_items();
                $('#edit-item-modal').modal('hide');
                $.notify('A item of itemder '+$scope.current_itemder.itemder_number+' has been updated.');
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

        $scope.delete_item_form = function(data) {
            alertify.confirm(
            'Delete item','Are you sure you want to delete this item? Note that this action is not reversible.',
            function(){
                $scope.delete_item(data);
            },
            function(){
                // alertify.error('Cancel');
            }
            );
        }

        $scope.delete_item = function(data) {
            $http({
                method: 'DELETE',
                url: route('api.inventory.item.destroy',[data.id]).url()
            }).then(function(response) {
                show_items();
                $.notify('A item has been removed.');
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
    });
</script>
@endpush