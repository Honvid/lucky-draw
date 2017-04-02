<?php
session_start();
/**
 * @author Honvid
 * @time: 2017/3/31  下午7:48
 */
require 'plugins/wechat/WeChat.class.php';
if(empty($_SESSION['open_id'])) {
    if (!empty($_GET['code'])) {
        WeChat::getUserInfo($_GET['code']);
    } else {
        WeChat::redirect();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Honvid">
    <link rel="shortcut icon" href="assets/images/favicon_1.ico">
    <title>抽奖</title>
    <link href="assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/lucky.css" rel="stylesheet" type="text/css" />
    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <script src="assets/js/modernizr.min.js"></script>
    <style>

    </style>
</head>
<body>
    <div class="form-group">
        <label for="exampleInputEmail1">手机号</label>
        <input type="text" class="form-control" id="phone">
    </div>
    <a href="javascript:;" class="btn btn-default">提交</a>
</body>
<script src="assets/js/jquery.min.js"></script>
<script type="text/javascript">
    $('.btn').click(function () {
        if(check()) {
            $.ajax({
                url:'/submit.php',
                data:{'phone': $('#phone').val(), ''}
            })
        }
    });
    function check() {
        var phone = $('#phone');
        if (phone.val() == "") {
            alert("手机号码不能为空！");
            phone.focus();
            return false;
        }

        if (!phone.val().match(/^1[34578]\d{9}$/)) {
            alert("手机号码格式不正确！");
            phone.focus();
            return false;
        }
        return true;
    }
</script>
</html>
