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
  <div class="container login" style="height:90vh; display: flex; justify-content:center; align-items:center" >
      <div class="col-sm-8 panel panel-default" >
          <div class="col-sm-6" style="text-align:center" >
              <br>
              <br>
                <img src="/assets/images/logo.png" style="width: 100%;" />
              <h2>
                  <b>ORDERING SYSTEM</b>
              </h2>
          </div>
          <div class="col-sm-6" >
              <br>
              <form class="form-horizontal panel-body " method="POST" action="/login">
                {{csrf_field()}}

                  <div class="form-group">

                      <div class="col-md-12">
                          <label for="username" class="control-label"> Username: </label>
                          <div class="ui left icon input fluid">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" autocomplete="new-password" required autofocus>
                            <i class="user icon"></i>
                          </div>
                          <p class="help-block" id="account_help-block">{{ $errors->first('username') }}</p>

                      </div>
                  </div>

                  <div class="form-group">
                      <div class="col-md-12">
                          <label for="username" class="control-label"> Password: </label>
                          <div class="ui left icon input fluid">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" autocomplete="new-password">
                            <i class="lock icon"></i>
                          </div>

                          <p class="help-block" id="account_password_help-block">{{ $errors->first('password') }}</p>
                      </div>
                  </div>

                  <div class="form-group">
                      <div class="col-md-12">
                          <br>
                          <button type="submit" class="btn btn-primary btn-block">
                              Login
                          </button>
                      </div>
                  </div>

              </form> 

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