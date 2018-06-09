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

        // 非超级管理员只能看到自己的
        if(!$_SESSION['_super'])
            $where .= sprintf(" and pro_u_id ='%s' ",$_SESSION['_userid']);

        // 排序
        $order .= "order by pro_atime desc";
        $res = $MainBase->get('sh_product left join sh_user on u_id=pro_u_id',['sh_product.*','u_realname'],$where.$order.sprintf(" limit %d,%d",($page-1)*$perPage,$perPage));
        $base = $res['data'];
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

        // 分页
        $res = $MainBase->get('sh_product',['count(*) sum'],$where,true);
        LibTpl::Set('count',$res['data']['sum']);
        LibTpl::Set('perPage',$perPage);
        LibTpl::Set('page',$page);

        // 状态枚举 0待审 1已审核 2审核不通过
        LibTpl::Set('proStat',['0'=>'待审','1'=>'已审核','2'=>'审核不通过']);
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

        if(empty($Params['proId']) || !LibFc::Int($Params['proId']))
            LibTpl::Error('审核配件不存在！');

        // 获取
        $MainProduct=new MainProduct();
        $res=$MainProduct->getProduct($Params['proId']);
        LibTpl::Set('data',$res['data']);
        LibTpl::Set('title', '产品审核');

        LibTpl::Set('Params', $Params);
        LibTpl::Set('menu', 'check');
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




	public function batch()
	{
		LibTpl::Set('title', '产品导入');
        LibTpl::Set('menu', 'batch');
		LibTpl::Put();
	}
	/**
	 * @method    产品导入
	 * @author 卢
	 * @copyright 2018-05-21
	 */
	public function impProBase()
	{
		$MainRule = new MainRule();
		$Params = mvc::$URL_PARAMS;
		switch ($Params['type'])
		{
			case 'base':
        		if(!$MainRule->rule('PR09',false))
		        {
		        	echo json_encode(['code'=>1,'msg'=>'无操作权限','status'=>false]);
		        	exit();
		        }
        		break;
        	case 'num':
        		if(!$MainRule->rule('PR11',false))
		        {
		        	echo json_encode(['code'=>1,'msg'=>'无操作权限','status'=>false]);
		        	exit();
		        }
        		break;
        	case 'model':
        		if(!$MainRule->rule('PR12',false))
		        {
		        	echo json_encode(['code'=>1,'msg'=>'无操作权限','status'=>false]);
		        	exit();
		        }
        		break;
		}
		include(mvc::$cfg['ROOT'].'vendor/PHPExcel/Classes/PHPExcel.php');
        include(mvc::$cfg['ROOT'].'vendor/PHPExcel/Classes/PHPExcel/Writer/Excel5.php');
        
        $file=$_FILES['file']['tmp_name'];
        $file = iconv("utf-8", "gb2312", $file);   //转码  
        if(empty($file) OR !file_exists($file)) {  
            echo json_encode(['code'=>1,'msg'=>'文件上传失败','status'=>false]);
            exit();  
        }  
        $objRead = new PHPExcel_Reader_Excel2007();   //建立reader对象  
        if(!$objRead->canRead($file)){  
            $objRead = new PHPExcel_Reader_Excel5();  
            if(!$objRead->canRead($file)){  
                echo json_encode(['code'=>1,'msg'=>'文件上传失败','status'=>false]);
	            exit();  
            }  
        }
        $data = array();  
        switch ($Params['type']) 
        {
        	case 'base':
        		$cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M');
        		break;
        	case 'num':
        		$cellName = array('A', 'B', 'C');
        		break;
        	case 'model':
        		$cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M','N');
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
        // print_r($data);
        // die;
        $MainProduct=new MainProduct();
        switch ($Params['type']) 
        {
        	case 'base':
        		$res=$MainProduct->impoProBase($data);
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
        echo json_encode($res);
	}
	/**
	 * @method    图片导入
	 * @author 卢
	 * @copyright 2018-05-21
	 */
	public function impProImg()
	{
		$MainRule = new MainRule();
        $MainRule->rule('PR10');
		$Params = mvc::$URL_PARAMS;
		if(!empty($Params['fileName']))
		{
			$zip = new ZipArchive;
		    $MainUpload=new MainUpload();
		    $path=mvc::$cfg['ROOT'].'file/'.$Params['fileName'];
		    if(!file_exists($path))
		    {
		    	$res=[
		    		'code'=>1,
		    		'data'=>'文件不存在',
		    	];
		    	echo json_encode($res);
		    	exit;
		    }
		    $info=pathinfo($path);
		    if($info['extension']!='zip')
		    {
		    	$res=[
		    		'code'=>1,
		    		'data'=>'仅支持.zip后缀文件',
		    	];
		    	echo json_encode($res);
		    	exit;
		    }
		    // print_r($info);
		    // die;
	        $res = $zip->open($path);
	        if ($res === TRUE) 
	        { 
	            // $tempDir='temp'.uniqid();
	            $tempDir=mvc::$cfg['ROOT'].'temp';
	             //解压缩到test文件夹 
	            $zip->extractTo($tempDir);
	            $zip->close(); 
	            $dir=$tempDir.'/'.$info['filename'];
	            $file=scandir($dir);
	            $data=[];
	            foreach ($file as $v) 
	            {
	                if($v!='.'&&$v!='..')
	                {
	                    if(strpos($v, '@'))
	                    {
	                        $vArr=explode('@', $v);
	                        $data[$vArr[0]][]=$v;
	                    }
	                    else
	                    {
	                        $vArr=explode('.', $v);
	                        $data[$vArr[0]][]=$v;
	                    }
	                }
	            }

	            if(!empty($data))
	            {
	                $MainProduct=new MainProduct();
	                $res=$MainProduct->impoProImgs($dir,$data);
	                $res['code']=0;
	            }
	            echo json_encode($res);
	        } else { 
	            echo json_encode($res); 
	        }
		}
	}


}