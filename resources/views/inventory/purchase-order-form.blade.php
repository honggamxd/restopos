@extends('layouts.main')

@section('title', 'Purchase Order')

@section('css')
<style type="text/css">


</style>
@endsection
@section('breadcrumb')
<a class="section" href="{{route('inventory.purchase-order.list')}}">Purchase Orders</a>
<i class="right angle icon divider"></i>
<div class="active section" ng-if="edit_mode=='create'" ng-cloak>Create Purchase Order</div>
<div class="active section" ng-if="edit_mode=='update'" ng-cloak>Edit Purchase Order</div>
<i class="divider">|</i>
<a class="section" href="{{route('inventory.purchase-order.settings')}}">Settings</a>
@endsection

@section('overlay-div')
<div ng-style="overlay_div" ng-cloak>
    <div style="margin-top: 10vh;text-align: center;padding-right: 20%;padding-left: 20%;">
        <span ng-if="sent_emails!=recipients.length">
            Sending Purchase Order to: @{{recipient.user.name}}<@{{recipient.user.email_address}}>
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
        <p ng-if="generated_form.form"><a ng-href="@{{generated_form.form}}">View Generated Purchase Order Form</a></p>
        <p><a href="{{route('inventory.purchase-order.create')}}">Create new Purchase Order</a></p>
  </div>
</div>
@endsection

@section('padded_content')
<form id="add-form" ng-submit="save_form()" class="form">
<div class="row">
    <div class="col-sm-12">
        <h2 style="text-align: center;">Purchase Order Header</h2>
        <br>
        <i>All fields marked with asterisk (*) are required field.</i>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="purchase_order_number"><small style="color:red">*</small> Purchase Order Number:</label>
                    {{-- <input type="text" class="form-control" placeholder="Enter Purchase Order Number" id="purchase_order_number" ng-model="formdata.purchase_order_number"> --}}
                    <input type="text" class="form-control" placeholder="Enter Purchase Order Number" id="purchase_order_number" value="Auto Generated" readonly>
                    <p class="help-block" ng-cloak>@{{formerrors.purchase_order_number[0]}}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="inventory_purchase_request_id">PR Number:</label>
                    <span class="form-control" ng-bind="purchase_request_number_formatted"></span>
                    <p class="help-block" ng-cloak></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="supplier_name"><small style="color:red">*</small> Supplier Name:</label>
                    <input type="text" class="form-control" placeholder="Enter Supplier Name" id="supplier_name" ng-model="formdata.supplier_name">
                    <p class="help-block" ng-cloak>@{{formerrors.supplier_name[0]}}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="purchase_order_date"><small style="color:red">*</small> Date:</label>
                    <input type="text" class="form-control" placeholder="Enter Date" id="purchase_order_date" ng-model="formdata.purchase_order_date" readonly>
                    <p class="help-block" ng-cloak>@{{formerrors.purchase_order_date[0]}}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="supplier_address">Supplier Address:</label>
                    <input type="text" class="form-control" placeholder="Enter Supplier Address" id="supplier_address" ng-model="formdata.supplier_address">
                    <p class="help-block" ng-cloak>@{{formerrors.supplier_address[0]}}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="requesting_department"><small style="color:red">*</small> Requesting Department:</label>
                    <input type="text" class="form-control" placeholder="Enter Requesting Department" id="requesting_department" ng-model="formdata.requesting_department">
                    <p class="help-block" ng-cloak>@{{formerrors.requesting_department[0]}}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="supplier_tin">Supplier TIN:</label>
                    <input type="text" class="form-control" placeholder="Enter Supplier TIN" id="supplier_tin" ng-model="formdata.supplier_tin">
                    <p class="help-block" ng-cloak>@{{formerrors.supplier_tin[0]}}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="purpose">Purpose:</label>
                    <input type="text" class="form-control" placeholder="Enter Purpose" id="purpose" ng-model="formdata.purpose">
                    <p class="help-block" ng-cloak>@{{formerrors.purpose[0]}}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="term"><small style="color:red">*</small> Term:</label>
                    <input type="text" class="form-control" placeholder="Enter Term" id="term" ng-model="formdata.term">
                    <p class="help-block" ng-cloak>@{{formerrors.term[0]}}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="request_chargeable_to">Reason Chargeable To:</label>
                    <input type="text" class="form-control" placeholder="Enter Reason Chargeable To" id="request_chargeable_to" ng-model="formdata.request_chargeable_to">
                    <p class="help-block" ng-cloak>@{{formerrors.request_chargeable_to[0]}}</p>
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
        <h2 style="text-align: center;">Purchase Order Items</h2>
        <br>
        <div class="row">
            <div class="col-sm-12">
                <label ng-hide="edit_mode=='update'" ng-cloak>Search Purchase Request Number</label>
                <div class="ui icon input fluid" ng-hide="edit_mode=='update'" ng-cloak data-tooltip="Search for approved purchase requests" data-position="top right" data-inverted="">
                    <i class="search icon"></i>
                    <input type="text" placeholder="Search Purchase Request Number" id="search-purchase-request-number" ng-model="search_purchase_request">
                </div>
            </div>
            {{-- <div class="col-sm-6">
                <label ng-hide="edit_mode=='update'" ng-cloak>Search Request to Canvass Number</label>
                <div class="ui icon input fluid" ng-hide="edit_mode=='update'" ng-cloak>
                    <i class="search icon"></i>
                    <input type="text" placeholder="Search Request to Canvass Number" id="search-request-to-canvass-number" ng-model="search_request_to_canvass">
                </div>
            </div> --}}
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
                    <tr ng-repeat="(index,item) in items" ng-if="edit_mode=='create'">
                        <td class="center aligned middle aligned">@{{item.inventory_item.category}}</td>
                        <td class="center aligned middle aligned">@{{item.inventory_item.subcategory}}</td>
                        <td class="center aligned middle aligned" style="width: 100%">@{{item.inventory_item.item_name}}</td>
                        <td class="center aligned middle aligned">@{{item.inventory_item.unit_of_measure}}</td>
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                <input type="number" min="1" max="@{{item.quantity}}" placeholder="Quantity" ng-model="item.quantity">
                            </div>
                        </td>
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                <input type="number" min="0" step="0.01" placeholder="Unit Cost" ng-model="item.unit_price">
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
                                <input type="number" min="1" max="@{{item.quantity}}" placeholder="Quantity" ng-model="item.quantity">
                            </div>
                        </td>
                        <td class="center aligned middle aligned">
                            <div class="ui input">
                                <input type="number" min="0" step="0.01" placeholder="Unit Cost" ng-model="item.unit_price">
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
        <h2 style="text-align: center;">Purchase Order Footer</h2>
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
                    <input type="text" class="form-control" placeholder="Enter Noted By" id="noted_by_name" ng-model="formdata.noted_by_name">
                    <p class="help-block" ng-cloak>@{{formerrors.noted_by_name[0]}}</p>
                </div>
            </div>
            <div class="col-sm-4" ng-if="edit_mode=='update'">
                <div class="form-group">
                    <label for="approved_by_name">Approved By: <a href="javascript:void(0);" ng-click="fill_approved_by()"><small data-tooltip="This will fill your name and date" data-position="right center" data-inverted="">Fill</small></a></label>
                    <input type="text" class="form-control" placeholder="Enter Approved By" id="approved_by_name" ng-model="formdata.approved_by_name">
                    <p class="help-block" ng-cloak>@{{formerrors.approved_by_name[0]}}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="requested_by_date"><small style="color:red">*</small> Date:</label>
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
            <div class="col-sm-4" ng-hide="edit_mode=='create'">
                <div class="form-group">
                    <label for="approved_by_date">Date:</label>
                    <input type="text" class="form-control" placeholder="Enter Date" id="approved_by_date" ng-model="formdata.approved_by_date" readonly>
                    <p class="help-block" ng-cloak>@{{formerrors.approved_by_date[0]}}</p>
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
    if($scope.edit_mode=='create'){
        $scope.formdata = {};
        $scope.items = {};
        $scope.purchase_request_number_formatted = null;
        $scope.formdata.requested_by_date = moment().format("MM/DD/YYYY");
        $scope.formdata.requested_by_name = user_data.name;
        // $scope.formdata.noted_by_date = moment().format("MM/DD/YYYY");
        $scope.formdata.purchase_order_date = moment().format("MM/DD/YYYY");
        get_settings();
    }else{
        $scope.formdata = {!! isset($data) ? json_encode($data): '{}' !!};
        $scope.formdata.purchase_order_date = $scope.formdata.purchase_order_date ? moment($scope.formdata.purchase_order_date).format("MM/DD/YYYY") : null;
        $scope.formdata.requested_by_date = $scope.formdata.requested_by_date ? moment($scope.formdata.requested_by_date).format("MM/DD/YYYY") : null;
        $scope.formdata.noted_by_date = $scope.formdata.noted_by_date ? moment($scope.formdata.noted_by_date).format("MM/DD/YYYY") : null;
        $scope.formdata.approved_by_date = $scope.formdata.approved_by_date ? moment($scope.formdata.approved_by_date).format("MM/DD/YYYY") : null;
        $scope.items = {!! isset($data) ? json_encode($data['details']['data']) : '{}' !!};
        delete $scope.formdata.details;
    }

    $scope.$watch('formdata["purchase_order_date"]', function (newValue, oldValue, scope) {
        $scope.formdata.requested_by_date = newValue;
    });
    $scope.formerrors = {};
    $scope.submit = false;
    $scope.loading = false;

    $scope.delete_item = function(index) {
        $scope.items.splice(index, 1);
        // delete $scope.items[index];
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
                    'Approving Purchase Order',
                    'After submitting, the system cannot unapprove this purchase order form. continue?',
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
                    $scope.purchase_request_number_formatted = null;
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
            url: route('api.inventory.purchase-order.store').url(),
            data: $.param($scope.formdata)
        }).then(function(response) {
            $scope.submit = false;
            $scope.mail_progress = '';
            $scope.sent_emails = 0;
            $scope.generated_form = response.data.purchase_order;
            $scope.recipients = response.data.recipients.data;

            $scope.formdata = {};
            $scope.formdata.type_of_item_requested = 'operations';
            $scope.formdata.purchase_order_number = response.data.purchase_order_number + 1;
            $scope.items = {};
            if($scope.recipients.length == 0){
                $.notify('Redirecting to print preview.','info');
                setTimeout(() => {
                    window.location.href = route('inventory.purchase-order.index',[$scope.generated_form.uuid]);
                }, 2000);
            }else{
                $scope.mail_progress = "0%";
                $scope.mail_users();
                $scope.open_overlay_div();
            }
            $.notify('Purchase Order has been generated.');
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
            url: route('api.inventory.purchase-order.update',[$scope.formdata.id]).url(),
            data: $.param($scope.formdata)
        }).then(function(response) {
            $scope.submit = false;
            $.notify('Purchase Order has been updated.');
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
            url: route('api.inventory.purchase-order.notify.recipient',{uuid:$scope.generated_form.uuid,recipient:user.user_id}).url(),
            data: $.param(
                {
                    user:user,
                    form_type: "Purchase Order",
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
            url: route('api.inventory.purchase-order.footer.get').url(),
        }).then(function mySuccess(response) {
            let footer = response.data.footer;
            $scope.formdata.noted_by_name = footer.noted_by_name;
            $scope.formdata.noted_by_date = moment().format("MM/DD/YYYY");
        }, function(rejection) {
            if (rejection.status != 422) {
                request_error(rejection.status);
            } else if (rejection.status == 422) {
                console.log(rejection.statusText);
            }
        });
    }

    $("#search-purchase-request-number").autocomplete({
        source: function(request, response)
        {
            $.ajax({
                url: route('api.inventory.purchase-request.list').url(),
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
                $scope.items = {};
                $scope.items = ui.item.details.data;
                $scope.formdata.inventory_purchase_request_id = ui.item.id;
                $scope.purchase_request_number_formatted = ui.item.purchase_request_number_formatted;
                $scope.formdata.requesting_department = ui.item.requesting_department;
            });
        }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
        .append( "<div> PR No. <b>" + item.purchase_request_number_formatted + "</b><br> <small> PR DATE: <b>" + item.purchase_request_date_formatted + "</b></small>" + "</div>" )
        .appendTo( ul );
    };

    $('#purchase_order_date,#date_needed').datepicker();
    $('#noted_by_date,#approved_by_date').datepicker();

    window.onbeforeunload = confirmExit;

    function confirmExit() {
      if ($scope.sent_emails!=$scope.recipients.length) return "Exporting is still in progress.";
    }
});
</script>
@endpush