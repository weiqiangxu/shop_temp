<?php
class indexAction
{


    /**
     * @method    后台管理主页
     * @author    xu
     * @copyright 2018-05-16
     */
	public function index()
	{
        // meta 
		LibTpl::Set('title', '后台管理');
		LibTpl::Put();
	}


    /**
     * @method    控制台
     * @author    xu
     * @copyright 2018-05-16
     */
	public function main()
	{
		LibTpl::Set('title', '产品列表-后台管理');
		LibTpl::Put();
	}

}