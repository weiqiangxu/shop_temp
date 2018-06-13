<?php include_once(mvc::$cfg['PATH_TPL'].'public/headBase.php');?>
    <div class="layui-fluid">
        <div class="layui-card-body">
            <form class="layui-form" action="" method="post">
                <div class="layui-row layui-col-space10 layui-form-item">
                    <div class="layui-col-lg3">

                        <label class="layui-form-label">
                            <span class="red">*</span> 用户名：
                        </label>
                        <div class="layui-input-block" style="height: 36px;line-height: 36px;">
                            <?php echo $data['u_name'];?>&nbsp;&nbsp;<span class="green">[ 已启用 ]</span>
                            &nbsp;&nbsp;
                            <?php if($_SESSION['_super']){ ?>
                            [超级管理员 ]
                            <?php }else{ ?>
                            [<?php echo $typeDes[$data['u_style']];?> ]
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="layui-row layui-col-space10 layui-form-item">
                    <div class="layui-col-lg3">
                        <label class="layui-form-label"><span class="red">*</span> 公司名称：</label>
                        <div class="layui-input-block">
                            <input type="text" lay-verType="tips" lay-verify="required" name="data[u_company]" value="<?php echo $data['u_company'];?>" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-col-lg3">
                        <label class="layui-form-label">联系人：</label>
                        <div class="layui-input-block">
                            <input type="text" value="<?php echo $data['u_realname'];?>" name="data[u_realname]" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
				<div class="layui-row layui-col-space10 layui-form-item">
                    <div class="layui-col-lg3">
                        <label class="layui-form-label">手机：</label>
                        <div class="layui-input-block">
                            <input type="text" lay-verType="tips" lay-verify="memmobile" name="data[u_mobile]" value="<?php echo $data['u_mobile'];?>" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-col-lg3">
                        <label class="layui-form-label">电话：</label>
                        <div class="layui-input-block">
                            <input type="text" lay-verType="tips" name="data[u_tel]" value="<?php echo $data['u_tel'];?>" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-row layui-col-space10 layui-form-item">
                    <div class="layui-col-lg3">
                        <label class="layui-form-label">微信号：</label>
                        <div class="layui-input-block">
                        <input type="text" lay-verType="tips" name="data[u_weixin]" value="<?php echo $data['u_weixin'];?>" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-col-lg3">
                        <label class="layui-form-label">QQ：</label>
                        <div class="layui-input-block">
                            <input type="text" lay-verify="memqq" lay-verType="tips" name="data[u_qq]" value="<?php echo $data['u_qq'];?>" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="setuserinfo">提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<script>
    layui.config({
        base: layuiUrl //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'set'],function(){
        var layer = layui.layer
        ,form = layui.form;
        form.verify({
          checkName: function(value, item){ //value：表单的值、item：表单的DOM对象
            var isExit = true;
            $.ajax({
                url:adminUri+'sys/checkName?isAjax=1',
                data:{'name':value},
                dataType:'json',
                type:'post',
                async:false,
                success:function(res) {
                    isExit = res.status;
                }
            });
            if(!isExit) {
                return '用户名已经被使用！';
            }
          },
          memmobile:function(value,item){
            if(value!="") {
                if(!VERIFICATION.mobile(value)) {
                    return '手机号码格式不正确！';
                }
            }
          },
          mempaswword:function(value,item){
            if(value!="") {
                if(!VERIFICATION.mempassword(value)) {
                    return '密码格式不正确，请输入6-12个非空白字符！';
                }
            }
          },
          mememail:function(value,item){
            if(value!="") {
                if(!VERIFICATION.email(value)) {
                    return '邮箱格式不正确，请重新输入！';
                }
            }
          },
          memqq:function(value,item){
            if(value!="") {
                if(!VERIFICATION.memqq(value)) {
                    return 'QQ号码格式不正确，请重新输入！';
                }
            }
          },
        });  
    });
</script>