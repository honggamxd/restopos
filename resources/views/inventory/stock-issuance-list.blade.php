@extends('layouts.main')

@section('title', 'Stock Issuance')

@section('css')
<style type="text/css">


</style>
@endsection
@section('breadcrumb')
<div class="active section">Stock Issuances</div>
<i class="right angle icon divider"></i>
<a class="section hideprint" href="{{route('inventory.stock-issuance.create')}}" ng-if="user_data.privilege == 'admin' || user_data.permissions.can_add_stock_issuances">Create Stock Issuance</a>
<i class="divider" ng-if="user_data.privilege == 'admin' || user_data.permissions.can_add_stock_issuances">|</i>
<a class="section" href="{{route('inventory.stock-issuance.settings')}}">Settings</a>
@endsection

@section('two_row_content')
    <h1 style="text-align: center">Stock Issuances</h1>
    <br>
    <div class="row">
        <div class="col-sm-5">
            <div class="ui left icon input">
                <input type="text" placeholder="Search Stock Issuance Number" ng-model="searchString" ng-keyup="search()">
                <i class="search icon"></i>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="text-align: center">Issuance No.</th>
                            <th style="text-align: center">Issuance Date</th>
                            <th style="text-align: center">Requesting Dept.</th>
                            <th style="text-align: center">Chargeable to</th>
                            <th style="text-align: center">Supplier Address</th>
                            <th style="text-align: center">Issued By</th>
                            <th style="text-align: center">Approved By</th>
                            <th style="text-align: center"></th>
                        </tr>
                    </thead>
                    <tbody ng-repeat="(index,item) in items" ng-cloak>
                        <tr>
                            <td style="text-align: center">
                                <a ng-href="@{{item.form}}" target="_blank">@{{item.stock_issuance_number_formatted}}</a>
                            </td>
                            <td style="text-align: center">@{{item.stock_issuance_date_formatted}}</td>
                            <td style="text-align: center">@{{item.requesting_department}}</td>
                            <td style="text-align: center">@{{item.request_chargeable_to}}</td>
                            <td style="text-align: center">@{{item.supplier_address}}</td>
                            <td style="text-align: center">@{{item.issued_by_name}}</td>
                            <td style="text-align: center">@{{item.approved_by_name}}</td>
                            <td style="text-align: center">
                                <div class="ui buttons">
                                    <button type="button" class="ui green button" ng-click="approve_confirm(item,index)" ng-if="!item.is_approved && (user_data.privilege == 'admin' || user_data.permissions.can_approve_stock_issuances)"><span class="glyphicon glyphicon-ok"></span></button>
                                    <button type="button" class="ui blue button" ng-click="edit_form(item)" ng-if="user_data.privilege == 'admin' || user_data.permissions.can_edit_stock_issuances"><span class="glyphicon glyphicon-edit"></span></button>
                                    <button type="button" class="ui red button" ng-click="delete_confirm(item)" ng-if="user_data.privilege == 'admin' || user_data.permissions.can_delete_stock_issuances"><span class="glyphicon glyphicon-trash"></span></button>
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
                        <tr>
                            <th colspan="200">
                                &nbsp;
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div ng-bind-html="pages" class="text-center" ng-cloak></div>
        </div>
    </div>
@endsection

@section('modals')

@endsection

@push('scripts')
<script type="text/javascript">
app.controller('content-controller', function($scope,$http, $sce, $window) {
    $scope.formdata = {};
    $scope.loading = true;
    $scope.items = {};
    $scope.pages = "";
    $scope.searchString = "";
    $scope.user_data = user_data;

    $scope.search = _.debounce(function(argument) {
        $scope.show_items();
    },250);

    $scope.show_items = function(url_string) {
        url_string = (typeof url_string !== 'undefined') && url_string !== "" ? url_string : route('api.inventory.stock-issuance.list').url();
        $scope.items = {};
        $scope.loading = true;
        $scope.pages = "";
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
    }
    $scope.show_items();
    $scope.edit_form = function(item) {
        window.location = route('inventory.stock-issuance.edit',[item.uuid]).url();
    }

    $scope.delete_confirm = function(item) {
        alertify.confirm(
            'DELETE PURCHASE REQUEST',
            'Are you sure to delete this stock issuance. This action is irreversible?',
            function(){
                $scope.delete_form(item.id);
            },
            function()
            {
                // alertify.error('Cancel')
            }
        );
    }

    $scope.delete_form = function(id) {
        $http({
            method: 'DELETE',
            url: route('api.inventory.stock-issuance.delete',[id]).url(),
        }).then(function(response) {
            $scope.show_items();
            $.notify('Stock Issuance has been deleted.');
        }, function(rejection) {
            if (rejection.status != 422) {
                request_error(rejection.status);
            } else if (rejection.status == 422) {
                var errors = rejection.data;
                $scope.formerrors = errors;
            }
        });
    }

    $scope.approve_confirm = function(item,index){
        alertify.confirm(
            'Save Stock Issuance',
            'After approving, the items in the form will update its quantity. continue?',
            function(){
                $scope.approve_form(item,index);
            },
            function()
            {
                // alertify.error('Cancel')
            }
        );
    }

    $scope.approve_form = function(item,index) {
        let id = item.id;
        let uuid = item.uuid;
        $http({
            method: 'PATCH',
            url: route('api.inventory.stock-issuance.approve',[id]).url(),
        }).then(function(response) {
            $scope.items[index] = response.data;
            $.notify('Stock Issuance has been approved.');
        }, function(rejection) {
            if (rejection.status != 422) {
                request_error(rejection.status);
            } else if (rejection.status == 422) {
                var errors = rejection.data;
                $.notify('Unable to approve the form, redirecting to update form page','error');
                setTimeout(() => {
                    window.location.href = route('inventory.stock-issuance.edit',[uuid]);
                }, 2000);
            }
        });
    }

    $(document).on('click','.pagination li a',function(e) {
        e.preventDefault();
        $scope.show_items(e.target.href);
    });

});
</script>
@endpush