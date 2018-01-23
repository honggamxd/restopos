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
    @if(Session::get('users.user_data')->privilege=="restaurant_cashier")
    <a class="item" href="/">
        <i class="food icon"></i>
        {{Session::get('users.user_data')->restaurant}}
    </a>
    <a class="item" href="/restaurant/reports">
        <i class="bar chart icon"></i>
        My Reports
    </a>
    @endif
    @if(Session::get('users.user_data')->privilege=="restaurant_admin")
    <a class="item" href="/restaurant/cancellations">
        <i class="settings icon"></i>
        Cancellation<br>
        Requests
    </a>
    <a class="item" href="/restaurant/menu">
        <i class="food icon"></i>
        {{Session::get('users.user_data')->restaurant}}<br>Menu
    </a>
    <a class="item" href="/restaurant/settings">
        <i class="settings icon"></i>
        {{Session::get('users.user_data')->restaurant}}<br>
        Settings
    </a>
    <a class="item" href="/restaurant/reports">
        <i class="bar chart icon"></i>
        {{Session::get('users.user_data')->restaurant}}<br>
        Order Slip<br>
        Summary
    </a>
    <a class="item" href="/restaurant/orders">
        <i class="bar chart icon"></i>
        {{Session::get('users.user_data')->restaurant}}<br>
        Food Orders
    </a>
    @endif
    @if(Session::get('users.user_data')->privilege=="admin")
    <a class="item" href="/inventory">
        <i class="browser icon"></i>
        Inventory
    </a>
    <a class="item" href="/issuance">
        <i class="browser icon"></i>
        Issuance
    </a>
    <a class="item" href="/purchase">
        <i class="in cart icon"></i>
        Purchases
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
      {{Session::get('users.user_data')->name}}
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
      <div class="row">
        @yield('content')
      </div>
        @yield('two_row_content')
    </div>
</div>

@yield('modals')
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>