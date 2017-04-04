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
    <title>晚宴抽奖</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/lucky.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="fen-bg-phone text-center">
    <img src="assets/images/lucky/background/pure.jpg" style="position: fixed; width: 100%; left: 0; height: 100%; z-index: -999;">
    <img id="bg" src="assets/images/lucky/background/dinner-bg.png" style="width: 66%;margin-top: 5%; position: relative; z-index: -99">
    <div class="row" style="margin-top: -58%">
        <div class="col-md-12 text-center">
            <img class="lucky-logo" style="margin-top: 7%;" src="assets/images/lucky/title/<?php echo $type; ?>.png" alt="">
        </div>
    </div>
    <div class="step-one" style="display: block; padding-top: 6%;">
        <div class="lucky-body">
            <div class="row">
                <div class="col-md-2" style="padding-top: 4%;">
                    <?php if($place['prize_three_status'] == 0) {?>
                        <a href="javascript:;" class="pull-left btn-prize active">三等奖</a>
                    <?php }elseif($place['prize_two_status'] == 0) {?>
                        <a href="javascript:;" class="pull-left btn-prize active">二等奖</a>
                    <?php }elseif($place['prize_one_status'] == 0) {?>
                        <a href="javascript:;" class="pull-left btn-prize active">一等奖</a>
                    <?php }else {?>
                        <a href="javascript:;" class="pull-left btn-prize disabled">三等奖</a>
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
        </div>
        <div class="row">
            <div class="col-md-12">
                <p class="prize-info" style="padding: 0;padding-top: 15px; margin: 0 auto; text-align: center">
                    <?php if($place['prize_three_status'] == 0 ) {?>
                    <img src="assets/images/lucky/er-dinner-chou.png" alt=""></p>
                <?php }elseif($place['prize_two_status'] == 0 ){ ?>
                    <img src="assets/images/lucky/er-dinner-chou.png" alt=""></p>
                <?php }elseif($place['prize_one_status'] == 0){ ?>
                    <img src="assets/images/lucky/er-dinner-chou.png" alt=""></p>
                <?php }else{ ?>
                    <img src="assets/images/lucky/er-dinner-chou.png" alt=""></p>
                <?php } ?>
            </div>
        </div>

    </div>
    <div class="step-two" style="display: none;">
        <div class="lucky-body-two" style="width: 53%">
            <div class="row">
                <div class="list" style="margin-top: -4%"></div>
            </div>
        </div>
    </div>
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
                speed:100, //数值越大，速度越慢
                rowHeight:34, //li的高度
                margin:1
            });
        });
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
                    alert("请选择抽奖的等级哦");
                    return false;
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
                                $('#bg').attr('src', 'assets/images/lucky/background/yi-dinner.png');
                                $.each(data.data, function (i, val) {
                                    var str = val.dinnerphone;
                                    list += '<div class="dinner-list-one">';
                                    list += '    <div class="user-info">';
                                    list += '       <img src="'+val.headimgurl+'">';
                                    list += '       <p class="text-center">' + str.substr(0,3)+"****"+str.substr(7) + '</p>';
                                    list += '    </div>';
                                    list += '</div>';
                                });
                                list += '<p style="padding-top: 40%; color: yellow"><img src="assets/images/lucky/yi-prize-dinner.png" alt=""></p>';
                            }else if(prize_name.val() == 'prize_two_status') {
                                $('#bg').attr('src', 'assets/images/lucky/background/er-dinner.png');
                                $.each(data.data, function (i, val) {
                                    var str = val.dinnerphone;
                                    list += '<div class="dinner-list-two">';
                                    list += '    <div class="user-info">';
                                    list += '       <img src="'+val.headimgurl+'">';
                                    list += '       <p class="text-center">' + str.substr(0,3)+"****"+str.substr(7) + '</p>';
                                    list += '    </div>';
                                    list += '</div>';
                                });
                                list += '<p style="padding-top: 40%; color: yellow"><img src="assets/images/lucky/er-prize-dinner.png" alt=""></p>';
                            }else if(prize_name.val() == 'prize_three_status') {
                                $('#bg').attr('src', 'assets/images/lucky/background/san-dinner.png');
                                $.each(data.data, function (i, val) {
                                    list += '<div class="dinner-list">';
                                    list += '    <div class="user-info">';
                                    list += '       <img src="'+val.headimgurl+'">';
                                    list += '       <p class="pull-right text-left">' + str.substr(0,3)+"****"+str.substr(7) + '&nbsp;&nbsp;&nbsp;&nbsp;</p>';
                                    list += '    </div>';
                                    list += '</div>';
                                });
                                list += '<p style="padding-top: 45%; color: yellow"><img src="assets/images/lucky/san-prize-dinner.png" alt=""></p>';
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
            window.location.href = '/lucky.php?type='+ "<?php echo $type; ?>";
        })
    });
</script>
</html>