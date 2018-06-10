<?php include_once(mvc::$cfg['PATH_TPL'].'public/headBase.php');?>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="" method="get" class="layui-form">
                            <div class="test-table-reload-btn" style="margin-bottom: 10px;">
                                关键字：
                                <div class="layui-inline">
                                <input class="layui-input" lay-tips="品牌、车型、名称模糊查找" name="name" value="<?php echo $Params['name'];?>" autocomplete="off">
                                </div>
                                &nbsp;
                                <?php if($_SESSION['_style']!='0'){ ?>
                                状态：
                                <div class="layui-inline">
                                    <select name="status" class="layui-inline">
                                        <option value="">全部</option>
                                        <option value="0" <?php if($Params['status']=='0'){echo 'selected';}?>>待审</option>
                                        <option value="1" <?php if($Params['status']=='1'){echo 'selected';}?>>已审核</option>
                                        <option value="2" <?php if($Params['status']=='2'){echo 'selected';}?>>审核不通过</option>
                                    </select>
                                </div>
                                <?php } ?>
                                &nbsp;
                                <button class="layui-btn" type="submit" data-type="reload">搜索</button>
                            </div>
                        </form>
                        <table class="layui-table">
                            <colgroup>
                                <col width="90">
                                <col width="50">
                                <col width="120">
                                <col width="160">
                                <col width="100">
                                <col width="100">
                                <col>
                                <col width="100">
                                <col width="100">
                                <col width="100">
                                <col width="100">
                                <col width="100">
                                <col width="100">
                                <col width="50">
                                <col width="80">
                                <col width="120">
                              </colgroup>
                            <thead>
                                <tr>
                                    <th>操作</th>
                                    <th>ID</th>
                                    <th>发布者</th>
                                    <th>状态</th>
                                    <th>图片</th>
                                    <th>产品名<br/>[<?php echo mvc::$cfg['LANG']['1'];?>]</th>
                                    <th>产品名<br/>[<?php echo mvc::$cfg['LANG']['2'];?>]</th>
                                    <th>产品名<br/>[<?php echo mvc::$cfg['LANG']['3'];?>]</th>
                                    <th>品牌<br/>[<?php echo mvc::$cfg['LANG']['1'];?>]</th>
                                    <th>品牌<br/>[<?php echo mvc::$cfg['LANG']['2'];?>]</th>
                                    <th>品牌<br/>[<?php echo mvc::$cfg['LANG']['3'];?>]</th>
                                    <th>车型<br/>[<?php echo mvc::$cfg['LANG']['1'];?>]</th>
                                    <th>车型<br/>[<?php echo mvc::$cfg['LANG']['2'];?>]</th>
                                    <th>车型<br/>[<?php echo mvc::$cfg['LANG']['3'];?>]</th>
                                    <th>价格</th>
                                    <th>添加时间</th>
                                </tr> 
                            </thead>
                            <tbody>
                                <?php if(!empty($data)){ ?>
                                <?php foreach($data as $k => $v){ ?>
                                <tr>
                                    <td>
                                        <div class="layui-table-cell laytable-cell-1-10">
                                        <?php if($_SESSION['_style']=='2'){ ?>
                                        <a class="layui-btn layui-btn-xs" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/publish?proId=<?php echo $v['pro_id'];?>">编辑</a>
                                        <a class="layui-btn layui-btn-danger layui-btn-xs" onclick='product.del("<?php echo $v['pro_id'];?>")'>删除</a>
                                        <?php }elseif($_SESSION['_style']=='1'){ ?>
                                        <a class="layui-btn layui-btn-xs" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/check?proId=<?php echo $v['pro_id'];?>">审核</a>
                                        <?php }elseif($_SESSION['_style']=='0'){ ?>
                                        <a class="layui-btn layui-btn-xs" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/check?proId=<?php echo $v['pro_id'];?>">查看</a>
                                        <?php } ?>
                                        </div>
                                    </td>
                                    <td><?php echo $v['pro_id'];?></td>
                                    <td><?php echo $v['u_realname'];?></td>
                                    <td class="<?php echo $proStatColor[$v['pro_status']];?>">
                                        <?php echo $proStat[$v['pro_status']];?>
                                    </td>
                                    <td>
                                        <div class="layui-table-cell laytable-cell-1-imgUrl"><img src="<?php echo $v['imgUrl'];?>"></div>
                                    </td>
                                    <td><?php echo $v['pro_name1'];?></td>
                                    <td><?php echo $v['pro_name2'];?></td>
                                    <td><?php echo $v['pro_name3'];?></td>

                                    <td><?php echo $v['pro_make1'];?></td>
                                    <td><?php echo $v['pro_make2'];?></td>
                                    <td><?php echo $v['pro_make3'];?></td>

                                    <td><?php echo $v['pro_model1'];?></td>
                                    <td><?php echo $v['pro_model2'];?></td>
                                    <td><?php echo $v['pro_model3'];?></td>

                                    <td><?php echo sprintf("%.2f",$v['pro_price']);?></td>
                                    <td><?php if(!empty($v['pro_atime'])) echo date('Y-m-d H:i:s',$v['pro_atime']);?></td>
                                </tr>
                                <?php } ?>
                                <?php }else{ ?>
                                    <tr>
                                        <td colspan="15" class="warn center">无相关记录！</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="layui-card-body">
        <div id="test-laypage-demoz"></div>
    </div>
    <script>
    layui.config({
        base: '<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/layuiAdmin/src/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'table'], function(){
        var laypage = layui.laypage;
        //总页数大于页码总数
        laypage.render({
            elem: 'test-laypage-demoz'
            ,limit : <?php echo $perPage;?>
            ,count: <?php echo $count;?> //数据总数
            ,curr:  <?php echo $page;?>//当前页
            ,jump: function(obj,first){
                if(!first){
                    //do something
                    location.href = adminUri+'product/list?<?php if(!empty($Params)){echo http_build_query($Params).'&';}?>page='+obj.curr;
                }
            }
        });
    });
    </script>








<?php include_once(mvc::$cfg['PATH_TPL'].'public/footBase.php');?>