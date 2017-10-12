<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
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
<style>
#login-container{
  margin-top: 20vh;
  border: 1px solid grey;
  padding: 1rem 1rem 1rem 1rem;
}
</style>
</head>
<body id="has-logo" ng-controller="content-controller">
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-8 col-md-4 col-lg-4 col-sm-push-2 col-md-push-4 col-lg-push-4">
      <div id="login-container" style="background-color: white;">
      <div style="padding: 10px">
        <img class="img-responsive" src="/assets/images/logo.png">
      </div>
        <form action="/login" method="post" class="form-horizontal">
          {{csrf_field()}}
          <div class="form-group">
            <label class="col-sm-2 col-xs-4 col-md-3" for="email">Username:</label>
            <div class="col-sm-10 col-xs-8 col-md-9">
              <div class="ui left icon input fluid">
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username">
                <i class="user icon"></i>
              </div>
              <p class="help-block" id="account_help-block">{{ $errors->first('username') }}</p>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 col-xs-4 col-md-3" for="pwd">Password:</label>
            <div class="col-sm-10 col-xs-8 col-md-9"> 
              <div class="ui left icon input fluid">
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
                <i class="lock icon"></i>
              </div>
              <p class="help-block" id="account_password_help-block">{{ $errors->first('password') }}</p>
            </div>
          </div>
          <div class="form-group"> 
            <div class="col-sm-12">
              <button type="submit" class="btn btn-primary btn-block">Login</button>
            </div>
          </div>
        </form>
      </div>
      <div>
        <br>
        <b>Admin Password</b><br>
        Username: <b>admin</b><br>
        Password: <b>admin</b><br>
        <br>
        <b>Viewdeck Café User</b><br>
        Restaurant Admin: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>admin1</b>/<b>admin1</b><br>
        Restaurant Cashier: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>user1</b>/<b>user1</b><br>
        <br>
        <b>Koi Café</b><br>
        Restaurant Admin: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>admin2</b>/<b>admin2</b><br>
        Restaurant Cashier: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>user2</b>/<b>user2</b><br>
        <br>
        <b>Restaurant 3</b><br>
        Restaurant Admin: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>admin3</b>/<b>admin3</b><br>
        Restaurant Cashier: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>user3</b>/<b>user3</b><br>
      </div>
    </div>
  </div>

</div>
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

<!-- Angularjs -->
<script type="text/javascript" src="/assets/js/1.6.5/angular.min.js"></script>
<script type="text/javascript" src="/assets/js/1.6.5/angular-route.min.js"></script>
<script type="text/javascript" src="/assets/js/1.6.5/angular-sanitize.min.js"></script>

<script type="text/javascript" src="/assets/js/moment.js"></script>
<script type="text/javascript" src="/assets/js/tablesort.js"></script>
<script type="text/javascript" src="/assets/js/alertify.js"></script>
<script type="text/javascript" src="/assets/js/shortcut.js"></script>

<script type="text/javascript" src="/assets/js/core.js"></script>
<script type="text/javascript">
  var app = angular.module('main', ['ngSanitize']);
  app.controller('content-controller', function($scope,$http, $sce) {

  });
  angular.bootstrap(document, ['main']);
</script>
</body>