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
    <!-- ztree start -->
    <link rel="stylesheet" href="<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/zTree_v3-master/css/zTreeStyle/zTreeStyle.css" type="text/css">
    <script type="text/javascript" src="<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/zTree_v3-master/js/jquery.ztree.core.js"></script>
    <script type="text/javascript" src="<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/zTree_v3-master/js/jquery.ztree.excheck.js"></script>
    <!-- ztree end -->
    <script>
    var adminUrl = '<?php echo mvc::$cfg['HOST']['adminUrl'];?>';
    var adminUri = '<?php echo mvc::$cfg['HOST']['adminUri'];?>';
    var layuiUrl = adminUrl+'static/layuiAdmin/src/layuiadmin/';
    layui.config({base: layuiUrl});
    </script>
</head>
<body>
