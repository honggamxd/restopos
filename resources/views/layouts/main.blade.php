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
<div class="ui left vertical inverted labeled icon sidebar menu">
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
    @if(Auth::user()->privilege=="admin")
    <a class="item" href="/inventory">
        <i class="browser icon"></i>
        Items
    </a>
    <a class="item" href="{{ route('inventory.purchase-request.create') }}">
        <i class="browser icon"></i>
        Purhcase<br>Requeest
    </a>
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

@yield('modals')

@routes
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>