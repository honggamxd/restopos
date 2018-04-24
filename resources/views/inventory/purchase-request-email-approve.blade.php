@extends('layouts.main')

@section('title', 'Purchase Request')

@section('css')
<style type="text/css">


</style>
@endsection
@section('breadcrumb')
<a class="section" href="{{route('inventory.purchase-request.list')}}">Purchase Requests</a>
<i class="right angle icon divider"></i>
<div class="active section" ng-cloak>Confirmation of Approval</div>
@endsection

@section('two_row_content')
    <div class="row">
        <div class="col-sm-12">
            <div style="text-align: center" ng-if="!is_approved">
                <div class="ui buttons">
                    <button class="ui green button" ng-click="approve_confirm(item)"><span class="glyphicon glyphicon-ok"></span>Approve</button>
                </div>
            </div>
            <br>
            <iframe style="width: 100%;height: 80vh" src="{{$form}}">
                <p>Your browser does not support iframes.</p>
            </iframe>
        </div>
    </div>
@endsection

@section('modals')

@endsection

@push('scripts')
<script type="text/javascript">
app.controller('content-controller', function($scope,$http, $sce, $window) {
    $scope.is_approved = false;
    $scope.approve_confirm = function(){
        alertify.confirm(
            'Approving Purchase Request',
            'After submitting, the system cannot unapprove this purchase request form. continue?',
            function(){
                $scope.approve_form();
            },
            function()
            {
                // alertify.error('Cancel')
            }
        );
    }

    $scope.approve_form = function() {
        let id = {!! json_encode($purchase_request['id']) !!};
        $http({
            method: 'PATCH',
            url: route('api.inventory.purchase-request.approve',[id]).url(),
        }).then(function(response) {
            $.notify('Purchase Request has been approved.', {
                position: "top right"
            });
            $scope.is_approved = true;
        }, function(rejection) {
            if (rejection.status != 422) {
                request_error(rejection.status);
            } else if (rejection.status == 422) {
                var errors = rejection.data;
                $.notify(errors.error[0],'error');
            }
        });
    }
});
</script>
@endpush