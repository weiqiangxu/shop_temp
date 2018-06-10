<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>登入 - 后台管理</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/layuiAdmin/src/layuiadmin/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/layuiAdmin/src/layuiadmin/style/admin.css" media="all">
    <link rel="stylesheet" href="<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/layuiAdmin/src/layuiadmin/style/login.css" media="all">
    <script src="<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/js/jquery-1.9.1.min.js?ver=1.0.2"></script>
    <script>
        var adminUrl = '<?php echo mvc::$cfg['HOST']['adminUrl'];?>';
        var adminUri = '<?php echo mvc::$cfg['HOST']['adminUri'];?>';
        var layuiUrl = adminUrl+'static/layuiAdmin/src/layuiadmin/';
    </script>
</head>
<body>
    <div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;" >

        <div class="layadmin-user-login-main">
            <div class="layadmin-user-login-box layadmin-user-login-header">
                <h2>后台管理系统</h2>
            </div>
            <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-username" for="LAY-user-login-username"></label>
                    <input type="text" name="username" id="LAY-user-login-username" lay-verify="required" placeholder="用户名" class="layui-input">
                </div>
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
                    <input type="password" name="password" id="LAY-user-login-password" lay-verify="required" placeholder="密码" class="layui-input">
                </div>
                <div class="layui-form-item">
                    <button id="subm" class="layui-btn layui-btn-fluid" lay-submit lay-filter="LAY-user-login-submit">登 入</button>
                </div>
            </div>
        </div>
        <div class="layui-trans layadmin-user-login-footer">
            版权所有 &copy; 2008-2018 广州宜配科技信息有限公司 <a href="http://www.yiparts.com" class="blue" target="_blank">www.yiparts.com
            </div>
        </div>

    <script src="<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/layuiAdmin/src/layuiadmin/layui/layui.js?t=1"></script>
    <script>
    layui.config({
        base: '<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/layuiAdmin/src/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'user'], function(){
        var form = layui.form;
        form.render();
        //提交
        form.on('submit(LAY-user-login-submit)', function(obj){
            $.ajax({
                url:adminUri+'user/ajaxLogin?isAjax=1',
                data:{'data':obj.field},
                dataType:'json',
                type:'POST',
                success:function(res){
                    if(res.status) {
                        //登入成功的提示与跳转
                        layer.msg('登入成功', {
                            offset: '15px'
                            ,icon: 1
                            ,time: 1000
                        }, function(){
                            location.href = adminUri+'product/list';
                        });
                    }else{
                        layer.alert(res.data, {icon: 5,title:'温馨提示'});
                    }
                }
            });
        });
        // 监听enter
        $(document).on('keydown', function(e){  
            if(e.keyCode == 13){
                $("#subm").trigger('click');   
            }
        })

    });
    </script>
</body>
</html>