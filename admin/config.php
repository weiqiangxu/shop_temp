<?php
//公共配置文件
$com_config = require(str_replace('\\','/', dirname ( dirname ( __FILE__ ) ) . '/'). '/mvc/common.config.php');
//当前站点根路径
$SitePath = $comConfig['ROOT'].'admin/';
! defined ( 'LibPage' ) && define ( 'LibPage', 'all' );
$config = [
	'DEBUG' => true, //调试模式总开关
	'DEBUG_TPL' => false, //模板调试模式
	'SITE_NAME' => 'shopAdmin', //站点名称,必须，于于自动生成时的目录创建(例如缓存)
	'SHOW_RUN_TIME' => false, //显示运行花费时间
	/*相对不太变化的配置*/
	'PATH_ROOT' => $SitePath,                     //网站根目录
	'PATH_MVC' => $com_config['ROOT_MVC'],        //MVC目录
	'PATH_CACHE' => $SitePath.'../cache/',        //缓存目录
	'PATH_ACTION' => $SitePath.'action/',         //视图目录
	'ACTION_FILE_TAG' => 'Action',                //视图类与文件名后缀标识
	'PATH_MODULE' => $SitePath.'module/',         //模块目录
	'PATH_TPL' => $SitePath.'tpl/'                //模板目录
];
$config['MONEYTURN']="6.4056";
return array_merge_recursive($com_config, $config);
