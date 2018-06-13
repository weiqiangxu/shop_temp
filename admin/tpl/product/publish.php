<?php include_once(mvc::$cfg['PATH_TPL'].'public/headBase.php');?>
<div class="layui-fluid">
    <form class="layui-form" id="publish" method="post" action="" lay-filter="component-form-group">
		<input type="hidden" name="base[pro_id]" value="<?php echo $Params['proId']?>">
		<input type="hidden" id="status" value="<?php echo $data['base']['pro_status'];?>">
        <input type="hidden" id="style" value="<?php echo $_SESSION['_style'];?>">
		<div class="layui-form-item">
			<label class="layui-form-label"><span class="red">*</span>名称</label>
			<div class="layui-input-inline">
				<div class="martb5">
					<input type="text" name="base[pro_name1]" lay-verify="namefifter" placeholder="请输入<?php echo mvc::$cfg['LANG'][1];?>名称" value="<?php echo $data['base']['pro_name1'];?>" class="layui-input w500" >
					<span class="gray"><?php echo mvc::$cfg['LANG'][1];?></span>
				</div>
				<div class="martb5">
					<input type="text" name="base[pro_name2]" placeholder="请输入<?php echo mvc::$cfg['LANG'][2];?>名称" value="<?php echo $data['base']['pro_name2'];?>"  class="layui-input w500">
					<span class="gray"><?php echo mvc::$cfg['LANG'][2];?></span>
				</div>
				<div class="martb5">
					<input type="text" name="base[pro_name3]" placeholder="请输入<?php echo mvc::$cfg['LANG'][3];?>名称" value="<?php echo $data['base']['pro_name3'];?>"  class="layui-input w500">
					<span class="gray"><?php echo mvc::$cfg['LANG'][3];?></span>
				</div>
			</div>
		</div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label"><span class="red">*</span>销售价(元)</label>
                <div class="layui-input-inline">
                    <input type="tel" maxlength="10" name="base[pro_price]" lay-verify="price" placeholder="请输入产品销售价"  value="<?php if(!empty($data['base']['pro_price']) && $data['base']['pro_price']>0 ) echo sprintf("%.2f",$data['base']['pro_price']);?>" class="layui-input">
                </div>
            </div>         
        </div>




        <div class="layui-form-item">
            <label class="layui-form-label">配件图片</label>
            <div class="layui-inline">
				<div class="layui-upload">
					<div class="layui-upload-list">
					<?php if(!empty($data['images'][0]['prp_name'])){?>
                    <a href="<?php echo mvc::$cfg['HOST']['files'].$data['images'][0]['prp_path'].'240/'.$data['images'][0]['prp_name'].'.'.$data['images'][0]['prp_ext'];?>" target="_blank">
					<img  class="layui-upload-img" src="<?php echo mvc::$cfg['HOST']['files'].$data['images'][0]['prp_path'].'100/'.$data['images'][0]['prp_name'].'.'.$data['images'][0]['prp_ext'];?>">
                    </a>
					<?php }else{?>
					<img src="<?php echo mvc::$cfg['HOST']['adminUrl'].'static/images/noimg.gif'?>" class="layui-upload-img">
					<?php }?>
                    <div class="del-wrapper"><i class="layui-icon layui-icon-delete delete-pro-img"></i></div>
                    </div>
					<input type="hidden" class="img" name="img[<?php echo $data['images'][0]['prp_id']?>]" value='<?php echo json_encode($data['images'][0],JSON_UNESCAPED_UNICODE);?>'>
					<button type="button" class="layui-btn pro-img w100">上传图片</button>
				</div>   
            </div>
            <div class="layui-inline">
				<div class="layui-upload">
					<div class="layui-upload-list">
					<?php if(!empty($data['images'][1]['prp_name'])){?>
                    <a href="<?php echo mvc::$cfg['HOST']['files'].$data['images'][1]['prp_path'].'240/'.$data['images'][1]['prp_name'].'.'.$data['images'][1]['prp_ext'];?>" target="_blank">
					<img class="layui-upload-img" src="<?php echo mvc::$cfg['HOST']['files'].$data['images'][1]['prp_path'].'100/'.$data['images'][1]['prp_name'].'.'.$data['images'][1]['prp_ext'];?>">
                    </a>
					<?php }else{?>
					<img src="<?php echo mvc::$cfg['HOST']['adminUrl'].'static/images/noimg.gif'?>" class="layui-upload-img">
					<?php }?>
                    <div class="del-wrapper"><i class="layui-icon layui-icon-delete delete-pro-img"></i></div>
					</div>
					<input type="hidden" class="img" name="img[<?php echo $data['images'][1]['prp_id']?>]" value='<?php echo json_encode($data['images'][1],JSON_UNESCAPED_UNICODE);?>'>
					<button type="button" class="layui-btn pro-img w100">上传图片</button>
				</div>     
            </div>
            <div class="layui-inline">
               
				<div class="layui-upload">
					<div class="layui-upload-list">
					<?php if(!empty($data['images'][2]['prp_name'])){?>
                    <a href="<?php echo mvc::$cfg['HOST']['files'].$data['images'][2]['prp_path'].'240/'.$data['images'][2]['prp_name'].'.'.$data['images'][2]['prp_ext'];?>" target="_blank">
					<img class="layui-upload-img" src="<?php echo mvc::$cfg['HOST']['files'].$data['images'][2]['prp_path'].'100/'.$data['images'][2]['prp_name'].'.'.$data['images'][2]['prp_ext'];?>">
                    </a>
					<?php }else{?>
					<img src="<?php echo mvc::$cfg['HOST']['adminUrl'].'static/images/noimg.gif'?>" class="layui-upload-img">
					<?php }?>
                    <div class="del-wrapper"><i class="layui-icon layui-icon-delete delete-pro-img"></i></div>
					</div>
					<input type="hidden" class="img" name="img[<?php echo $data['images'][2]['prp_id']?>]" value='<?php echo json_encode($data['images'][2],JSON_UNESCAPED_UNICODE);?>'>
					<button type="button" class="layui-btn pro-img w100">上传图片</button>
				</div>   
                   
            </div>
            <div class="layui-inline">
               
                        <div class="layui-upload">
                            <div class="layui-upload-list">
                            <?php if(!empty($data['images'][3]['prp_name'])){?>
                            <a href="<?php echo mvc::$cfg['HOST']['files'].$data['images'][3]['prp_path'].'240/'.$data['images'][3]['prp_name'].'.'.$data['images'][3]['prp_ext'];?>" target="_blank">
                            <img class="layui-upload-img" src="<?php echo mvc::$cfg['HOST']['files'].$data['images'][3]['prp_path'].'100/'.$data['images'][3]['prp_name'].'.'.$data['images'][3]['prp_ext'];?>">
                            </a>
                            <?php }else{?>
                            <img src="<?php echo mvc::$cfg['HOST']['adminUrl'].'static/images/noimg.gif'?>" class="layui-upload-img">
                            <?php }?>
                            <div class="del-wrapper"><i class="layui-icon layui-icon-delete delete-pro-img"></i></div>
                            </div>
                            <input type="hidden" class="img" name="img[<?php echo $data['images'][3]['prp_id']?>]" value='<?php echo json_encode($data['images'][3],JSON_UNESCAPED_UNICODE);?>'>
                            <button type="button" class="layui-btn pro-img w100">上传图片</button>
                        </div>   
               
            </div>
            <div class="layui-inline">
            
                        <div class="layui-upload">
                            <div class="layui-upload-list">
                            <?php if(!empty($data['images'][4]['prp_name'])){?>
                            <a href="<?php echo mvc::$cfg['HOST']['files'].$data['images'][4]['prp_path'].'240/'.$data['images'][4]['prp_name'].'.'.$data['images'][4]['prp_ext'];?>" target="_blank">
                            <img class="layui-upload-img" src="<?php echo mvc::$cfg['HOST']['files'].$data['images'][4]['prp_path'].'100/'.$data['images'][4]['prp_name'].'.'.$data['images'][4]['prp_ext'];?>">
                            </a>
                            <?php }else{?>
                            <img src="<?php echo mvc::$cfg['HOST']['adminUrl'].'static/images/noimg.gif'?>" class="layui-upload-img">
                            <?php }?>
                            <div class="del-wrapper"><i class="layui-icon layui-icon-delete delete-pro-img"></i></div>
                            </div>
                            <input type="hidden" class="img" name="img[<?php echo $data['images'][4]['prp_id']?>]" value='<?php echo json_encode($data['images'][4],JSON_UNESCAPED_UNICODE);?>'>
                            <button type="button" class="layui-btn pro-img w100">上传图片</button>
                        </div>   
                   
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">互换号码</label>
            <div class="layui-inline">
                <table class="layui-table">
                    <colgroup>
                        <col width="200">
                        <col width="250">
                        <col>
                    </colgroup>
                    <thead>
                        <tr>
                            <th>
                                <i class="layui-icon layui-icon-add-1 marr10" onclick="additem(this)" style="color: #009688;">&#xe654;</i>
                                <span>厂商</span>
                            </th>
                            <th>互换号码</th>
                            <th>主号</th>
                            <th></th>
                        </tr> 
                    </thead>
                    <tbody>

                        <?php if(!empty($data['nums'])){?>
                        <?php foreach($data['nums'] as $v){?>
                        <tr>
                        <td>
                          <input type="text" name="num[prn_factory][<?php echo $v['prn_id'];?>]" placeholder="请输入厂商"  class="layui-input" value="<?php echo $v['prn_factory'];?>">
                        </td>
                        <td>
                          <input type="text" name="num[prn_display][<?php echo $v['prn_id'];?>]" placeholder="请输入互换号码"  class="layui-input" value="<?php echo $v['prn_display'];?>">
                        </td>
                        <td><input type="radio" name="mainNum" <?php if($v['prm_ismain']){echo 'checked';}?> value="<?php echo $v['prn_id'];?>"></td>
                        <td><i class="layui-icon layui-icon-delete delete-num-btn" style="font-size: 25px;"></i></td>
                        </tr>
                        <?php }?>
                        <?php }else{ ?>
                        <tr>
                        <td>
                          <input type="text" name="num[prn_factory][-1]" placeholder="请输入厂商"  class="layui-input">
                        </td>
                        <td>
                          <input type="text" name="num[prn_display][-1]" placeholder="请输入互换号码"  class="layui-input">
                        </td>
                        <td><input type="radio" name="mainNum" value="-1"></td>
                        <td><i class="layui-icon layui-icon-delete delete-num-btn" style="font-size: 25px;"></i></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div> 
		<div class="layui-form-item">
			<label class="layui-form-label">品牌</label>
			<div class="layui-input-inline">
				<div class="martb5">
					<input type="text" name="base[pro_make1]" placeholder="请输入<?php echo mvc::$cfg['LANG'][1];?>品牌" value="<?php echo $data['base']['pro_make1'];?>" class="layui-input w500" >
					<span class="gray"><?php echo mvc::$cfg['LANG'][1];?></span>
				</div>
				<div class="martb5">
					<input type="text" name="base[pro_make2]" placeholder="请输入<?php echo mvc::$cfg['LANG'][2];?>品牌" value="<?php echo $data['base']['pro_make2'];?>"  class="layui-input w500">
					<span class="gray"><?php echo mvc::$cfg['LANG'][2];?></span>
				</div>
				<div class="martb5">
					<input type="text" name="base[pro_make3]" placeholder="请输入<?php echo mvc::$cfg['LANG'][3];?>品牌" value="<?php echo $data['base']['pro_make3'];?>"  class="layui-input w500">
					<span class="gray"><?php echo mvc::$cfg['LANG'][3];?></span>
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">车型</label>
			<div class="layui-input-inline">
				<div class="martb5">
					<textarea name="base[pro_model1]" placeholder="请输入<?php echo mvc::$cfg['LANG'][1];?>车型" lay-verify="modellen" class="layui-textarea modelset"><?php echo $data['base']['pro_model1'];?></textarea>
					<span class="gray"><?php echo mvc::$cfg['LANG'][1];?></span>
				</div>
				<div class="martb5">
					<textarea name="base[pro_model2]" placeholder="请输入<?php echo mvc::$cfg['LANG'][2];?>车型" lay-verify="modellen" class="layui-textarea modelset"><?php echo $data['base']['pro_model2'];?></textarea>
					<span class="gray"><?php echo mvc::$cfg['LANG'][2];?></span>
				</div>
				<div class="martb5">
					<textarea name="base[pro_model3]" placeholder="请输入<?php echo mvc::$cfg['LANG'][3];?>车型" lay-verify="modellen" class="layui-textarea modelset"><?php echo $data['base']['pro_model3'];?></textarea>
					<span class="gray"><?php echo mvc::$cfg['LANG'][3];?></span>
				</div>
			</div>
		</div>
            <div class="layui-form-item layui-layout-admin">
                <div class="layui-input-block">
                  <div class="layui-footer" style="left: 0;">
                    <button class="layui-btn" lay-submit lay-filter="*">立即提交</button>
                  </div>
                </div>
            </div>
    </form>
</div>

<script>
layui.use('upload', function(){
    //图片上传
    var upload = layui.upload;
    //同时绑定多个元素，并将属性设定在元素上
    upload.render({
      elem: '.pro-img',
      accept:'images',
      acceptMime:'image/*',
      url:adminUri+'product/upload?type=pro&isAjax=1'
      ,done: function(res, index, upload){
        var item = this.item;
        item.parent('.layui-upload').find('img').attr('src',res['data']['src']);
        item.parent('.layui-upload').find('.img').val(JSON.stringify(res['info']));
      }
    });
});

layui.use('form', function(){
    var form = layui.form;
    form.verify({
        namefifter: function(value, item){ //value表单的值、item：表单的DOM对象
            if(value=='' && $("input[name='base[pro_name2]']").val()=="" && $("input[name='base[pro_name3]']").val()==""){
                return '名称至少输入一个';
            }
        },
        makefifter: function(value, item){ //value：表单的值、item：表单的DOM对象
            if(value=='' && $("input[name='data[pro_make2]']").val()=="" && $("input[name='data[pro_make2]']").val()==""){
                return '品牌至少输入一个';
            }
        },
        modelfifter: function(value, item){ //value：表单的值、item：表单的DOM对象
            if(value=='' && $("input[name='data[pro_model2]']").val()=="" && $("input[name='data[pro_model3]']").val()==""){
                return '车型至少输入一个';
            }
        },
        modellen: function(value, item){ //value：表单的值、item：表单的DOM对象
            if(value.length>255){
                return '车型长度不得超过255个字符！';
            }
        },
        //价格验证
        price:function(value){
            if(value!=""){
                if(!(/^[0-9]+\.?[0-9]*$/).test(value))
                {
                    return '价格必须是数字';
                }
            }
        }
    });
    //各种基于事件的操作，下面会有进一步介绍
    form.on('submit(*)', function(data){
        var style = $("#style").val(); 
        if($("input[name='base[pro_id]']").val()!="" && $("#status").val()=='1' && style!='1')
        {
            layer.confirm('提交后需要管理员重新审核，确认提交?', {icon: 3, title:'温馨提示'}, function(index){
                //do something
                $("#publish").submit();
                layer.close(index);
            });
            return false;
        }

    });
});
</script>
<script type="text/javascript">
    //添加互换号码
    function additem(e){
        var numId = $(e).parents('.layui-table').length+1;
        numId = -numId;
        var html = '<tr><td>'
        +'<input type="text" name="num[prn_factory]['+numId+']" placeholder="请输入厂商"  class="layui-input">'
        +'</td>'
        + '<td>'
        +  '<input type="text" name="num[prn_display]['+numId+']" placeholder="请输入互换号码"  class="layui-input" >'
        +'</td>'
        +'<td><input type="radio" value="'+numId+'" name="mainNum"></td>'
        +'<td><i class="layui-icon layui-icon-delete delete-num-btn" style="font-size: 25px;"></i></td>'
        +'</tr>';
        $(e).parents('.layui-table').find('tbody').append(html);
        layui.form.render();
        return false;
    }
    $(document).on('click','.delete-num-btn',function(){
        $(this).parents('tr').remove();
        layui.form.render();
    });
    $(".delete-pro-img").bind('click',function(){
        $(this).parents(".layui-upload").find("img").attr('src',adminUrl+"static/images/noimg.gif");
        $(this).parents(".layui-upload").find(".img").val("");
    });
    // 绑定悬浮事件
    $('.layui-upload').on( 'mouseenter', function() {
        if($(this).find("img").attr('src')!=adminUrl+"static/images/noimg.gif")
        {
            $(this).find(".del-wrapper").animate({height: 20});
        }
    });
    $('.layui-upload').on( 'mouseleave', function() {
        $(this).find(".del-wrapper").animate({height: 0});
    });
</script>
<?php include_once(mvc::$cfg['PATH_TPL'].'public/footBase.php');?>