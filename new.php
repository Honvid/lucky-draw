<?php
    require 'require.php';
    require 'partial/header.php';
    $count = Data::countPerson();
    $data = Data::getPlaceListNew();
?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel">
                            <div class="panel-body">

                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>编号</th>
                                        <th>主题</th>
                                        <th>标识</th>
                                        <th>奖项</th>
                                        <th>人数</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <div class="form-inline m-b-20">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <button type="button" id="add" class="btn waves-effect waves-light btn-primary">添加</button>
                                            </div>
                                        </div>
                                    </div>
                                    <tbody>
                                    <?php foreach ($data as $key => $value): ?>
                                        <tr>
                                            <td><?php echo $key+1; ?></td>
                                            <td>
                                                <p><?php echo $value['name']; ?></p>
                                                <p><a href="http://mobile-show.cn/h3c/hzmeet/lucky/lucky.php?type=<?php echo $value['type']; ?>" target="_blank">抽奖链接</a></p>
                                            </td>
                                            <td><p><?php echo $value['type']; ?></p></td>
                                            <td style="">
                                                <p>名称：<?php echo $value['prize_one']; ?>，数量：<?php echo $value['prize_one_num']; ?>个， 状态：<?php echo $value['prize_one_status'] == 1 ? '已抽奖' : '待抽奖'; ?></p>
                                                <p>名称：<?php echo $value['prize_two']; ?>，数量：<?php echo $value['prize_two_num']; ?>个， 状态：<?php echo $value['prize_two_status'] == 1 ? '已抽奖' : '待抽奖'; ?></p>
                                                <p>名称：<?php echo $value['prize_three']; ?>，数量：<?php echo $value['prize_three_num']; ?>个， 状态：<?php echo $value['prize_three_status'] == 1 ? '已抽奖' : '待抽奖'; ?></p>
                                            </td>
                                            <td>
                                                <?php echo isset($count[$value['type']]) ? $count[$value['type']] : 0; ?>
                                            </td>
                                            <td class="actions">
                                                <?php if(isset($_GET['role']) && $_GET['role'] == 'admin') { ?>
                                                <a href="javascript:;" data-key="<?php echo $value['type']; ?>" class="btn btn-warning waves-effect waves-light edit">编辑</a>
                                                    <a href="javascript:;" data-key="<?php echo $value['type']; ?>" class="btn btn-danger waves-effect waves-light clear">清空测试数据</a>
                                                <?php } ?>
                                                <a href="/prize.php?type=<?php echo $value['type']; ?>" class="btn btn-success waves-effect waves-light">查看获奖名单</a>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- end Panel -->
                    </div>
                </div>
            </div> <!-- container -->
        </div> <!-- content -->

        <footer class="footer">
            © 2016. All rights reserved.
        </footer>

    </div>
    <!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->
</div>
<!-- END wrapper -->

<script>
    var resizefunc = [];
</script>

<!-- jQuery  -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/detect.js"></script>
<script src="assets/js/fastclick.js"></script>
<script src="assets/js/jquery.slimscroll.js"></script>
<script src="assets/js/jquery.blockUI.js"></script>
<script src="assets/js/waves.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/jquery.nicescroll.js"></script>
<script src="assets/js/jquery.scrollTo.min.js"></script>

<script src="assets/plugins/switchery/js/switchery.min.js"></script>
<script src="assets/plugins/bootstrap-table/js/bootstrap-table.min.js"></script>
<script src="assets/plugins/select2/js/select2.min.js" type="text/javascript"></script>

<script src="assets/pages/jquery.bs-table.js"></script>

<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#add').on('click', function () {
            $('#myModal').modal('show');
        });
        $('.clear').on('click', function () {
            var type = $(this).data('key');
            $.ajax({
                url: '/place.php',
                data: {'type': type, 'action': 4},
                type: 'POST',
                success: function (response) {
                    alert(response.msg);
                    window.location.reload();
                },
                error: function (message) {
                    console.log(message)
                }
            });
        });
        $('.edit').on('click', function () {
            var type = $(this).data('key');
            $.ajax({
                url: '/place.php',
                data: {'type': type, 'action': 1},
                type: 'POST',
                success: function (response) {
                    if(response.code == 200) {
                        $('.modal-title').text('编辑' + response.data.name);
                        $('#name').val(response.data.name);
                        $('#type').val(response.data.type);
                        $('#type_default').val(response.data.type);
                        $('#prize_one').val(response.data.prize_one);
                        $('#prize_one_num').val(response.data.prize_one_num);
                        if(response.data.prize_one_status == 1) {
                            $('#prize_one_status_1').attr('checked', 'checked');
                        }else{
                            $('#prize_one_status_0').attr('checked', 'checked');
                        }
                        $('#prize_two').val(response.data.prize_two);
                        $('#prize_two_num').val(response.data.prize_two_num);
                        if(response.data.prize_two_status == 1) {
                            $('#prize_two_status_1').attr('checked', 'checked');
                        }else{
                            $('#prize_two_status_0').attr('checked', 'checked');
                        }
                        $('#prize_three').val(response.data.prize_three);
                        $('#prize_three_num').val(response.data.prize_three_num);
                        if(response.data.prize_three_status == 1) {
                            $('#prize_three_status_1').attr('checked', 'checked');
                        }else{
                            $('#prize_three_status_0').attr('checked', 'checked');
                        }
                    }else{
                        alert(response.msg);
                    }
                },
                error: function (message) {
                    console.log(message)
                }
            });
            $('#myModal').modal('show');
        });
        $('.save').click(function () {
            var data = {};
            data.action = 2;
            data.name = $('#name').val();
            data.default_type = $('#type_default').val();
            data.type = $('#type').val();
            data.prize_one = $('#prize_one').val();
            data.prize_one_num = $('#prize_one_num').val();
            data.prize_one_status = $("input[name='prize_one_status']:checked").val();
            data.prize_two = $('#prize_two').val();
            data.prize_two_num = $('#prize_two_num').val();
            data.prize_two_status = $("input[name='prize_two_status']:checked").val();
            data.prize_three = $('#prize_three').val();
            data.prize_three_num = $('#prize_three_num').val();
            data.prize_three_status = $("input[name='prize_three_status']:checked").val();
            $.ajax({
                url: '/place.php',
                data: data,
                type: 'POST',
                success: function (response) {
                    if(response.code == 200) {
                        window.location.reload();
                    }else{
                        alert(response.msg);
                    }
                },
                error: function (message) {
                    console.log(message)
                }
            });
        })
    });
</script>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">添加会场</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="type" id="type_default">
                <div class="form-group">
                    <label for="name">会议主题</label>
                    <input type="text" class="form-control" id="name">
                </div>
                <div class="form-group">
                    <label for="type">标识</label>
                    <input type="text" class="form-control" id="type">
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="prize_one">一等奖</label>
                            <input type="text" class="form-control" id="prize_one">
                        </div>
                        <div class="form-group">
                            <label for="prize_one_num">数量</label>
                            <input type="number" class="form-control" id="prize_one_num">
                        </div>
                        <div class="form-group">
                            <label class="checkbox-inline" style="padding-left: 0;">
                                <input type="radio" name="prize_one_status" id="prize_one_status_0" value="0"> 待抽奖
                            </label>
                            <label class="checkbox-inline" style="padding-left: 0;">
                                <input type="radio" name="prize_one_status" id="prize_one_status_1" value="1"> 已抽奖
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="prize_two">二等奖</label>
                            <input type="text" class="form-control" id="prize_two">
                        </div>
                        <div class="form-group">
                            <label for="prize_two_num">数量</label>
                            <input type="number" class="form-control" id="prize_two_num">
                        </div>
                        <div class="form-group">
                            <label class="checkbox-inline" style="padding-left: 0;">
                                <input type="radio" name="prize_two_status" id="prize_two_status_0" value="0"> 待抽奖
                            </label>
                            <label class="checkbox-inline" style="padding-left: 0;">
                                <input type="radio" name="prize_two_status" id="prize_two_status_1" value="1"> 已抽奖
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="prize_three">三等奖</label>
                            <input type="text" class="form-control" id="prize_three">
                        </div>
                        <div class="form-group">
                            <label for="prize_three_num">数量</label>
                            <input type="number" class="form-control" id="prize_three_num">
                        </div>
                        <div class="form-group">
                            <label class="checkbox-inline" style="padding-left: 0;">
                                <input type="radio" name="prize_three_status" id="prize_three_status_0" value="0"> 待抽奖
                            </label>
                            <label class="checkbox-inline" style="padding-left: 0;">
                                <input type="radio" name="prize_three_status" id="prize_three_status_1" value="1"> 已抽奖
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary save">保存</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</body>
</html>