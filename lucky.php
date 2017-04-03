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
<div style="margin: 0 auto;">
    <div class="row">
        <div class="col-md-12 text-center">
            <img class="lucky-logo" src="assets/images/lucky/title1.png" alt="">
        </div>
    </div>
    <div class="row text-center">
        <div class="lucky-body">
            <div class="content">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2">
                            <a href="javascript:;" class="btn btn-md btn-success m-b-10 pull-left" id="one" <?php if($place['prize_one_status'] == 1) {echo 'disabled="disabled"';}?>>一等奖</a>
                            <a href="javascript:;" class="btn btn-md btn-success m-b-10 pull-left" id="two" <?php if($place['prize_two_status'] == 1) {echo 'disabled="disabled"';}?>>二等奖</a>
                            <a href="javascript:;" class="btn btn-md btn-success pull-left" id="three" <?php if($place['prize_three_status'] == 1) {echo 'disabled="disabled"';}?>>三等奖</a>
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
    </div>
    <div class="row">
        <div class="col-md-12 text-center m-b-20 m-t-20">
            <input type="hidden" id="prize" value="0">
            <input type="hidden" id="prize_name" value="0">
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
        var prize = $('#prize');
        var prize_name = $('#prize_name');
        var number = $('#number');
        $('#one').click(function () {
            prize_name.val('prize_one_status');
            prize.val('<?php echo $place['prize_one']; ?>');
            number.val(<?php echo $place['prize_one_num']; ?>);
        });
        $('#two').click(function () {
            prize_name.val('prize_two_status');
            prize.val('<?php echo $place['prize_two']; ?>');
            number.val(<?php echo $place['prize_two_num']; ?>);
        });
        $('#three').click(function () {
            prize_name.val('prize_three_status');
            prize.val('<?php echo $place['prize_three']; ?>');
            number.val(<?php echo $place['prize_three_num']; ?>);
        });
        $('#start').click(function () {
            if($(this).text() == '开始抽奖') {
                if (prize.val() == 0) {
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
                    data: {'name': prize_name.val(),'prize' : prize.val(), 'number' : number.val(), 'type' : "<?php echo $type; ?>"},
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
                                list += '       <img width="100" src="' + data.data[0].headimgurl + '" alt="' + data.data[0].nickname + '">';
                                list += '       <div class="caption">';
                                list += '           <h3>' + data.data[0].phone + '</h3>';
                                list += '       </div>';
                                list += '   </div>';
                            }else {
                                $.each(data.data, function (i, val) {
                                    list += '<div class="col-md-6">';
                                    list += '   <div class="thumbnail">';
                                    list += '       <img width="100" src="' + val.headimgurl + '" alt="' + val.nickname + '">';
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
            window.location.href = '/lucky.php?type='+ "<?php echo $type; ?>";
        })
    });
</script>
</html>