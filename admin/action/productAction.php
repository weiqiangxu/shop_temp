<?php
class productAction
{

	/**
	 * @method    配件列表
	 * @author    xu
	 * @copyright 2018-05-16
	 */
    function list()
    {
        $Params = mvc::$URL_PARAMS;
        $MainBase = new MainBase();
        $page = isset($Params['page']) ? (int)$Params['page']:1;
        $perPage = 5;

        $all = [
            'pro_id' => '产品ID',
            'u_realname' => '发布者',
            'pro_status' => '状态',
            'img' => '图片',
            'pro_name1' => '名称['.mvc::$cfg['LANG']['1'].']',
            'pro_name2' => '名称['.mvc::$cfg['LANG']['2'].']',
            'pro_name3' => '名称['.mvc::$cfg['LANG']['3'].']',
            'pro_make1' => '品牌['.mvc::$cfg['LANG']['1'].']',
            'pro_make2' => '品牌['.mvc::$cfg['LANG']['2'].']',
            'pro_make3' => '品牌['.mvc::$cfg['LANG']['3'].']',
            'pro_model1' => '车型['.mvc::$cfg['LANG']['1'].']',
            'pro_model2' => '车型['.mvc::$cfg['LANG']['2'].']',
            'pro_model3' => '车型['.mvc::$cfg['LANG']['3'].']',
            'pro_price' => '价格',
            'check_admin' => '审核人',
            'check_time' => '审核时间',
            'check_remark' => '审核备注',
            'pro_atime' => '添加时间',
            'pro_etime' => '最近编辑'
        ];

        if(empty($Params['show']))
        {
            $show = [
                'pro_id',
                'u_realname',
                'pro_status',
                'img',
                'pro_name1',
                // 'pro_name2',
                // 'pro_name3',
                'pro_make1',
                // 'pro_make2',
                // 'pro_make3',
                'pro_model1',
                // 'pro_model2',
                // 'pro_model3',
                'pro_price',
                // 'check_admin',
                // 'check_time',
                // 'check_remark',
                'pro_atime',
                // 'pro_etime'
            ];
        }
        else
        {
            $show = explode(',', $Params['show']);
        }


        $showWidth = [
            'pro_id' => '90',
            'u_realname' => '50',
            'pro_status' => '120',
            'img' => '100',
            'pro_name1' => '100',
            'pro_name2' => '100',
            'pro_name3' => '100',
            'pro_make1' => '100',
            'pro_make2' => '100',
            'pro_make3' => '100',
            'pro_model1' => '100',
            'pro_model2' => '100',
            'pro_model3' => '100',
            'pro_price' => '100',
            'check_admin' => '100',
            'check_time' => '50',
            'check_remark' => '80',
            'pro_atime' => '120',
            'pro_etime' => '100'
        ];



        // 关键字查找
        $where = '';
        if(!empty(trim($Params['name'])))
        {
            $Params['name'] = trim($Params['name']);
            $where .= sprintf(" and ( pro_name1 like '%%%s%%' ",$Params['name']);//
            $where .= sprintf(" or pro_name1 like '%%%s%%' ",$Params['name']);
            $where .= sprintf(" or pro_name1 like '%%%s%%' ",$Params['name']);
            $where .= sprintf(" or pro_make1 like '%%%s%%' ",$Params['name']);
            $where .= sprintf(" or pro_make1 like '%%%s%%' ",$Params['name']);
            $where .= sprintf(" or pro_make1 like '%%%s%%' ",$Params['name']);
            $where .= sprintf(" or pro_model1 like '%%%s%%' ",$Params['name']);
            $where .= sprintf(" or pro_model2 like '%%%s%%' ",$Params['name']);
            $where .= sprintf(" or pro_model3 like '%%%s%%') ",$Params['name']);
        }
        if(!empty($Params['uid']) && LibFc::Int($Params['uid']))
            $where .= sprintf(" and pro_u_id =%d ",$Params['uid']);
        if(!empty($Params['status']))
            $where .= sprintf(" and pro_status ='%s' ",$Params['status']);

        // 工厂只能看到自己的 1管理员  2工厂 0客户
        if($_SESSION['_style']=='2')
            $where .= sprintf(" and pro_u_id ='%s' ",$_SESSION['_userid']);
        // 客户只能看到已经审核过的
        if($_SESSION['_style']=='0')
            $where .= " and pro_status =1 ";


        // 排序
        $order .= "order by pro_atime desc";
        $res = $MainBase->get('sh_product left join sh_user on u_id=pro_u_id',['sh_product.*','u_realname'],$where.$order.sprintf(" limit %d,%d",($page-1)*$perPage,$perPage));
        $base = $res['data'];
        foreach ($base as $key => $value) {
            $base[$key]['pro_check_detail'] = json_decode($value['pro_check_json'],true);
        }
        // 主图
        $proIds = array_column($base, 'pro_id');
        if(!empty($proIds)){
        	$MainProduct = new MainProduct();
        	$res = $MainProduct->getProductMainPic($proIds);
        	$pic = $res['data'];
        }
        foreach ($base as $k => $v) {
        	$base[$k]['imgUrl']=!empty($pic[$v['pro_id']]['prp_name'])?mvc::$cfg['HOST']['files'].$pic[$v['pro_id']]['prp_path'].'100/'.$pic[$v['pro_id']]['prp_name'].'.'.$pic[$v['pro_id']]['prp_ext']:mvc::$cfg['HOST']['adminUrl'].'static/images/noimg.gif';
        }

        LibTpl::Set('data',$base);

        $ztree = [];
        foreach ($all as $k => $v) {
            $ztree[] = ['id'=>$k,'name'=>$v,'checked'=>in_array($k, $show)];
        }
        LibTpl::Set('ztreeNode',json_encode($ztree));

        // 显示列
        LibTpl::Set('show',$show);
        LibTpl::Set('all',$all);
        LibTpl::Set('showWidth',$showWidth);
        
        $str = [];
        $colids = [];
        if(!empty($Params['show'])){ 
            foreach ($all as $k => $v) {
                if(in_array($k, $show))
                {
                    $str[] = $v;
                }
            }
            $colids = $show;
        }
        LibTpl::Set('str',implode(',', $str));
        LibTpl::Set('colids',implode(',', $colids));
        array_pop($show);
        LibTpl::Set('shows',$show);

        // 分页
        $res = $MainBase->get('sh_product',['count(*) sum'],$where,true);
        LibTpl::Set('count',$res['data']['sum']);
        LibTpl::Set('perPage',$perPage);
        LibTpl::Set('page',$page);

        // 状态枚举 0待审 1已审核 2审核不通过
        LibTpl::Set('proStat',['0'=>'待审','1'=>'已审核','2'=>'审核不通过']);
        LibTpl::Set('proStatColor',['0'=>'gray','1'=>'green','2'=>'red']);
        // meta 
        LibTpl::Set('menu', 'list');
        LibTpl::Set('Params',$Params);
        LibTpl::Set('title', '产品列表');
        LibTpl::Put();
    }
	
    /**
     * @method    删除配件
     * @author    xu
     * @copyright 2018-05-18
     */
    public function deleteProduct()
    {
        $res=['status'=>false,'data'=>'删除失败，请稍后重试'];
        if(!empty($_POST['id']))
        {
            $MainProduct=new MainProduct();
            $res=$MainProduct->delProduct($_POST['id']);
        }
        LibFc::ajaxJsonEncode($res);
    }


	/**
	 * @method    产品添加
	 * @author    xu
	 * @copyright 2018-05-17
	 */
	public function publish()
    {

        if($_SERVER['REQUEST_METHOD']=='POST')
        {            
            $MainBase = new MainBase();
            $dataArr=[];
            //基本信息
            $dataArr['base'] = array_map('trim', $_POST['base']);
            $dataArr['base']['pro_u_id'] = $_SESSION['_userid'];

            // 图片信息
            $dataArr['img']=[];
            $img = array_filter($_POST['img']);
            foreach ($img as $k => $v) {
                $temp=json_decode($v,true);
                $dataArr['img'][$k]=[
                    'prp_path'=>$temp['prp_path'],
                    'prp_name'=>$temp['prp_name'],
                    'prp_ext'=>$temp['prp_ext'],
                    'prp_seq'=>(int)$k,
                    'prp_crc32'=>$temp['prp_crc32'],
                ]; 
            }

            //号码
            $dataArr['num']=[];
            if(!empty($_POST['num']))
            {
                foreach ($_POST['num']['prn_factory'] as $k => $v) 
                {
                    if(!empty(trim($v))&&!empty(trim($_POST['num']['prn_display'][$k])))
                    {
                        if(preg_match('/\w+/',trim($_POST['num']['prn_display'][$k])))
                        {
                            $tArr = [
                                'prn_factory'=>trim($v),
                                'prn_display'=>trim($_POST['num']['prn_display'][$k]),
                            ];
                            $tArr['prm_ismain'] = $_POST['mainNum'] == $k? 1: 0;
                            $dataArr['num'][$k] = $tArr;
                        }
                    }
                }
            }
            
            $MainProduct=new MainProduct();
            $res=$MainProduct->setProduct($dataArr);
            LibFc::Go(mvc::$cfg['HOST']['adminUri'].'product/publish?proId='.$res['data']);
        }

        //获取信息
        $MainBase=new MainBase();
        $Params = mvc::$URL_PARAMS;
        if(LibFc::Int($Params['proId']))
        {
            $MainProduct=new MainProduct();
            $res=$MainProduct->getProduct($Params['proId']);
            LibTpl::Set('data',$res['data']);
            LibTpl::Set('title', '产品编辑');
        }else{
            LibTpl::Set('title', '产品添加');
        }

        LibTpl::Set('Params', $Params);
        LibTpl::Set('menu', 'publish');
        LibTpl::Put();
    }


    /**
     * @method    产品审核
     * @author    xu
     * @copyright 2018-05-17
     */
    public function check()
    {

        if($_SERVER['REQUEST_METHOD']=='POST')
        {            
            $MainBase = new MainBase();
            
        }

        //获取信息
        $MainBase=new MainBase();
        $Params = mvc::$URL_PARAMS;
        LibTpl::Set('menu', 'check');

        if(empty($Params['proId']) || !LibFc::Int($Params['proId']))
            LibTpl::Error('审核配件不存在！');

        // 获取
        $MainProduct=new MainProduct();
        $res=$MainProduct->getProduct($Params['proId']);
        LibTpl::Set('data',$res['data']);
        if($_SESSION['_style']==1)
        {
            LibTpl::Set('title', '产品审核');
        }
        if($_SESSION['_style']==0)
        {
            LibTpl::Set('title', '产品详情');
        }

        // 状态枚举 0待审 1已审核 2审核不通过
        LibTpl::Set('proStat',['0'=>'待审','1'=>'已审核','2'=>'审核不通过']);
        LibTpl::Set('proStatColor',['0'=>'gray','1'=>'green','2'=>'red']);

        LibTpl::Set('Params', $Params);
        LibTpl::Put();
    }


	/**
	 * @method    图片上传
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
                LibFc::ajaxJsonEncode(['status'=>false,'data'=>'文件上传参数错误']);
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
			LibFc::ajaxJsonEncode($data);
		}
	}



    /**
     * @method    产品导入
     * @author    xu
     * @copyright 2018-05-16
     */
	public function batch()
	{
		LibTpl::Set('title', '产品导入');
        LibTpl::Set('menu', 'batch');
		LibTpl::Put();
	}

	/**
	 * @method   批量编辑|导入产品信息
	 * @author   卢
	 * @copyright 2018-05-21
	 */
	public function impProBase()
	{
		$Params = mvc::$URL_PARAMS;
		include(mvc::$cfg['ROOT'].'vendor/PHPExcel/Classes/PHPExcel.php');
        include(mvc::$cfg['ROOT'].'vendor/PHPExcel/Classes/PHPExcel/Writer/Excel5.php');
        
        $file=$_FILES['file']['tmp_name'];
        $file = iconv("utf-8", "gb2312", $file);   //转码  
        if(empty($file) OR !file_exists($file)) {  
            LibFc::ajaxJsonEncode(['status'=>false,'data'=>'文件上传失败']);
            exit();  
        }  
        $objRead = new PHPExcel_Reader_Excel2007();   //建立reader对象  
        if(!$objRead->canRead($file)){  
            $objRead = new PHPExcel_Reader_Excel5();  
            if(!$objRead->canRead($file)){  
                LibFc::ajaxJsonEncode(['status'=>false,'data'=>'请上传Excel类型文件']);
	            exit();  
            }  
        }
        $data = array();  
        switch ($Params['type']) 
        {
        	case 'base':
        		$cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M');
        		break;
        }
        $obj = $objRead->load($file);  //建立excel对象  
        $currSheet = $obj->getSheet(0);   //获取指定的sheet表  
        $columnCnt=count($cellName);
        $rowCnt = $currSheet->getHighestRow();   //获取总行数
        for($_row=2; $_row<=$rowCnt; $_row++){  //读取内容  
            for($_column=0; $_column<$columnCnt; $_column++){  
                $cellId = $cellName[$_column].$_row;  
                $cell=$currSheet->getCell($cellId);
                $cellValue =$cell ->getValue();  
                if($cellValue instanceof PHPExcel_RichText){   //富文本转换字符串  
                    $cellValue = $cellValue->__toString();  
                }
                //日期格式处理
                if($cell->getDataType()==PHPExcel_Cell_DataType::TYPE_NUMERIC){
				       $cellstyleformat=$currSheet->getStyle($cell->getCoordinate())->getNumberFormat();  
				       $formatcode=$cellstyleformat->getFormatCode();  
				       if (preg_match('/^(\[\$[A-Z]*-[0-9A-F]*\])*[hmsdy]/i', $formatcode)) {  
				             $cellValue=gmdate("Y/m/d", PHPExcel_Shared_Date::ExcelToPHP($cellValue));  
				       }else{  
				             $cellValue=PHPExcel_Style_NumberFormat::toFormattedString($cellValue,$formatcode);  
				       }  
				}   
      
                $data[$_row][$cellName[$_column]] = $cellValue;  
            }  
        }

        $MainProduct=new MainProduct();
        switch ($Params['type']) 
        {
        	case 'base':
        		$res=$MainProduct->impoProBase($data,$_SESSION['_userid']);
        		$res['code']=0;
        		break;
        	
        	case 'num':
        		$res=$MainProduct->impoProNum($data);
        		$res['code']=0;
        		break;
        	case 'model':
        		$res=$MainProduct->impoProModel($data);
        		$res['code']=0;
        		break;
        }
        LibFc::ajaxJsonEncode($res);
	}

	/**
	 * @method    批量编辑导入产品图片
	 * @author 卢
	 * @copyright 2018-05-21
	 */
	public function impProImg()
	{
		$Params = mvc::$URL_PARAMS;

		$zip = new ZipArchive;
	    $MainUpload=new MainUpload();


        // print_r($_FILES['file']['tmp_name']);die;
	    // $path=mvc::$cfg['ROOT'].'file/'.$Params['fileName'];
	    if(empty($_FILES) || !file_exists($_FILES['file']['tmp_name']))
	    {
            LibFc::ajaxJsonEncode(['status'=>false,'data'=>'请上传zip类型压缩文件！']);
	    	exit;
	    }
        if(!($_FILES['file']['type']=='application/x-zip-compressed'))
        {
            LibFc::ajaxJsonEncode(['status'=>false,'data'=>'请上传zip类型压缩文件！']);
            exit;
        }
        $file = $_FILES['file'];
        $res = $zip->open($file['tmp_name']);
        if ($res === TRUE) 
        { 
            // $tempDir='temp'.uniqid();
            $tempDir = mvc::$cfg['ROOT'].'temp';
             //解压缩到test文件夹 
            $zip->extractTo($tempDir);
            $zip->close();
            $dir=$tempDir.'/'.current(explode('.', $file['name']));
            $file=scandir($dir);
            $data=[];
            foreach ($file as $v) 
            {
                if($v!='.'&&$v!='..')
                {
                    if(strpos($v, '@'))
                    {
                        $vArr=explode('@', $v);
                        if(!empty(current(explode('.', $vArr[1]))))
                        {
                            $data[$vArr[0]][current(explode('.', $vArr[1]))-1]=$v;
                        }else{
                            $data[$vArr[0]][]=$v;
                        }
                    }
                    else
                    {
                        $vArr=explode('.', $v);
                        $data[$vArr[0]][]=$v;
                    }
                    if(count($data[$vArr[0]])>5)
                    {   //至多五张图
                        array_pop($data[$vArr[0]]);
                    }
                }
            }

            if(!empty($data))
            {
                $MainProduct=new MainProduct();
                $res=$MainProduct->impoProImgs($dir,$data,$_SESSION['_userid']);
                $res['code']=0;
            }
            LibFc::ajaxJsonEncode($res);
        } else { 
            LibFc::ajaxJsonEncode($res);
        }
	}

    /**
     * @method    配件审核
     * @author      xu
     * @copyright 2018-05-21
     */
    public function ajaxCheck()
    {
        if($_SESSION['_style']!=1)
            LibFc::ajaxJsonEncode(['status'=>false,'data'=>'您没有权限审核商品！']);

        if(empty($_POST['data']) || !LibFc::Int($_POST['data']['pro_id']))
            LibFc::ajaxJsonEncode(['status'=>false,'data'=>'参数错误！']);
        $data = $_POST['data'];
        $tmp['pro_status'] = $data['status'];
        $tmp['pro_check_json'] = json_encode(['u_id'=>$_SESSION['_userid'],'u_name'=>$_SESSION['u_realname'],'time'=>time(), 'remark'=>$data['remark']]);

        $MainBase = new MainBase();
        $MainBase->set('sh_product',$tmp,sprintf("and pro_id=%d",$data['pro_id']));
        
        LibFc::ajaxJsonEncode(['status'=>true,'data'=>'操作成功！']);
    }


}