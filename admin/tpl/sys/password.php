<?php include_once(mvc::$cfg['PATH_TPL'].'public/headBase.php');?>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">修改密码</div>
                <div class="layui-card-body" pad15>

                    <div class="layui-form" lay-filter="">
                        <div class="layui-form-item">
                            <label class="layui-form-label">当前密码</label>
                            <div class="layui-input-inline">
                                <input type="password" name="oldPassword" lay-verify="required" lay-verType="tips" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">新密码</label>
                            <div class="layui-input-inline">
                                <input type="password" name="password" lay-verify="pass|mempaswword" lay-verType="tips" autocomplete="off" id="LAY_password" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">6到16个字符</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">确认新密码</label>
                            <div class="layui-input-inline">
                                <input type="password" name="repassword" lay-verify="repass|mempaswword" lay-verType="tips" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit lay-filter="setmypass">确认修改</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    layui.config({
        base: '<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/layuiAdmin/src/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'set'],function(){
    // 注册表单提交事件
        var form = layui.form;
        form.render();
        form.verify({
          mempaswword:function(value,item){
            if(value!="") {
                if(!VERIFICATION.mempassword(value)) {
                    return '密码格式不正确，请输入6-12个非空白字符！';
                }
            }
          }
        }); 

        //提交
        form.on('submit(setmypass)', function(obj){
            $.ajax({
                url:adminUri+'sys/ajaxSetPwd?isAjax=1',
                data:{'data':obj.field},
                dataType:'json',
                type:'POST',
                success:function(res){
                    if(res.status) {
                        //登入成功的提示与跳转
                        layer.msg('修改成功', {
                            offset: '15px'
                            ,icon: 1
                            ,time: 1000
                            },function(){
                                location.reload(); //刷新
                            });
                        }else{
                            layer.alert(res.data, {icon: 5,title:'温馨提示'});
                        }
                    }
                });
            });
        });
</script>