<ul class="layui-nav" lay-filter="">
    <!--客户菜单-->
    <?php if($_SESSION['_style']=='0'){ ?>
    <li class="layui-nav-item <?php if($menu=='list'){echo 'layui-this';}?>">
        <a target="mainFrame" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/list">产品查询</a>
    </li>
    <?php } ?>
    <!--管理员菜单-->
    <?php if($_SESSION['_style']==1){ ?>
    <li class="layui-nav-item <?php if($menu=='list'){echo 'layui-this';}?>">
        <a target="mainFrame" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/list">产品列表</a>
    </li>
    <li class="layui-nav-item <?php if($menu=='check'){echo 'layui-this';}?>">
        <a target="mainFrame"  href="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/list?status=0">产品审核</a>
    </li>
    <li class="layui-nav-item <?php if($menu=='sys'){echo 'layui-this';}?>" >
        <a href="javascript:;">账号管理</a>
        <dl class="layui-nav-child"> <!-- 二级菜单 -->
            <?php if($_SESSION['_super']){ ?>
            <dd><a target="mainFrame" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>sys/userList?type=1" >管理员</a></dd>
            <?php } ?>
            <dd><a target="mainFrame" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>sys/userList?type=2" >工厂</a></dd>
            <dd><a target="mainFrame" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>sys/userList?type=0" >客户</a></dd>
            <dd><a target="mainFrame" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>sys/addUser" >添加账户</a></dd>
        </dl>
    </li>
    <?php } ?>
    <?php if($_SESSION['_style']==2){ ?>
    <!--工厂菜单-->
    <li class="layui-nav-item <?php if($menu=='list'){echo 'layui-this';}?>">
        <a target="mainFrame" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/list">产品列表</a>
    </li>
    <li class="layui-nav-item <?php if($menu=='publish'){echo 'layui-this';}?>">
        <a target="mainFrame" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/publish">产品添加</a>
    </li>
    <li class="layui-nav-item <?php if($menu=='batch'){echo 'layui-this';}?>">
        <a target="mainFrame" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/batch">产品导入</a>
    </li>
    <?php } ?>
    <li class="layui-nav-item">
        <?php if($_SESSION['_style']==1){ ?>
        <a href="javascript:;">1美元 = <input data-ori="<?php echo sprintf("%.4f",mvc::$cfg['MONEYTURN']);?>" id="stowid" type="text" onchange="autochange(this)" value="<?php echo sprintf("%.4f",mvc::$cfg['MONEYTURN']);?>"> 人民币</a>
        <?php }else{ ?>
            <a href="javascript:;">1美元 = <?php echo sprintf("%.4f",mvc::$cfg['MONEYTURN']);?>人民币</a>
        <?php } ?>
    </li>

    <li class="layui-nav-item layui-layout-right padding-10">
        <a href="javascript:;"><?php echo $_SESSION['_username'];?></a>
        <dl class="layui-nav-child">
            <dd><a target="mainFrame" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>sys/account">个人信息</a></dd>
            <dd><a target="mainFrame" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>sys/password">修改密码</a></dd>
            <dd><a target="mainFrame" href="javascript:;" onclick="adminIndex.logout()">退出登录</a></dd>
        </dl>
    </li>
</ul>
<!-- <iframe src="<?php echo mvc::$cfg['HOST']['adminUri'];?>index/main" frameborder="0" class="" name="mainFrame"></iframe> -->
<script type="text/javascript">
    function autochange(e){
        var ori = $(e).attr("data-ori");
        var val = $(e).val();
        if(isNaN(parseFloat(val))){
            $(e).val(ori);
            return false;
        }
        $.ajax({
            url:adminUri+'sys/ajaxSetMoney?isAjax=1',
            data:{'val':val},
            dataType:"json",
            type:"POST",
            success:function(res){
                if(res.status) {
                    //登入成功的提示与跳转
                    layer.msg('修改成功', {
                        offset: '15px'
                        ,icon: 1
                        ,time: 1000
                    },function(){
                        $(e).attr("data-ori",parseFloat(val));
                    });
                }else{
                    layer.alert(res.data, {icon: 5,title:'温馨提示'});
                }
            }
        });
    }
</script>