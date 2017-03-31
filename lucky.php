<?php
/**
 * @author Honvid
 * @time: 2017/3/31  上午10:25
 */
require 'helper/JsonHelper.php';
//$phone_pre = [139, 138, 137, 136, 186, 185, 135, 134, 147, 188, 187, 184, 183, 182, 159, 158, 157, 152, 151, 150, 145, 156, 155, 132, 131, 130, 189, 181, 180, 153, 133];
//$phone_list = [];
//for($i = 0; $i < 10000; $i++) {
//    $phone_list[] = $phone_pre[array_rand($phone_pre)] . '****' . rand(0, 9). rand(0, 9). rand(0, 9). rand(0, 9);
//}
if(!isset($_GET['fen']) || $_GET['fen'] > 18) {
    exit('参数错误');
}
$fen = $_GET['fen'];
$phone = JsonHelper::readPhone('phone');
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
<div style="width: 60%; margin: 0 auto;">
    <div class="row">
        <div class="col-md-8">家庭云时代：H3C Magic消费类新品发布</div>
        <div class="col-md-4">LOGO</div>
    </div>
    <div class="row" style="background: gray">
        <h2 class="text-center">现场抽奖</h2>
        <hr style="color: #fff;" class="content">
        <div class="content">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2">
                        <a href="javascript:;" class="btn btn-md btn-success m-b-10 pull-left" id="one">一等奖</a>
                        <a href="javascript:;" class="btn btn-md btn-success m-b-10 pull-left" id="two">二等奖</a>
                        <a href="javascript:;" class="btn btn-md btn-success pull-left" id="three">三等奖</a>
                    </div>
                    <div class="col-md-2 animate"><ul></ul></div>
                    <div class="col-md-2 animate"><ul></ul></div>
                    <div class="col-md-2 animate"><ul></ul></div>
                    <div class="col-md-2 animate"><ul></ul></div>
                    <div class="col-md-2 animate"><ul></ul></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center m-b-20 m-t-20">
            <input type="hidden" id="type" value="0">
            <input type="hidden" id="number" value="0">
            <a href="javascript:;" class="btn btn-danger text-center" id="start">开始抽奖</a>
        </div>
    </div>
</div>
<div class="overflow">
    <div class="box">
        <div class="row text-center">
            <div class="col-md-12 list"></div>
            <p class="btn btn-danger" style="margin-top: 10px;" id="close">关闭</p>
        </div>
    </div>
</div>
</body>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/scroll.js"></script>
<script type="text/javascript" charset="utf-8">
    $(function () {
        var phone = <?php echo $phone; ?>;
        var count = phone.length;
        var animate = $('.animate');
        for (var i = 0; i < count; i++ ) {
            var index = GetRandomNum(0, 4);
            var th = '<li>' + phone[i] + '</li>';
            $('ul').eq(index).append(th);
        }
        animate.each(function () {
            $(this).myScroll({
                speed:40, //数值越大，速度越慢
                rowHeight:34, //li的高度
                margin:1
            });
        });
        function GetRandomNum(Min, Max) {
            var Range = Max - Min;
            var Rand = Math.random();
            return(Min + Math.round(Rand * Range));
        }
        var type = $('#type');
        var number = $('#number');
        $('#one').click(function () {
            type.val(1);
            number.val(1);
        });
        $('#two').click(function () {
            type.val(2);
            number.val(1);
        });
        $('#three').click(function () {
            type.val(3);
            number.val(4);
        });
        $('#start').click(function () {
            if($(this).text() == '开始抽奖') {
                if (type.val() == 0) {
                    alert("请选择抽奖的等级哦");
                    return false;
                }
                $(this).text('停止抽奖');
                animate.each(function () {
                    $(this).myScroll({
                        speed:1, //数值越大，速度越慢
                        rowHeight:34, //li的高度
                        margin:20
                    });
                });
            }else if ($(this).text() == '停止抽奖'){
                $.ajax({
                    url: '/judge.php',
                    data: {'type' : type.val(), 'number' : number.val(), 'fen' : <?php echo $fen; ?>},
                    type: 'post',
                    success:function (data) {
                        if(data.code == 200) {
                            animate.each(function () {
                                $(this).find('li').remove()
                            });
                            $('.overflow').css('height', '100%').css('width', '100%').show();
                            var list = '';
                            if(number.val() == 1) {
                                list += '   <div class="thumbnail">';
                                list += '       <img src="' + data.data.photo + '" alt="' + data.data.uid + '">';
                                list += '       <div class="caption">';
                                list += '           <h3>' + data.data.phone + '</h3>';
                                list += '       </div>';
                                list += '   </div>';
                            }else {
                                $.each(data.data, function (i, val) {
                                    list += '<div class="col-md-6">';
                                    list += '   <div class="thumbnail">';
                                    list += '       <img src="' + val.photo + '" alt="' + val.uid + '">';
                                    list += '       <div class="caption">';
                                    list += '           <h3>' + val.phone + '</h3>';
                                    list += '       </div>';
                                    list += '   </div>';
                                    list += '</div>';
                                });
                            }
                            $('.list').append(list);
                        }else{
                            alert(data.msg);
                        }
                    },
                    error:function () {
                        alert('服务器错误！');
                    }
                });
            }else{
                return false;
            }
        });
        $('#close').click(function () {
            location.reload()
        })
    });
</script>
</html>