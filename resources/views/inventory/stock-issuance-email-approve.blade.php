@extends('layouts.main')

@section('title', 'Stock Issuance')

@section('css')
<style type="text/css">


</style>
@endsection
@section('breadcrumb')
<a class="section" href="{{route('inventory.stock-issuance.list')}}">Stock Issuances</a>
<i class="right angle icon divider"></i>
<div class="active section" ng-cloak>Confirmation of Approval</div>
@endsection

@section('two_row_content')
    <div class="row">
        <div class="col-sm-12">
            <div style="text-align: center">
                <div class="ui buttons">
                    <button class="ui green button" ng-if="!is_approved" ng-click="approve_confirm(item)"><span class="glyphicon glyphicon-ok"></span>Approve</button>
                    <button class="ui red button" onclick="window.close()"><span class="glyphicon glyphicon-remove"></span>Close</button>
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
            'Approving Stock Issuance',
            'After approving, the items in the form will update its quantity, continue?',
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
        let id = {!! json_encode($stock_issuance['id']) !!};
        let uuid = {!! json_encode($stock_issuance['uuid']) !!};
        $http({
            method: 'PATCH',
            url: route('api.inventory.stock-issuance.approve',[id]).url(),
        }).then(function(response) {
            $.notify('Stock Issuance has been approved.', {
                position: "top right"
            });
            $scope.is_approved = true;
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
});
</script>
@endpush