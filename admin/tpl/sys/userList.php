<?php include_once(mvc::$cfg['PATH_TPL'].'public/headBase.php');?>            
<div class="layui-fluid">
    <table class="layui-table">
        <colgroup>
            <col width="90">
            <col width="50">
            <col width="100">
            <col width="60">
            <col width="80">
            <col width="80">
            <col width="100">
            <col width="100">
            <col width="100">
            <col width="100">
            <col width="100">
            <col width="150">
            <col>
          </colgroup>
        <thead>
            <tr>
                <th>操作</th>
                <th>ID</th>
                <th>角色</th>
                <th>状态</th>
                <th>账户</th>
                <th>姓名</th>
                <th>手机</th>
                <th>电话</th>
                <th>微信</th>
                <th>QQ</th>
                <th>公司</th>
                <th>添加时间</th>
                <th>最近登录</th>
            </tr> 
        </thead>
        <tbody>
            <?php if(!empty($data)){ ?>
            <?php foreach($data as $k => $v){ ?>
            <tr>
                <td>
                    <div class="layui-table-cell laytable-cell-1-10">
                    <a class="layui-btn layui-btn-xs" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>sys/setUser?id=<?php echo $v['u_id'];?>" target="_blank">
                        编辑
                    </a>
                    <?php if($v['u_id']!=1){ ?>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" onclick='adminIndex.del("<?php echo $v['u_id'];?>","<?php echo $v['adm_username'];?>")'>
                        删除
                    </a>
                    <?php } ?>
                    </div>
                </td>
                <td><?php echo $v['u_id'];?></td>
                <td><?php if($v['u_id']=='1') echo "超级管理员"; else echo $typeDes[$v['u_style']];?></td>
                <td>
                    <?php if($v['u_status']=='-1'){ ?>
                        <span class="red">
                            <?php echo $userStat[$v['u_status']];?>
                        </span>
                    <?php }else{ ?>
                        <?php echo $userStat[$v['u_status']];?>
                    <?php } ?>
                </td>
                <td><?php echo $v['u_name'];?></td>
                <td><?php echo $v['u_realname'];?></td>
                <td><?php echo $v['u_mobile'];?></td>
                <td><?php echo $v['u_tel'];?></td>
                <td><?php echo $v['u_weixin'];?></td>
                <td><?php echo $v['u_qq'];?></td>
                <td><?php echo $v['u_company'];?></td>
                <td><?php if(!empty($v['u_reg_time'])){echo date('Y-m-d H:i:s',$v['u_reg_time']);}?></td>
                <td><?php if(!empty($v['u_login_time'])){echo date('Y-m-d H:i:s',$v['u_login_time']);}?></td>
            </tr>
            <?php } ?>
            <?php }else{ ?>
            <tr>
                <td colspan="13" class="center warn">
                    无相关记录！
                </td>
            </tr> 
            <?php } ?>
        </tbody>
    </table>
   <div id="page-box"></div>
</div>

<script>
    layui.config({
        base: '<?php echo mvc::$cfg['HOST']['adminUrl'];?>static/layuiAdmin/src/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'set','laypage'],function(){
        var laypage = layui.laypage;
        //总页数大于页码总数
        laypage.render({
            elem: 'page-box'
            ,limit : <?php echo $perPage;?>
            ,count: <?php echo $count;?> //数据总数
            ,curr:  <?php echo $page;?>//当前页
            ,jump: function(obj,first){
                if(!first){
                    //do something
                    location.href = adminUri+'sys/userList?<?php if(!empty($Params)){echo http_build_query($Params).'&';}?>page='+obj.curr;
                }
            }
        });
    });
</script>

<?php include_once(mvc::$cfg['PATH_TPL'].'public/footBase.php');?>      


