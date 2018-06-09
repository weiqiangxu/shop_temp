<?php
/**
 * 产品表操作接口
 */
class MainProduct extends MainBase
{
	private $table='sh_product';

	/**不存在的产品将加入购物车**/
	public function getCartProdes($proIdArr, $uid)
	{
		$proArr = [];
		$uid = (int) $uid;
		$res = $this->get('sh_user', ['u_ul_id'], sprintf(" and u_id=%d and u_status=1 and u_order=1", $uid), true);
		$priceLevel = (int) $res['data']['u_ul_id'];

		if(!empty($proIdArr))
		{
			$this->MainDb->Begin();
			$res = $this->get('sh_cart', ['car_pro_id'], sprintf(" and car_u_id=%d and car_pro_id in(%s)", $uid, implode(',', $proIdArr)));
			$hasIdArr = array_column($res['data'], 'car_pro_id');
			$newProArr = array_diff($proIdArr, $hasIdArr);
			foreach($newProArr as $proId)
			{
				$addArr = [
					'car_u_id'=>$uid,
					'car_pro_id'=>$proId,
					'car_count'=>1,
					'car_checked'=>1,
				];
				$this->add('sh_cart', $addArr);
			}
			$this->MainDb->End();
			$where = sprintf(" and car_u_id=%d and car_pro_id in(%s) order by car_id", $uid, implode(',', $proIdArr));
		}
		else
		{
			$where = sprintf(" and car_u_id=%d  order by car_id", $uid);
		}

		$table = sprintf("sh_cart 
		join sh_product on pro_id=car_pro_id and car_u_id=%d 
		left join sh_part on part_id=pro_part_id
		join sh_product_number on prn_pro_id=pro_id and prm_self=1", $uid);
		$res = $this->get($table, ['pro_id, part_name, pro_lab1,pro_url,pro_name, prn_show, pro_price, pro_price_vip1, pro_price_vip2, pro_price_vip3, pro_price_vip4,car_id, car_count, car_checked'], $where);
		$proArr = [];
		if(!empty($res['data']))
		{
			foreach($res['data'] as $v)
			{
				$v['pro_price'] = $v['pro_price_vip'.$priceLevel] > 0 && $v['pro_price_vip'.$priceLevel] < $v['pro_price'] ? $v['pro_price_vip'.$priceLevel]: $v['pro_price'];
				$proArr[$v['pro_id']] = $v;
			}
			//图片
			$MainProduct = new MainProduct();
			$res = $MainProduct->getProductMainPic(array_keys($proArr));
			foreach($res['data'] as $k=>$v)
			{
				$proArr[$k]['img'] = $v;
			}
		}
		
		return LibFc::ReturnData(true, $proArr);
	}
	
	
	
	
	
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
	 * @method    修改分类
	 * @param    arr dataArr['parent'=>[-1=>11111],['child'=>[-1=>[1=>111,2=>2222]]],['label'=>[-1=>[1]=>[1=>111,2=>22]]]]
	 * @author 卢
	 * @copyright 2018-05-17
	 */
	public function setProductPart($dataArr)
	{
		if(is_array($dataArr))
		{
			//获取原来的分类
			$res=$this->get('sh_part',['part_id']);
			$oriArr=array_column($res['data'], 'part_id');
			$editArr=!empty($dataArr['parent'])?array_keys($dataArr['parent']):[];
			if(!empty($dataArr['child']))
			{
				foreach ($dataArr['child'] as $v) 
				{
					if(!empty($v))
					{
						$editArr=array_merge($editArr,array_keys($v));
					}
				}
			}
			$delArr=array_diff($oriArr, $editArr);
			$this->MainDb->Begin();
			if(!empty($dataArr['parent']))
			{
				foreach ($dataArr['parent'] as $k => $v) 
				{
					if(!empty($v))
					{
						$pArr=[
							'part_name'=>$v,
							'part_parent'=>0,
							'part_art_id'=>(!empty($dataArr['artParent'][$k])?$dataArr['artParent'][$k]:0)
						];
						// print_r($pArr);
						// die;
						if($k>0)
						{
							$this->set('sh_part',$pArr,sprintf(' and part_id=%d',$k));
							$pId=$k;
						}else
						{
							$res=$this->add('sh_part',$pArr);
							$pId=$res['data'];
						}
						//二级
						if(!empty($dataArr['plabel'][$k]))
						{
							$lArr=[];
							foreach ($dataArr['plabel'][$k] as $la => $lav) 
							{
								$lArr['part_lab'.$la]=$lav;
							}
							$this->set('sh_part',$lArr,sprintf(' and part_id=%d',$k));
						}

						if(!empty($dataArr['child'][$k]))
						{
							foreach ($dataArr['child'][$k] as $i => $j) 
							{
								if(!empty($j))
								{
									$cArr=[
										'part_name'=>$j,
										'part_parent'=>$pId,
										'part_art_id'=>(!empty($dataArr['art'][$k][$i])?$dataArr['art'][$k][$i]:0)
									];							
									if(!empty($dataArr['label'][$k][$i]))
									{
										foreach ($dataArr['label'][$k][$i] as $la => $lav) 
										{
											$cArr['part_lab'.$la]=$lav;
										}
									}
									if($i>0)
									{
										$this->set('sh_part',$cArr,sprintf(' and part_id=%d',$i));
										$cid=$i;
									}
									else
									{
										$res=$this->add('sh_part',$cArr);
										$cid=$res['data'];
									}
								}
							}
						}
						// echo 123;
						// die;
					}
				}
			}
			if(!empty($delArr))
			{
				$this->del('sh_part',sprintf(' and part_id in(%s)',implode(',',$delArr)));
			}
			$this->MainDb->End();
			return LibFc::ReturnData(true, true);
		}
		return LibFc::ReturnData(false, 'setProductPart 提供的参数不正确');
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
	 * @author 卢
	 * @copyright 2018-05-21
	 */
	public function impoProBase($data)
	{
		if(!empty($data))
		{
			$fieldArr=[
	            'A'=>'pro_wl_code',
	            'B'=>'pro_code',
	            'C'=>'pro_lab1',
	            'E'=>'pro_name',
	            'G'=>'pro_price',
	            'H'=>'pro_price_vip1',
	            'I'=>'pro_price_vip2',
	            'J'=>'pro_price_vip3',
	            'K'=>'pro_count',
	            'L'=>'pro_unit',
	            'M'=>'pro_info'
	        ];
	        $dataArr=[];
			foreach ($data as $k => $v) 
			{
				//获取原来是否有该产品
				if(!empty(trim($v['A'])))
				{
					$where=sprintf(" and pro_wl_code='%s'",trim($v['A']));
				}else if(!empty(trim($v['B'])))
				{
					$where=sprintf(" and pro_code='%s'",trim($v['B']));
				}
				else{
					continue;
				}
				$res=$this->get($this->table,['pro_id'],$where,true);

				$proId=0;
				if(!empty($res['data']))
				{
					$proId=$res['data']['pro_id'];
				}
				//基本信息
				foreach ($v as $cellId => $val) 
				{
					if(isset($fieldArr[$cellId]))
					{
						switch ($fieldArr[$cellId]) 
						{
							case 'pro_price':
							case 'pro_price_vip1':
							case 'pro_price_vip2':
							case 'pro_price_vip3':
								$dataArr[$fieldArr[$cellId]]=(!empty((float)trim($val))?(float)trim($val):0);
							break;
							// case 'pro_code':
							// 	$dataArr[$fieldArr[$cellId]]=LibFc::FormatNum($val);
							// 	break;
							case 'pro_count':
								$dataArr[$fieldArr[$cellId]]=(int)($val);
							break;
							default:
								$dataArr[$fieldArr[$cellId]]=trim($val);
							break;
						}
						
					}
				}
				// print_r($dataArr);
				// die;
				//分类处理
				$where=sprintf(" and part_name='%s'",trim($v['D']));
				$res=$this->get('sh_part',['part_id'],$where,true);
				$partRes=$res['data'];
				$this->MainDb->Begin();
				if($proId>0)
				{
					//修改
					$this->set($this->table,$dataArr,sprintf(' and pro_id=%d',$proId));
				}
				else
				{
					$res=$this->add($this->table,$dataArr);
					$proId=$res['data'];

				}

				// $res = $this->get('sh_product join sh_product_number on pro_id=prn_pro_id and prm_self=1',['pro_code', 'pro_url'],sprintf(' and pro_id=%d',$proId),true);
				// if(empty($res['data']['pro_url']))
				// {
					
				// }
				$url='item_'.LibFc::FormatNum($dataArr['pro_code']).'_'.$proId.'.html';
				$res=$this->set($this->table,['pro_url'=>$url],sprintf(' and pro_id=%d',$proId));
				if(!empty(trim($v['B'])))
				{
					$this->setProductSelfNum($proId, ['prn_display'=>trim($v['B'])]);
				}
				if(!empty($partRes['part_id']))
				{
					$part_id=$partRes['part_id'];
					if(empty($partRes['part_lab1']))
					{
						$this->set('sh_part',['part_lab1'=>'原型号'],sprintf(' and part_id=%d',$part_id));
					}
				}
				else
				{
					//添加分类
					$partArr=[
						'part_name'=>$v['D'],
						'part_lab1'=>'原型号'
					];
					$res=$this->add('sh_part',$partArr);
					$part_id=$res['data'];
				}
				$this->set($this->table,['pro_part_id'=>$part_id],sprintf(' and pro_id=%d',$proId));
				//号码处理
				$numStr=trim($v['F']);
				$reArr=[
					'，'=>',',
					'：'=>':',
					'；'=>';',
					'，'=>',',
					'：'=>':',
					'；'=>';'
				];
				if(!empty($numStr))
				{
					foreach ($reArr as $find => $replace) 
					{
						$numStr=str_replace($find, $replace, $numStr);
					}
					$numArr=explode(';',$numStr);
					// print_r($numArr);
					// die;
					$numArrData=[];
					$index=0;
					foreach ($numArr as $val) 
					{
						$val=explode(':', $val);
						if(!empty($val))
						{
							$where=sprintf(" and prn_factory='%s' and prn_display='%s' and prn_pro_id=%d",trim($val[0]),trim($val[1]),$proId,true);
							$res=$this->get('sh_product_number',['prn_id'],$where,true);
							if($res['data'])
							{
								$numArrData[$res['data']['prn_id']]=[
									'prn_factory'=>trim($val[0]),
									'prn_display'=>trim($val[1]),
									'prn_show'=>trim($val[1])
								];
								continue;
							}
						}
						$numArrData[$index]=[
							'prn_factory'=>trim($val[0]),
							'prn_display'=>trim($val[1]),
							'prn_show'=>trim($val[1])
						];
						$index--;
					}
					$this->setNums($numArrData,$proId);
				}
				$this->MainDb->End();
				
			}
			return LibFc::ReturnData(true, true);
		}
		return LibFc::ReturnData(false, 'impoProBase 提供的参数不正确');
	}
	/**
	 * @method    产品数量导入
	 * @param     [arr]       $data [读取到的excel数据]
	 * @return    [bool]           []
	 * @author 卢
	 * @copyright 2018-05-21
	 */
	public function impoProNum($data)
	{
		if(!empty($data))
		{
			foreach ($data as $v) 
			{
				if(!empty(trim($v['A'])))
				{
					$where=sprintf(" and pro_wl_code='%s'",trim($v['A']));
				}else if(!empty(trim($v['B'])))
				{
					$where=sprintf(" and pro_code='%s'",trim($v['B']));
				}else
				{
					continue;
				}
				$this->set($this->table,['pro_count'=>(int)$v['C']],$where);
			}
			return LibFc::ReturnData(true, true);
		}
		return LibFc::ReturnData(false, 'impoProNum 提供的参数不正确');
	}
	/**
	 * @method    车型导入
	 * @param     [arr]       $data [读取到的excel数据]
	 * @return    [bool]             []
	 * @author 卢
	 * @copyright 2018-05-21
	 */
	public function impoProModel($data)
	{
		if(!empty($data))
		{
			$modArr=[];
			$this->MainDb->Begin();
			foreach ($data as $v) 
			{
				$dataArr=[];
				$v['B']=str_replace('（', '(', $v['B']);
				$v['B']=str_replace('）', ')', $v['B']);
				preg_match('/^(.*\s?\S?)\((.*\s?\S?)\)$/', $v['B'],$temp);
				$dataArr=[
					'mod_brand'=>(!empty($temp[1])?$temp[1]:''),
					'mod_make'=>(!empty($temp[2])?$temp[2]:''),
					'mod_name'=>trim($v['C']),
					'mod_engine'=>trim($v['D']),
					'mod_ml'=>trim($v['E']),
					'mod_kw'=>trim($v['F']),
					'mod_cylinder'=>trim($v['G']),
					'mod_fuel'=>trim($v['H']),
					'mod_start'=>trim($v['I']),
					'mod_end'=>trim($v['J']),
					'mod_drive'=>trim($v['K']),
				];
				$code=md5(implode('', $dataArr));
				//根据唯一标识码查车型
				$res=$this->get('sh_model',['mod_id'],sprintf(" and mod_code='%s'",$code),true);
				if(empty($res['data']))
				{
					$dataArr['mod_brand_code']=(!empty($temp[1])?LibFc::GetInitial($temp[1]):'');
					$dataArr['mod_code']=$code;
					$res=$this->add('sh_model',$dataArr);
					$modId=$res['data'];
				}
				else
				{
					$modId=$res['data']['mod_id'];
				}
				$proDataArr=[];
				$res=$this->get('sh_product',['pro_id'],sprintf(" and pro_code='%s'",trim($v['A'])),true);
				
				if(!empty($res['data']))
				{
					$proId=$res['data']['pro_id'];
					
					$proDataArr=[
						'prm_start'=>trim($v['L']),
						'prm_end'=>trim($v['M']),
						'prm_engine'=>trim($v['N'])
					];
					$res=$this->get('sh_product_model',['prm_id'],sprintf(' and prm_pro_id=%d and prm_mod_id=%d',$proId,$modId));
					if(!empty($res['data']))
					{
						$modArr[$proId][]=$modId;
						$this->set('sh_product_model',$proDataArr,sprintf(' and prm_mod_id=%d',$modId));
					}
					else
					{
						$modArr[$proId][]=$modId;
						$proDataArr['prm_mod_id']=$modId;
						$proDataArr['prm_pro_id']=$proId;
						$this->add('sh_product_model',$proDataArr);
					}
				}
			}
			if(!empty($modArr))
			{
				$proArr=array_keys($modArr);
				$res=$this->get('sh_product_model',['*'],sprintf(' and prm_pro_id in (%s)',implode(',',$proArr)));
				$orData=[];
				if(!empty($res['data']))
				{
					foreach ($res['data'] as $v) 
					{
						$orData[$v['prm_pro_id']][]=$v['prm_mod_id'];
					}
					foreach ($proArr as $proId) 
					{
						$delArr=array_diff($orData[$proId], $modArr[$proId]);
						if(!empty($delArr))
						{
							$this->del('sh_product_model',sprintf( 'and prm_pro_id=%d and prm_mod_id in(%s)',$proId,implode(',',$delArr)));
						}
					}
				}
			}
			$this->MainDb->End();
			return LibFc::ReturnData(true, true);
		}
		return LibFc::ReturnData(false, 'impoProModel 提供的参数不正确');
	}
	/**
	 * @method    导入图片
	 * @param     [压缩包解压目录]      $dir  []
	 * @param     [arr]      $data [Array
									(
									    [4147A12A04] => Array
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
	public function impoProImgs($dir,$data)
	{
		if(!empty($data)&&!empty($dir))
		{
			// print_r($data);
			// die;
			$this->MainDb->Begin();
			$MainUpload=new MainUpload();
			foreach ($data as $code=> $file) 
			{
				$res=$this->get('sh_product',['pro_id'],sprintf(" and pro_code='%s'",$code),true);
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
	                	// print_r($fileArr);
	                	// die;
	                    $res=$MainUpload->uploadFile($fileArr,'pro','image');
	                    if($res['status']&&!empty($res['data']['prp_path']))
	                    {
	                    	@unlink($fileArr['tmp_name']);
	                    	$temp=$res['data'];
	                    	if(!empty($imgArr))
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
					// print_r($dataArr);
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
	public function setModel($modId,$proId)
	{
		if(!empty($modId)&&!empty($proId))
		{
			$res=$this->get('sh_product_model',['prm_mod_id','prm_id'],sprintf(' and prm_pro_id=%d',$proId),true);
			if(!empty($res['data']))
			{
				if($modId!=$res['data']['prm_mod_id'])
				{
					$dataArr=[
						'prm_mod_id'=>$modId,
						'prm_start'=>'',
						'prm_end'=>'',
						'prm_engine'=>''
					];
					$this->set('sh_product_model',$dataArr,sprintf(' and prm_id=%d',$res['data']['prm_id']));
				}
			}
			else
			{
				$this->add('sh_product_model',['prm_pro_id'=>$proId,'prm_mod_id'=>$modId]);
			}
			return LibFc::ReturnData(true, true);
		}
		return LibFc::ReturnData(false, 'setModel 提供的参数不正确');
	}
		/**
	 * @method    字符串转正则
	 * @param     [str]     $preg [4147A12A##]
	 * @return    [arr]           ['preg'=>'/^(\d\d\d\d\w\d\d\w)\w{2}$/','replace'=>'${1}**']
	 * @author 卢
	 * @copyright 2018-05-22
	 */
	function strToPreg($preg)
	{
		$sData='/^';
		$aim='';
		$aIndex=1;
		$exArr=['.','*','^','&','?','\\'];
		if(strpos($preg, '#')!==false)
		{
			if(strpos($preg, '#')!==0)
			{
				$str1='('.substr($preg, 0,strpos($preg, '#')).')';
				$aim.='${'.$aIndex.'}';
				$aIndex++;
				for($i=0;$i<strlen($str1);$i++){
					if($str1{$i}==1){
						$sData.='\\d';
					}else if($str1{$i}==='A'){
						$sData.='\\w';
					}else if(in_array($str1{$i}, $exArr)){
						$sData.='\\'.$str1{$i};
					}
					else{
						$sData.=$str1{$i};
					}
				}
				$str2=substr($preg, strpos($preg, '#'));
			}else{
				$str2=$preg;
			}
			
			while (strpos($str2, '#')!==false) {
				$num=substr_count($str2, '#');
				$wNum=0;
				$index=0;
				
				for($i=0;$i<$num;$i++){
					if($str2{$i}=='#'){
						$wNum++;
					}
					if(empty($str2{$i+1})||$str2{$i+1}!='#'){
						$index=$i;
						break;
					}
				}
				$sData.='\w{'.$wNum.'}';
				$aim.=str_repeat(' ', $wNum);
				$str2=substr($str2, $index+1);//-11-1#-1##A
				if(strpos($str2, '#')!==false){
					$aim.='${'.$aIndex.'}';
					$aIndex++;
					$temp='('.substr($str2, 0,strpos($str2, '#')).')';//-11-1
					for($i=0;$i<strlen($temp);$i++){
						if($temp{$i}==1){
							$sData.='\\d';
						}else if($temp{$i}==='A'){
							$sData.='\\w';
						}else if(in_array($temp{$i}, $exArr)){
							$sData.='\\'.$temp{$i};
						}
						else{
							$sData.=$temp{$i};
						}
					}
					$str2=substr($str2, strpos($str2, '#'));
				}
			}
		}else{
			$str2=$preg;
		}
		if(strlen($str2))
		{
			$aim.='${'.$aIndex.'}';
			$str2='('.$str2.')';
			for($i=0;$i<strlen($str2);$i++){
				if($str2{$i}==1){
					$sData.='\\d';
				}else if($str2{$i}==='A'){
					$sData.='\\w';
				}else if(in_array($str2{$i}, $exArr)){
					$sData.='\\'.$str2{$i};
				}
				else{
					$sData.=$str2{$i};
				}
			}
		}
		$sData.='$/';
		return ['preg'=>$sData,'replace'=>$aim];
	}
	/**
	 * @method    根据自编号转换为显示号码
	 * @param     [str]     $numStr [4147A12A04]
	 * @return    [arr]             [['status'=>true,'data'=>'4147A12A**']]
	 * @author 卢
	 * @copyright 2018-05-22
	 */
	public function pregStr($numStr)
	{
		$path=mvc::$cfg['ROOT'].'mod_main/lib/show.cofig.php';
		if(file_exists($path))
		{
			$rule=include($path);
			if(!empty($rule))
			{
				$ruleArr=[];
				foreach ($rule as $v) 
				{
					$ruleArr[]=$this->strToPreg($v);
				}
				if(!empty($ruleArr))
				{
					foreach ($ruleArr as $v) 
					{
						if(preg_match($v['preg'],$numStr))
						{
							$numStr=preg_replace($v['preg'], $v['replace'], $numStr);
							break;
						}
					}
				}
				// print_r($ruleArr);
			}
		}
		return LibFc::ReturnData(true, $numStr);
	}
	/**
	 * @method    刷新自编号
	 * @author 卢
	 * @copyright 2018-05-22
	 */
	public function refreshNum()
	{
		$res=$this->get('sh_product_number',['*'],' and prm_self=1');
		if(!empty($res['data']))
		{
			foreach ($res['data'] as $v) 
			{
				$showNum=$this->pregStr($v['prn_display']);
				$dataArr=['prn_show'=>$showNum['data']];
				$this->set('sh_product_number',$dataArr,sprintf(' and prn_id=%d',$v['prn_id']));
			}
		}
		return LibFc::ReturnData(true, true);
	}
	/**
	 * @method    获取销量top
	 * @param     int    $limit [限定条数]
	 * @return    [arr]            [0] => Array([count] => 1 [pro_name] => 报警器)
	 * @author 卢
	 * @copyright 2018-05-23
	 */
	public function getSaleTop($limit=10)
	{
		$where=' and ord_status>0 group by orp_pro_id order by count desc limit '.$limit;
		$res=$this->get('sh_order left join sh_order_product on ord_id=orp_ord_id inner join sh_product on pro_id=orp_pro_id',['orp_pro_id','count(1) count'],$where);
		$saleArr=$res['data'];
		// print_r($res);
		$data=[];
		$proIdArr=array_column($saleArr, 'orp_pro_id');
		if(!empty($saleArr))
		{
			$res=$this->get('sh_product',['pro_name','pro_id'],sprintf(' and pro_id in(%s)',implode(',',$proIdArr)));
			$proArr=array_column($res['data'],null, 'pro_id');
			foreach ($saleArr as $v) 
			{
				$data[]=['count'=>$v['count'],'pro_name'=>$proArr[$v['orp_pro_id']]['pro_name']];
			}
		}
		if(count($proIdArr)<$limit)
		{
			$where=(count($proIdArr))?sprintf(' and pro_id not in(%s) order by pro_id desc',implode(',',$proIdArr)):' order by pro_id desc';
			$res=$this->get('sh_product',['pro_name','pro_id'],$where);
			if(!empty($res['data']))
			{
				foreach ($res['data'] as $v) 
				{
					$data[]=['pro_name'=>$v['pro_name'],'count'=>0];
				}
			}
		}

		return LibFc::ReturnData(true, $data);
	}
	/**
	 * @method    获取浏览量TOP
	 * @param     Int    $limit [description]
	 * @return    [arr]            [description]
	 * @author 卢
	 * @copyright 2018-05-25
	 */
	public function getViewTop($limit=10)
	{
		$where=' order by pro_view desc';
		$where.=sprintf(' limit %d',$limit);
		$res=$this->get('sh_product',['pro_name','pro_id','pro_view'],$where);
		return $res;
	}
	/**
	 * @method    获取收藏量TOP
	 * @param     Int    $limit [description]
	 * @return    [arr]            [description]
	 * @author 卢
	 * @copyright 2018-05-25
	 */
	public function getCollTop($limit=10)
	{
		$where=' group by sc_pro_id order by count desc';
		$where.=sprintf(' limit %d',$limit);
		$res=$this->get('sh_user_coll inner join sh_product on pro_id=sc_pro_id',['sc_pro_id','count(1) count'],$where);
		$proIdArr=$res['data'];
		$data=[];
		$proId=array_column($proIdArr, 'sc_pro_id');
		if(!empty($proIdArr))
		{
			$res=$this->get('sh_product',['pro_name','pro_id'],sprintf(' and pro_id in(%s)',implode(',', $proId)));
			$proArr=array_column($res['data'],null, 'pro_id');
			foreach ($proIdArr as $v) 
			{
				$data[]=['pro_name'=>$proArr[$v['sc_pro_id']]['pro_name'],'count'=>$v['count']];
			}
		}
		if(count($proId)<$limit)
		{
			$where=(count($proId))?sprintf(' and pro_id not in(%s) order by pro_id desc',implode(',', $proId)):' order by pro_id desc';
			$res=$this->get('sh_product',['pro_name','pro_id'],$where);
			if(!empty($res['data']))
			{
				foreach ($res['data'] as $v) 
				{
					$data[]=['pro_name'=>$v['pro_name'],'count'=>0];
				}
			}
		}
		return LibFc::ReturnData(true, $data);
	}
	public function getBuyerTop($limit=10)
	{
		$where=' and ord_status>0 group by ord_u_id order by count desc';
		$where.=sprintf(' limit %d',$limit);
		$res=$this->get('sh_order left join sh_user a on u_id=ord_u_id',['sum(ord_money) count','ord_u_id','a.*'],$where);
		return $res;
	}
}