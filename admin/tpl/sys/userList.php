<?php include_once(mvc::$cfg['PATH_TPL'].'public/headBase.php');?>            
<div class="mar5">
    <table class="layui-table">
        <thead>
            <tr>
                <th width="80">操作</th>
                <th width="80">账户</th>
                <th width="80">角色</th>
                <th width="50">状态</th>
				<th>公司</th>
				<th width="80">联系人</th>
                <th>手机</th>
                <th>电话</th>
                <th>微信</th>
                <th>QQ</th>
                <th width="120">最近登录</th>
            </tr> 
        </thead>
        <tbody>
            <?php if(!empty($data)){ ?>
            <?php foreach($data as $k => $v){ ?>
            <tr>
                <input type="hidden" class="uid" value="<?php echo $v['u_id'];?>">
                <input type="hidden" class="stat"  value="<?php echo $v['u_status'];?>">
                <td>
                    <a class="layui-btn layui-btn-xs" href="<?php echo mvc::$cfg['HOST']['adminUri'];?>sys/setUser?id=<?php echo $v['u_id'];?>">编辑</a>
                    <?php if($v['u_id']!=1){ ?>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" onclick='adminIndex.del("<?php echo $v['u_id'];?>","<?php echo $v['adm_username'];?>")'>删除</a>
                    <?php } ?>
                </td>
                <td><?php echo $v['u_name'];?></td>
                <td><?php if($v['u_id']=='1') echo "超级管理员"; else echo $typeDes[$v['u_style']];?></td>
                <td class="layui-form">
                    <input type="checkbox" name="switch" lay-skin="switch" lay-text="启用|禁用"<?php if($v['u_status']==1){echo 'checked';}?> >  
                </td>
				<td><?php echo $v['u_company'];?></td>
				<td><?php echo $v['u_realname'];?></td>
                <td><?php echo $v['u_mobile'];?></td>
                <td><?php echo $v['u_tel'];?></td>
                <td><?php echo $v['u_weixin'];?></td>
                <td><?php echo $v['u_qq'];?></td>
                <td><?php if(!empty($v['u_login_time'])){echo date('Y-m-d H:i',$v['u_login_time']);}?></td>
            </tr>
            <?php } ?>
            <?php }else{ ?>
            <tr>
                <td colspan="11" class="center warn">
                    无相关记录！
                </td>
            </tr> 
            <?php } ?>
        </tbody>
    </table>
   <div id="page-box" class="center"></div>
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

        $(".layui-form-switch").each(function(index,i){
            $(i).bind('click',function(){
                var id = $(this).parents('tr').find('.uid').val();
                var stat = $(this).parents('tr').find('.stat').val();
                var z = $(this);
                // 更新状态
                $.ajax({
                        url:adminUri+'user/ajaxStat?isAjax=1',
                        data:{'status':stat,'id':id},
                        dataType:'json',
                        type:'POST',
                        success:function(res){
                            if(res.status) {
                                //登入成功的提示与跳转
                                layer.msg('修改成功', {
                                    offset: '15px'
                                    ,icon: 1
                                    ,time: 1000
                                });
                                if(stat==1){
                                    z.parents('tr').find('.stat').val('-1');
                                }else{
                                    z.parents('tr').find('.stat').val('1');
                                }
                            }else{
                                layer.alert(res.data, {icon: 5,title:'温馨提示'});
                        }
                    }
                });
                

            });
        });


    });
</script>

<?php include_once(mvc::$cfg['PATH_TPL'].'public/footBase.php');?>      


