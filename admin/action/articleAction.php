<?php
class articleAction
{
	/**
	 * @method    文件上传
	 * @author 卢
	 * @copyright 2018-05-16
	 */
	public function upload()
	{
		if(!empty($_FILES['file']))
		{
			$Params = mvc::$URL_PARAMS;
			if(!in_array($Params['type'], ['pro','article']))
			{
				$data=['code'=>1,'msg'=>'参数错误'];
				echo json_encode($data);
				exit();
			}
			$MainUpload=new MainUpload();
			$res=$MainUpload->uploadFile($_FILES['file'],$Params['type']);
			$data['info']=[];
			if($res['status'])
			{
				$data=['code'=>0,'data'=>['src'=>$res['data']['url']]];
				$data['info']=$res['data'];
			}
			else
			{
				$data=['code'=>1,'msg'=>$res['data']];
			}
			echo json_encode($data);
		}
	}


	/**
	 * @method    文章列表
	 * @author 	xu
	 * @copyright 2018-05-21
	 */
	public function lists()
	{	
        $MainRule = new MainRule();
        $MainRule->rule('AR01');
		$Params = mvc::$URL_PARAMS;
        $MainBase = new MainBase();
        $MainArticle = new MainArticle();
        $page = isset($Params['page']) ? (int)$Params['page']:1;
        $perPage = 20;

        // 搜索条件
        $where = '';
        if(!empty($Params['title']))
            $where .= sprintf(" and art_title like '%%%s%%' ",$Params['title']);
        if(!empty($Params['status']))
            $where .= sprintf(" and art_status ='%s' ",$Params['status']);
        if(!empty($Params['catId']))
        {
            $where .= sprintf(" and cat_id in (%s)",$Params['catId']);
            $catId = explode(',', $Params['catId']);
        }
        LibTpl::Set('Params',array_filter($Params));
        // print_r($Params);die;

        // 排序
        $order .= " order by art_seq asc,art_atime desc";
        $tab = "cm_article left join cm_article_ctegory on art_id=arc_art_id left join cm_category on cat_id=arc_cat_id ";

        // 分页
        $res = $MainBase->get($tab,['art_id'],$where.' group by art_id');
        LibTpl::Set('count',count($res['data']));
        LibTpl::Set('perPage',$perPage);
        LibTpl::Set('page',$page);

        $res = $MainBase->get($tab,['*'],$where.' group by art_id'.$order.sprintf(" limit %d,%d",($page-1)*$perPage,$perPage));
        $art = $res['data'];

		// 给文章拼接文章分类
		$res = $MainArticle->get('cm_category',['*'],"");
        $catTxt = array();
        $siteName = ['0' => '','1' => '-PC端','2' => '-手机端',];
        foreach ($res['data'] as $k => $v) {
            $catTxt[$v['cat_id']] = $v['cat_name'].$siteName[$v['cat_site']];
        }
        $artIds = array_column($art, 'art_id');
        if(!empty($artIds))
        {
            $res = $MainBase->get('cm_article_ctegory',['*'],sprintf("and arc_art_id in (%s)",implode(",", $artIds)));
            $temp = [];
            foreach ($res['data'] as $k => $v)
            {
            	if(isset($catTxt[$v['arc_cat_id']]))
            	{
            		$temp[$v['arc_art_id']][] = $catTxt[$v['arc_cat_id']];
            	}
            }
            foreach ($art as $k => $v) {
            	if(!empty($temp[$v['art_id']]))
            	{
            		$art[$k]['catTxt'] = implode(',', $temp[$v['art_id']]);
            	}
            }  
        }

        LibTpl::Set('data',$art);



        // 所有分类
        $res = $MainArticle->getCategory($Params['id']);
        LibTpl::Set('cat',$res['data']);
        $temp = [];  
        foreach ($res['data'] as $k => $v) {
            $temp[] = [
                    'id'=>$v['cat_id'],
                    'pId'=>$v['cat_pcat_id'],
                    'checked'=>(!empty($catId) && in_array($v['cat_id'],
                     $catId))?true:false,
                    'open'=>true,
                    'name'=>$v['cat_name']
                ];
            if(!empty($v['son']))
            {
                foreach ($v['son'] as $kk => $vv) {
                    $temp[] = ['id'=>$vv['cat_id'],'pId'=>$vv['cat_pcat_id'],'open'=>false,'checked'=>(!empty($catId) && in_array($vv['cat_id'], $catId))?true:false,'name'=>$vv['cat_name']];
                }
            }
        }
        // $temp[] = ['id'=>0,'pId'=>0,'checked'=>false,'open'=>false,'name'=>'全部'];
        LibTpl::Set('ztree',json_encode($temp));

        // 状态枚举
        LibTpl::Set('artStat',['-1'=>'禁用','1'=>'正常']);
		LibTpl::Set('title', '文章列表-'.mvc::$cfg['shopName']);
        LibTpl::Put();

	}

	/**
	 * @method    添加文章
	 * @author 	xu
	 * @copyright 2018-05-21
	 */
	public function add()
	{
        $MainRule = new MainRule();
        $MainRule->rule('AR02');
        $MainArticle = new MainArticle();
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
			$data = array_merge($_POST['basic'],['art_txt'=>$_POST['txt']],['arc_cat_id'=>$_POST['cat']]);
			$res = $MainArticle->addArticel($data);
			LibFc::ajaxJsonEncode(['status'=>true, 'data'=>mvc::$cfg['HOST']['adminUri'].'article/editArt?id='.$res['data']]);
		}

        $res = $MainArticle->getCategory();
        LibTpl::Set('cat',$res['data']);

        $temp = [];  
        foreach ($res['data'] as $k => $v) {
            $temp[] = ['id'=>$v['cat_id'],'pId'=>$v['cat_pcat_id'],'checked'=>false,'open'=>false,'name'=>$v['cat_name']];
            if(!empty($v['son']))
            {
                foreach ($v['son'] as $kk => $vv) {
                    $temp[] = ['id'=>$vv['cat_id'],'pId'=>$vv['cat_pcat_id'],'open'=>true,'open'=>false,'checked'=>false,'name'=>$vv['cat_name']];
                }
            }
        }
        LibTpl::Set('ztree',json_encode($temp));


		LibTpl::Set('title', '添加文章-'.mvc::$cfg['shopName']);
        LibTpl::Put();
	}



	/**
	 * @method    编辑文章
	 * @author 	xu
	 * @copyright 2018-05-21
	 */
	public function editArt()
	{
        $MainRule = new MainRule();
        $MainRule->rule('AR03');
		$Params = mvc::$URL_PARAMS;
        $MainArticle = new MainArticle();
        if($_SERVER['REQUEST_METHOD']=='POST')
        {
            // 保存编辑结果
            $data = array_merge($_POST['basic'],['art_txt'=>$_POST['txt']],['arc_cat_id'=>$_POST['cat']]);
            $res = $MainArticle->editArticel($data,$data['art_id']);
            LibFc::ajaxJsonEncode(['status'=>true, 'data'=>true]);
        }

		if(empty($Params['id']) || !LibFc::Int($Params['id']))
			LibTpl::Error('文章不存在！');

		// 文章内容
        $res = $MainArticle->getArticel($Params['id']);
        LibTpl::Set('data',$res['data']);

        // 文章分类
        $res = $MainArticle->get('cm_article_ctegory',['*'],sprintf("and arc_art_id=%d",$Params['id']));
        $selCat = [];
        foreach ($res['data'] as $k => $v)
        {
            $selCat[] = $v['arc_cat_id'];
        }
        LibTpl::Set('selCat',$selCat);
		// 所有分类
		$res = $MainArticle->getCategory();
		LibTpl::Set('cat',$res['data']);
        $temp = [];  
        foreach ($res['data'] as $k => $v) {
            $temp[] = ['id'=>$v['cat_id'],'pId'=>$v['cat_pcat_id'],'checked'=>boolval(in_array($v['cat_id'], $selCat)),'open'=>false,'name'=>$v['cat_name']];
            if(!empty($v['son']))
            {
                foreach ($v['son'] as $kk => $vv) {
                    $temp[] = ['id'=>$vv['cat_id'],'pId'=>$vv['cat_pcat_id'],'open'=>false,'checked'=>boolval(in_array($vv['cat_id'], $selCat)),'name'=>$vv['cat_name']];
                }
            }
        }
        LibTpl::Set('ztree',json_encode($temp));

		LibTpl::Set('title', '添加文章-'.mvc::$cfg['shopName']);
        LibTpl::Put();
	}



	/**
	 * @method    文章分类
	 * @author 	xu
	 * @copyright 2018-05-21
	 */
	public function ctegory()
	{
        $MainRule = new MainRule();
        $MainRule->rule('AR08');
		$MainArticle = new MainArticle();
		$res = $MainArticle->getCategory();
		LibTpl::Set('data',$res['data']);

        LibTpl::Set('title', '文章分类-'.mvc::$cfg['shopName']);
        LibTpl::Put();
	}


	/**
	 * @method    新增分类
	 * @author 	xu
	 * @copyright 2018-05-21
	 */
	public function addCat()
	{

        $MainRule = new MainRule();
        $MainRule->rule('AR05');
		$MainArticle = new MainArticle();
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
			// 入库新数据
			$data = $_POST['data'];
			$MainArticle->addCategory($data);
			LibFc::ajaxJsonEncode(['status'=>true, 'data'=>'添加成功！']);
		}

		// 分类信息
		$res = $MainArticle->getCategory();
		LibTpl::Set('cat',$res['data']);

		// meta
        LibTpl::Set('title', '管理员信息-'.mvc::$cfg['shopName']);
        LibTpl::Put();
	}


	/**
	 * @method    编辑分类
	 * @author 	xu
	 * @copyright 2018-05-21
	 */
	public function editCat()
	{
        $MainRule = new MainRule();
        $MainRule->rule('AR06');
		$Params = mvc::$URL_PARAMS;
		$MainArticle = new MainArticle();


		if($_SERVER['REQUEST_METHOD']=='POST')
		{
			// 入库新数据
			$data = $_POST['data'];
			$MainArticle->editCategory($data,$data['cat_id']);
			LibFc::ajaxJsonEncode(['status'=>true, 'data'=>'编辑成功！']);
		}

		if(empty($Params['id']) || !LibFc::Int($Params['id']))
		{
			LibTpl::Error('参数错误！');
		}

		// 分类详情
		$res = $MainArticle->get('cm_category',['*'],sprintf("and cat_id=%d",$Params['id']),true);
		LibTpl::Set('data',$res['data']);

		// 所有分类
		$res = $MainArticle->getCategory($Params['id']);
		LibTpl::Set('cat',$res['data']);


		// meta
        LibTpl::Set('title', '编辑分类-'.mvc::$cfg['shopName']);
        LibTpl::Put();
	}
	


	/**
     * @method    删除分类
     * @author    xu
     * @copyright 2018-05-17
     */
    function ajaxDelCat()
    {
        $MainRule = new MainRule();
        $MainRule->rule('AR07');
        if(empty($_POST['id']) || !LibFc::Int($_POST['id']))
            LibFc::ajaxJsonEncode(['status'=>false, 'data'=>'删除分类参数错误！']);

        $MainArticle = new MainArticle();
        $res = $MainArticle->delCategory($_POST['id']);

        LibFc::ajaxJsonEncode(['status'=>true, 'data'=>'操作成功！']);
    }


	/**
     * @method    删除文章
     * @author    xu
     * @copyright 2018-05-17
     */
    function ajaxDelArt()
    {
        $MainRule = new MainRule();
        $MainRule->rule('AR04');
        if(empty($_POST['id']) || !LibFc::Int($_POST['id']))
            LibFc::ajaxJsonEncode(['status'=>false, 'data'=>'删除文章参数错误！']);

        $MainArticle = new MainArticle();
        $res = $MainArticle->delArticel($_POST['id']);

        LibFc::ajaxJsonEncode(['status'=>true, 'data'=>'操作成功！']);
    }


    /**
     * @method    问答列表
     * @author    xu
     * @copyright 2018-05-17
     */
    function ask()
    {
        $MainRule = new MainRule();
        $MainRule->rule('AR09');
        $Params = mvc::$URL_PARAMS;
        $MainBase = new MainBase();
        $page = isset($Params['page']) ? (int)$Params['page']:1;
        $perPage = 16;

        // 搜索条件
        $where = '';
        if(!empty($Params['title']))
            $where .= sprintf(" and ask_question like '%%%s%%' ",$Params['title']);
        if(!empty($Params['start']))
            $where .= sprintf(" and ask_atime>%d ",strtotime($Params['start']));
        if(!empty($Params['end']))
            $where .= sprintf(" and ask_atime<%d ",strtotime($Params['end']));
        // 排序
        $where .= "order by ask_seq asc,ask_atime desc";
        $res = $MainBase->get('cm_ask',['*'],$where.sprintf(" limit %d,%d",($page-1)*$perPage,$perPage));
        LibTpl::Set('data',$res['data']);
        $askInfo = $res['data'];

        $admiId = array_column($res['data'], 'ask_adm_id');
        if(!empty($admiId))
        {
            // 管理员id与名称映射 
            $res = $MainBase->get('sh_admin',['adm_id','adm_username'],sprintf("and adm_id in (%s)",implode(',', $admiId)));
            $adminName = array_column($res['data'], 'adm_username','adm_id');
            LibTpl::Set('adminName',$adminName);
        }

        
        $memId = array_filter(array_column($askInfo, 'ask_uid'));
        if(!empty($memId))
        {
            //客户与id的映射关系 
            $res = $MainBase->get('sh_user',['u_id','u_name'],sprintf("and u_id in (%s)",implode(',', $memId)));
            $memName = array_column($res['data'], 'u_name','u_id');
            LibTpl::Set('memName',$memName);
        }

        // 分页
        $res = $MainBase->get('cm_ask',['count(*) sum'],"",true);
        LibTpl::Set('count',$res['data']['sum']);
        LibTpl::Set('perPage',$perPage);
        LibTpl::Set('page',$page);

        // meta 
        LibTpl::Set('Params',$Params);
        LibTpl::Set('title', '问答列表-'.mvc::$cfg['shopName']);
        LibTpl::Put();
    }


    /**
     * @method    问答列表
     * @author    xu
     * @copyright 2018-05-17
     */
    function addAsk()
    {

        $MainRule = new MainRule();
        $MainRule->rule('AR02');
        if($_SERVER['REQUEST_METHOD']=='POST')
        {
            // 提交新问题
            $data = array_merge($_POST['basic'],['ask_answer'=>$_POST['txt']]);
            $MainArticle = new MainArticle();
            $res = $MainArticle->addQuestion($data,$_SESSION['_adminid']);
            LibFc::ajaxJsonEncode(['status'=>true, 'data'=>$res['data']]);
        }

        // meta
        LibTpl::Set('title', '问答列表-'.mvc::$cfg['shopName']);
        LibTpl::Put();
    }


    /**
     * @method    编辑问答内容
     * @author    xu
     * @copyright 2018-05-17
     */
    function editAsk()
    {
        $MainRule = new MainRule();
        $MainRule->rule('AR03');
        if($_SERVER['REQUEST_METHOD']=='POST')
        {
            // 编辑
            $MainArticle = new MainArticle();
            $data = array_merge($_POST['basic'],['ask_answer'=>$_POST['txt']]);
            $MainArticle->setQuestion($data,$_SESSION['_adminid']);
            LibFc::ajaxJsonEncode(['status'=>true, 'data'=>true]);
        }
        
        // 获取
        $Params = mvc::$URL_PARAMS;
        $MainBase = new MainBase();
        $res = $MainBase->get('cm_ask',['*'],sprintf("and ask_id=%d",$Params['id']),true);
        LibTpl::Set('data',$res['data']);

        // meta
        LibTpl::Set('title', '问答列表-'.mvc::$cfg['shopName']);
        LibTpl::Put();
    }


    /**
     * @method    删除分类
     * @author    xu
     * @copyright 2018-05-17
     */
    function ajaxDelAsk()
    {
        $MainRule = new MainRule();
        $MainRule->rule('AR04');
        if(empty($_POST['id']) || !LibFc::Int($_POST['id']))
            LibFc::ajaxJsonEncode(['status'=>false, 'data'=>'删除问题参数错误！']);

        $MainArticle = new MainArticle();
        $res = $MainArticle->delQuestion($_POST['id']);

        LibFc::ajaxJsonEncode(['status'=>true, 'data'=>'操作成功！']);
    }

    /**
     * @method    给文章排序
     * @author    xu
     * @copyright 2018-05-17
     */
    function changeArtSeq()
    {
        $MainRule = new MainRule();
        $MainRule->rule('AR13');
        if(empty($_POST['artId']) || !LibFc::Int($_POST['artId']) || empty($_POST['num']) || !LibFc::Int($_POST['num']))
            LibFc::ajaxJsonEncode(['status'=>false, 'data'=>'操作失败，请稍后重试！']);
        $MainBase = new MainBase();
        $MainBase->set('cm_article',['art_seq'=>$_POST['num']],sprintf("and art_id=%d",$_POST['artId']));
        LibFc::ajaxJsonEncode(['status'=>true, 'data'=>'操作成功！']);
    }
        
    /**
     * @method    给问题排序
     * @author    xu
     * @copyright 2018-05-17
     */
    function changeAskSeq()
    {
        $MainRule = new MainRule();
        $MainRule->rule('AR13');
        if(empty($_POST['askId']) || !LibFc::Int($_POST['askId']) || empty($_POST['num']) || !LibFc::Int($_POST['num']))
            LibFc::ajaxJsonEncode(['status'=>false, 'data'=>'操作失败，请稍后重试！']);
        $MainBase = new MainBase();
        $MainBase->set('cm_ask',['ask_seq'=>$_POST['num']],sprintf("and ask_id=%d",$_POST['askId']));
        LibFc::ajaxJsonEncode(['status'=>true, 'data'=>'操作成功！']);
    }


}