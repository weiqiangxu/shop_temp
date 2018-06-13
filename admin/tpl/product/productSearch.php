<?php include_once(mvc::$cfg['PATH_TPL'].'public/headBase.php');?>
<div style="width: 290px;">
	<form action="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/productList" target="ListFrame" method="get" class="layui-form">
		<table class="layui-table">
			<tr>
				<th class="right" width="60">名称：</th>
				<td><input class="layui-input layui-input-inline" placeholder="" name="name" value="<?php echo $Params['name'];?>"></td>
			</tr>
			<tr>
				<th class="right">品牌：</th>
				<td><input class="layui-input layui-input-inline" placeholder="" name="make" value="<?php echo $Params['make'];?>"></td>
			</tr>
			<tr>
				<th class="right">车型：</th>
				<td><input class="layui-input layui-input-inline" placeholder="" name="model" value="<?php echo $Params['model'];?>"></td>
			</tr>
			<?php if($_SESSION['_style']=='1'){ ?>
			<tr>
				<th class="right">公司：</th>
				<td><input class="layui-input layui-input-inline" placeholder="" name="company" value="<?php echo $Params['company'];?>"></td>
			</tr>
			<?php } ?>
			<?php if($_SESSION['_style']!='0'){ ?>
			<tr>
				<th class="right">状态：</th>
				<td>	
					<select name="status" class="layui-inline layui-input-inline w180">
						<option value="">全部</option>
						<option value="0" <?php if($Params['status']=='0'){echo 'selected';}?>>待审</option>
						<option value="1" <?php if($Params['status']=='1'){echo 'selected';}?>>已审核</option>
						<option value="2" <?php if($Params['status']=='2'){echo 'selected';}?>>不通过</option>
					</select>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<th class="right">显示列：</th>
				<td>
					<div class="layui-blcok" style="position: relative;">
						<input type="hidden" value="<?php echo $colids;?>" id="showinp" name="show">
						<input class="layui-input" placeholder="请选择" value="<?php echo $str;?>" id="show" onclick="showCol()" autocomplete="off">
						<div id="treeDemo-box" >
							<ul id="treeDemo" class="ztree"></ul>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<th class="right">语言：</th>
				<td>
					<select name="lang" class="w180">
					<?php foreach(mvc::$cfg['LANG'] as $k => $v){ ?>
						<option value="<?php echo $k;?>" <?php if($Params['lang']==$k){echo 'selected';}?>><?php echo $v;?></option>
					<?php } ?>
					</select>
				</td>
			</tr>
			<?php if($_SESSION['_style']=='0'){ ?>
			<tr>
				<th class="right">公司：</th>
				<td><input class="layui-input" name="company" value="<?php echo $Params['company'];?>"></td>
			</tr>
			<tr>
				<th class="right">手机：</th>
				<td><input class="layui-input" name="mobile" value="<?php echo $Params['mobile'];?>"></td>
			</tr>
			<?php } ?>
		</table>
		<div class="center"><button class="layui-btn" type="submit" data-type="reload">搜 索</button></div>
	</form>
</div>
<script>
layui.config({
	base: '<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/layuiAdmin/src/layuiadmin/' //静态资源所在路径
}).extend({
	index: 'lib/index' //主入口模块
}).use(['index', 'table'], function(){
	
});
</script>


<script type="text/javascript">
	<!--
	var setting = {
		check: {
			enable: true
		},
		data: {
			simpleData: {
				enable: true
			}
		},
		view: {
			showIcon: false
		},
		callback:{
			onCheck: selItem
		}
	};

	var zNodes =<?php echo $ztreeNode;?>;
	
	
	$(document).ready(function(){
		$.fn.zTree.init($("#treeDemo"), setting, zNodes);
	});
	

	$("#treeDemo-box").click(function(event){
		event.stopPropagation();
	});
	function showCol(){
		$("#treeDemo-box").slideToggle("normal",function(){
			$(document).one("click", function(e){
				$("#treeDemo-box").hide();
			});
		});
	}

	function selItem(event, treeId, treeNode) {
		// 监听分类选中
		var treeObj = $.fn.zTree.getZTreeObj(treeId);
		var nodes = treeObj.getCheckedNodes(true);
		var inp = new Array();
		var ids = new Array();
		for (var i = nodes.length - 1; i >= 0; i--) {
			inp.push(nodes[i].name);
			ids.push(nodes[i].id);
		}
		$("#show").val(inp.toString());
		ids = ids.reverse();
		$("#showinp").val(ids.toString());
	}
</script>




<?php include_once(mvc::$cfg['PATH_TPL'].'public/footBase.php');?>