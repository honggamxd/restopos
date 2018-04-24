@extends('layouts.main')

@section('title', 'Capital Expenditure Request')

@section('css')
<style type="text/css">


</style>
@endsection
@section('breadcrumb')
<a class="section" href="{{route('inventory.capital-expenditure-request.list')}}">Capital Expenditure Requests</a>
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
            'Approving Capital Expenditure Request',
            'After submitting, the system cannot unapprove this capital expenditure request form. continue?',
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
        let id = {!! json_encode($capital_expenditure_request['id']) !!};
        $http({
            method: 'PATCH',
            url: route('api.inventory.capital-expenditure-request.approve',[id]).url(),
        }).then(function(response) {
            $.notify('Capital Expenditure Request has been approved.', {
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