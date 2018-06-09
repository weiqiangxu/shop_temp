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
        <label class="layui-form-label">配件图片：</label>
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
                        <td><input type="radio" readonly <?php if($v['prm_ismain']){echo 'checked';}?>></td>
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
</div>

<?php include_once(mvc::$cfg['PATH_TPL'].'public/footBase.php');?>