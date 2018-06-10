<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $title;?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/layuiAdmin/src/layuiadmin/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/layuiAdmin/src/layuiadmin/layui/css/modules/laydate/default/laydate.css" media="all">
    <link rel="stylesheet" href="<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/layuiAdmin/src/layuiadmin/style/admin.css" media="all">
    <link rel="stylesheet" href="<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/css/common.css?ver=<?php echo mvc::$cfg['VER']['css'];?>">
    <link rel="stylesheet" href="<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/css/style.css?ver=<?php echo mvc::$cfg['VER']['css'];?>">
    <script src="<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/js/jquery-1.9.1.min.js?ver=1.0.2"></script>
    <script src="<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/layuiAdmin/src/layuiadmin/layui/lay/modules/laydate.js?ver=1.0.2"></script>

    <script src="<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/layuiAdmin/src/layuiadmin/layui/layui.js?ver=1.0.2"></script>
    <script src="<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/ueditor/ueditor.config.js"></script>
    <script src="<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/ueditor/ueditor.all.min.js"></script>
    <script src="<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/webuploader/webuploader.min.js?ver=1.0.0"></script>
    <script src="<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/js/common.js?ver=<?php echo mvc::$cfg['VER']['js'];?>"></script> 
    <!-- 主页 -->
    <script src="<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/js/index.js?ver=<?php echo mvc::$cfg['VER']['js'];?>"></script>  
    <script>
    var adminUrl = '<?php echo mvc::$cfg['HOST']['adminUrl'];?>';
    var adminUri = '<?php echo mvc::$cfg['HOST']['adminUri'];?>';
    var layuiUrl = adminUrl+'static/layuiAdmin/src/layuiadmin/';
    layui.config({base: layuiUrl});
    </script>

</head>
<body>

<ul class="layui-nav" lay-filter="">
    <!--客户菜单-->
    <?php if($_SESSION['_style']=='0'){ ?>
    <li class="layui-nav-item <?php if($menu=='list'){echo 'layui-this';}?>">
        <a href="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/list">产品查询</a>
    </li>
    <?php } ?>
    <!--管理员菜单-->
    <?php if($_SESSION['_style']==1){ ?>
    <li class="layui-nav-item <?php if($menu=='list'){echo 'layui-this';}?>">
        <a href="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/list">产品列表</a>
    </li>
    <li class="layui-nav-item <?php if($menu=='check'){echo 'layui-this';}?>">
        <a href="javascript:;">产品审核</a>
    </li>
    <?php if($_SESSION['_super']){ ?>
    <li class="layui-nav-item <?php if($menu=='sys'){echo 'layui-this';}?>" >
        <a href="javascript:;">账号管理</a>
        <dl class="layui-nav-child"> <!-- 二级菜单 -->
            <dd><a href="<?php echo mvc::$cfg['HOST']['adminUri'];?>sys/userList?type=1" >管理员</a></dd>
            <dd><a href="<?php echo mvc::$cfg['HOST']['adminUri'];?>sys/userList?type=2" >工厂</a></dd>
            <dd><a href="<?php echo mvc::$cfg['HOST']['adminUri'];?>sys/userList?type=0" >客户</a></dd>
            <dd><a href="<?php echo mvc::$cfg['HOST']['adminUri'];?>sys/addUser" >添加账户</a></dd>
        </dl>
    </li>
    <?php } ?>
    <?php } ?>
    <?php if($_SESSION['_style']==2){ ?>
    <!--工厂菜单-->
    <li class="layui-nav-item <?php if($menu=='list'){echo 'layui-this';}?>">
        <a href="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/list">产品列表</a>
    </li>
    <li class="layui-nav-item <?php if($menu=='publish'){echo 'layui-this';}?>">
        <a href="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/publish">产品添加</a>
    </li>
    <li class="layui-nav-item <?php if($menu=='batch'){echo 'layui-this';}?>">
        <a href="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/batch">产品导入</a>
    </li>
    <?php } ?>

    <li class="layui-nav-item layui-layout-right padding-10">
        <a href=""><?php echo $_SESSION['_username'];?></a>
        <dl class="layui-nav-child">
            <dd><a href="<?php echo mvc::$cfg['HOST']['adminUri'];?>sys/account">个人信息</a></dd>
            <dd><a href="<?php echo mvc::$cfg['HOST']['adminUri'];?>sys/password">修改密码</a></dd>
            <dd><a href="javascript:;" onclick="adminIndex.logout()">退出登录</a></dd>
        </dl>
    </li>


</ul>
<!-- <iframe src="<?php echo mvc::$cfg['HOST']['adminUri'];?>index/main" frameborder="0" class="" name="mainFrame"></iframe> -->