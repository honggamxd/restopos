@extends('layouts.main')

@section('title', 'Receiving Report Settings')

@section('css')
<style type="text/css">


</style>
@endsection
@section('breadcrumb')
<a class="section" href="{{route('inventory.receiving-report.list')}}">Receiving Reports</a>
<i class="right angle icon divider"></i>
<a class="section hideprint" href="{{route('inventory.receiving-report.create')}}">Create Receiving Report</a>
<i class="divider">|</i>
<div class="active section">Settings</div>
@endsection

@section('padded_content')
    <h1 style="text-align: center">Receiving Report Settings</h1>
    <br>
    <div class="row">
        <div class="col-sm-12">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="pill" href="#footer-settings">Footer</a></li>
            </ul>
            <br>
            <br>
            <div class="tab-content">
                <div id="footer-settings" class="tab-pane fade in active">
                    <div class="form-group">
                        <label for="checked_by_name">Checked By:</label>
                        <input type="text" class="form-control" placeholder="Search for Name of User" id="checked_by_name" ng-model="footer.checked_by_name" ng-change="update_footer_settings()">
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

    $scope.get_settings = function() {
        $http({
            method: "GET",
            url: route('api.inventory.receiving-report.footer.get').url(),
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
                url: route('api.inventory.receiving-report.recipient.store').url(),
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
            url: route('api.inventory.receiving-report.recipient.update',[item.id]).url(),
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
            item.user.name+' can no longer receive purchase request email notification, continue?',
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
            url: route('api.inventory.receiving-report.recipient.destroy',[id]).url(),
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
            url: route('api.inventory.receiving-report.footer.update').url(),
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

    $("#checked_by_name").autocomplete({
        source: route('api.user.list').url() + "?fieldName=privilege&fieldValue=restaurant_admin",
        select: function(event, ui) {
            $scope.footer.checked_by_name = ui.item.value;
            $scope.update_footer_settings();
        }
    });

});
</script>
@endpush