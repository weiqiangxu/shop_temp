<?php include_once(mvc::$cfg['PATH_TPL'].'public/headBase.php');?>
<script type="text/javascript">
    function jump()
    {
    	lessTime=lessTime-1;
    	if(lessTime==0){
	        location.href="<?php echo $jumpUrl;?>";
    	}
    	$('.wait').html(lessTime);
    }
    var lessTime=<?php echo $wait;?>;
	setInterval(jump,1000);
</script>
<br>
<div class="message" style="width:960px;margin:0 auto;">
	<div class="msg">
	<span class="success"><?php echo $message;?></span>
	</div>
	<div class="tip">
		页面将在 <span class="wait"><?php echo $wait;?></span> 秒后自动关闭，如果不想等待请点击 <a href="<?php echo $jumpUrl;?>"><b>这里</b></a> 关闭
	</div>
</div>
<?php include_once(mvc::$cfg['PATH_TPL'].'public/footBase.php');?>