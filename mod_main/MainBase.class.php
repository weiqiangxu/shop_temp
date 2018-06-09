<?php
/**
  * @method 宜配网非产品库表基本操作
  * @author soul
  * @copyright 2016/3/31
  */
class MainBase
{
	public $MainDb = null;
	public function __construct($MainDb = null)
	{
		$this->MainDb = $MainDb==null ?  new LibDb (mvc::$cfg['DB']['MAIN']): $MainDb;
	}
	
	/**
	  * @method 查询表信息
	  * @param  $table 表明 如：yp_product 或者 yp_product as a join yp_number as b on a.pid=b.pid
	  * @param  $fieldArr 需要查询的字段名称数组
	  * @param  $where 查询条件
	  * @param  $oneRow true|false
	  * @author soul
	  * @copyright 2016/3/30
	  * @return array(3) { ["status"]=> bool(true) ["data"]=> $oneRow=false 二维数组 ; $oneRow=true 一维数组["code"]=> int(0) } 
	  */
	public function get($table, $fieldArr, $where = '', $oneRow = false)
	{
		if(!empty($fieldArr) && is_array($fieldArr))
		{
			$Sql = sprintf("SELECT %s FROM %s WHERE 1=1 %s", implode(',', $fieldArr), LibFc::Escape($table), $where);
			//echo $Sql, '<br>';
			$resFlag = $this->MainDb->Query($Sql);
			if($resFlag)
			{
				$Arr = array();
				while($row = $this->MainDb->Fetch())
				{
					if($oneRow)
					{
						$Arr = $row;
						break;
					}
					else
					{
						$Arr[] = $row;
					}
				}
				return LibFc::ReturnData(true, $Arr);
			}
			return LibFc::ReturnData(false, $table.'查询SQL语句执行失败。');
		}
		return LibFc::ReturnData(false, $table.'查询提供的参数不正确。');
	}

	/**
	  * @method 查询表信息
	  * @param  $Sql 
	  * @param  $oneRow true|false
	  * @author soul
	  * @copyright 2016/3/30
	  * @return array(3) { ["status"]=> bool(true) ["data"]=> $oneRow=true 二维数组 ; $oneRow=true 一维数组["code"]=> int(0) } 
	  */
	public function getDataBySql($Sql, $oneRow = false)
	{
		if(!empty($Sql))
		{
			$resFlag = $this->MainDb->Query($Sql);
			if($resFlag)
			{
				$Arr = array();
				while($row = $this->MainDb->Fetch())
				{
					if($oneRow)
					{
						$Arr = $row;
						break;
					}
					else
					{
						$Arr[] = $row;
					}
				}
				return LibFc::ReturnData(true, $Arr);
			}
			return LibFc::ReturnData(false, $Sql.'查询SQL语句执行失败。');
		}
		return LibFc::ReturnData(false, $Sql.'查询提供的参数不正确。');
	}

	
	/**
	  * @method 添加记录
	  * @param  $table 表明 如：yp_product
	  * @param  $dataArr 基本信息字段=>值 数组
	  * @author soul
	  * @copyright 2016/3/30
	  * @return array(3) { ["status"]=> bool(true) ["data"]=> ID ["code"]=> int(0) } 
	  */
	public function add($table, $dataArr)
	{
		if(empty($table) || !is_array($dataArr) || empty($dataArr))
		{
			return LibFc::ReturnData(false, $table.'添加记录提供的参数不正确。');
		}
		
		$FiledArr = array();
		$valArr = array();
		foreach($dataArr as $K=>$V)
		{
			$FiledArr[] = LibFc::Escape($K);
			$valArr[] = sprintf("'%s'", LibFc::Escape(trim($V)));
		}
		$addSql = sprintf("INSERT INTO %s (%s) VALUES(%s)", LibFc::Escape($table), implode(',', $FiledArr), implode(',', $valArr));
		$this->MainDb->Begin();
		$resFlag = $this->MainDb->Query($addSql);
		$rows = $this->MainDb->RowCount();
		if($resFlag && $rows > 0)
		{
			$Id = $this->MainDb->InsertID();
			$this->MainDb->End();
			return LibFc::ReturnData(true, $Id);
		}
		return LibFc::ReturnData(false, $table.'添加记录SQL语句执行失败。');
	}


	/**
	  * @method 添加多条记录
	  * @param  $table 表明
	  * @param  $FiledArr 基本信息字段['name', ...]
	  * @param  $dataArr [['name'=>'11'...], ['name'=>'22'...]....]
	  * @author soul
	  * @copyright 2016/3/30
	  * @return array(3) { ["status"]=> bool(true) ["data"]=>  ["code"]=> int(0) } 
	  */
	public function addAny($table, $FiledArr, $dataArr)
	{
		if(empty($table) || !is_array($FiledArr) || empty($FiledArr) || !is_array($dataArr) || empty($dataArr))
		{
			return LibFc::ReturnData(false, $table.'添加记录提供的参数不正确。');
		}

		$valArr = [];
		foreach($dataArr as $V)
		{
			$tempArr =[];
			foreach($FiledArr as $Filed)
			{
				$tempArr[] = sprintf("'%s'", LibFc::Escape($V[$Filed]));
			}
			$valArr[] = sprintf("(%s)", implode(',', $tempArr));
		}
		$addSql = sprintf("INSERT INTO %s (%s) VALUES %s", LibFc::Escape($table), implode(',', $FiledArr), implode(',', $valArr));
		$this->MainDb->Begin();
		$resFlag = $this->MainDb->Query($addSql);
		if($resFlag)
		{
			$this->MainDb->End();
			return LibFc::ReturnData(true);
		}
		return LibFc::ReturnData(false, $table.'添加记录SQL语句执行失败。');
	}

	/**
	  * @method 根据条件修改记录
	  * @param  $table 表明 如：yp_product 或者 yp_product as a join yp_number as b on a.pid=b.pid
	  * @param  $dataArr 基本信息字段=>值 数组
	  * @param  $where 修改条件
	  * @author soul
	  * @copyright 2016/3/30
	  * @return array(3) { ["status"]=> bool(true) ["data"]=> 影响行数  ["code"]=> int(0) } 
	  */
	public function set($table, $dataArr, $where)
	{
		if(empty($table) || !is_array($dataArr) || empty($dataArr))
		{
			return LibFc::ReturnData(false, $table.'修改记录提供的参数不正确。');
		}
		$valArr = array();
		foreach($dataArr as $K=>$V)
		{
			$valArr[] = sprintf("%s='%s'", LibFc::Escape($K), LibFc::Escape(trim($V)));
		}
		$setSql = sprintf("UPDATE %s SET %s WHERE 1 %s ", LibFc::Escape($table), implode(',', $valArr), $where);
		$this->MainDb->Begin();
		$resFlag = $this->MainDb->Query($setSql);
		if($resFlag)
		{
			$rows = $this->MainDb->RowCount();
			$this->MainDb->End();
			return LibFc::ReturnData(true, $rows);
		}
		return LibFc::ReturnData(false, $table.'修改记录SQL语句执行失败。');
	}

	/**
	  * @method 根据条件删除记录
	  * @param  $table 表明 如：yp_product 或者 yp_product as a join yp_number as b on a.pid=b.pid
	  * @param  $where 修改条件
	  * @author soul
	  * @copyright 2016/3/30
	  * @return array(3) { ["status"]=> bool(true) ["data"]=> 影响行数  ["code"]=> int(0) } 
	  */
	public function del($table, $where)
	{
		if(empty($table) || empty($where))
		{
			return LibFc::ReturnData(false, $table.'删除记录提供的参数不正确。');
		}
		$delSql = sprintf("DELETE FROM %s WHERE 1=1 %s",  LibFc::Escape($table), $where );
		$this->MainDb->Begin();
		$resFlag = $this->MainDb->Query($delSql);
		if($resFlag)
		{
			$rows = $this->MainDb->RowCount();
			$this->MainDb->End();
			return LibFc::ReturnData(true, $rows);
		}
		return LibFc::ReturnData(false,  $table.'删除记录SQL语句执行失败。');
	}
}