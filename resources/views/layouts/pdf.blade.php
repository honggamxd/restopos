<!DOCTYPE html>
<html>
<head>
<title>@yield('title')</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
@yield('meta')
@yield('css')
<style type="text/css">
    *{
      line-height: 1;
      padding: 0;
      margin: 0;
      font-size: 9pt;
    }
    .main-heading{
        font-weight: bold;
        font-size: 17pt;
    }
    .sub-heading{
        font-weight: bold;
        font-size: 13pt;
    }
    .page-break {
        page-break-after: always;
    }
    table tr td,table tr th{
      padding: 1pt;
      vertical-align: middle;
    }
    table{
        border-collapse: collapse;
    }
    .table-bordered tr td, .table-bordered tr th{
        border: 1pt solid black;
    }
    .container{
        padding-top: 0.5in;
        padding-left: 0.5in;
        padding-right: 0.5in;
    }
</style>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>

@stack('scripts')
</body>
</html>