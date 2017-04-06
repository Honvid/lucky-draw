<?php
if(!isset($_GET['type'])) {
    exit('参数错误');
}
/**
 * @author Honvid
 * @time: 2017/3/31  上午10:25
 */
require 'require.php';
$phone = getPhone(1500);
$type = $_GET['type'];
$place = Data::getPlace($type);
if(empty($place)) {
    exit('参数错误');
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Honvid">
    <link rel="shortcut icon" href="assets/images/favicon_1.ico">
    <title>2017新华三合作伙伴颁奖晚宴</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/lucky.css" rel="stylesheet" type="text/css" />
    <style>
        body{
            background: transparent;
        }
        .prize-btn{
            background-image: url(assets/images/lucky/btn.png);
            background-position: top;
            text-decoration: none;
            background-size: cover;
            width: 100%;
            height: 70px;
            font-size: 35px;
            color: #000;
            line-height: 66px;
            text-align: center;
        }
        .animate {
            height: 180px;
            overflow: hidden;
        }
        .animate li{
            font-size: 24px;
        }

        #start {
            background-image: url(assets/images/dinner/start.png);
            background-size: cover;
            height: 70px;
            margin-top: -2%;
            margin-bottom: 3%;
            font-size: 0;
            width: 180px;
        }
        #start.active{
            background-image: url(assets/images/dinner/stop.png);
            background-size: cover;
            height: 70px;
            margin-top: -2%;
            margin-bottom: 3%;
            font-size: 0;
            width: 180px;
        }
        .dinner-list-two .user-info{
            background-size: 180px;
        }
        .dinner-list-two .user-info img{
            width: 156px;
            height: 156px;
            margin-top: 13px;
        }
        .dinner-list-two .user-info p.text-center{
            font-size: 40px;
            padding-top: 10px;
        }
        .lucky-body-two .row{
            height: 253px;
            overflow: hidden;
        }
        .lucky-body-two ul.list{
            overflow: hidden;
            /*top: -15px;*/
            padding: 0;
            color: #fff;
            position: relative;
        }
        .lucky-body-two ul.list li{
            height: 253px;
            padding:0;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            list-style: none;
        }
    </style>
</head>
<body>
<div class="fen-bg-phone text-center">
    <img src="assets/images/dinner/pure.jpg" style="position: fixed; width: 100%; top: 0; left: 0; height: 100%; z-index: -999;">
    <img id="bg" src="assets/images/dinner/yi.jpg" style="width: 100%;top: 15%; position: fixed; z-index: -99; left: 0;">
    <div class="step-one" style="display: block; margin-top: 25%;">
        <div class="lucky-body" style="width: 80%;">
            <div class="row">
                <div class="col-md-2" style="padding-top: 4%;">
                    <?php if($place['prize_three_status'] == 0) {?>
                        <a href="javascript:;" class="pull-left prize-btn">三等奖</a>
                    <?php }elseif($place['prize_two_status'] == 0) {?>
                        <a href="javascript:;" class="pull-left prize-btn">二等奖</a>
                    <?php }elseif($place['prize_one_status'] == 0) {?>
                        <a href="javascript:;" class="pull-left prize-btn">一等奖</a>
                    <?php }else {?>
                        <a href="javascript:;" class="pull-left prize-btn">三等奖</a>
                    <?php }?>
                </div>
                <div class="col-md-2 animate"><ul></ul></div>
                <div class="col-md-2 animate"><ul></ul></div>
                <div class="col-md-2 animate"><ul></ul></div>
                <div class="col-md-2 animate"><ul></ul></div>
                <div class="col-md-2 animate"><ul></ul></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center" style="margin: 5% auto 1% auto;">
                <?php if($place['prize_three_status'] == 0 ) {?>
                    <input type="hidden" id="prize" value="<?php echo $place['prize_three']; ?>">
                    <input type="hidden" id="prize_name" value="prize_three_status">
                    <input type="hidden" id="number" value="<?php echo $place['prize_three_num']; ?>">
                <?php }elseif($place['prize_two_status'] == 0 ){ ?>
                    <input type="hidden" id="prize" value="<?php echo $place['prize_two']; ?>">
                    <input type="hidden" id="prize_name" value="prize_two_status">
                    <input type="hidden" id="number" value="<?php echo $place['prize_two_num']; ?>">
                <?php }elseif($place['prize_one_status'] == 0){ ?>
                    <input type="hidden" id="prize" value="<?php echo $place['prize_one']; ?>">
                    <input type="hidden" id="prize_name" value="prize_one_status">
                    <input type="hidden" id="number" value="<?php echo $place['prize_one_num']; ?>">
                <?php }else{ ?>
                    <input type="hidden" id="prize" value="0">
                    <input type="hidden" id="prize_name" value="0">
                    <input type="hidden" id="number" value="0">
                <?php } ?>
                <a href="javascript:;" class="btn text-center" id="start">开始抽奖</a>
            </div>

            <img src="assets/images/dinner/copy.png" style="margin: 1% auto;">
        </div>

    </div>
    <div class="step-two" style="display: none;">
        <div class="lucky-body-two" style="width:100%; margin: 26% auto 1% auto;">
            <div class="row">
                <ul class="list" style="padding: 0">
                    <li>
                        <div class="dinner-list-two">
                            <div class="user-info">
                                <img src="http://wx.qlogo.cn/mmopen/6t0VDe9bl5cayzHbwkOgHHs0sKH36uXrCbl02DN9bwibN7xAUYUgWyReY4h2bkxBd8c7SxxD6IicdmiaicaaJn80dg/0">
                                <p class="text-center">186****5555</p>
                            </div>
                        </div>
                        <div class="dinner-list-two">
                            <div class="user-info">
                                <img src="http://wx.qlogo.cn/mmopen/6t0VDe9bl5cayzHbwkOgHHs0sKH36uXrCbl02DN9bwibN7xAUYUgWyReY4h2bkxBd8c7SxxD6IicdmiaicaaJn80dg/0">
                                <p class="text-center">186****5555</p>
                            </div>
                        </div>
                        <div class="dinner-list-two">
                            <div class="user-info">
                                <img src="http://wx.qlogo.cn/mmopen/6t0VDe9bl5cayzHbwkOgHHs0sKH36uXrCbl02DN9bwibN7xAUYUgWyReY4h2bkxBd8c7SxxD6IicdmiaicaaJn80dg/0">
                                <p class="text-center">186****5555</p>
                            </div>
                        </div>
                        <div class="dinner-list-two">
                            <div class="user-info">
                                <img src="http://wx.qlogo.cn/mmopen/6t0VDe9bl5cayzHbwkOgHHs0sKH36uXrCbl02DN9bwibN7xAUYUgWyReY4h2bkxBd8c7SxxD6IicdmiaicaaJn80dg/0">
                                <p class="text-center">186****5555</p>
                            </div>
                        </div>
                        <div class="dinner-list-two">
                            <div class="user-info">
                                <img src="http://wx.qlogo.cn/mmopen/6t0VDe9bl5cayzHbwkOgHHs0sKH36uXrCbl02DN9bwibN7xAUYUgWyReY4h2bkxBd8c7SxxD6IicdmiaicaaJn80dg/0">
                                <p class="text-center">186****5555</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <img src="assets/images/dinner/copy2.png" style="margin: 8% auto 0 auto;">
        </div>
    </div>
</div>
<style>
    .overlay{
        display: none;
        position: fixed;
        top: 0;
        text-align: center;
        height: 100%;
        background: rgba(0,0,0,0.8);
        width: 100%;
        z-index: 99;
    }
    .overlay img{
        margin-top: 20%;
        width: 30%;
        z-index: 999;
    }
</style>
<div class="overlay">
    <img src="assets/images/lucky/not-enough.png" alt="">
</div>
</body>
<script src="//cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
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
                speed:10, //数值越大，速度越慢
                rowHeight:34, //li的高度
                margin:1
            });
        });
        function scrollPrize() {
            $('.lucky-body-two .row').myScroll({
                speed:20, //数值越大，速度越慢
                rowHeight:253, //li的高度
                margin:1
            });
        }
        function GetRandomNum(Min, Max) {
            var Range = Max - Min;
            var Rand = Math.random();
            return(Min + Math.round(Rand * Range));
        }
        var prize = $('#prize');
        var prize_name = $('#prize_name');
        var number = $('#number');
        $('#start').click(function () {
            if($(this).text() == '开始抽奖') {
                if (prize.val() == 0) {
                    $('.overlay img').attr('src', 'assets/images/lucky/no-prize.png');
                    $('.overlay').css('height', document.body.scrollHeight).show();
                }
                $(this).addClass('active');
                $(this).text('停止抽奖');
                animate.each(function () {
                    $(this).myScroll({
                        speed:1, //数值越大，速度越慢
                        rowHeight:34, //li的高度
                        margin:40
                    });
                });
            }else if ($(this).text() == '停止抽奖'){
                $(this).removeClass('active');
                $.ajax({
                    url: '/judge.php',
                    data: {'name': prize_name.val(),'prize' : prize.val(), 'number' : number.val(), 'type' : "<?php echo $type; ?>"},
                    type: 'post',
                    success:function (data) {
                        if(data.code == 200) {
                            $('.step-one').hide();
                            $('.step-two').show();
                            var list = '';
                            if(prize_name.val() == 'prize_one_status') {
                                $('#bg').attr('src', 'assets/images/dinner/yi.jpg');
                                list += '<li>';
                                $.each(data.data, function (i, val) {
                                    var str = val.dinnerphone;
                                    list += '<div class="dinner-list-two">';
                                    list += '    <div class="user-info">';
                                    list += '       <img src="'+val.headimgurl+'">';
                                    list += '       <p class="text-center">' + str.substr(0,3)+"****"+str.substr(7) + '</p>';
                                    list += '    </div>';
                                    list += '</div>';
                                });
                                list += '</li>';
                                $('.list').append(list);
                            }else if(prize_name.val() == 'prize_two_status') {
                                $('#bg').attr('src', 'assets/images/dinner/er.jpg');
                                list += '<li>';
                                $.each(data.data, function (i, val) {
                                    var str = val.dinnerphone;
                                    list += '<div class="dinner-list-two">';
                                    list += '    <div class="user-info">';
                                    list += '       <img src="'+val.headimgurl+'">';
                                    list += '       <p class="text-center">' + str.substr(0,3)+"****"+str.substr(7) + '</p>';
                                    list += '    </div>';
                                    list += '</div>';
                                });
                                list += '</li>';
                                $('.list').append(list);
                                scrollPrize();
                            }else if(prize_name.val() == 'prize_three_status') {
                                $('#bg').attr('src', 'assets/images/dinner/san.jpg');
                                list += '<li>';
                                $.each(data.data, function (i, val) {
                                    var str = val.dinnerphone;
                                    list += '<div class="dinner-list-two">';
                                    list += '    <div class="user-info">';
                                    list += '       <img src="'+val.headimgurl+'">';
                                    list += '       <p class="text-center">' + str.substr(0,3)+"****"+str.substr(7) + '</p>';
                                    list += '    </div>';
                                    list += '</div>';
                                });
                                list += '</li>';
                                $('.list').append(list);
                                scrollPrize();
                            }
                        }else{
                            $('.overlay img').attr('src', 'assets/images/lucky/not-enough.png');
                            $('.overlay').css('height', document.body.scrollHeight).show();
                        }
                    },
                    error:function () {
                        window.location.reload();
                    }
                });
            }else{
                return false;
            }
        });
        $('#close').click(function () {
            window.location.href = '/lucky.php?type='+ "<?php echo $type; ?>";
        });
        $('.overlay').on('click', function () {
            window.location.reload();
        });
        $('.overlay img').on('click', function () {
            window.location.reload();
        });
    });
</script>
</html>