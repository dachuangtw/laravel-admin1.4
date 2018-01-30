<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{config('admin.title')}} | 404</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="{{ asset("/css/top-bootstrap.css") }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset("/css/top-font-awesome.css") }}">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body style="background: #d2d6de;">
    <div id="container" style="">
        <div class="item-title" style="font-family: '微軟正黑體', 'Helvetica Neue', Helvetica, Arial, sans-serif;font-size: 20px;background:#fff;width:600px;margin:200px auto;border-radius: 5px;text-align:center;">
            <div id="message" style="color: #94a531;padding: 30px;">
                <p style="font-size: 150px;">404</p>
                <p>您可能輸入了錯誤的網址，</p>
                <p>或者此頁面已被管理者移除。</p>
            </div>
            <div class="link-bottom" style="padding:20px;">
                <a class="link-icon" href="/">
                    <i class="fa fa-home"></i> BACK HOME</a>
            </div>
        </div>
    </div>
</body>
</html>
