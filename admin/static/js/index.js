$(document).ready(function(){
    $('#mainFrame').height($(window).height()-63);
    $('#mainFrame').width($(window).width());
    // $('.iframes').height($(window).height()-15);
    // $(window).resize(function(){ $('#mainframe').height($(window).height());
    // $('.iframes').height($(window).height()-15);});
    // if(document.title != '') parent.document.title = document.title;
    $('#SearchFrame').height(window.outerHeight-63);
    // $('#SearchFrame').width(350);
    $('#ListFrame').height(window.outerHeight-63);
    // $('#ListFrame').width($(window).width()-340);
});

/**
 * 后台管理员登出
 *
 * @return 
 */
var adminIndex = {
    //登出确认弹框 
    logout: function(){
        layer.confirm('确定要退出当前系统吗?', {icon: 3, title:'温馨提示'}, function(index){
            //do something
            window.location.href = adminUri+'user/logout';
        });
    },
    //删除 
    del: function(id,name){
        layer.confirm('确定要删除管理员 '+name+' 吗?', {icon: 3, title:'温馨提示'}, function(index){
            //do something
            $.ajax({
                    url:adminUri+'sys/ajaxDelAdmin?isAjax=1',
                    data:{'id':id},
                    dataType:'json',
                    type:'POST',
                    success:function(res){
                        if(res.status) {
                            //登入成功的提示与跳转
                            layer.msg('删除成功', {
                                offset: '15px'
                                ,icon: 1
                                ,time: 1000
                            },function(){
                                location.reload();
                            });
                        }else{
                            layer.alert(res.data, {icon: 5,title:'温馨提示'});
                    }
                }
            });
        });
    }
};

/**
 * 产品编辑
 * @return 
 */
var product = {
    //登出确认弹框 
    del: function(id){
        layer.confirm('确定要删除产品吗?', {icon: 3, title:'温馨提示'}, function(index){
            //do something
            $.ajax({
                    url:adminUri+'product/deleteProduct?isAjax=1',
                    data:{'id':id},
                    dataType:'json',
                    type:'POST',
                    success:function(res){
                        if(res.status) {
                            //登入成功的提示与跳转
                            layer.msg('删除成功', {
                                offset: '15px'
                                ,icon: 1
                                ,time: 1000
                            },function(){
                                location.reload();
                            });
                        }else{
                            layer.alert(res.data, {icon: 5,title:'温馨提示'});
                    }
                }
            });
        });
    }
};