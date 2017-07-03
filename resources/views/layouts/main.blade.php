<!DOCTYPE html>
<html>
<head>
<title>@yield('title')</title>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
@yield('meta')
<link rel="stylesheet" type="text/css" href="/assets/css/balloon.css">
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-theme.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-select.min.css">
<link rel="stylesheet" type="text/css" href="/assets/semantic-ui/semantic.css">
<link rel="stylesheet" type="text/css" href="/assets/semantic-ui/components/dropdown.css">
<link rel="stylesheet" type="text/css" href="/assets/semantic-ui/components/transition.css">
<link rel="stylesheet" type="text/css" href="/assets/css/alertify-css/alertify.css">
<link rel="stylesheet" type="text/css" href="/assets/css/alertify-css/themes/default.min.css">
<link rel="stylesheet" type="text/css" href="/assets/jqueryui/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/core.css">

@yield('css')
</head>
<body>

<div class="container-fluid">
	<div class="row">
		@yield('content')
	</div>
</div>

@yield('modals')
<script type="text/javascript" src="/assets/js/jquery.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery-ui.min.js"></script>
<!-- <script type="text/javascript" src="/assets/semantic-ui/semantic.js"></script> -->
<script type="text/javascript" src="/assets/semantic-ui/components/dropdown.js"></script>
<script type="text/javascript" src="/assets/semantic-ui/components/transition.js"></script>
<script type="text/javascript" src="/assets/js/moment.js"></script>
<script type="text/javascript" src="/assets/js/alertify.js"></script>
<script type="text/javascript" src="/assets/js/angular.min.js"></script>
<script type="text/javascript" src="/assets/js/core.js"></script>

@yield('scripts')
</body>
</html>