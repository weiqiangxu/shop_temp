<?php
/**
 * 产品表操作接口
 */
class MainProduct extends MainBase
{
	public $table = 'sh_product';
	/**
	  * @method 修改或添加产品
	  * @author xu
	  * @copyright 2018-5-17
	  * @return [["status"]=> bool(true) ["data"]=> ["code"]=> int(0) ]
	  */
	public function setProduct($dataArr)
	{	
		if(empty($dataArr)|| !is_array($dataArr))
			return LibFc::ReturnData(false,'setProduct参数不正确');

		$this->MainDb->Begin();

		if(!empty($dataArr['base']))
		{
			if(!empty($dataArr['base']['pro_id']))
			{
				//修改
				$dataArr['base']['pro_etime'] = time();
				$res=$this->set('sh_product',$dataArr['base'],sprintf(' and pro_id=%d',$dataArr['base']['pro_id']));
				$proId=$dataArr['base']['pro_id'];
			}
			else
			{	
				// 新增
				unset($dataArr['base']['pro_id']);
				$dataArr['base']['pro_atime'] = time();
				$res=$this->add('sh_product',$dataArr['base']);
				$proId=$res['data'];
			}
		}


		if(isset($dataArr['img']))
		{
			// 配件图片
			$this->setProPic($dataArr['img'],$proId);
		}

		if(isset($dataArr['num']))
		{
			// 配件号码
			$this->setNums($dataArr['num'],$proId);
		}

		$this->MainDb->End();
		return LibFc::ReturnData(true,$proId);

	}
	/**
	  * @method 修改配件的图片
	  * @param  $dataArr arr  图片字段数组
	  * @param  $proId  int  产品ID
	  * @author xu
	  * @copyright 2018-5-17
	  * @return 
	  * 成功 ["status"=>true, "data"=>true ]
	  * 失败 ["status"=>false, "data"=>失败原因 ]
	  */
	public function setProPic($dataArr,$proId)
	{
		if(empty($dataArr) || !is_array($dataArr) ||!LibFc::Int($proId))
			return LibFc::ReturnData(false,'setProPic参数不正确');

		$picIdArr=array_keys($dataArr);

		$delArr=[];
		$delImgArr=[];
		$oriIdArr=[];

		if(!empty($picIdArr))
		{
			//获取原来的
			$res=$this->get('sh_product_pic',['*'],sprintf(' and prp_pro_id=%d and prp_id in (%s)',$proId,implode(',',$picIdArr)));
			$oriArr=array_column($res['data'],null, 'prp_id');
			$oriIdArr=array_column($res['data'], 'prp_id');				
		}
		$this->MainDb->Begin();


		foreach ($dataArr as $k=> $v) 
		{
			if(empty($v)||empty($v['prp_name']))
			{
				if(in_array($k, $oriIdArr))
				{
					$delArr[]=$k;//需要删除的图片记录ID
					$delImgArr[]=$k;//需要删除的图片文件ID
				}
				continue;
			}
			if(in_array($k, $oriIdArr))
			{
				$this->set('sh_product_pic',$v,sprintf(' and prp_id=%d and prp_pro_id=%d',$k,$proId));
				if(!empty($v['prp_crc32'])&&$v['prp_crc32']!=$oriArr[$k]['prp_crc32'])
				{
					$delImgArr[]=$k;//需要删除的图片文件ID
				}
			}
			else
			{
				$v['prp_pro_id']=$proId;
				$this->add('sh_product_pic',$v);
			}
		}
		//记录删除
		if(!empty($delArr))
		{
			$this->del('sh_product_pic',sprintf(' and prp_pro_id=%d and prp_id in(%s)',$proId,implode(',',$delArr)));
		}

		$this->MainDb->End();
		//文件删除
		if(!empty($delImgArr)&&!empty($oriIdArr))
		{
			$MainUpload=new MainUpload();
			// $oriIdArr=array_column($oriIdArr, null,'prp_id');
			foreach ($delImgArr as $v) 
			{
				if(isset($oriArr[$v]))
				{
					$fileName=$oriArr[$v]['prp_path'].$oriArr[$v]['prp_name'].'.'.$oriArr[$v]['prp_ext'];
					// echo $fileName,'<br/>';
					$MainUpload->deleteByName($fileName,'pro');
				}
			}
		}
		return LibFc::ReturnData(true,true);
	}
	/**
	  * @method 修改配件的号码
	  * @param  $proId  int  产品ID
	  * @param  $numArr  array  [11=>['factory'=>'', 'display'=>''....]....]
	  * @author 卢
	  * @copyright 2018-5-17
	  * @return 
	  * 成功 ["status"=>true, "data"=>true ]
	  * 失败 ["status"=>false, "data"=>失败原因 ]
	  */
	public function setNums($numArr,$proId)
	{
		$proId = (int) $proId;
		if($proId < 1 || !is_array($numArr))
		{
			return LibFc::ReturnData(false, 'setNums 提供的参数不正确');
		}
		$res = $this->get('sh_product_number', ['distinct prn_id'], sprintf(" and prn_pro_id=%d", $proId));
		$oldIdArr = array_column($res['data'], 'prn_id');
		//检测数据
		foreach($numArr as $k=>$v)
		{
			if(trim($v['prn_factory'])==''||trim($v['prn_display'])==''||($k>0&&!in_array($k,$oldIdArr)))
			{
				unset($numArr[$k]);
				continue;
			}
			$v['prm_format'] = LibFc::FormatNum($v['prn_display']);
			$uniStr = LibFc::formatStr($v['prn_factory'], 1).'@'.$v['prm_format'];
			$uniArr[$uniStr][] = $k;
			$numArr[$k] = $v;
		}
		if(count($uniArr))
		{
			//检测数据唯一性 num_factory@num_format
			foreach($uniArr as $$uniStr=>$v)
			{
				if(count($v) > 1)
				{
					foreach($v as $kk=>$numId)
					{
						if($kk > 0)
						{
							unset($numArr[$kk]);
						}
					}
				}
			}
		}
		// print_r($numArr);
		// die;
		$this->MainDb->Begin();

		$delIdArr = array_diff($oldIdArr, array_keys($numArr));
		// print_r($oldIdArr);
		// die;
		if(!empty($delIdArr))
		{
			$this->del('sh_product_number', sprintf(" and prn_pro_id=%d and prn_id in (%s)", $proId, implode(',', $delIdArr)));
		}

		foreach($numArr as $numId=>$v)
		{
			if($numId > 0)
			{
				$this->set('sh_product_number', $v, sprintf(" and prn_pro_id=%d and prn_id=%d", $proId, $numId));
			}
			else
			{
				$v['prn_pro_id'] = $proId;
				$this->add('sh_product_number', $v);
			}
		}
		$this->MainDb->End();
		return LibFc::ReturnData(true, true);
	}
	/**
	  * @method 将配件的商家编号加入到号码表
	  * @param  $proId  int  产品ID
	  * @param  $dataArr  array  ['factory'=>'', 'display'=>'']
	  * @author 卢
	  * @copyright 2018-5-17
	  * @return 
	  * 成功 ["status"=>true, "data"=>true ]
	  * 失败 ["status"=>false, "data"=>失败原因 ]
	  */
	private function setProductSelfNum($proId, $dataArr)
	{
		$proId = (int) $proId;
		if($proId < 1 || !is_array($dataArr))
		{
			return LibFc::ReturnData(false, 'setProductSelfNum 提供的参数不正确');
		}
		

		if(!empty($dataArr['prn_display']))
		{
			$setArr = [
				'prn_pro_id'=>$proId,
				'prn_factory'=>mvc::$cfg['factory'],
				'prn_display'=>$dataArr['prn_display'],
				'prm_format'=>LibFc::FormatNum($dataArr['prn_display']),
				'prm_self'=>1
			];
			$showNum=$this->pregStr($setArr['prn_display']);
			$setArr['prn_show']=$showNum['data'];
			$this->set('sh_product_number',$dataArr,sprintf(' and prn_id=%d',$v['prn_id']));
		}
		$this->MainDb->Begin();
		$res = $this->get('sh_product_number', ['prn_id'], sprintf(" and prn_pro_id=%d and prm_self=1", $proId), true);
		$numId = (int) $res['data']['prn_id'];
		if(!empty($setArr))
		{
			if($numId > 0)
			{
				$this->set('sh_product_number', $setArr, sprintf(" and prn_id=%d", $numId));
			}
			else
			{
				$this->add('sh_product_number', $setArr);
			}
		}
		else
		{
			$this->del('sh_product_number', sprintf(" and prn_id =%d", $numId));
		}
		$this->MainDb->End();
		return LibFc::ReturnData(true);
	}

	/**
	 * @method    获取产品图片
	 * @param     [产品ID]        $proId [int?arr]
	 * @return    arr
	 * @author 卢
	 * @copyright 2018-05-17
	 */
	public function getProductPic($proId)
	{
		if(!empty($proId))
		{
			if(is_array($proId))
			{
				$res=$this->get('sh_product_pic',['*'],sprintf(' and prp_pro_id in(%s) order by prp_seq asc,prp_id asc',implode(',',$proId)));
				if(!empty($res['data']))
				{
					$data=[];
					foreach ($res['data'] as $v) 
					{
						$data[$v['prp_pro_id']][]=$v;
					}
				}
				$res['data']=$data;
			}
			else
			{
				$res=$this->get('sh_product_pic',['*'],sprintf(' and prp_pro_id=%d order by prp_seq asc',$proId));
			}
			return $res;
		}
		return LibFc::ReturnData(false, 'getProductPic 提供的参数不正确');
	}


	/**
	 * @method    获取产品主图图片
	 * @param  $proId int|array 支持多配件ID
	 * @author soul
	  * @copyright 2017/6/5
	  * @return 	  
			$proIdArr 为 int  ["status"=>true, "data"=>[pp_pro_id=>100029, spi_id=>1, spi_path=>shop1/product/, spi_name=>4936e7662bab3151.jpg] ]
			$proIdArr 为 array  ["status"=>true, "data"=>[ 100029=>[pp_pro_id=>100029, spi_id=>1, spi_path=>shop1/product/, spi_name=>4936e7662bab3151.jpg]], ....]]
	  */
	public function getProductMainPic($proIdArr)
	{
		$returnArr = [];
		if(!empty($proIdArr))
		{
			$arrFlag = true;
			if(is_array($proIdArr))
			{
				foreach($proIdArr as $k=>$v)
				{
					$proIdArr[$k] = (int) $v;
				}
				$table = sprintf("sh_product_pic AS a JOIN ( SELECT prp_pro_id, MIN(prp_seq) prp_seq FROM sh_product_pic where prp_pro_id in (%s) GROUP BY prp_pro_id) b ON a.prp_pro_id=b.prp_pro_id AND a.prp_seq=b.prp_seq", implode(',', $proIdArr));
				$res = $this->get($table, ['*'], '');
				foreach($res['data'] as $v)
				{
					$returnArr[$v['prp_pro_id']] = $v;
				}
			}
			else
			{
				$where = sprintf(" and prp_pro_id =%d order by prp_seq limit 1", (int) $proIdArr);
				$res = $this->get('sh_product_pic', ['*'], $where, true);
				$returnArr = $res['data'];
			}
		}
		return LibFc::ReturnData(true, $returnArr);
	}



	// 获取配件信息
	public function getProduct($proId)
	{
		if(LibFc::Int($proId))
		{
			//基本信息
			$res=$this->get('sh_product',['*'],sprintf(' and pro_id=%d',$proId),true);
			$dataArr['base']=$res['data'];
			$dataArr['base']['pro_check_detail']=json_decode($res['data']['pro_check_json'],true);
			//号码
			$res=$this->get('sh_product_number',['*'],sprintf(' and prn_pro_id=%d',$proId));
			$dataArr['nums']=$res['data'];
			//图片
			$res=$this->getProductPic($proId);
			$dataArr['images']=$res['data'];
			return LibFc::ReturnData(true,$dataArr);
		}
		return LibFc::ReturnData(false, 'getProduct 提供的参数不正确');
	}


	/**
	 * @method    获取配件
	 * @param     [int|arr]     $proArr [产品ID]
	 * @return                 
	 * @author 	   xu
	 * @copyright 2018-05-24
	 */
	public function getProductInfo($proIds)
	{
		if(!is_array($proIds))
			$proIds = [$proIds];

		if(empty($proIds) || empty($proIds = array_filter($proIds,'LibFc::Int')))
			return LibFc::ReturnData(false, 'getProductInfo 提供的参数不正确');

		$res = $this->get('sh_product',['pro_name','pro_price','pro_id','pro_url','pro_price_vip1','pro_price_vip2','pro_price_vip3','pro_price_vip4'],sprintf("and pro_id in (%s)",implode(',',$proIds)));
		$base = $res['data'];

		//图片
		$res=$this->getProductMainPic($proIds);
		$pic = $res['data'];

		foreach($base as $k => $v){
			$base[$k] = empty($pic[$v['pro_id']])?$v:array_merge($v,$pic[$v['pro_id']]);
		}

		// 顺序恢复
		$base = array_column($base, NULL ,'pro_id');
		$newbase = [];
		foreach ($proIds as $id) {
			$newbase[] = $base[$id];
		}
		return LibFc::ReturnData(true, $newbase);
	}




	/**
	 * @method    删除产品
	 * @param     [int|arr]     $proArr [产品ID]
	 * @return    [bool]             
	 * @author 卢
	 * @copyright 2018-05-21
	 */
	public function delProduct($proArr)
	{
		if(!empty($proArr))
		{
			$proArr=is_array($proArr)?array_map('LibFc::Int', $proArr):[(int)$proArr];
			if(!empty($proArr))
			{
				$res=$this->get('sh_product_pic',['*'],sprintf(' and prp_pro_id in(%s)',implode(',',$proArr)));
				$this->MainDb->Begin();
				$this->del('sh_product',sprintf(' and pro_id in(%s)',implode(',',$proArr)));
				$this->del('sh_product_number',sprintf(' and prn_pro_id in(%s)',implode(',',$proArr)));
				$this->del('sh_product_pic',sprintf(' and prp_pro_id in(%s)',implode(',',$proArr)));
				$this->MainDb->End();
				//文件删除
				if(!empty($res['data']))
				{
					$MainUpload=new MainUpload();
					foreach ($res['data'] as $v) 
					{
						$fileName=$v['prp_path'].$v['prp_name'].'.'.$v['prp_ext'];
						$MainUpload->deleteByName($fileName,'pro');
					}
				}
			}
			return LibFc::ReturnData(true,true);
		}
		return LibFc::ReturnData(false, 'delProduct 提供的参数不正确');
	}
	/**
	 * @method    产品导入
	 * @param     [type]      $data [description]
	 * @return    [type]            [description]
	 * @author    卢
	 * @copyright 2018-05-21
	 */
	public function impoProBase($data,$uid)
	{
		if(!empty($data))
		{
			$fieldArr=[
	            'A'=>'pro_id',
	            'B'=>'pro_name1',
	            'C'=>'pro_name2',
	            'D'=>'pro_name3',
	            'E'=>'pro_make1',
	            'F'=>'pro_make2',
	            'G'=>'pro_make3',
	            'H'=>'pro_model1',
	            'I'=>'pro_model2',
	            'J'=>'pro_model3',
	            'K'=>'pro_price',
	            'L'=>'number',
	            'M'=>'main'
	        ];
	        $dataArr=[];

			foreach ($data as $k => $v) 
			{
				//获取原来是否有该产品
				$proId=0;
				if(!empty(trim($v['A'])) && LibFc::Int(trim($v['A'])))
				{
					$where=sprintf(" and pro_id='%s' and pro_u_id=%d",trim($v['A']),$uid);
					$res=$this->get($this->table,['pro_id'],$where,true);
					if(!empty($res['data']))
					{
						$proId=$res['data']['pro_id'];
					}
				}elseif((empty($v['B'])&&empty($v['C']&&empty($v['D'])))||empty($v['K'])||!is_numeric($v['K']) || sprintf("%.2f",$v['K'])=='0.00'){
					return ['status'=>false,'data'=>'第'.$k.'行的数据错误！价格不能为空并且必须是数字和名称至少有一个。'];
					continue;
				}

				// 互换号码
				$numbers = [];
				//基本信息
				foreach ($v as $cellId => $val) 
				{
					if(isset($fieldArr[$cellId]))
					{
						switch ($fieldArr[$cellId]) 
						{
							case 'pro_price':
								$dataArr[$fieldArr[$cellId]]=(!empty((float)trim($val))?(float)trim($val):0);
							break;
							// case 'pro_code':
							// 	$dataArr[$fieldArr[$cellId]]=LibFc::FormatNum($val);
							// 	break;
							case 'number':	
								$val = str_replace("：", ':', $val);
								$val = str_replace("；", ';', $val);
								$val = str_replace('，', ',', $val);
								$val = explode(';', $val);

								$m = str_replace("：", ':', $v['M']);

								$tmp = [];
								foreach ($val as $kk => $vv) {
									if($vv==$m){
										continue;
									}
									if(count(explode(':', $vv))==2)
									{
										$tmp[] = [
											'prn_factory'=> trim(current(explode(':', $vv))),
											'prn_display'=>trim(end(explode(':', $vv)))
										];
									}
								}
								if(!empty($tmp))
								{
									$numbers = $tmp;
								}
							break;
							case 'main':
								$val = str_replace("：", ':', $val);
								$tmp = [];
								if(count(explode(':', $val))==2)
								{
									$tmp['prn_factory']=trim(current(explode(':', $val)));
									$tmp['prn_display']=trim(end(explode(':', $val)));
									$tmp['prm_ismain']=1;
								}
								$numbers[] = $tmp;
							break;
							default:
								$dataArr[$fieldArr[$cellId]]=trim($val);
							break;
						}
						
					}
				}
				$this->MainDb->Begin();
				
				if($proId>0)
				{
					//修改
					$this->set($this->table,$dataArr,sprintf(' and pro_id=%d',$proId));
				}
				else
				{
					unset($dataArr['pro_id']);
					$dataArr['pro_u_id']=$uid;
					$dataArr['pro_atime']=time();
					$dataArr['pro_etime']=time();
					$res=$this->add($this->table,$dataArr);
					$proId=$res['data'];

				}
				$numArrData = [];
				$index=0;
				foreach ($numbers as $val) 
				{

					$where=sprintf(" and prn_factory='%s' and prn_display='%s' and prn_pro_id=%d",$val['prn_factory'],$val['prn_display'],$proId,true);
					$res=$this->get('sh_product_number',['prn_id'],$where,true);
					if(!empty($res['data']))
					{
						$numArrData[$res['data']['prn_id']]=[
							'prn_factory'=>$val['prn_factory'],
							'prn_display'=>$val['prn_display']
						];
						continue;
					}

					$numArrData[$index]=[
						'prn_factory'=>$val['prn_factory'],
						'prn_display'=>$val['prn_display'],
						'prm_ismain'=>(!empty($val['prm_ismain'])&&$val['prm_ismain']==1)?1:0
					];
					$index--;
				}
				$this->setNums($numArrData,$proId);
				
				$this->MainDb->End();
				
			}
			return LibFc::ReturnData(true, true);
		}
		return LibFc::ReturnData(false, 'impoProBase 提供的参数不正确');
	}


	/**
	 * @method    导入图片
	 * @param     [压缩包解压目录]      $dir  []
	 * @param     [arr]      $data [Array
									(
									    [123321] => Array
									        (
									            [0] => 1.jpg
									            [1] => 2.jpg
									            [2] => 3.jpg
									            [3] => 4.jpg
									            [4] => 5.jpg
									        )

									)]
	 * @return    [arr]            
	 * @author 卢
	 * @copyright 2018-05-21
	 */
	public function impoProImgs($dir,$data,$uid)
	{
		if(!empty($data)&&!empty($dir)&&LibFc::Int($uid))
		{
			// print_r($data);
			// die;
			$this->MainDb->Begin();
			$MainUpload=new MainUpload();
			foreach ($data as $code=> $file) 
			{
				$res=$this->get('sh_product',['pro_id'],sprintf(" and pro_id=%d and pro_u_id=%d",$code,$uid),true);
				if(!empty($res['data']['pro_id']))
				{
					$proId=$res['data']['pro_id'];
					$res=$this->getProductPic($proId);
					$imgArr=$res['data'];
					$dataArr=[];
					foreach ($file as $k => $v) 
					{
						$fileArr=[
	                		'name'=>$v,
	                		'size'=>filesize($dir.'/'.$v),
	                		'tmp_name'=>$dir.'/'.$v,
	                		'data'=>1
	                	];
	                	
	                    $res=$MainUpload->uploadFile($fileArr,'pro','image');
	                    if($res['status']&&!empty($res['data']['prp_path']))
	                    {
	                    	@unlink($fileArr['tmp_name']);
	                    	$temp=$res['data'];

	                    	if(!empty($imgArr[$k]))
	                    	{
	                    		$dataArr[$imgArr[$k]['prp_id']]=[
									'prp_path'=>$temp['prp_path'],
									'prp_name'=>$temp['prp_name'],
									'prp_ext'=>$temp['prp_ext'],
									'prp_seq'=>(int)$k,
									'prp_crc32'=>$temp['prp_crc32'],
								];
	                    	}else
	                    	{
	                    		$dataArr[$k]=[
									'prp_path'=>$temp['prp_path'],
									'prp_name'=>$temp['prp_name'],
									'prp_ext'=>$temp['prp_ext'],
									'prp_seq'=>(int)$k,
									'prp_crc32'=>$temp['prp_crc32'],
								];
	                    	}
	                    }
					}
					$this->setProPic($dataArr,$proId);
				}
			}
			// die;
			$this->MainDb->End();
			LibDir::DeleteDir(mvc::$cfg['ROOT'].'/temp');
			return LibFc::ReturnData(true, true);

		}
		else
		{
			LibDir::DeleteDir(mvc::$cfg['ROOT'].'/temp');
			return LibFc::ReturnData(false, 'impoProImgs 提供的参数不正确');
		}
	}

}