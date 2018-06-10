<?php include_once(mvc::$cfg['PATH_TPL'].'public/headBase.php');?>
<div class="layui-fluid">
    <div class="layui-form-item">
        <label class="layui-form-label">基本信息：</label>
        <div class="layui-inline">
            <table class="layui-table" style="width: 800px;">
              <colgroup>
                <col width="80">
                <col width="150">
                <col width="80">
                <col width="150">
                <col width="80">
                <col>
              </colgroup>
              <tbody>
                <tr>
                  <th><?php echo mvc::$cfg['LANG'][1];?>名称</th>
                  <td><?php echo $data['base']['pro_name1'];?></td>
                  <th><?php echo mvc::$cfg['LANG'][2];?>品牌</th>
                  <td><?php echo $data['base']['pro_make1'];?></td>
                  <th><?php echo mvc::$cfg['LANG'][3];?>车型</th>
                  <td><?php echo $data['base']['pro_model1'];?></td>
                </tr>
                <tr>
                  <th><?php echo mvc::$cfg['LANG'][1];?>名称</th>
                  <td><?php echo $data['base']['pro_name2'];?></td>
                  <th><?php echo mvc::$cfg['LANG'][2];?>品牌</th>
                  <td><?php echo $data['base']['pro_make2'];?></td>
                  <th><?php echo mvc::$cfg['LANG'][3];?>车型</th>
                  <td><?php echo $data['base']['pro_model2'];?></td>
                </tr>
                <tr>
                  <th><?php echo mvc::$cfg['LANG'][1];?>名称</th>
                  <td><?php echo $data['base']['pro_name3'];?></td>
                  <th><?php echo mvc::$cfg['LANG'][2];?>品牌</th>
                  <td><?php echo $data['base']['pro_make3'];?></td>
                  <th><?php echo mvc::$cfg['LANG'][3];?>车型</th>
                  <td><?php echo $data['base']['pro_model3'];?></td>
                </tr>
                <tr>
                  <th>价格</th>
                  <td colspan="5"><?php echo sprintf("%.2f",$data['base']['pro_price']);?></td>
                </tr>
              </tbody>
            </table>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">产品图片：</label>
        <div class="layui-inline">
            <table class="layui-table" style="width: 600px;">
              <colgroup>
                <col width="100">
                <col width="100">
                <col width="100">
                <col width="100">
                <col>
              </colgroup>
              <tbody>
                <tr> 
                    <td>
                        <div class="layui-table-cell laytable-cell-1-imgUrl">
                            <?php if(!empty($data['images'][0]['prp_name'])){?>
                            <img class="layui-upload-img" src="<?php echo mvc::$cfg['HOST']['files'].$data['images'][0]['prp_path'].'100/'.$data['images'][0]['prp_name'].'.'.$data['images'][0]['prp_ext'];?>">
                            <?php }else{?>
                            <img src="<?php echo mvc::$cfg['HOST']['adminUrl'].'/static/images/noimg.gif'?>" class="layui-upload-img">
                            <?php }?>
                        </div>
                    </td>
                    <td>
                        <div class="layui-table-cell laytable-cell-1-imgUrl">
                            <?php if(!empty($data['images'][1]['prp_name'])){?>
                            <img class="layui-upload-img" src="<?php echo mvc::$cfg['HOST']['files'].$data['images'][1]['prp_path'].'100/'.$data['images'][1]['prp_name'].'.'.$data['images'][1]['prp_ext'];?>">
                            <?php }else{?>
                            <img src="<?php echo mvc::$cfg['HOST']['adminUrl'].'/static/images/noimg.gif'?>" class="layui-upload-img">
                            <?php }?>
                        </div>
                    </td>
                    <td>
                        <div class="layui-table-cell laytable-cell-1-imgUrl">
                            <?php if(!empty($data['images'][2]['prp_name'])){?>
                            <img class="layui-upload-img" src="<?php echo mvc::$cfg['HOST']['files'].$data['images'][2]['prp_path'].'100/'.$data['images'][2]['prp_name'].'.'.$data['images'][2]['prp_ext'];?>">
                            <?php }else{?>
                            <img src="<?php echo mvc::$cfg['HOST']['adminUrl'].'/static/images/noimg.gif'?>" class="layui-upload-img">
                            <?php }?>
                        </div>
                    </td>
                    <td>
                        <div class="layui-table-cell laytable-cell-1-imgUrl">
                            <?php if(!empty($data['images'][3]['prp_name'])){?>
                            <img class="layui-upload-img" src="<?php echo mvc::$cfg['HOST']['files'].$data['images'][3]['prp_path'].'100/'.$data['images'][3]['prp_name'].'.'.$data['images'][3]['prp_ext'];?>">
                            <?php }else{?>
                            <img src="<?php echo mvc::$cfg['HOST']['adminUrl'].'/static/images/noimg.gif'?>" class="layui-upload-img">
                            <?php }?>
                        </div>
                    </td>
                    <td>
                        <div class="layui-table-cell laytable-cell-1-imgUrl">
                            <?php if(!empty($data['images'][4]['prp_name'])){?>
                            <img class="layui-upload-img" src="<?php echo mvc::$cfg['HOST']['files'].$data['images'][4]['prp_path'].'100/'.$data['images'][4]['prp_name'].'.'.$data['images'][4]['prp_ext'];?>">
                            <?php }else{?>
                            <img src="<?php echo mvc::$cfg['HOST']['adminUrl'].'/static/images/noimg.gif'?>" class="layui-upload-img">
                            <?php }?>
                        </div>
                    </td>
                </tr>
              </tbody>
            </table>
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
                            <span>厂商</span>
                            </th>
                            <th>互换号码</th>
                            <th>主号</th>
                        </tr> 
                    </thead>
                    <tbody>
                        <?php if(!empty($data['nums'])){?>
                        <?php foreach($data['nums'] as $v){?>
                        <tr>
                        <td><?php echo $v['prn_factory'];?></td>
                        <td><?php echo $v['prn_display'];?></td>
                        <td><input type="radio" disabled readonly <?php if($v['prm_ismain']){echo 'checked';}?>></td>
                        </tr>
                        <?php }?>
                        <?php }?>
                    </tbody>
                </table>
            </div>
        </div> 

        <?php if($_SESSION['_style']==1){ ?>
        <form class="layui-form" action="" method="post">
        <input type="hidden" name="pro_id" value="<?php echo $data['base']['pro_id'];?>">
        <div class="layui-form-item">
            <label class="layui-form-label">产品审核</label>
            <div class="layui-inline">
                    <div class="layui-form-item">
                    <div class="layui-input-inline" style="width: 350px;">
                      <input type="radio" name="status" value="0" title="待审" <?php if($data['base']['pro_status']==0)echo 'checked';?>>
                      <input type="radio" name="status" value="1" title="已审" <?php if($data['base']['pro_status']==1)echo 'checked';?>>
                      <input type="radio" name="status" value="2" title="审核不通过" <?php if($data['base']['pro_status']==2)echo 'checked';?>>
                    </div>
                  </div>
                  <div class="layui-form-item layui-form-text">
                    <div class="layui-input-inline" style="width: 498px;">
                      <textarea lay-verify="remark" name="remark" style="resize: none;" placeholder="审核备注" class="layui-textarea"><?php echo $data['base']['pro_check_detail']['remark'];?></textarea>
                    </div>
                  </div>
            </div>
        </div> 
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="*">立即提交</button>
            </div>
        </div>
        </form>
        <?php } ?>


        <?php if($_SESSION['_style']==0){ ?>
        <div class="layui-form-item layui-form">
            <label class="layui-form-label">审核详情</label>
            <div class="layui-inline">
              <table class="layui-table" style="width: 600px;">
                <colgroup>
                  <col width="80">
                  <col width="190">
                  <col width="60">
                  <col>
                </colgroup>
                <tbody>
                  <tr>
                    <th>产品状态</th>
                    <td colspan="3" class="<?php echo $proStatColor[$data['base']['pro_status']];?>">
                        <?php echo $proStat[$data['base']['pro_status']];?>
                    </td>
                  </tr>
                  <tr>
                    <th>审核人</th>
                    <td><?php echo $data['base']['pro_check_detail']['u_name'];?></td>
                    <th>时间</th>
                    <td><?php echo date('Y-m-d H:i:s',$data['base']['pro_check_detail']['time']);?></td>
                  </tr>
                  <tr>
                    <th>备注</th>
                    <td colspan="3"><?php echo $data['base']['pro_check_detail']['remark'];?></td>
                  </tr>
                </tbody>
              </table>
            </div>
        </div>
        <?php } ?> 
</div>
<script>
//Demo
layui.use('form', function(){
  var form = layui.form;
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
  //监听提交
  form.on('submit(*)', function(data){
    $.ajax({
        url:adminUri+'product/ajaxCheck?isAjax=1',
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
                layer.alert(res.data, {icon: 5,title:'温馨提示'});
            }
        }
    });
    return false;
  });
});
</script>
<?php include_once(mvc::$cfg['PATH_TPL'].'public/footBase.php');?>