<?php
/**
 * @author Honvid
 * @time: 2016/12/12  下午8:49
 */
?>
<div class="row">
    <div class="col-sm-12">
        <div class="panel">
            <div class="panel-body">
                <table data-toggle="table"
                       data-show-columns="false"
                       data-page-list="[20, 50, 100]"
                       data-page-size="20"
                       data-pagination="true" class="table-bordered ">
                    <thead>
                    <tr>
                        <th data-field="id">编号</th>
                        <th data-field="name">词组</th>
                        <th data-field="actions">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data as $key => $value): ?>
                        <tr>
                            <td><?php echo $key+1; ?></td>
                            <td>
                                <?php if (in_array($key+1, $filter)) :?>
                                    <div class="has-error">
                                        <input type="text" id="value-<?php echo $key; ?>" value="<?php echo $value; ?>" class="form-control">
                                    </div>
                                <?php else: ?>
                                    <input type="text" class="form-control" id="value-<?php echo $key; ?>" value="<?php echo $value; ?>">
                                <?php endif; ?>
                            </td>
                            <td class="actions">

                                <?php if (in_array($key+1, $filter)) :?>
                                    <a href="javascript:;" data-key="<?php echo $key; ?>" class="btn btn-danger waves-effect waves-light edit">保存</a>
                                <?php else: ?>
                                    <a href="javascript:;" data-key="<?php echo $key; ?>" class="btn btn-warning waves-effect waves-light edit">保存</a>
                                <?php endif; ?>
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
</div>
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

<script src="assets/plugins/bootstrap-table/js/bootstrap-table.min.js"></script>
<script src="assets/plugins/select2/js/select2.min.js" type="text/javascript"></script>

<script src="assets/pages/jquery.bs-table.js"></script>

<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.edit').on('click', function() {
            var key = $(this).data('key');
            var data = $('#value-' + key).val();
            if(data == '') {
                alert('不能为空！');
                return false;
            }
            $.ajax({
                url:'/save.php',
                data:{'type': '<?php echo $type; ?>','key':parseInt(key), 'name':data},
                type:'POST',
                success:function (response) {
                    console.log(response);
//                    alert('修改成功！');
                    location.reload();
                },
                error:function(message) {
                    console.log(message)
                }
            })
        })
    });
</script>
</body>
</html>
