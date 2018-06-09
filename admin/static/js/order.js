layui.extend({
    index: 'lib/index' //主入口模块
  }).use(['index', 'table','laypage','layedit','laydate','form'], function(){
		var table = layui.table
		,admin = layui.admin;
		var laypage = layui.laypage;
		var form = layui.form;
		var laydate = layui.laydate;
		//分页 
		laypage.render({
		  elem: 'proPage'
		  ,count: <?php echo $total;?>
		  ,limit:<?php echo $limit;?>
		  ,layout: ['count', 'prev', 'page', 'next', 'limit', 'skip']
		  ,curr:<?php echo $page;?>
		  ,jump: function(obj, first){
			if(!first){
			  var data=$('#search-order').serialize()
			  window.location.href=adminUri+'order/lists?page='+obj.curr+'&limit='+obj.limit+'&'+data
			}
		  }
		});
		form.render(null, 'list-form-style');
		form.on('switch(list-form-style)', function(data){
		  var ordId=$(data.elem).data('id');
		  if(data.elem.checked){
			var style=1;
		  }else{
			var style=0;
		  }
		  $.get(adminUri+'order/changeOrdStyle?isAjax=1',{'style':style,'ordId':ordId},function(res){
		  },'json');
		});
		// form.on('checkbox(list-form-checkbox)', function(data){
		//   // console.log(data.elem); //得到checkbox原始DOM对象
		//   // console.log(data.elem.checked); //是否被选中，true或者false
		//   // console.log(data.value); //复选框value值，也可以通过data.elem.value得到
		//   // console.log(data.othis); //得到美化后的DOM对象
		// });
		form.on('checkbox(list-form-checkall)', function(data){
		  if(data.elem.checked){
			$('input[name="ordArr[]"]:not(:checked)').next().trigger('click');
		  }else{
			$('input[name="ordArr[]"]:checked').next().trigger('click');
		  }
		});
		form.render(null, 'component-form-group');
		laydate.render({ 
		  elem: '#add-time'
		  ,range: true //或 range: '~' 来自定义分割字符
		});  
		var $ = layui.$;
		//审核
		var layedit = layui.layedit;
		layedit.set({
		  uploadImage: {
			url: adminUri+'article/upload?type=article' //接口url
			,type: 'post',//默认post
			accept:'images',
			acceptMime:'image/*',
		  },
		  height: 320 //设置编辑器高度
		});
		$(document).on('click','.apply-btn',function(){
		  var ordId=$(this).data('id');
		  layer.open({
			type: 1,
			title:'订单审核',
			skin: 'layui-layer-rim', //加上边框
			area: ['50%', '95%'], //宽高
			btn: ['确定', '取消'], 
			scrollbar:false,
			btnAlign: 'c',
			content: '<div class="layui-row" style="margin-top:10px;">'+
				  '<div class="layui-col-md11">'+
				  '<form class="layui-form" lay-filter="verify-form" id="verify-order">'+
					  '<div class="layui-form-item layui-form-text">'+
						'<label class="layui-form-label">支付凭证</label>'+
						'<div class="layui-input-block">'+
						  '<textarea placeholder="请输入支付凭证" lay-verify="required" class="layui-textarea" name="ord_pay_info" id="ord_pay_info"></textarea>'+
						'</div>'+
					  '</div>'+
					'<div class="layui-form-item">'+
					  '<label class="layui-form-label">审核状态</label>'+
					  '<div class="layui-input-block">'+
							'<input type="radio" name="type" checked value="1" title="通过">'+
							'<input type="radio" name="type" value="0" title="不通过">'+
						'</div>'+
					'</div>'+
					'<div class="layui-form-item">'+
						  '<label class="layui-form-label">支付时间</label>'+
						'<div class="layui-input-block">'+
						  '<input type="text" name="pay_time" required="" placeholder="请选择支付时间" autocomplete="off" class="layui-input" id="pay-time">'+
						'</div>'+
					  '</div>'+
					  '<div class="layui-form-item layui-form-text">'+
						'<label class="layui-form-label">审核意见</label>'+
						'<div class="layui-input-block">'+
						  '<textarea placeholder="请输入审核意见" class="layui-textarea" lay-verify="required" name="text"></textarea>'+
						'</div>'+
					  '</div>'+
					  '<input type="hidden" value="" name="ordId">'+
				  '</form>'+
				  '</div>'+
				'</div>',
			  success:function(layero, index){
				// form.verify({});
				$('#verify-order input[name="ordId"]').val(ordId);
				//建立编辑器
				$.get(adminUri+'order/getPayTicket?isAjax=1',{'ordId':ordId},function(res){
				  $('#ord_pay_info').html(res.data['ord_pay_info']);
				  editor=layedit.build('ord_pay_info');
				  form.render(null, 'verify-form');
				},'json');
				laydate.render({
				  elem: '#pay-time' //指定元素
				  ,type: 'datetime'
				});
			  },
			  yes:function(index){
				layedit.sync(editor);
				if($('#ord_pay_info').val()==''){
				  layer.msg('支付凭证不能为空');
				  return false;
				}
				var data=$('#verify-order').serialize();
				$.post(adminUri+'order/agreeVerify?isAjax=1',data,function(res){
				  if(!res['status']){
					layer.msg(res.data);
				  }else{
					layer.msg('审核成功');
					window.location.reload();
					layer.close(index);
				  }
				},'json');
				return false;
			  }
		  });
		});
	  //发货
	  $(document).on('click','.send',function(){
		var ordId=$(this).data('id');
		layer.open({
			type: 1,
			title:'发货',
			skin: 'layui-layer-rim', //加上边框
			area: ['50%', '50%'], //宽高
			btn: ['确定', '取消'], 
			btnAlign: 'c',
			content: '<div class="layui-row" style="margin-top:10px;">'+
				  '<div class="layui-col-md11">'+
				  '<form class="layui-form" lay-filter="verify-form" id="verify-order">'+
					  '<div class="layui-form-item">'+
						'<label class="layui-form-label">买家留言</label>'+
						'<div class="layui-input-block">'+
						  '<input type="text" id="remark" autocomplete="off" class="layui-input" layui-disabled" disabled>'+
						'</div>'+
					  '</div>'+
					  '<div class="layui-form-item">'+
						'<label class="layui-form-label">快递公司</label>'+
						'<div class="layui-input-block" id="express-box">'+
						  // '<input type="text" name="ord_kuaidi" required="" placeholder="请输入快递公司" autocomplete="off" class="layui-input">'+
						'</div>'+
					  '</div>'+
					  '<div class="layui-form-item">'+
						'<label class="layui-form-label">快递单号</label>'+
						'<div class="layui-input-block">'+
						  '<input type="text" name="ord_kuaidi_number" required="" placeholder="请输入快递单号" autocomplete="off" class="layui-input">'+
						'</div>'+
					  '</div>'+
					  '<input type="hidden" value="" name="ordId">'+
				  '</form>'+
				  '</div>'+
				'</div>',
			  success:function(layero, index){
				$.get(adminUri+'order/getOrderInfo?isAjax=1',{'ordId':ordId},function(res){
				  if(res['status']&&res['express'].length>0){
					var html='<option value="0">请选择快递公司</option>'
					for(var i in res['express']){
					  html+='<option value="'+res['express'][i]['exp_name']+'">'+res['express'][i]['exp_name']+'</option>';
					}
					html='<select lay-verify="required" name="ord_kuaidi">'+html+'</select>';
					$('#express-box').html(html);
					form.render(null, 'verify-form');
				  }
				  if(res['remark']!=null){
					$('#remark').val(res['remark']);
				  }
				},'json');
				$('#verify-order input[name="ordId"]').val(ordId);
			  },
			  yes:function(index){
				if($('select[name="ord_kuaidi"]').val()=='0'){
				  layer.msg('请选择快递公司');
				  return false;
				}
				if($('input[name="ord_kuaidi_number"]').val()==''){
				  layer.msg('请输入快递单号');
				  return false;
				}
				var data=$('#verify-order').serialize();
				$.post(adminUri+'order/sendExpress?isAjax=1',data,function(res){
				  if(!res['status']){
					layer.msg(res.data);
				  }else{
					layer.msg('发货成功');
					window.location.reload();
					layer.close(index);
				  }
				},'json');
			  }
		  });
	  })
	  //改价
	  $(document).on('click','.edit-oder',function(){
		var ordId=$(this).data('id');
		layer.open({
		  type: 2,
		  title: '修改价格',
		  shadeClose: true,
		  shade: 0.8,
		  area: ['50%', '80%'],
		  content: adminUri+'order/editOrder?ordId='+ordId//iframe的url
		});
	  });
	  $(document).on('click','.del-order',function(){
		var ordId=$(this).data('id');
		layer.confirm('真的删除订单么', function(index){
		  $.post(adminUri+'order/deleteOrder?isAjax=1',{'orderArr':ordId},function(res){
			if(res['status']){
			  window.location.reload();
			  layer.msg('删除成功');
			}else{
			  layer.msg(res.data);
			}
		  },'json');
		  layer.close(index);
		});
	  })
	  // $(document).on('click','.check-one',function(){
	  //   $(this).toggleClass('layui-form-checked');
	  //   if($(this).hasClass('layui-form-checked')){
	  //     $(this).parents('.laytable-cell-checkbox').find('input').prop('checked',true);
	  //   }else{
	  //     $(this).parents('.laytable-cell-checkbox').find('input').prop('checked',false);
	  //   }
	  // });
	  // $(document).on('click','.layTableAllChoose',function(){
	  //   $(this).toggleClass('layui-form-checked');
	  //   if($(this).hasClass('layui-form-checked')){
	  //     $('.check-one').toggleClass('layui-form-checked',true);
	  //     $('.check-one').parents('.laytable-cell-checkbox').find('input').prop('checked',true);
	  //   }else{
	  //     $('.check-one').toggleClass('layui-form-checked',false);
	  //     $('.check-one').parents('.laytable-cell-checkbox').find('input').prop('checked',false);
	  //   }
	  // })
	  $(document).on('click','.delete-all',function(){
		var item=$('input[name="ordArr[]"]:checked');
		// console.log(item);
		// return false;
		if(item.length>0){
		  layer.confirm('真的删除该订单么', function(index){
			  var orderArr=[];
			  for(var i =0;i<item.length;i++){
				orderArr.push(item.eq(i).val());
			  }
			  $.post(adminUri+'order/deleteOrder?isAjax=1',{'orderArr':orderArr},function(res){
				if(res['status']){
				  window.location.reload();
				  layer.msg('删除成功');
				}else{
				  layer.msg(res.data);
				}
			  },'json');
			});
		}
	  })
  });