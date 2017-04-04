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
    <title>抽奖</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/lucky.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="fen-bg-phone text-center">
    <img src="assets/images/lucky/background/pure.jpg" style="position: fixed; width: 100%; left: 0; height: 100%; z-index: -999;">
    <img id="bg" src="assets/images/lucky/background/fen-bg.png" style="width: 66%;margin-top: 5%; position: relative; z-index: -99">
    <div class="row" style="margin-top: -58%">
        <div class="col-md-12 text-center">
            <img class="lucky-logo" src="assets/images/lucky/title/<?php echo $type; ?>.png" alt="">
        </div>
    </div>
    <div class="step-one" style="display: block; padding-top: 6%;">
        <div class="lucky-body">
            <div class="row">
                <div class="col-md-2">
                    <a href="javascript:;" class="m-b-10 pull-left btn-prize <?php if($place['prize_one_status'] == 1) {echo 'disabled';}?>" id="one">一等奖</a>
                    <a href="javascript:;" class="m-b-10 pull-left btn-prize <?php if($place['prize_two_status'] == 1) {echo 'disabled';}?>" id="two">二等奖</a>
                    <?php if($place['prize_one_status'] == 0 && $place['prize_two_status'] == 0 && $place['prize_three_status'] == 0) {?>
                        <a href="javascript:;" class="pull-left btn-prize active" id="three">三等奖</a>
                    <?php } else {?>
                        <a href="javascript:;" class="pull-left btn-prize <?php if($place['prize_three_status'] == 1) {echo 'disabled';}?>" id="three">三等奖</a>
                    <?php } ?>
                </div>
                <div class="col-md-2 animate"><ul></ul></div>
                <div class="col-md-2 animate"><ul></ul></div>
                <div class="col-md-2 animate"><ul></ul></div>
                <div class="col-md-2 animate"><ul></ul></div>
                <div class="col-md-2 animate"><ul></ul></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center" style="margin: 3% auto 1% auto;">
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
                <p class="prize-info">
                    <span class="prize-span">一等奖</span> <?php echo $place['prize_one'] . ' '.$place['prize_one_num'] . '名 '; ?>
                    <span class="prize-span">二等奖</span> <?php echo $place['prize_two'] . ' '.$place['prize_two_num'] . '名 '; ?>
                    <span class="prize-span">三等奖</span><?php echo $place['prize_three'] . ' '.$place['prize_three_num'] . '名 '; ?></p>
            </div>
        </div>

    </div>
    <div class="step-two" style="display: none;">
        <div class="lucky-body-two">
            <div class="row">
                <div style="width: 90%; margin: 0 auto; padding: 10px 0;" class="list"></div>
            </div>
        </div>
        <div class="row" style="margin-bottom: 20px">
            <div class="col-md-12 text-center">
                <img width="50" id="close" src="assets/images/lucky/close_btn.png">
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
        $('#one').click(function () {
            <?php if($place['prize_one_status'] == 0) { ?>
            $('#one').addClass('active');
            $('#two').removeClass('active');
            $('#three').removeClass('active');
            prize_name.val('prize_one_status');
            prize.val('<?php echo $place['prize_one']; ?>');
            number.val(<?php echo $place['prize_one_num']; ?>);
            <?php }else{ ?>
            alert('一等奖已经抽完');
            <?php } ?>
        });
        $('#two').click(function () {
            <?php if($place['prize_two_status'] == 0) { ?>
            $('#one').removeClass('active');
            $('#two').addClass('active');
            $('#three').removeClass('active');
            prize_name.val('prize_two_status');
            prize.val('<?php echo $place['prize_two']; ?>');
            number.val(<?php echo $place['prize_two_num']; ?>);
            <?php }else{ ?>
            alert('二等奖已经抽完');
            <?php } ?>
        });
        $('#three').click(function () {
            <?php if($place['prize_three_status'] == 0) { ?>
            $('#one').removeClass('active');
            $('#two').removeClass('active');
            $('#three').addClass('active');
            prize_name.val('prize_three_status');
            prize.val('<?php echo $place['prize_three']; ?>');
            number.val(<?php echo $place['prize_three_num']; ?>);
            <?php }else{ ?>
            alert('三等奖已经抽完');
            <?php } ?>
        });
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
                                $('#bg').attr('src', 'assets/images/lucky/background/yi-fen.png')
                            }else if(prize_name.val() == 'prize_two_status') {
                                $('#bg').attr('src', 'assets/images/lucky/background/er-fen.png')
                            }else if(prize_name.val() == 'prize_three_status') {
                                $('#bg').attr('src', 'assets/images/lucky/background/san-fen.png')
                            }
                            if(number.val() == 1) {
                                var str = data.data[0].phone;
                                list += '<div class="col-md-12 text-center">';
                                list += '    <div class="row" style="margin: 25px">';
                                list += '       <div class="col-md-6 user-info-one">';
                                list += '           <img src="' + data.data[0].headimgurl + '" alt="">';
                                list += '       </div>';
                                list += '    <div class="col-md-4 text-center">';
                                list += '       <p style="margin: 0; margin-top: 10px; color: yellow">恭喜此用户获得</p>';
                                list += '       <p style="margin: 0; color: yellow">'+prize.val()+'</p>';
                                list += '       <hr style="margin: 5px 0; color: yellow">';
                                list += '       <p style="margin: 0; color: yellow;font-size: 24px;font-weight: bold;">' + str.substr(0,3)+"****"+str.substr(7) + '</p>';
                                list += '    </div>';
                                list += '</div>';
                            }else {
                                list += '<div class="col-md-12">';
                                list += '    <p class="prize-title">恭喜以下用户获得'+prize.val()+'</p>';
                                list += '</div>';
                                $.each(data.data, function (i, val) {
                                    var str = val.phone;
                                    list += '<div class="col-md-3 text-center">';
                                    list += '    <div class="user-info">';
                                    list += '    <img src="' + val.headimgurl + '" width="100" alt="' + val.nickname + '">';
                                    list += '       <p>' + str.substr(0,3)+"****"+str.substr(7) + '</p>';
                                    list += '    </div>';
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
            window.location.href = '/lucky.php?type='+ "<?php echo $type; ?>";
        })
    });
</script>
</html>