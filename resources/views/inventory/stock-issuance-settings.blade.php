@extends('layouts.main')

@section('title', 'Stock Issuance Settings')

@section('css')
<style type="text/css">


</style>
@endsection
@section('breadcrumb')
<a class="section" href="{{route('inventory.stock-issuance.list')}}">Stock Issuances</a>
<i class="right angle icon divider"></i>
<a class="section hideprint" href="{{route('inventory.stock-issuance.create')}}">Create Stock Issuance</a>
<i class="divider">|</i>
<div class="active section">Settings</div>
@endsection

@section('padded_content')
    <h1 style="text-align: center">Stock Issuance Settings</h1>
    <br>
    <div class="row">
        <div class="col-sm-12">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="pill" href="#notification-settings">Email Notifications</a></li>
                {{-- <li><a data-toggle="pill" href="#footer-settings">Footer</a></li> --}}
            </ul>
            <br>
            <br>
            <div class="tab-content">
                <div id="notification-settings" class="tab-pane fade in active">
                    <button class="ui primary button" ng-click="add_recipient_form()">Add Recipient</button>
                    <br>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="text-align: center">Username</th>
                                    <th style="text-align: center">Name</th>
                                    <th style="text-align: center">Email Address</th>
                                    <th style="text-align: center">Email Notification</th>
                                    <th style="text-align: center">Approve via Email</th>
                                    <th style="text-align: center"></th>
                                </tr>
                            </thead>
                            <tbody ng-repeat="(index,item) in items" ng-cloak>
                                <tr>
                                    <td style="text-align: center">@{{item.user.username}}</td>
                                    <td style="text-align: center">@{{item.user.name}}</td>
                                    <td style="text-align: center">@{{item.user.email_address}}</td>
                                    <td style="text-align: center">
                                        <div class="ui toggle checkbox">
                                            <input type="checkbox" ng-model="item.notify_email" ng-change="edit_recipient(item)">
                                            <label ng-if="item.notify_email">On</label>
                                            <label ng-if="!item.notify_email">Off</label>
                                        </div>
                                    </td>
                                    <td style="text-align: center">
                                        <div class="ui toggle checkbox">
                                            <input type="checkbox" ng-model="item.allow_approve" ng-change="edit_recipient(item)">
                                            <label ng-if="item.allow_approve">Allowed</label>
                                            <label ng-if="!item.allow_approve">Disallowed</label>
                                        </div>
                                    </td>
                                    <td style="text-align: center">
                                        <div class="ui buttons">
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
                <div id="footer-settings" class="tab-pane fade">
                    <div class="form-group">
                        <label for="posted_by_name">Posted by:</label>
                        <input type="text" class="form-control" placeholder="Enter Search for Name of User" id="posted_by_name" ng-model="footer.posted_by_name" ng-change="update_footer_settings()">
                    </div>    
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
<div id="add-recipient-modal" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Item</h4>
            </div>
            <div class="modal-body">
                <form id="add-recipient-form" ng-submit="add_recipient()">
                    
                    <div class="form-group">
                        <label for="user">User</label>
                        <select class="form-control" ng-model="formdata.user" ng-options="item as item.name for item in users track by item.id">
                            <option value="">Select User</option>
                        </select>
                        <p class="help-block">@{{formerrors.user[0]}}</p>
                    </div>
                    <div class="form-group">
                        <label for="notify_email">Notify Email</label>
                        <div>
                            <div class="ui toggle checkbox">
                            <input type="checkbox" ng-model="formdata.notify_email">
                            <label ng-if="formdata.notify_email">On</label>
                            <label ng-if="!formdata.notify_email">Off</label>
                        </div>
                        </div>
                        <p class="help-block">@{{formerrors.notify_email[0]}}</p>
                    </div>
                    <div class="form-group">
                        <label for="allow_approve">Approve via Email</label>
                        <div>
                            <div class="ui toggle checkbox">
                            <input type="checkbox" ng-model="formdata.allow_approve">
                            <label ng-if="formdata.allow_approve">Allowed</label>
                            <label ng-if="!formdata.allow_approve">Disallowed</label>
                        </div>
                        </div>
                        <p class="help-block">@{{formerrors.allow_approve[0]}}</p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="ui default button" data-dismiss="modal">Close</button>
                <button type="submit" class="ui primary button" form="add-recipient-form" ng-disabled="submit" ng-class="{'loading':submit}">Submit</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
app.controller('content-controller', function($scope,$http, $sce, $window) {
    $scope.formdata = {};
    $scope.loading = true;
    $scope.items = {};
    $scope.pages = "";
    $scope.searchString = "";
    $scope.footer = {};
    $scope.users = {!! json_encode($users['data']) !!};

    $scope.show_recipients = function(url_string) {
        url_string = (typeof url_string !== 'undefined') && url_string !== "" ? url_string : route('api.inventory.stock-issuance.recipient.list').url();
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

    $scope.get_settings = function() {
        $http({
            method: "GET",
            url: route('api.inventory.stock-issuance.footer.get').url(),
        }).then(function mySuccess(response) {
            $scope.footer = response.data.footer;
        }, function(rejection) {
            $scope.loading = false;
            if (rejection.status != 422) {
                request_error(rejection.status);
            } else if (rejection.status == 422) {
                console.log(rejection.statusText);
            }
        });
    }
    
    $scope.show_recipients();
    $scope.get_settings();

    $scope.add_recipient_form = function(){
        $scope.formdata = {
            notify_email: false,
            allow_approve: false,
        };
        $('#add-recipient-modal').modal('show');
    }

    $scope.add_recipient = function() {
        $scope.formerrors = {};
            $scope.submit = true;
            $http({
                method: 'POST',
                url: route('api.inventory.stock-issuance.recipient.store').url(),
                data: $.param($scope.formdata)
            }).then(function(response) {
                $scope.submit = false;
                $scope.items.push(response.data);
                $.notify('A recipient has been added.');
                $scope.formdata = {
                    notify_email: false,
                    allow_approve: false,
                };
            }, function(rejection) {
                if (rejection.status != 422) {
                    request_error(rejection.status);
                } else if (rejection.status == 422) {
                    var errors = rejection.data;
                    if(errors['user.email_address']){
                        $.notify('This user does not have email address, or if it has, please try to refresh the page','error');
                    }
                    $scope.formerrors = errors;
                }
                $scope.submit = false;
            });
    }
    
    $scope.edit_recipient = function(item) {
        $scope.formerrors = {};
        $scope.submit = true;
        $http({
            method: 'PATCH',
            url: route('api.inventory.stock-issuance.recipient.update',[item.id]).url(),
            data: $.param(item)
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

    $scope.delete_confirm = function(item) {
        alertify.confirm(
            'DELETE RECIPIENT',
            item.user.name+' can no longer receive stock issuance email notification, continue?',
            function(){
                $scope.delete_recipient(item);
            },
            function()
            {
                // alertify.error('Cancel')
            }
        );
    }
    
    $scope.delete_recipient = function(item) {
        let id = item.id;
        let name = item.user.name;
        $http({
            method: 'DELETE',
            url: route('api.inventory.stock-issuance.recipient.destroy',[id]).url(),
        }).then(function(response) {
            $scope.show_recipients();
            $.notify(name+' has been removed.');
        }, function(rejection) {
            if (rejection.status != 422) {
                request_error(rejection.status);
            } else if (rejection.status == 422) {
                var errors = rejection.data;
                $scope.formerrors = errors;
            }
        });
    }

    $scope.update_footer_settings = function() {
        $http({
            method: 'PATCH',
            url: route('api.inventory.stock-issuance.footer.update').url(),
            data: $.param($scope.footer)
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

    $("#posted_by_name").autocomplete({
        source: route('api.user.list').url() + "?fieldName=privilege&fieldValue=restaurant_admin",
        select: function(event, ui) {
            $scope.footer.posted_by_name = ui.item.value;
            $scope.update_footer_settings();
        }
    });

});
</script>
@endpush