<?php include_once(mvc::$cfg['PATH_TPL'].'public/headBase.php');?>
<div class="layui-fluid">
    <button class="layui-btn layui-bg-gray" style="margin-bottom: 10px;">
        批量添加 | 编辑配件信息
    </button>
    <button type="button" class="layui-btn" id="test1" style="margin-bottom: 10px;">
        <i class="layui-icon">&#xe67c;</i>上传Excel文件
    </button>
    <br/>
    <img src="<?php echo mvc::$cfg['HOST']['adminUrl'].'static/images/base2.png'?>">
    <br/>
    <span class="gray">若未填写配件ID则自动新增否则编辑</span>
    <br/>
    <br/>
    <button class="layui-btn layui-bg-gray" style="margin-bottom: 10px;">批量更新配件图片</button>
    <button type="button" class="layui-btn" id="test1" style="margin-bottom: 10px;">
        <i class="layui-icon">&#xe67c;</i>上传zip压缩文件
    </button>
    <br/>
    <img src="<?php echo mvc::$cfg['HOST']['adminUrl'].'static/images/pic.png'?>">
    <br/>
    <span class="gray">请将含有以上excel内容的文件和图片压缩至zip文件夹，选中zip文件夹批量更新</span>
</div>
<script>
layui.use('upload', function(){
  var upload = layui.upload;
   
  //执行实例
  var uploadInst = upload.render({
    elem: '#test1' //绑定元素
    ,accept:'file'
    ,dataType:'json'
    ,url: adminUri+'/product/impProBase?type=base' //上传接口
    ,done: function(res){
        if(res.status){
            //上传完毕回调
            layer.open({
              title: '温馨提示'
              ,content: '操作成功！'
            });   
        }else{
            layer.alert(res.data, {icon: 5,title:'温馨提示'});
        }
 
    }
  });
});
</script>
<?php include_once(mvc::$cfg['PATH_TPL'].'public/footBase.php');?>