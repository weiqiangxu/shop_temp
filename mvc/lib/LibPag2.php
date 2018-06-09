<?php
/*
<class>
	<cate></cate>
	<name>PageHome</name>
	<remark>前台分页类</remark>
	<exp.></exp.>
	<author>Soul 2013/10/15</author>
</class>
*/
class LibPag2
{
	private static $Total = 0;
	private static $PerPage = 10;
	//url的分页参数
	private static $PageName = 'page';
	
	/*
	<method>
		<name>ShowPage</name>
		<remark>显示分页</remark>
		<exp.></exp.>
		<Parameter>
			<para name="Total" type="int">总记录数</para>
			<para name="PerPage" type="int">每页数量</para>
		</Parameter>
		<return></return>
		<author>Soul 2013/10/12</author>
	</method>
	*/
	public static function Show($Total = 0, $PerPage = 10)
	{
		self::$Total = $Total > 0 ? (int) $Total: self::$Total;
		self::$PerPage = $PerPage > 0 ? (int) $PerPage: self::$PerPage;
		$TotalPage = ceil(self::$Total/self::$PerPage);
		$NowPage = !empty($_GET[self::$PageName]) && $_GET[self::$PageName] > 0 ? (int) $_GET[self::$PageName] : 1;
		$NowPage = $NowPage > $TotalPage? $TotalPage: $NowPage;
		//$UrlParamArr = $_SERVER ['QUERY_STRING'];
		$UrlParamArr = array();
		foreach($_GET as $K=>$V)
		{
			$UrlParamArr[$K] = $V;
		}
		$UrlParamArr[self::$PageName] = '-_-page_-_';
		$Url = $_SERVER ['PHP_SELF'].'?'.http_build_query($UrlParamArr);
		$PageStr = '';
		$NowPage = $NowPage > $TotalPage? $TotalPage: $NowPage; 
		//上一页 1/30 下一页
		if($TotalPage > 1)
		{
			$Pre = $NowPage-1 > 0 ? '<li><a href="'.self::ReplaceUrl($Url, $NowPage-1).'">上一页</a></li>': '<li class="disabled"><a>下一页</a></li>';
			$Next = $NowPage+1 <= $TotalPage ? '<li><a href="'.self::ReplaceUrl($Url, $NowPage+1).'">下一页</a></li>': '<li class="disabled"><a>上一页</a></li>';
			$PageStr = '<nav><ul class="pager">'.$Pre.'<li><span class="page_msg">'.$NowPage.'/'.$TotalPage.'</span></li>'.$Next.'</ul></nav>';
		}
		return $PageStr;
	}

	private static function ReplaceUrl($Url, $Page)
	{
		return str_replace('-_-page_-_', $Page, $Url);
	}
}
?>