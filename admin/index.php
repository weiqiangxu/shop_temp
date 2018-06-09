<?php
@session_start();
$config = require(__DIR__ . '/config.php');
if(strpos($_SERVER['REQUEST_URI'], 'user/login') === false && empty($_SESSION['_userid']) && strpos($_SERVER['REQUEST_URI'], 'user/ajaxLogin') === false )
{
	header('Location:'.$config['HOST']['adminUri'].'user/login');
	exit;
}
require($config['PATH_MVC'] . '/mvc.php');
mvc::execute($config);