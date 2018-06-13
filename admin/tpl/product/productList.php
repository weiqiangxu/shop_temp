<?php include_once(mvc::$cfg['PATH_TPL'].'public/headBase.php');?>
<div class="mar5">
	<table class="layui-table layui-form" id="pro-list-tab">
		<thead>
			<tr>
				<th><input type="checkbox" id="checkAll" lay-filter="allselect"  lay-skin="primary"></th>
				<?php if($_SESSION['_style']!='0'){ ?>
				<th width="80">操作</th>
				<?php } ?>
				<?php foreach($all as $k=>$v){ ?>
				<?php if(in_array($k, $show)){ ?>
				<th <?php if(isset($showWidth[$k])) echo ' width="'.$showWidth[$k].'"';?>><?php echo $v;?></th>
				<?php } ?>
				<?php } ?>
			</tr> 
		</thead>
		<tbody>
			<?php if(!empty($data)){ ?>
			<?php foreach($data as $k => $v){ ?>
			<tr>
				<td><input type="checkbox" value="<?php echo $v['pro_id'];?>" lay-skin="primary"></td>
				<?php if($_SESSION['_style']!='0'){ ?>
				<td>
					<?php if($_SESSION['_style']=='2'){ ?>
					<a class="layui-btn layui-btn-xs" target="_blank" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/publish?proId=<?php echo $v['pro_id'];?>">编辑</a>
					<a class="layui-btn layui-btn-danger layui-btn-xs" onclick='product.del("<?php echo $v['pro_id'];?>")'>删除</a>
					<?php }elseif($_SESSION['_style']=='1'){ ?>
					<a class="layui-btn layui-btn-xs" target="_blank" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/publish?proId=<?php echo $v['pro_id'];?>">编辑</a>
					<a target="_blank" class="layui-btn layui-btn-xs layui-btn-normal" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/check?proId=<?php echo $v['pro_id'];?>">审核</a>
					<?php } ?>
				</td>
				<?php } ?>
				<?php if(in_array('pro_id', $show)){ ?>
				<td><?php echo $v['pro_id'];?></td>
				<?php } ?>
				<?php if(in_array('pro_status', $show)){ ?>
				<td class="<?php echo $proStatColor[$v['pro_status']];?>">
					<?php echo $proStat[$v['pro_status']];?>
				</td>
				<?php } ?>
				<?php if(in_array('img', $show)){ ?>
				<td>
					<div class="layui-table-cell laytable-cell-1-imgUrl">
						<a href="<?php echo $v['imgUrlBg'];?>" target="_blank">
							<img style="max-width: 50px;" src="<?php echo $v['imgUrl'];?>">
						</a>
					</div>
				</td>
				<?php } ?>
				<?php if(in_array('pro_name', $show)){ ?>
				<td><a target="_blank" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/check?proId=<?php echo $v['pro_id'];?>"><?php echo $v['pro_name'.$Params['lang']];?></a></td>
				<?php } ?>

				 <?php if(in_array('pro_price', $show)){ ?>
				<td class="right">
					<?php echo sprintf("%.2f",$v['pro_price']);?>
				</td>
				<?php } ?>

				<?php if(in_array('pro_make', $show)){ ?>
				<td><?php echo $v['pro_make'.$Params['lang']];?></td>
				<?php } ?>

				<?php if(in_array('pro_model', $show)){ ?>
				<td><?php echo $v['pro_model'.$Params['lang']];?></td>
				<?php } ?>

			   
				<?php if(in_array('check_admin', $show)){ ?>
					<td>
						<?php if(!empty($v['pro_check_detail']['u_name'])) echo $v['pro_check_detail']['u_name'];?>
						</td>
				<?php } ?>
				<?php if(in_array('check_time', $show)){ ?>
					<td>
						<?php if(!empty($v['pro_check_detail']['time'])) echo date('Y-m-d H:i:s',$v['pro_check_detail']['time']);?>
						</td>
				<?php } ?>
				<?php if(in_array('check_remark', $show)){ ?>
					<td>
						<?php if(!empty($v['pro_check_detail']['remark'])) echo $v['pro_check_detail']['remark'];?>
						</td>
				<?php } ?>
				<?php if(in_array('u_company', $show)){ ?>
					<td>
						<?php if(!empty($v['u_company'])) echo $v['u_company'];?>
						</td>
				<?php } ?>
				<?php if(in_array('u_mobile', $show)){ ?>
					<td>
						<?php if(!empty($v['u_mobile'])) echo $v['u_mobile'];?>
						</td>
				<?php } ?>
				<?php if(in_array('u_tel', $show)){ ?>
					<td>
						<?php if(!empty($v['u_tel'])) echo $v['u_tel'];?>
						</td>
				<?php } ?>
				
				
				<?php if(in_array('pro_atime', $show)){ ?>
					<td><?php if(!empty($v['pro_atime'])) echo date('Y-m-d H:i',$v['pro_atime']);?></td>
				<?php } ?>
				<?php if(in_array('pro_etime', $show)){ ?>
					<td>
						<?php if(!empty($v['pro_etime'])) echo date('Y-m-d H:i',$v['pro_etime']);?>
						</td>
				<?php } ?>
			</tr>
			<?php } ?>
			<?php }else{ ?>
				<tr>
					<td colspan="<?php if($_SESSION['_style']!='0') echo count($show)+2;else echo count($show);?>" class="warn center">无相关记录！</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php if($_SESSION['_style']=='1'){ ?>
	<button onclick="checkSelect()" class="layui-btn layui-btn-normal layui-btn-sm">批量审核</button>
	<?php } ?>
	<?php if($_SESSION['_style']!='0'){ ?>
	<button onclick="delSelect()"  class="layui-btn layui-btn-danger layui-btn-sm">删除选中</button>
	<?php } ?>
	<div id="test-laypage-demoz" class="center"></div>
</div>

<div id="inputiFrame" style="display: none;">
	<form class="layui-form" action="" method="post" style="margin: 15px;">
		<input type="hidden" name="ids">
		<div class="layui-form-item">
		    <div class="layui-inline">
		            <div class="layui-form-item">
		            <div class="layui-input-inline" style="width: 350px;">
		              <input type="radio" name="status" value="0" title="待审">
		              <input type="radio" name="status" value="1" title="已审" checked>
		              <input type="radio" name="status" value="2" title="审核不通过">
		            </div>
		          </div>
		          <div class="layui-form-item layui-form-text">
		            <div class="layui-input-inline" style="width: 450px;">
		              <textarea lay-verify="remark" name="remark" style="resize: none;margin-top: 10px;" placeholder="审核备注" class="layui-textarea"><?php echo $data['base']['pro_check_detail']['remark'];?></textarea>
		            </div>
		          </div>
		    </div>
		</div> 
		<div class="layui-form-item">
		    <div class="layui-input-inline" style="padding-left: 170px;">
		        <button class="layui-btn" lay-submit lay-filter="sub-check">提交审核结果</button>
		    </div>
		</div>
	</form>
</div>
 
<script>
//Demo
layui.use('form', function(){
  var form = layui.form;
  
  //监听提交
  form.on('submit(formDemo)', function(data){
    layer.msg(JSON.stringify(data.field));
    return false;
  });
});
</script>
</div>
<script>
layui.config({
    base: '<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/layuiAdmin/src/layuiadmin/' //静态资源所在路径
}).extend({
    index: 'lib/index' //主入口模块
}).use(['index', 'table'], function(){
    var laypage = layui.laypage;
    var form = layui.form;
    //总页数大于页码总数
    laypage.render({
        elem: 'test-laypage-demoz'
        ,limit : <?php echo $perPage;?>
        ,count: <?php echo $count;?> //数据总数
        ,curr:  <?php echo $page;?>//当前页
        ,jump: function(obj,first){
            if(!first){
                //do something
                location.href = adminUri+'product/productList?<?php if(!empty($Params)){echo http_build_query($Params).'&';}?>page='+obj.curr;
            }
        }
    });

    // 全选按钮
	form.on('checkbox(allselect)', function(data){
	  	if(data.elem.checked) {
	  		$("#pro-list-tab").find("input[type='checkbox']").each(function(index,i){
	  			$(i).prop('checked',true);
	  		});
	  	}else{
	  		$("#pro-list-tab").find("input[type='checkbox']").each(function(index,i){
	  			$(i).prop('checked',false);
	  		});
	  	}
	  	form.render();
	});

	// 普通复选
	form.on('checkbox', function(data){
		if(data.elem.checked){
			$("#pro-list-tab tbody").find("input[type='checkbox']").each(function(index,i){
				if(!i.checked){
					$("#checkAll").prop('checked',false);
					return false;
				}else{
					$("#checkAll").prop('checked',true);
				}
	  		});
		}else{
			$("#checkAll").prop('checked',false);
		}
		form.render();
	});
	form.verify({
      remark: function(value, item){ //value：表单的值、item：表单的DOM对象
        var aces = $("input[name='status']:checked").val();
        if(aces==2 && value==''){
          return '审核不通过必须输入备注';
        }
        if(value.length>200){
            return '备注最多输入200个字符！';
        }
      }
    });
    //监听批量审核提交
	  form.on('submit(sub-check)', function(data){
	    $.ajax({
	        url:adminUri+'product/checkSelect?isAjax=1',
	        data:{'data':data.field},
	        dataType:'json',
	        type:'post',
	        success:function(res){
	            if(res.status) {
	                //登入成功的提示与跳转
	                layer.msg('操作成功！', {
	                    offset: '15px'
	                    ,icon: 1
	                    ,time: 1000
	                }, function(){
	                    location.reload();
	                });
	            }else{
	                layer.alert(res.data, {icon: 5,title:'温馨提示'},function(){
		                layer.closeAll();
	                });
	            }
	        }
	    });
	    return false;
	  });

});

function checkSelect(){
	var ids = new Array();
	$("#pro-list-tab tbody").find("input[type='checkbox']").each(function(index,i){
		if(i.checked){
			ids.push($(i).val());
		}
	});
	if(ids.length==0){
		return false;
	}else{
		$("#inputiFrame").find("input[name='ids']").val(ids.toString());
		layer.open({
			title: '批量审核',
			type: 1,
			content: $('#inputiFrame'),
			area: ['500px', '250px']
		});
		return false;
		// layer.close(index);
	}
}

// 删除
function delSelect(){
	var ids = new Array();
	$("#pro-list-tab tbody").find("input[type='checkbox']").each(function(index,i){
		if(i.checked){
			ids.push($(i).val());
		}
	});
	if(ids.length==0){
		return false;
	}else{
		//eg1
		layer.confirm('您确定要删除所选条目吗?', {icon: 3, title:'确认删除'}, function(index){
			//do something
			$.ajax({
				url:adminUri+'product/delSelect?isAjax=1',
				data:{'ids':ids.toString()},
				dataType:'json',
				type:'POST',
				success:function(res){
					if(res.status){
						layer.msg('删除成功！', {
                            offset: '300px'
                            ,icon: 1
                            ,time: 1000
                        }, function(){
                            location.reload();
                        });  
					}else{
						layer.alert(res.data, {icon: 5}); 
					}
				}
			});
		});
	}
}

</script>
<?php include_once(mvc::$cfg['PATH_TPL'].'public/footBase.php');?>