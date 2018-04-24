@extends('layouts.main')

@section('title', 'Purchase Request')

@section('css')
<style type="text/css">


</style>
@endsection
@section('breadcrumb')
<div class="active section">Purchase Requests</div>
<i class="right angle icon divider"></i>
<a class="section hideprint" href="{{route('inventory.purchase-request.create')}}">Create Purchase Request</a>
<i class="divider">|</i>
<a class="section" href="{{route('inventory.purchase-request.settings')}}">Settings</a>
@endsection

@section('two_row_content')
    <h1 style="text-align: center">Purchase Requests</h1>
    <br>
    <div class="row">
        <div class="col-sm-5">
            <div class="ui left icon input">
                <input type="text" placeholder="Search Purchase Request Number" ng-model="searchString" ng-keyup="search()">
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
                            <th style="text-align: center">PR No.</th>
                            <th style="text-align: center">PR Date</th>
                            <th style="text-align: center">Requesting Dept.</th>
                            <th style="text-align: center">Request Reason</th>
                            <th style="text-align: center">Chargeable to</th>
                            <th style="text-align: center">Date Needed</th>
                            <th style="text-align: center">Type of Item</th>
                            <th style="text-align: center">Requested By</th>
                            <th style="text-align: center">Approved By</th>
                            <th style="text-align: center"></th>
                        </tr>
                    </thead>
                    <tbody ng-repeat="(index,item) in items" ng-cloak>
                        <tr>
                            <td style="text-align: center">
                                <a ng-href="@{{item.form}}" target="_blank">@{{item.purchase_request_number_formatted}}</a>
                            </td>
                            <td style="text-align: center">@{{item.purchase_request_date_formatted}}</td>
                            <td style="text-align: center">@{{item.requesting_department}}</td>
                            <td style="text-align: center">@{{item.reason_for_the_request}}</td>
                            <td style="text-align: center">@{{item.request_chargeable_to}}</td>
                            <td style="text-align: center">@{{item.date_needed_formatted}}</td>
                            <td style="text-align: center">@{{item.type_of_item_requested}}</td>
                            <td style="text-align: center">@{{item.requested_by_name}}</td>
                            <td style="text-align: center">@{{item.approved_by_name}}</td>
                            <td style="text-align: center">
                                <div class="ui buttons">
                                    <button type="button" class="ui green button" ng-click="approve_confirm(item,index)" ng-if="!item.is_approved"><span class="glyphicon glyphicon-ok"></span></button>
                                    <button type="button" class="ui blue button" ng-click="edit_form(item)"><span class="glyphicon glyphicon-edit"></span></button>
                                    <button type="button" class="ui red button" ng-click="delete_confirm(item)"><span class="glyphicon glyphicon-trash"></span></button>
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

    $scope.search = _.debounce(function(argument) {
        $scope.show_items();
    },250);

    $scope.show_items = function(url_string) {
        url_string = (typeof url_string !== 'undefined') && url_string !== "" ? url_string : route('api.inventory.purchase-request.list').url();
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
        window.location = route('inventory.purchase-request.edit',[item.uuid]).url();
    }

    $scope.delete_confirm = function(item) {
        alertify.confirm(
            'DELETE PURCHASE REQUEST',
            'Are you sure to delete this purchase request. This action is irreversible?',
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
            url: route('api.inventory.purchase-request.delete',[id]).url(),
        }).then(function(response) {
            $scope.show_items();
            $.notify('Purchase Request has been deleted.');
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
            'Approving Purchase Request',
            'After submitting, the system cannot unapprove this purchase request form. continue?',
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
        $http({
            method: 'PATCH',
            url: route('api.inventory.purchase-request.approve',[id]).url(),
        }).then(function(response) {
            $scope.items[index] = response.data;
            $.notify('Purchase Request has been approved.');
        }, function(rejection) {
            if (rejection.status != 422) {
                request_error(rejection.status);
            } else if (rejection.status == 422) {
                var errors = rejection.data;
                $.notify(errors.error[0],'error');
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