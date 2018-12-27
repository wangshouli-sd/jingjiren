<?php defined('IN_IA') or exit('Access Denied');?>












<!-- Placed js at the end of the document so the pages load faster -->

<!--<script src="<?php  echo MODEL_LOCAL?>static/bootstrap/js/jquery-1.10.2.min.js"></script>-->







<script type="text/javascript">

    /**

     * 退出操作

     */

    $(function() {

        $(".logout").click(function () {

            layer.confirm('确认退出系统？', {

                btn: ['退出', '取消'] //按钮

            }, function () {

                window.location.href = "/app/index.php?c=entry&do=logout&m=monai_market&i=<?php  echo $_W['uniacid'];?>"

            }, function () {

            });

        })

    })

</script>





</body>

</html>