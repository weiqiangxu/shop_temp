<?php include_once(mvc::$cfg['PATH_TPL'].'public/headBase.php');?>
<table class="iframeTable">
    <tr>
        <td width="300px">
            <iframe  src="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/productSearch?<?php echo $_SERVER['QUERY_STRING'];?>" class="iframes iframes-left"  name="SearchFrame" id="SearchFrame"></iframe>
        </td>
        <td>
            <iframe src="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/productList?<?php echo $_SERVER['QUERY_STRING'];?>" class="iframes" name="ListFrame" id="ListFrame"></iframe>
        </td>
    </tr>
</table>
<?php include_once(mvc::$cfg['PATH_TPL'].'public/footBase.php');?>