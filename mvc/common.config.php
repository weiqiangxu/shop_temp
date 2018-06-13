<?php
/**
  * @method 公共配置文件，例如数据、公共接口.....
  * @remark 调用方法：mvc::$cfg['ROOT'] ($comConfig['ROOT'])
  * @author soul
  * @copyright 2017/3/18
  */

//根路径（mvc上一级路径）
$comConfig['ROOT'] = str_replace ( '\\', '/', dirname ( dirname ( __FILE__ ) ) . '/' );
//宜配项目公共配置目录
$commonDir = $comConfig['ROOT'].'../common/';
$comConfig['ROOT_MVC'] = $comConfig['ROOT'].'mvc/';

//网址配置
$comConfig['HOST'] = [
	'adminUrl'=>'http://localhost/shop_temp/admin/',
	'adminUri'=>'http://localhost/shop_temp/admin/index.php/',
	'files'=>'http://localhost/shop_temp/admin/files/',
];
//JS CSS 版本号控制
$comConfig['VER'] = [
	'css'=>time(),
	'js'=>time()
];

$comConfig['LANG'] = [
	1=>'中文',
	2=>'英文',
	3=>'俄语',
];


//数据库配置
$comConfig['DB'] = [
	'MAIN' => [
		'db_type'	=>'mysql',
		'db_host'	=>'192.168.1.42',
		'db_port'	=>'3306',
		'db_name'	=>'shop_temp',
		'db_user'	=>'root',
		'db_pass'	=>'123456',
		'db_charset'=>'utf8'
	]
];


//接口配置
$comConfig['AUTOLOAD'] = [
    'Main' =>  ['path'=>$comConfig['ROOT'].'mod_main/', 'ext'=> '.class.php'],
    'Oth' =>  ['path'=>$comConfig['ROOT'].'mod_oth/', 'ext'=> '.class.php']
];
return $comConfig;