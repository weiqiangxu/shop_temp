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
                                显示列：
                                <div class="layui-inline" style="position: relative;">
                                <input type="hidden" value="<?php echo $colids;?>" id="showinp" name="show">
                                <input class="layui-input" value="<?php echo $str;?>" id="show" onclick="showCol()" autocomplete="off">
                                <div id="treeDemo-box" >
                                    <ul id="treeDemo" class="ztree"></ul>
                                </div>
                                </div>
                                &nbsp;
                                <button class="layui-btn" type="submit" data-type="reload">搜索</button>
                            </div>
                        </form>
                        <table class="layui-table">
                            <colgroup>
                                <col width="100">
                                <?php foreach($showWidth as $k=>$v){ ?>
                                <col width="<?php if(in_array($k, $show)) echo $v;?>">
                                <?php } ?>
                                <col>
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>操作</th>
                                    <?php foreach($all as $k=>$v){ ?>
                                    <?php if(in_array($k, $show)){ ?>
                                    <th><?php echo $v;?></th>
                                    <?php } ?>
                                    <?php } ?>
                                </tr> 
                            </thead>
                            <tbody>
                                <?php if(!empty($data)){ ?>
                                <?php foreach($data as $k => $v){ ?>
                                <tr>
                                    <td>
                                        <?php if($_SESSION['_style']=='2'){ ?>
                                        <a class="layui-btn layui-btn-xs" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/publish?proId=<?php echo $v['pro_id'];?>">编辑</a>
                                        <a class="layui-btn layui-btn-danger layui-btn-xs" onclick='product.del("<?php echo $v['pro_id'];?>")'>删除</a>
                                        <?php }elseif($_SESSION['_style']=='1'){ ?>
                                        <a class="layui-btn layui-btn-xs" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/check?proId=<?php echo $v['pro_id'];?>">审核</a>
                                        <?php }elseif($_SESSION['_style']=='0'){ ?>
                                        <a class="layui-btn layui-btn-xs" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>product/check?proId=<?php echo $v['pro_id'];?>">查看</a>
                                        <?php } ?>
                                    </td>
                                    <?php if(in_array('pro_id', $show)){ ?>
                                    <td><?php echo $v['pro_id'];?></td>
                                    <?php } ?>
                                    <?php if(in_array('u_realname', $show)){ ?>
                                    <td><?php echo $v['u_realname'];?></td>
                                    <?php } ?>
                                    <?php if(in_array('pro_status', $show)){ ?>
                                    <td class="<?php echo $proStatColor[$v['pro_status']];?>">
                                        <?php echo $proStat[$v['pro_status']];?>
                                    </td>
                                    <?php } ?>
                                    <?php if(in_array('img', $show)){ ?>
                                    <td>
                                        <div class="layui-table-cell laytable-cell-1-imgUrl">
                                            <img style="max-width: 50px;" src="<?php echo $v['imgUrl'];?>">
                                        </div>
                                    </td>
                                    <?php } ?>
                                    <?php if(in_array('pro_name1', $show)){ ?>
                                    <td><?php echo $v['pro_name1'];?></td>
                                    <?php } ?>
                                    <?php if(in_array('pro_name2', $show)){ ?>
                                    <td><?php echo $v['pro_name2'];?></td>
                                    <?php } ?>
                                    <?php if(in_array('pro_name3', $show)){ ?>
                                    <td><?php echo $v['pro_name3'];?></td>
                                    <?php } ?>
                                    <?php if(in_array('pro_make1', $show)){ ?>
                                    <td><?php echo $v['pro_make1'];?></td>
                                    <?php } ?>
                                    <?php if(in_array('pro_make2', $show)){ ?>
                                    <td><?php echo $v['pro_make2'];?></td>
                                    <?php } ?>
                                    <?php if(in_array('pro_make3', $show)){ ?>
                                    <td><?php echo $v['pro_make3'];?></td>
                                    <?php } ?>
                                    <?php if(in_array('pro_model1', $show)){ ?>
                                    <td><?php echo $v['pro_model1'];?></td>
                                    <?php } ?>
                                    <?php if(in_array('pro_model2', $show)){ ?>
                                    <td><?php echo $v['pro_model2'];?></td>
                                    <?php } ?>
                                    <?php if(in_array('pro_model3', $show)){ ?>
                                    <td><?php echo $v['pro_model3'];?></td>
                                    <?php } ?>
                                    <?php if(in_array('pro_price', $show)){ ?>
                                    <td class="right">
                                        <?php echo sprintf("%.2f",$v['pro_price']);?>
                                    </td>
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

                                    <?php if(in_array('pro_atime', $show)){ ?>
                                        <td>
                                            <?php if(!empty($v['pro_atime'])) echo date('Y-m-d H:i:s',$v['pro_atime']);?>
                                                
                                            </td>
                                    <?php } ?>
                                    <?php if(in_array('pro_etime', $show)){ ?>
                                        <td>
                                            <?php if(!empty($v['pro_etime'])) echo date('Y-m-d H:i:s',$v['pro_etime']);?>
                                            </td>
                                    <?php } ?>
                                </tr>
                                <?php } ?>
                                <?php }else{ ?>
                                    <tr>
                                        <td colspan="<?php echo count($show);?>" class="warn center">无相关记录！</td>
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
        

        function showCol(){
            $("#treeDemo-box").slideToggle();
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
            $("#showinp").val(ids.toString());
        }


    </script>




<?php include_once(mvc::$cfg['PATH_TPL'].'public/footBase.php');?>