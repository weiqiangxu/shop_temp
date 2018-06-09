<?php
/**
 * 文件上传类
 */

class MainUpload
{
	protected $pathArr=['article','pro'];//文件目录
	protected $url;
	protected $basePath;//文件上传根目录
	protected $size=[
	    'image'=>3*1024*1024,
	    'file'=>3*1024*1024*1024
	    ];//大小
	protected $limitArr=[//类型
		'image'=>'jpeg|jpg|gif|png',
	];


	public function __construct($basePath='')
	{
		$this->basePath=!empty($basePath)?$basePath:$GLOBALS['config']['ROOT'].'admin/files/';
		$this->url=$GLOBALS['config']['HOST']['files'];
	}

	/**
	 * @method    文件上传
	 * @param     [$_FILES['file']]    ['name','size','tmp_name']
	 * @param     string     $path    [目录]
	 * @param     string     $type    [类型  image|file]
	 * @return    [type]              [status] => 1
		    [data] => Array
		        (
		            [prp_path] => pro/442/
		            [prp_name] => 5afbf3c9ca4cc
		            [prp_ext] => jpg
		            [prp_crc32] => 24228DD4
		            [url] => http://localhost/shop_temb/home/files/pro/442/5afbf3c9ca4cc.jpg
		        )
	 * @author 卢
	 * @copyright 2018-05-16
	 */
	public function uploadFile($fileArr,$path='pro',$type='image')
	{
		if(empty($fileArr)||!in_array($path, $this->pathArr)||!in_array($type, array_keys($this->limitArr)))
		{
			return LibFc::ReturnData(false, '提供的参数不正确。');
		}
		if(!isset($fileArr['name']))
		{
			return LibFc::ReturnData(false, '获取数据失败，请重新上传。');
		}
		//尺寸判断
		$maxSize =$this->size[$type];
		if(empty($fileArr['size'])||($fileArr['size']>$maxSize))
		{
			return LibFc::ReturnData(false, '文件大小超过'.floor($maxSize).'KB，上传失败。');
		}
		//类型判断
		$typeArr=$this->limitArr[$type];
		$typeArr=explode('|', $typeArr);
		$FileNameArr = LibFc::GetPathInfo($fileArr['name']);
		if($FileNameArr === false)
		{
			return LibFc::ReturnData(false, '获取文件名或者后缀失败。');
		}
		if(!in_array($FileNameArr['ext'], $typeArr))
		{
			return LibFc::ReturnData(false, '不允许的文件类型。');
		}
		$ext = $FileNameArr['ext'];
		$saveName = strtolower(uniqid());
		switch ($path) 
		{
			case 'article':
				$savePath = 'article/'.date('Ym',time()).'/';
				$saveDir = $this->basePath.$savePath; 
				break;
			case 'pro':
				$RandNum=LibFc::RandNum(1,500);
				$savePath = 'pro/'.$RandNum.'/';
				$copyPath= 'proSrc/'.$RandNum.'/';
				$saveDir = $this->basePath.$savePath; 
				$copyDir=$this->basePath.$copyPath;
				$copyNameFile=$copyDir.$saveName.'.'.$ext;
				break;
		}
		LibDir::CreateDir($saveDir);

		$oriNameFile = $saveDir.$saveName.'.'.$ext;
		if(!empty($fileArr['data']))
		{
			$r=copy($fileArr["tmp_name"], $oriNameFile);
		}else
		{
			$r=move_uploaded_file($fileArr["tmp_name"], $oriNameFile);
		}
		if(!$r)
		{
			return LibFc::ReturnData(false, '文件上传失败，请重新上传。');
		}
		$delFileArr[] = $oriNameFile;
		$crc = LibFc::GetCrc32(file_get_contents($oriNameFile));
		$res=[
			'prp_path'=>$savePath,
			'prp_name'=>$saveName,
			'prp_ext'=>$ext,
			'prp_crc32'=>$crc,
			'url'=>$this->url.$savePath.$saveName.'.'.$ext
		];
		// return LibFc::ReturnData(true, $res);
		// die;
		//缩略图处理
		$Image = new LibImage();
		switch($path)
		{
			case 'pro':
				$res['prp_ext'] = 'jpg';
				$res['url'] = $this->url.$savePath.'100/'.$saveName.'.jpg';
				$thumbDir = $saveDir;
				LibDir::CreateDir($thumbDir);
				$tempPic = $thumbDir.'temp'.$saveName.'.jpg';
				if($Ext != 'jpg')
				{//转图
					$ResFlag = $Image->ToJPG($oriNameFile, $tempPic);
					if(!$ResFlag)
					{
						$this->delFiles($delFileArr);
						return LibFc::ReturnData(false, '转图失败。');
					}
					$copyNameFile = $copyDir.$saveName.'.jpg';
				}
				else
				{
					if(!copy($oriNameFile, $tempPic))
					{
						$this->delFiles($delFileArr);
						return LibFc::ReturnData(false, '复制图片失败。');
					}
				}
				$delFileArr[] = $tempPic;
				$ThumbArr = array(
					array('w'=>800, 'h'=>800),
					array('w'=>240, 'h'=>240),
					array('w'=>100, 'h'=>100)
				);
				foreach($ThumbArr as $V)
				{
					$tempThumbDir = $thumbDir.$V['w'].'/';
					@mkdir($tempThumbDir);
					$imgName = $tempThumbDir.$saveName.'.jpg';
					// echo $imgName.'---'.$tempPic;
					// die;
					$ResFlag =$Image->Resize($tempPic, $imgName ,$V['w'], $V['h']);
					// var_dump($ResFlag);
					// die;
					if(!$ResFlag)
					{
						$this->delFiles($delFileArr);
						return LibFc::ReturnData(false, '生成'.$V['w'].'*'.$V['h'].'的缩略图失败。');
					}
					$delFileArr[] = $imgName;
				}
				LibDir::CreateDir($copyDir);
				if(!copy($tempPic, $copyNameFile))
				{
					$this->delFiles($delFileArr);
					return LibFc::ReturnData(false, '复制图片失败。');
				}
				@unlink($oriNameFile);
				@unlink($tempPic);
				break;
		}
		return LibFc::ReturnData(true, $res);
	}
	/**
	 * @method    文件删除
	 * @param     [arr]     $fileArr [路径数组]
	 * @author 卢
	 * @copyright 2018-05-16
	 */
	private function delFiles($fileArr)
	{
		foreach($fileArr as $File)
		{
			@unlink($File);
		}
	}
	public function deleteByName($fileArr,$type)
	{
		if(!in_array($type, $this->pathArr)||empty($fileArr))
		{
			return LibFc::ReturnData(false, '文件删除参数错误。');
		}
		$fileArr=is_array($fileArr)?$fileArr:[$fileArr];
		foreach ($fileArr as $v) 
		{
			switch ($type) 
			{
				case 'article':
					break;
				case 'pro':
					$pathArr=explode('/', $v);
					// print_r()
					$path=$this->basePath.$v;
					$copyPath=$this->basePath.'proSrc'.substr($v, strpos($v, '/'));
					//缩略图
					$thumbPath=$this->basePath.$pathArr[0].'/'.$pathArr[1].'/800/'.$pathArr[2];
					@unlink($thumbPath);
					$thumbPath=$this->basePath.$pathArr[0].'/'.$pathArr[1].'/240/'.$pathArr[2];
					@unlink($thumbPath);
					$thumbPath=$this->basePath.$pathArr[0].'/'.$pathArr[1].'/100/'.$pathArr[2];
					@unlink($thumbPath);
					//原图
					@unlink($path);
					//proSrc图
					@unlink($copyPath);
					break;
			}
		}

	}
}