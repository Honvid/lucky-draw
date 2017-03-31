<?php
    require 'require.php';
    $data = JsonHelper::read('Base');
    $type = '核心词组';
    $persons = JsonHelper::read('Persons');
    require 'partial/header.php';
?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel">
                            <div class="panel-body">

                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
<!--                                        <th><input type="checkbox" class="check-all"></th>-->
                                        <th>编号</th>
                                        <th>词组</th>
                                        <th>现在数量</th>
                                        <th>7:30</th>
                                        <th>8:00</th>
                                        <th>9:00</th>
                                        <th>12:00</th>
                                        <th>17:00</th>
<!--                                        <th>是否启用</th>-->
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <div class="form-inline m-b-20">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-user"></i> 参与人数</span>
                                                    <input
                                                            type="number"
                                                            id="persons"
                                                            name="persons"
                                                            class="form-control" placeholder="参与人数" value="<?php echo $persons[0]; ?>">
                                                    <span class="input-group-btn">
                                                        <button type="button" id="person-submit" class="btn waves-effect waves-light btn-primary">提交</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <tbody>
                                    <?php foreach ($data as $key => $value): ?>
                                        <tr>
                                            <td><?php echo $key+1; ?></td>
                                            <td>
                                                <input type="text" class="form-control"  id="title-<?php echo $key; ?>"  value="<?php echo $value['title']; ?>">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control number-<?php echo $key; ?>" value="<?php echo $value['number'][0]; ?>">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control number-<?php echo $key; ?>" value="<?php echo $value['number'][1]; ?>">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control number-<?php echo $key; ?>" value="<?php echo $value['number'][2]; ?>">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control number-<?php echo $key; ?>" value="<?php echo $value['number'][3]; ?>">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control number-<?php echo $key; ?>" value="<?php echo $value['number'][4]; ?>">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control number-<?php echo $key; ?>" value="<?php echo $value['number'][5]; ?>">
                                            </td>
                                            <td class="actions">
                                                <a href="javascript:;" data-key="<?php echo $key; ?>" class="btn btn-danger waves-effect waves-light edit">保存</a>
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

        $('#person-submit').click(function () {
            var number = $('#persons').val();
            if(!number) {
                alert('不能为空！');
                return false;
            }
            $.ajax({
                url: '/save.php',
                data: {'type': 'Persons', 'num': number},
                type: 'POST',
                success: function (response) {
                    console.log(response);
                    location.reload();
                },
                error: function (message) {
                    console.log(message)
                }
            })
        });
        $('.edit').on('click', function () {
            var key = $(this).data('key');
            var title = $('#title-' + key).val();
            var number = $('.number-'+key);
            var num = [];
            number.each(function () {
                num.push($(this).val());
            });
            if (title == '' || !num) {
                alert('不能为空！');
                return false;
            }
            $.ajax({
                url: '/save.php',
                data: {'type': 'Base', 'key': parseInt(key), 'title': title, 'num': num},
                type: 'POST',
                success: function (response) {
                    console.log(response);
//                    alert('修改成功！');
                    location.reload();
                },
                error: function (message) {
                    console.log(message)
                }
            })
        });
    });
</script>
</body>
</html>