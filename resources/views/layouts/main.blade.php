<!DOCTYPE html>
<html>
<head>
<title>@yield('title')</title>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
@yield('meta')
<link rel="stylesheet" type="text/css" href="/assets/jqueryui/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/balloon.css">

<!-- Bootstrap -->
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-theme.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-select.min.css">

<!-- Semantic ui -->
<link rel="stylesheet" type="text/css" href="/assets/semantic-ui/semantic.css">
<link rel="stylesheet" type="text/css" href="/assets/semantic-ui/components/dropdown.css">
<link rel="stylesheet" type="text/css" href="/assets/semantic-ui/components/transition.css">
<link rel="stylesheet" type="text/css" href="/assets/semantic-ui/components/sidebar.min.css">
<link rel="stylesheet" type="text/css" href="/assets/semantic-ui/components/popup.min.css">
<link rel="stylesheet" type="text/css" href="/assets/semantic-ui/components/checkbox.min.css">

<!-- Alertify -->
<link rel="stylesheet" type="text/css" href="/assets/css/alertify-css/alertify.css">
<link rel="stylesheet" type="text/css" href="/assets/css/alertify-css/themes/default.min.css">

<link rel="stylesheet" type="text/css" href="/assets/css/core.css">

@yield('css')
</head>
<body>
<div class="ui left vertical inverted labeled icon sidebar menu">
    <a class="item" href="/">
        <i class="food icon"></i>
        Restaurant
    </a>
    <a class="item" href="/inventory">
        <i class="browser icon"></i>
        Inventory
    </a>
    <a class="item">
        <i class="in cart icon"></i>
        Purchases
    </a>
    <a class="item">
        <i class="users icon"></i>
        Users
    </a>
    <a class="item">
        <i class="bar chart icon"></i>
        Reports
    </a>
    <a class="item">
        <i class="info icon"></i>
        Keyboard<br>Shortcuts
    </a>
    <a class="item">
        <i class="settings icon"></i>
        Settings
    </a>
</div>
<div class="pusher">
<nav class="navbar navbar-default nav-stacked">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#pos-navbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span> 
      </button>
      <a class="navbar-brand" href="#"><img src="/assets/images/logo.png"></a>
    </div>
    <div class="collapse navbar-collapse" id="pos-navbar">
    <ul class="nav navbar-nav navbar-right">
    <li><a href="#" id="time-display"></a></li>
    <li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
      <i class="fa fa-user" aria-hidden="true"></i>
      <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="#">Page 1-1</a></li>
        <li><a href="#">Page 1-2</a></li>
        <li><a href="#">Page 1-3</a></li>
      </ul>
    </li>
    </ul>
    </div>
  </div>
</nav>
    <div class="container-fluid">
      <div class="ui breadcrumb">
        <a class="section" id="menu">Menu</a>
        <i class="right angle icon divider"></i>
        @yield('breadcrumb')
      </div>
      <div class="row">
        @yield('content')
      </div>
    </div>
</div>

@yield('modals')
<!-- jQuery -->
<script type="text/javascript" src="/assets/js/jquery.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery-ui.min.js"></script>

<!-- Bootstrap -->
<script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>

<!-- Semantic ui components -->
<script type="text/javascript" src="/assets/semantic-ui/components/dropdown.js"></script>
<script type="text/javascript" src="/assets/semantic-ui/components/transition.js"></script>
<script type="text/javascript" src="/assets/semantic-ui/components/sidebar.min.js"></script>
<script type="text/javascript" src="/assets/semantic-ui/components/popup.min.js"></script>
<script type="text/javascript" src="/assets/semantic-ui/components/checkbox.min.js"></script>

<script type="text/javascript" src="/assets/js/angular.min.js"></script>
<script type="text/javascript" src="/assets/js/moment.js"></script>
<script type="text/javascript" src="/assets/js/tablesort.js"></script>
<script type="text/javascript" src="/assets/js/alertify.js"></script>
<script type="text/javascript" src="/assets/js/shortcut.js"></script>

<script type="text/javascript" src="/assets/js/core.js"></script>
@yield('scripts')
</body>
</html>