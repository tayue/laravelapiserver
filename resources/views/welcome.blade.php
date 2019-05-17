<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel</title>
    <script src="https://cdn.bootcss.com/jquery/2.0.0/jquery.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<div class="container">
  <!--  <div id="app">



    </div>-->
</div>

<script src="{{ mix('js/app.js') }}"></script>

<script>
    window.onload=function () {
        console.log($("#app"));
    }
</script>

</body>
</html>
