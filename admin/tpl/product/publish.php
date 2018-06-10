<?php include_once(mvc::$cfg['PATH_TPL'].'public/headBase.php');?>
<div class="layui-fluid">
    <form class="layui-form" method="post" action="" lay-filter="component-form-group">

        <div class="layui-col-md3">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <input type="hidden" name="base[pro_id]" value="<?php echo $Params['proId']?>">
                    <label class="layui-form-label" style="padding-left: 5px;">名称：</label>
                    <div class="layui-input-inline">
                        <table class="layui-table">
                          <colgroup>
                            <col width="50">
                            <col>
                          </colgroup>
                          <tbody>
                            <tr>
                              <td><?php echo mvc::$cfg['LANG'][1];?></td>
                              <td><input type="text" name="base[pro_name1]" lay-verify="namefifter" placeholder="请输入<?php echo mvc::$cfg['LANG'][1];?>名称" value="<?php echo $data['base']['pro_name1'];?>" autocomplete="off" class="layui-input" ></td>
                            </tr>
                            <tr>
                              <td><?php echo mvc::$cfg['LANG'][2];?></td>
                              <td><input type="text" name="base[pro_name2]" placeholder="请输入<?php echo mvc::$cfg['LANG'][2];?>名称" value="<?php echo $data['base']['pro_name2'];?>" autocomplete="off" class="layui-input" ></td>
                            </tr>
                            <tr>
                              <td><?php echo mvc::$cfg['LANG'][3];?></td>
                              <td><input type="text" name="base[pro_name3]" placeholder="请输入<?php echo mvc::$cfg['LANG'][3];?>名称" value="<?php echo $data['base']['pro_name3'];?>" autocomplete="off" class="layui-input" ></td>
                            </tr>
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-col-md3">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <input type="hidden" name="base[pro_id]" value="<?php echo $Params['proId']?>">
                    <label class="layui-form-label" style="padding-left: 5px;">品牌：</label>
                    <div class="layui-input-inline">
                        <table class="layui-table">
                          <colgroup>
                            <col width="50">
                            <col>
                          </colgroup>
                          <tbody>
                            <tr>
                              <td><?php echo mvc::$cfg['LANG'][1];?></td>
                              <td><input type="text" name="base[pro_make1]" placeholder="请输入<?php echo mvc::$cfg['LANG'][1];?>品牌" value="<?php echo $data['base']['pro_make1'];?>" autocomplete="off" class="layui-input" ></td>
                            </tr>
                            <tr>
                              <td><?php echo mvc::$cfg['LANG'][2];?></td>
                              <td><input type="text" name="base[pro_make2]" placeholder="请输入<?php echo mvc::$cfg['LANG'][2];?>品牌" value="<?php echo $data['base']['pro_make2'];?>" autocomplete="off" class="layui-input" ></td>
                            </tr>
                            <tr>
                              <td><?php echo mvc::$cfg['LANG'][3];?></td>
                              <td><input type="text" name="base[pro_make3]" placeholder="请输入<?php echo mvc::$cfg['LANG'][3];?>品牌" value="<?php echo $data['base']['pro_make3'];?>" autocomplete="off" class="layui-input" ></td>
                            </tr>
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-col-md3">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <input type="hidden" name="base[pro_id]" value="<?php echo $Params['proId']?>">
                    <label class="layui-form-label" style="padding-left: 5px;">车型：</label>
                    <div class="layui-input-inline">
                        <table class="layui-table">
                          <colgroup>
                            <col width="50">
                            <col>
                          </colgroup>
                          <tbody>
                            <tr>
                              <td><?php echo mvc::$cfg['LANG'][1];?></td>
                              <td><input type="text" name="base[pro_model1]" placeholder="请输入<?php echo mvc::$cfg['LANG'][1];?>车型" value="<?php echo $data['base']['pro_model1'];?>" autocomplete="off" class="layui-input" ></td>
                            </tr>
                            <tr>
                              <td><?php echo mvc::$cfg['LANG'][2];?></td>
                              <td><input type="text" name="base[pro_model2]" placeholder="请输入<?php echo mvc::$cfg['LANG'][2];?>车型" value="<?php echo $data['base']['pro_model2'];?>" autocomplete="off" class="layui-input" ></td>
                            </tr>
                            <tr>
                              <td><?php echo mvc::$cfg['LANG'][3];?></td>
                              <td><input type="text" name="base[pro_model3]" placeholder="请输入<?php echo mvc::$cfg['LANG'][3];?>车型" value="<?php echo $data['base']['pro_model3'];?>" autocomplete="off" class="layui-input" ></td>
                            </tr>
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">销售价：</label>
                <div class="layui-input-inline">
                    <input type="tel" maxlength="10" name="base[pro_price]" lay-verify="number|price" placeholder="请输入产品销售价" autocomplete="off" value="<?php if(!empty($data['base']['pro_price']) && $data['base']['pro_price']>0 ) echo sprintf("%.2f",$data['base']['pro_price']);?>" class="layui-input">
                </div>
            </div>         
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">配件图片：</label>
            <div class="layui-inline">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-upload">
                            <div class="layui-upload-list">
                            <?php if(!empty($data['images'][0]['prp_name'])){?>
                            <img class="layui-upload-img" src="<?php echo mvc::$cfg['HOST']['files'].$data['images'][0]['prp_path'].'100/'.$data['images'][0]['prp_name'].'.'.$data['images'][0]['prp_ext'];?>">
                            <?php }else{?>
                            <img src="<?php echo mvc::$cfg['HOST']['adminUrl'].'/static/images/noimg.gif'?>" class="layui-upload-img">
                            <?php }?>
                            </div>
                            <input type="hidden" class="img" name="img[<?php echo $data['images'][0]['prp_id']?>]" value='<?php echo json_encode($data['images'][0],JSON_UNESCAPED_UNICODE);?>'>
                            <button type="button" class="layui-btn pro-img">上传图片</button>
                        </div>   
                    </div>
                </div>
            </div>
            <div class="layui-inline">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-upload">
                            <div class="layui-upload-list">
                            <?php if(!empty($data['images'][1]['prp_name'])){?>
                            <img class="layui-upload-img" src="<?php echo mvc::$cfg['HOST']['files'].$data['images'][1]['prp_path'].'100/'.$data['images'][1]['prp_name'].'.'.$data['images'][1]['prp_ext'];?>">
                            <?php }else{?>
                            <img src="<?php echo mvc::$cfg['HOST']['adminUrl'].'/static/images/noimg.gif'?>" class="layui-upload-img">
                            <?php }?>
                            </div>
                            <input type="hidden" class="img" name="img[<?php echo $data['images'][1]['prp_id']?>]" value='<?php echo json_encode($data['images'][1],JSON_UNESCAPED_UNICODE);?>'>
                            <button type="button" class="layui-btn pro-img">上传图片</button>
                        </div>   
                    </div>
                </div>
            </div>
            <div class="layui-inline">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-upload">
                            <div class="layui-upload-list">
                            <?php if(!empty($data['images'][2]['prp_name'])){?>
                            <img class="layui-upload-img" src="<?php echo mvc::$cfg['HOST']['files'].$data['images'][2]['prp_path'].'100/'.$data['images'][2]['prp_name'].'.'.$data['images'][2]['prp_ext'];?>">
                            <?php }else{?>
                            <img src="<?php echo mvc::$cfg['HOST']['adminUrl'].'/static/images/noimg.gif'?>" class="layui-upload-img">
                            <?php }?>
                            </div>
                            <input type="hidden" class="img" name="img[<?php echo $data['images'][2]['prp_id']?>]" value='<?php echo json_encode($data['images'][2],JSON_UNESCAPED_UNICODE);?>'>
                            <button type="button" class="layui-btn pro-img">上传图片</button>
                        </div>   
                    </div>
                </div>
            </div>
            <div class="layui-inline">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-upload">
                            <div class="layui-upload-list">
                            <?php if(!empty($data['images'][3]['prp_name'])){?>
                            <img class="layui-upload-img" src="<?php echo mvc::$cfg['HOST']['files'].$data['images'][3]['prp_path'].'100/'.$data['images'][3]['prp_name'].'.'.$data['images'][3]['prp_ext'];?>">
                            <?php }else{?>
                            <img src="<?php echo mvc::$cfg['HOST']['adminUrl'].'/static/images/noimg.gif'?>" class="layui-upload-img">
                            <?php }?>
                            </div>
                            <input type="hidden" class="img" name="img[<?php echo $data['images'][3]['prp_id']?>]" value='<?php echo json_encode($data['images'][3],JSON_UNESCAPED_UNICODE);?>'>
                            <button type="button" class="layui-btn pro-img">上传图片</button>
                        </div>   
                    </div>
                </div>
            </div>
            <div class="layui-inline">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-upload">
                            <div class="layui-upload-list">
                            <?php if(!empty($data['images'][4]['prp_name'])){?>
                            <img class="layui-upload-img" src="<?php echo mvc::$cfg['HOST']['files'].$data['images'][4]['prp_path'].'100/'.$data['images'][4]['prp_name'].'.'.$data['images'][4]['prp_ext'];?>">
                            <?php }else{?>
                            <img src="<?php echo mvc::$cfg['HOST']['adminUrl'].'/static/images/noimg.gif'?>" class="layui-upload-img">
                            <?php }?>
                            </div>
                            <input type="hidden" class="img" name="img[<?php echo $data['images'][4]['prp_id']?>]" value='<?php echo json_encode($data['images'][4],JSON_UNESCAPED_UNICODE);?>'>
                            <button type="button" class="layui-btn pro-img">上传图片</button>
                        </div>   
                    </div>
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
                          <input type="text" name="num[prn_factory][<?php echo $v['prn_id'];?>]" required="" lay-verify="" placeholder="请输入厂商" autocomplete="off" class="layui-input" value="<?php echo $v['prn_factory'];?>">
                        </td>
                        <td>
                          <input type="text" name="num[prn_display][<?php echo $v['prn_id'];?>]" required="" lay-verify="" placeholder="请输入互换号码" autocomplete="off" class="layui-input" value="<?php echo $v['prn_display'];?>">
                        </td>
                        <td><input type="radio" name="mainNum" <?php if($v['prm_ismain']){echo 'checked';}?> value="<?php echo $v['prn_id'];?>"></td>
                        <td><i class="layui-icon layui-icon-delete delete-num-btn" style="font-size: 25px;"></i></td>
                        </tr>
                        <?php }?>
                        <?php }?>
                    </tbody>
                </table>
            </div>

        </div> 

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="*">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
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
      url:adminUri+'product/upload?type=pro'
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
        namefifter: function(value, item){ //value：表单的值、item：表单的DOM对象
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
        //价格验证
        price:function(value){
            if(value<=0){
              return '价格不正确';
            }
        }
    });
    //各种基于事件的操作，下面会有进一步介绍
    form.on('submit(*)', function(data){
        console.log(data.field);

    });
});
</script>
<script type="text/javascript">
    
    function additem(e){
        var numId = $(e).parents('.layui-table').length+1;
        numId = -numId;

        var html = '<tr><td>'
        +'<input type="text" name="num[prn_factory]['+numId+']" placeholder="请输入厂商" autocomplete="off" class="layui-input">'
        +'</td>'
        + '<td>'
        +  '<input type="text" name="num[prn_display]['+numId+']" placeholder="请输入互换号码" autocomplete="off" class="layui-input" >'
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

</script>



<?php include_once(mvc::$cfg['PATH_TPL'].'public/footBase.php');?>