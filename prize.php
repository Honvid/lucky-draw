<?php
    require 'require.php';
    require 'partial/header.php';
    $type = isset($_GET['type']) ? $_GET['type'] : '';
    $data = Data::getPrizePerson($type);
?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel">
                            <div class="panel-body">

                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>编号</th>
                                        <th>基本信息</th>
                                        <th>会场</th>
                                        <th>手机号</th>
                                        <th>头像</th>
                                        <th>奖项</th>
                                        <th>注册时间</th>
                                        <th>获奖时间</th>
                                        <th>领取时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <div class="form-inline m-b-20">
                                        <div class="row">
                                            <div class="col-sm-6">
<!--                                                <button type="button" id="add" class="btn waves-effect waves-light btn-primary">添加</button>-->
                                            </div>
                                        </div>
                                    </div>
                                    <tbody>
                                    <?php foreach ($data as $key => $value): ?>
                                        <tr>
                                            <td><?php echo $key+1; ?></td>
                                            <td>
                                                <p>昵称：<?php echo $value['nickname']; ?></p>
                                                <p>性别：<?php echo $value['sex']; ?></p>
                                                <p>地址: <?php echo $value['province'] . $value['city']; ?></p>
                                            </td>
                                            <td><?php echo $value['name']; ?></td>
                                            <td><p>分会场：<?php echo $value['phone']; ?></p><p>晚宴：<?php echo $value['dinnerphone']; ?></p></td>
                                            <td>
                                                <img width="100" src="<?php echo $value['headimgurl']; ?>" alt="<?php echo $value['nickname']; ?>" title="<?php echo $value['nickname']; ?>">
                                            </td>
                                            <td><?php echo $value['prize_name']; ?></td>
                                            <td><?php echo $value['create_time']; ?></td>
                                            <td><?php echo $value['get_time']; ?></td>
                                            <td><?php echo $value['accept_time']; ?></td>
                                            <td class="actions">
                                                <?php if(empty($value['accept_time'])) { ?>
                                                <a href="javascript:;" data-key="<?php echo $value['id']; ?>" class="btn btn-warning waves-effect waves-light edit">确定领取</a>
                                                <?php } ?>
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
        $('.edit').on('click', function () {
            var id = $(this).data('key');
            $.ajax({
                url: '/place.php',
                data: {'id': id, 'action': 3},
                type: 'POST',
                success: function (response) {
                    if (response.code == 200) {
                        alert('操作成功');
                        window.location.reload();
                    } else {
                        alert(response.msg);
                    }
                },
                error: function (message) {
                    console.log(message)
                }
            });
        });
    })
</script>
</body>
</html>