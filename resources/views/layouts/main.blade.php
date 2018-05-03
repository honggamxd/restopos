<!DOCTYPE html>
<html>
<head>
<title>@yield('title')</title>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
@yield('meta')

<link href="{{ asset('css/app.css') }}" rel="stylesheet">
@yield('css')
</head>
<body ng-app="main" ng-controller="content-controller">
@yield('overlay-div')
<div class="ui left vertical inverted labeled icon sidebar menu" ng-controller="nav_controller">
    @if(Auth::user()->privilege=="restaurant_cashier")
    <a class="item" href="/">
        <i class="food icon"></i>
        {{App\Restaurant::find(Auth::user()->restaurant_id)->name}}
    </a>
    <a class="item" href="/restaurant/reports">
        <i class="bar chart icon"></i>
        My Reports
    </a>
    @endif
    @if(Auth::user()->privilege=="restaurant_admin")
    <a class="item" href="/restaurant/cancellations">
        <i class="settings icon"></i>
        Cancellation<br>
        Requests
    </a>
    <a class="item" href="/restaurant/menu">
        <i class="food icon"></i>
        {{App\Restaurant::find(Auth::user()->restaurant_id)->name}}<br>Menu
    </a>
    <a class="item" href="/restaurant/settings">
        <i class="settings icon"></i>
        {{App\Restaurant::find(Auth::user()->restaurant_id)->name}}<br>
        Settings
    </a>
    <a class="item" href="/restaurant/reports">
        <i class="bar chart icon"></i>
        {{App\Restaurant::find(Auth::user()->restaurant_id)->name}}<br>
        Order Slip<br>
        Summary
    </a>
    <a class="item" href="/restaurant/orders">
        <i class="bar chart icon"></i>
        {{App\Restaurant::find(Auth::user()->restaurant_id)->name}}<br>
        Food Orders
    </a>
    @endif
    @if(Auth::user()->privilege=="admin" || Auth::user()->privilege=="inventory_user"))
    <a class="item" href="{{route('inventory.item.index')}}" ng-if="user_data.privilege == 'admin' || user_data.permissions.can_view_items">
        <i class="browser icon"></i>
        Items
    </a>
    <a class="item" href="{{ route('inventory.purchase-request.list') }}" ng-if="user_data.privilege == 'admin' || user_data.permissions.can_view_purchase_requests">
        <i class="browser icon"></i>
        Purchase<br>Request
    </a>
    <a class="item" href="{{ route('inventory.request-to-canvass.list') }}" ng-if="user_data.privilege == 'admin' || user_data.permissions.can_view_request_to_canvasses">
        <i class="browser icon"></i>
        Request to<br>Canvass
    </a>
    <a class="item" href="{{ route('inventory.capital-expenditure-request.list') }}" ng-if="user_data.privilege == 'admin' || user_data.permissions.can_view_capital_expenditure_requests">
        <i class="browser icon"></i>
        Capital<br>Expenditure<br>Request
    </a>
    <a class="item" href="{{ route('inventory.purchase-order.list') }}" ng-if="user_data.privilege == 'admin' || user_data.permissions.can_view_purchase_orders">
        <i class="browser icon"></i>
        Purchase<br>Order
    </a>
    <a class="item" href="{{ route('inventory.receiving-report.list') }}" ng-if="user_data.privilege == 'admin' || user_data.permissions.can_view_receiving_reports">
        <i class="browser icon"></i>
        Receiving<br>Report
    </a>
    <a class="item" href="{{ route('inventory.stock-issuance.list') }}" ng-if="user_data.privilege == 'admin' || user_data.permissions.can_view_stock_issuances">
        <i class="browser icon"></i>
        Stock<br>Issuance
    </a>
    @if(Auth::user()->privilege=="admin")
    <a class="item" href="/users">
        <i class="users icon"></i>
        Users
    </a>
    <a class="item" href="/reports">
        <i class="bar chart icon"></i>
        Reports
    </a>
    <a class="item" href="/restaurant/settings">
        <i class="settings icon"></i>
        Restaurant<br>
        Settings
    </a>
    @endif
    @endif

</div>
<div class="pusher">
<nav class="navbar navbar-default nav-stacked navbar-fixed-bottom">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#pos-navbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span> 
      </button>
      <a class="navbar-brand" href="javascript:void(0);"><img src="{{asset('assets/images/logo.png')}}"></a>
    </div>
    <div class="collapse navbar-collapse" id="pos-navbar">
    <ul class="nav navbar-nav navbar-right">
    <li><a href="javascript:void(0);" id="time-display"></a></li>
    <li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
      {{Auth::user()->name}}
      <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="/account-settings">Account Settings</a></li>
        <li><a href="/logout">Logout</a></li>
      </ul>
    </li>
    </ul>
    </div>
  </div>
</nav>
<div class="container-fluid">
    <div class="ui breadcrumb">
        <a class="section hideprint hideprint" id="menu">App Menu</a>
        <i class="right angle icon divider hideprint"></i>
        @yield('breadcrumb')
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        @yield('content')
    </div>
    @yield('two_row_content')
</div>
<div class="container">
    @yield('padded_content')
</div>

</div>
@yield('modals')

@routes
<script src="{{ asset('js/app.js') }}"></script>
<script>user_data = {privilege: "{{Auth::user()->privilege}}" ,name: "{{Auth::user()->name}}",username: "{{Auth::user()->username}}", permissions:{!! fractal(App\Inventory\Inventory_user_permission::where('user_id',Auth::user()->id)->first(), new App\Transformers\Inventory_user_permission_transformer)->parseincludes('permissions')->toJson() !!} };</script>
<script>
    app.controller('nav_controller', function($scope,$http, $sce, $window) {
        $scope.user_data = user_data;
        // console.log($scope.user_permission);
    });
</script>
@stack('scripts')
</body>
</html>