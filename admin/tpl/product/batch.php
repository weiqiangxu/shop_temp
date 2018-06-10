<?php include_once(mvc::$cfg['PATH_TPL'].'public/headBase.php');?>
<div class="layui-fluid">
    <button class="layui-btn layui-bg-gray" style="margin-bottom: 10px;">
        批量添加 | 编辑产品信息
    </button>
    <button class="layui-btn layui-bg-cyan" onclick="location.href='<?php echo mvc::$cfg['HOST']['adminUrl']?>static/css/base.xls'" style="margin-bottom: 10px;">
        <i class="layui-icon layui-icon-download-circle"></i>
        模板下载
    </button>
    <button type="button" class="layui-btn" id="probase" style="margin-bottom: 10px;">
        <i class="layui-icon">&#xe67c;</i>上传Excel文件
    </button>
    <br/>
    <img src="<?php echo mvc::$cfg['HOST']['adminUrl'].'static/images/base2.png'?>">
    <br/>
    <div class="gray">1、若未填写产品ID则新增产品</div>
    <div class="gray">2、如填写产品ID则编辑产品信息</div>
    <br/>
    <br/>
    <button class="layui-btn layui-bg-gray" style="margin-bottom: 10px;">批量更新产品图片</button>
    <button class="layui-btn layui-bg-cyan" onclick="location.href='<?php echo mvc::$cfg['HOST']['adminUrl']?>static/css/model.zip'" style="margin-bottom: 10px;">
        <i class="layui-icon layui-icon-download-circle"></i>
        模板下载
    </button>
    <button type="button" class="layui-btn" id="piczip" style="margin-bottom: 10px;">
        <i class="layui-icon">&#xe67c;</i>上传zip压缩文件
    </button>
    <br/>
    <div class="gray">1、请图片压缩放置于一个文件夹中，将该文件夹压缩为zip文件夹，选中zip文件夹上传批量更新</div>
    <div class="gray">2、图片命名使用'产品ID.png'若是排序图片则'产品ID@图片序号.jpg/png/',如果序号大于实际图片数目则自动由小到大排序。图片最多五张。</div>
</div>
<script>
layui.use('upload', function(){
  var upload = layui.upload;
   
  //执行实例
  var uploadInst = upload.render({
    elem: '#probase' //绑定元素
    ,accept:'file'
    ,dataType:'json'
    ,url: adminUri+'/product/impProBase?type=base&isAjax=1' //上传接口
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
    ,error: function(){
      //请求异常回调
    }
  });

    //执行实例
  var uploadInstPic = upload.render({
    elem: '#piczip' //绑定元素
    ,accept:'file'
    ,dataType:'json'
    ,url: adminUri+'/product/impProImg?type=base&isAjax=1' //上传接口
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
    ,error: function(){
      //请求异常回调
    }
  });

});
</script>
<?php include_once(mvc::$cfg['PATH_TPL'].'public/footBase.php');?>