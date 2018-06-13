<?php include_once(mvc::$cfg['PATH_TPL'].'public/headBase.php');?>
<div class="layui-fluid" id="check">

    <div class="layui-form-item">
        <label class="layui-form-label">名称</label>
        <div class="layui-inline">
            <table class="layui-table" style="width: 800px;">
              <colgroup>
                <col width="80">
                <col>
              </colgroup>
              <tbody>
                <tr>
                  <th class="right"><?php echo mvc::$cfg['LANG'][1];?></th>
                  <td><?php echo $data['base']['pro_name1'];?></td>
                </tr>
                <tr>
                  <th class="right"><?php echo mvc::$cfg['LANG'][2];?></th>
                  <td><?php echo $data['base']['pro_name2'];?></td>
                </tr>
                <tr>
                  <th class="right"><?php echo mvc::$cfg['LANG'][3];?></th>
                  <td><?php echo $data['base']['pro_name3'];?></td>
                </tr>
              </tbody>
            </table>
        </div>
    </div>

	<div class="layui-form-item">
        <label class="layui-form-label">价格</label>
        <div class="layui-inline" style="padding: 9px 0px;font-weight: 400;line-height: 20px;"><?php echo sprintf("%.2f",$data['base']['pro_price']);?> 元</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">产品图片</label>
        <div class="layui-inline">
            <table class="layui-table">
              <tbody>
                <tr> 
                  <?php if(!empty($data['images'])){ ?>
                  <?php foreach($data['images'] as $k=>$v){ ?>
                  <td>
                      <div class="layui-table-cell laytable-cell-1-imgUrl">
                          <?php if(!empty($data['images'][$k]['prp_name'])){?>
                          <a href="<?php echo mvc::$cfg['HOST']['files'].$data['images'][$k]['prp_path'].'240/'.$data['images'][$k]['prp_name'].'.'.$data['images'][$k]['prp_ext'];?>" target="_blank">
                          <img class="layui-upload-img" src="<?php echo mvc::$cfg['HOST']['files'].$data['images'][$k]['prp_path'].'100/'.$data['images'][$k]['prp_name'].'.'.$data['images'][$k]['prp_ext'];?>">
                          </a>
                          <?php }else{?>
                          <img src="<?php echo mvc::$cfg['HOST']['adminUrl'].'/static/images/noimg.gif'?>" class="layui-upload-img">
                          <?php }?>
                      </div>
                  </td>
                  <?php } ?>
                  <?php }else{ ?>
                    <td style="border: 0px;">
                      <div class="layui-table-cell laytable-cell-1-imgUrl">
                          <img src="<?php echo mvc::$cfg['HOST']['adminUrl'].'/static/images/noimg.gif'?>" class="layui-upload-img">
                      </div>
                    </td>
                  <?php } ?>
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
                        <?php }else{ ?>
                          <tr><td colspan="3" class="center warn"></td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div> 

        <div class="layui-form-item">
            <label class="layui-form-label">品牌</label>
            <div class="layui-inline">
                <table class="layui-table" style="width: 800px;">
                  <colgroup>
                    <col width="60">
                    <col>
                  </colgroup>
                  <tbody>
                    <tr>
                      <th class="right"><?php echo mvc::$cfg['LANG'][1];?></th>
                      <td><?php echo $data['base']['pro_make1'];?></td>
                    </tr>
                    <tr>
                      <th class="right"><?php echo mvc::$cfg['LANG'][2];?></th>
                      <td><?php echo $data['base']['pro_make2'];?></td>
                    </tr>
                    <tr>
                      <th class="right"><?php echo mvc::$cfg['LANG'][3];?></th>
                      <td><?php echo $data['base']['pro_make3'];?></td>
                    </tr>
                  </tbody>
                </table>
            </div>
        </div>

        <div class="layui-form-item">
          <label class="layui-form-label">车型</label>
          <div class="layui-input-inline max-width-max">
              <table class="layui-table">
                    <colgroup>
                        <col width="60">
                        <col>
                    </colgroup>
                    <tbody>
                        <?php if(!empty($data['base']['pro_model1'])){ ?>
                        <tr>
                          <th><?php echo mvc::$cfg['LANG'][1];?></th>
                          <td><?php echo $data['base']['pro_model1'];?></td>
                        </tr>
                        <?php } ?>
                        <?php if(!empty($data['base']['pro_model2'])){ ?>
                        <tr>
                          <th><?php echo mvc::$cfg['LANG'][2];?></th>
                          <td><?php echo $data['base']['pro_model2'];?></td>
                        </tr>
                        <?php } ?>
                        <?php if(!empty($data['base']['pro_model3'])){ ?>
                        <tr>
                          <th><?php echo mvc::$cfg['LANG'][3];?></th>
                          <td><?php echo $data['base']['pro_model3'];?></td>
                        </tr>
                        <?php } ?>
                        <?php if(empty($data['base']['pro_model1'])&&empty($data['base']['pro_model2'])&&empty($data['base']['pro_model3'])){ ?>
                          
                        <?php } ?>
                    </tbody>
                </table>
          </div>
        </div>


        <?php if($data['base']['pro_status']!=1){ ?>
        <?php if($_SESSION['_style']==1){ ?>
        <form class="layui-form" action="" method="post">
        <input type="hidden" name="pro_id" value="<?php echo $data['base']['pro_id'];?>">
        <div class="layui-form-item" style="margin-bottom: 15px;">
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
                      <textarea lay-verify="remark" name="remark" style="resize: none;margin-top: 10px;" placeholder="审核备注" class="layui-textarea"><?php echo $data['base']['pro_check_detail']['remark'];?></textarea>
                    </div>
                  </div>
            </div>
        </div> 
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="*">提交审核结果</button>
            </div>
        </div>
        </form>
        <?php } ?>
        <?php } ?>


        <?php if($_SESSION['_style']==0){ ?>

          <div class="layui-form-item layui-form">
            <label class="layui-form-label">发布者详情</label>
            <div class="layui-inline">
              <table class="layui-table" style="width: 600px;">
                <colgroup>
                  <col width="80">
                  <col width="190">
                  <col width="75">
                  <col>
                </colgroup>
                <tbody>
                  <tr>
                    <th class="right">公司</th>
                    <td colspan="3">
                        <?php echo $data['base']['u_company'];?>
                    </td>
                  </tr>
                  <tr>
                    <th class="right">联系人</th>
                    <td><?php echo $data['base']['u_name'];?></td>
                    <th class="right">电话</th>
                    <td><?php echo $data['base']['u_mobile'];?><?php if(!empty($data['base']['u_tel'])) echo ' / '.$data['base']['u_tel'];?></td>
                  </tr>
                  <tr>
                    <th class="right">发布时间</th>
                    <td><?php echo date("Y-m-d H:i:s",$data['base']['pro_atime']);?></td>
                    <th class="right">最近编辑</th>
                    <td><?php echo date("Y-m-d H:i:s",$data['base']['pro_etime']);?></td>
                  </tr>
                </tbody>
              </table>
            </div>
        </div>
        <?php } ?>


        <?php if($_SESSION['_style']==1 && $data['base']['pro_status']!='0'){ ?>
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
                    <th class="right">产品状态</th>
                    <td colspan="3" class="<?php echo $proStatColor[$data['base']['pro_status']];?>">
                        <?php echo $proStat[$data['base']['pro_status']];?>
                    </td>
                  </tr>
                  <tr>
                    <th class="right">审核人</th>
                    <td><?php echo $data['base']['pro_check_detail']['u_name'];?></td>
                    <th class="right">时间</th>
                    <td><?php echo date('Y-m-d H:i:s',$data['base']['pro_check_detail']['time']);?></td>
                  </tr>
                  <tr>
                    <th class="right">备注</th>
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