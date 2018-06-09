$(document).ready(function(){
	
});


var AJAX = {
	//同步执行，一般在表单验证时使用
	synchronize: function(atype, aurl, adata){
		var return_data = {};
		adata['isAjax'] = 1;
		$.ajax({
			type: atype,
			url: aurl,
			async: false,
			data: adata,
			dataType: "json",
			success:function(data){
				return_data = data;
			}
		});
		return return_data;
	}
};

function ChangeCheckCode(ToId)
{
	$('#'+ToId+'').attr('src', ADMIN_URI+'/user/checkcode?rand='+Math.random());
}

/*
*分页使用
*/
function SetCookiePage(value)
{
	$.cookie('Count',value,{ expires: 7 }); 
}


/*
*全选/全不选
*/
function SelectAll(Obj, Name)
{
	$("input[name='"+Name+"']:not(:disabled)").prop('checked', Obj.checked);
}

/*
*反选
*/
function Inverse(Name)
{
	$("input[name='"+Name+"']:not(:disabled)").each(function(){
		$(this).prop('checked', !$(this).prop('checked'));
	});
}

/*
*监听复选框并给选中的表格添加选中样式
*/
function ListenCheckbox(Name)
{
	$("input[name='"+Name+"']").change(function(){
		if($(this).prop('checked')){
			$(this).parent().parent().addClass('selectbg');
		}else{
			$(this).parent().parent().removeClass('selectbg');
		}
	});
}

/*
*给选中的值
array
*/
function GetCheckboxVal(Name)
{
	var Val = new Array();
	$("input[name='"+Name+"']:checked").each(function(){
		Val.push(this.value);
	});
	return Val;
}

/*
*检查值是否在json中，如果存在则返回ID
*只适合一维
*/
function InJson(SearchVal, Json)
{
	SearchVal = $.trim(SearchVal.toUpperCase());
	for(var Key in Json)
	{
		if($.trim(Json[Key].toUpperCase()) == SearchVal) return Key;
	}
	return false;
}

//验证码
var IMGCODE = {
	change:function(toid, type){
		$('#'+toid+'').attr('src', '/index/imgcode?type='+type+'&t='+Math.random());
	},
	check:function(val){
		return AJAX.synchronize("post", "/index/checkImgcode", {"val": val});
	}
};

var MOBILECODE = {
	//mobile手机号码, code验证码, act功能(bind|unbind|password)
	check:function(mobile, code, act){
		return AJAX.synchronize("post", "/index/checkMobilecode", {'mobile': mobile, 'code': code, 'act':act});
	}
};

//常用JS验证
var VERIFICATION = {
	email: function(val){ return (/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/).test($.trim(val)); },
	imgcode: function(val){ return (/^[0-9a-zA-Z]{5}$/).test($.trim(val)); },
	username: function(val){ return (/^[a-zA-Z][a-zA-Z0-9_]{3,19}$/).test($.trim(val)); },
	mobile: function(val){ return (/^1\d{10}$/).test($.trim(val)); },
	mobile_code: function(val){ return (/^\d{6}$/).test($.trim(val)); },
	url:function(val){return (/^((https|http|ftp|rtsp|mms)?:\/\/)[^\s]+/).test($.trim(val)); },
	realname:function(val){return (/^[\u4E00-\u9FA5]{2,4}$/).test($.trim(val)); },
	idcard:function(val){return (/^\d{17}[\d|x]|\d{15}$/).test($.trim(val)); },
	mempassword:function(val){return (/^\S{6,12}$/u).test($.trim(val));},//6到12个任意非空白字符
	memqq:function(val){return (/^[0-9]+$/).test($.trim(val));},//纯数字
	//强制保留小数点位数
	floatNum:function(num, count){
		var f = parseFloat(num);
        if (isNaN(f))  return false;
        var f = new Number(num).toFixed(count);
        var s = f.toString();
        var rs = s.indexOf('.');
        if (rs < 0) {
            rs = s.length;
            s += '.';
        }
        while (s.length <= rs + count) {
            s += '0';
        }
        return parseFloat(s).toFixed(count);
	}
};

/**
  * @method 消息弹窗
  * @author soul 2017/6/16
  * @example MESSAGE.alert('aa');
			 MESSAGE.confirm('确定要删除此记录吗', "test(1,2)"); callback为回调方法的字符串
			 MESSAGE.show('跑街的用户注册协议', '协议内容', '<button type="button" class="btn btn-primary">确定</button><button type="button" class="btn btn-default message_close">取消</button>');
  */
var MESSAGE = {
	alert: function(msg){
		MESSAGE.allType('alert', '消息提示', msg, '', '');
	},
	//tarId【缺省】指定加载的标签ID
	show:function(title, msg, foot, tarId){
		if(!arguments[3]) var tarId = '';
		MESSAGE.allType('show', title, msg, foot, tarId);
	},
	confirm: function(msg, callback){
		MESSAGE.allType('confirm', '温馨提醒', msg, callback, '');
	},
	ajaxError: function(json){
		var errorMsg = '';
		if(json.hasOwnProperty("status") && json.status){
		}else if(json.hasOwnProperty("code")){
			if(json.code == 'noLogin'){
				TOURL.login();
				return false;
			}
			errorMsg = json.data;
		}else{
			errorMsg = '网络异常，请稍后重试！';
		}

		if(errorMsg != ''){
			if($('.message_box:visible').length > 0){
				alert(json.data);
			}else{
				MESSAGE.allType('alert', '消息提示', json.data, '', '');
			}
			return false;
		}
		return true;
	},
	//可以移动的
	moveBox: function(title, msg, foot, tarId){
		if(!arguments[3]) var tarId = '';
		MESSAGE.allType('move', title, msg, foot, tarId);
	},
	allType: function(type, title, msg, foot, tarId){
		var randClass = '';
		if(tarId == '')
		{
			randClass = ' message_rand';
			tarId = 'massage_'+ (new Date().getTime() + Math.ceil(Math.random()*1000000));
		}

		$('.message_box:not("#'+tarId+'")').hide();
		if($('#'+tarId).length < 1)
		{
			if(type == 'confirm'){
				var footHtml = '<div class="massage_foot"><button type="button" class="btn btn-primary confirmOk">确定</button><button type="button" class="btn btn-default message_close">取消</button></div>';
			}else{
				var footHtml = foot == ''? '': '<div class="massage_foot">'+foot+'</div>';
			}
			var tplHtml = '<div class="message_box message_'+type+randClass+'" id="'+tarId+'">'
				+'<div class="message_head"><h4 class="message_title">'+title+'</h4><button type="button" class="close message_close"><span aria-hidden="true">×</span></button><div class="clear"></div></div>'
				+'<div class="massage_body">'+msg+'</div>'+footHtml+'</div>';
		   $('body').append(tplHtml);
		}
		if(type != 'move') $('#mask_layer').show();
		$('#'+tarId).show();
		MESSAGE.resize(tarId);
		$(window).resize(function(){MESSAGE.resize(tarId);});
		$('#'+tarId+' .message_close').click(function(){MESSAGE.hide()});
		if(type != 'move') $('#mask_layer').click(function(){MESSAGE.hide()});
		if(type == 'confirm')
		{
			$('.confirmOk').click(function(){
				MESSAGE.hide();
				eval(foot);
			});
		}
	},
	hide: function(){
		$('.message_box.message_rand').remove();
		$('.message_box').hide();
		$('#mask_layer').hide();
	},
	resize: function(tarId){
		var top = ( $(window).height() - $('#'+tarId).height() )/2;
		var left = ( $(window).width() - $('#'+tarId).width() )/2;
		$('#'+tarId).css({"top": top,"left": left });
	},
	fadeMsg: function(json){
		// 传入数据：
		// 成功回调： {status: true, data: "success msg", code: 0}
		// 错误回调： {status: false, data: "error msg", code: 0}
		// MESSAGE.ajaxError(json);
		if(json.status){ var className = 'btn-success'; }else{ var className = 'btn-danger'; }
		var btn = '<button class="btn message-fademsg-btn '+className+'" style="position: fixed;top:0%;left: 15%;z-inde:999999;">'+json.data+'</button>';
		$("body").append(btn);
		$(".message-fademsg-btn").fadeIn(3000);
	    $(".message-fademsg-btn").fadeOut(3000,function(){
	    	$(".message-fademsg-btn").remove();
	    });
	}
};

/**
  * @method URL 跳转、获取其参数值....
  * @author soul 2017/6/16
  */
var TOURL = {
	go:function(url){
		url = url == ''? TOURL.getParam('goto'): url;
		url = url == null || url == ''? '/': url;
		window.location.href = url;
	},
	getParam: function(name){
		var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
		var r = window.location.search.substr(1).match(reg);
		if (r != null) return unescape(r[2]); 
		return null; 
	}
};

/**
  * @method 分页处理
  * @author soul 2017/6/16
  */
var PAGE = {
	jump:function(){
		window.location.href = $('.to_page_txt:eq(0)').attr('base').replace('-_-page_-_', $('.to_page_txt:eq(0)').val());
	},
	perPage:function(perPage){
		$.cookie('perPage', perPage, { expires: 7, path: '/' });
		location.reload();
	}
}

/**
  * @method 可收拢容器
  * @author soul 2017/6/16
  */
var EXTENDBOX = {
	//初始化关闭打开事件....
	init: function(){
		$('.extend_closed .extend_body').hide();
		$('.extend_box .open_close').click(function(){
			var obj = $(this).closest('.extend_box');
			if(obj.hasClass('extend_closed'))
			{
				obj.removeClass('extend_closed').addClass('extend_opened');
				obj.find('.extend_body').show();
			}
			else
			{
				obj.removeClass('extend_opened').addClass('extend_closed');
				obj.find('.extend_body').hide();
			}
		});
	}
};


/**
  * @method 文件上传
  * @author soul 2017/6/16
  * @example var params = {
				pickId: 'btnid',//按钮
				tarImg: 'imgid',//图片
				tarVal: 'valid',//接收返回值
				postData: {site:'yp|b2c',cate: "product"....},site可以缺省，默认为b2c;
				okFun: uploadOk,
				errFun: uploadErr,
			};
			UPLOAD.img(params);
  */
var UPLOAD = {
	//params {}
	img: function(params){
		if(typeof(WebUploader) == "undefined") return false;
		params.postData = params.hasOwnProperty("postData")? params.postData: {};
		uploader = WebUploader.create({
			auto: true,
			compress: false,
			duplicate: true,
			resize: false,
			swf: ADMIN_URI+'static/ueditor/Uploader.swf',
			server: ADMIN_URI+'upload/uploadImg?isAjax=1',
			pick: '#'+params.pickId,
			formData: params.postData,
			accept: {
				title: '图片上传',
				extensions: 'gif,jpg,jpeg,bmp,png',
				mimeTypes: 'image/jpg,image/jpeg,image/png'
			},
			fileVal : "file",
			fileSingleSizeLimit: 3145728
		});
		//上传前loading
		uploader.on( 'fileQueued', function( file) {
			if(params.hasOwnProperty("beforeFun")){
				params.beforeFun(file, params);
			}else if(params.hasOwnProperty("tarImg")){
				$('#'+params.tarImg).attr('src', ADMIN_URL+'static/images/loading.gif');
			}
		});
		// 文件上传成功，给item添加成功class, 用样式标记上传成功。
		uploader.on( 'uploadSuccess', function( file , data) {
			if(params.hasOwnProperty("okFun")){
				params.okFun(file, data, params);
			}else if(data.status){
				if(params.hasOwnProperty("tarImg")){
					$('#'+params.tarImg).attr('src', data.data.url);
				}
				if(params.hasOwnProperty("tarVal")){
					$('#'+params.tarVal).val(MYJSON.toSting(data.data));
				}
			}else{
				MESSAGE.alert('图片上传失败，请稍后重试！'+data.data);
			}
		});
		//上传前出错
		uploader.on('error', function( type ){
			if (type=="Q_TYPE_DENIED"){
				MESSAGE.alert("请选择 gif,jpg,jpeg,bmp,png 格式的图片");
			}else if(type=="Q_EXCEED_SIZE_LIMIT"){
				MESSAGE.alert("图片大小不能超过3M");
			}
		});
		//上传出错
		uploader.on( 'uploadError', function( file, reason) {
			if(params.hasOwnProperty("errFun")){
				params.errFun(file, reason, params);
			}else{
				MESSAGE.alert('图片上传失败，请稍后重试！'+reason);
			}
		});
		return uploader;
	}
};

/**
  * @method JSON 字符串 和 对象的互转
  * @author soul 2017/6/16
  */
var MYJSON = {
	toSting: function(json){
		return JSON.stringify(json);
	},
	toJson: function(string){
		return JSON.parse(string);
	}
};

/**
  * @method 字符串处理
  * @author soul 2017/6/30
  */
var STRING = {
	formatNum: function(str){
		return str.replace(/[^0-9a-zA-Z]/ig, '');
	},
	simple: function(str){
		return str.replace(/[^0-9a-zA-Z\u4e00-\u9fa5]/ig, '');
	},
	toEn: function(str){
		return str.replace(/[^0-9a-zA-Z]/ig, '');
	},
	toCnEn: function(str){
		return str.replace(/[^0-9a-zA-Z\u4e00-\u9fa5]/ig, '');
	},
	toKeyword: function(obj){
		return $(obj).val($.trim($(obj).val()).replace(/[;:：；，。]/ig, ','));
	}
};

var ARRAY = {
	unique: function(arr){
		arr.sort();
		var resarr =new Array();
		var temp = '';
		for(var i in arr)
		{
			if(arr[i] != temp && arr[i] != '')
			{
				resarr.push(arr[i]);
				temp = arr[i];
			}
		}
		return resarr;
	},
	merge: function(arr1, arr2){
		return $.merge(arr1, arr2);
	},
	has:function(val, arr){
		return $.inArray(val, arr) == -1? false: true;
	}
};

/**
  * @method checkbox  select  radio 的操作
  * @author soul 2017/6/30
  */
var INPUT = {
	checkAll: function(obj, toName){
		$('[name="'+toName+'"]').prop('checked', $(obj).prop('checked'));
	},
	getCheckedVal: function(toName){
		var val = new Array(); 
		$('[name="'+toName+'"]:checked').each(function(){ 
			val.push($(this).val()); 
		}); 
		return val;
	}
};


function delOrderPro(obj, type)
{
    layer.confirm('确定要删除此产品吗？', function(index){
      $(obj).parents('tr').remove();
	  changeMOney();
	  layer.close(index);
    });
}

function addOrderPro()
{
	var num = 0;
	$('[num]').each(function(){
		var t = parseInt($(this).attr('num'));
		num = t > num ? t: num;
	});
	num++;
	var tr = '<tr num="#num#">'
				+'<td><input type="hidden" name="pro[#num#][opp_pro_id]" class="proid"><a href="javascript:" class="a_blue" onclick="delOrderPro(this)">删除</a></td>'
				+'<td> </td><td><input type="text" name="pro[#num#][opp_pro_code]" class="layui-input orderProCode" autocomplete="off"></td>'
				+'<td> </td><td><input type="text" name="pro[#num#][opp_price]" class="layui-input cmoney"></td>'
				+'<td>0</td><td><input type="text" name="pro[#num#][opp_count]" class="layui-input acount"></td><td class="right xj">0.00</td><td class="blue"><input type="text" name="pro[#num#][has]" class="layui-input hcount"/></td><td class="warn"><input type="text" name="pro[#num#][no]" class="layui-input" readonly/></td>'
			'</tr>';
	$('#ordProList').append(tr.replace(/#num#/g, num));
	rowEvent();
}

function rowEvent()
{
	$('.orderProCode').keyup(function(){
		var obj = $(this);
		var lproId = obj.parents('tr').find('.proid').val();
		if(lproId > 0)
		{
			obj.parents('tr').find('.proid').attr('lproId', lproId);
			obj.parents('tr').find('.proid').val(0);
		}
		var code = $.trim(obj.val());
		$('.pro-ul').remove();
		if(code != '')
		{
			$.get(adminUri+'order/ajaxSearchPro?isAjax=1&num='+code, function(data){
				if(data.length > 0)
				{
					var ul = '<ul class="pro-ul">';
					for(var i in data)
					{
						ul += '<li proid="'+data[i]['pro_id']+'">'+data[i]['pro_code']+'</li>';
					}
					ul += '</ul>';
					obj.parent().append(ul);
					$('.pro-ul').parent().mouseleave(function(){
						$('.pro-ul').remove();
					});
					//选择产品
					$('.pro-ul li').click(function(){
						var proId = parseInt($(this).attr('proid'));
						//判断是否存在
						var falg = -1;
						$('.proid').each(function(i, e){
							if(proId == $(this).val())
							{
								falg = i;
								return false;
							}
						});
						if(falg > -1)
						{
							layer.msg('此规格型号已存在, 请勿重复添加！');
							$('.proid').eq(0).parent('tr').find('.orderProCode').focus();
						}
						else
						{
							$.get(adminUri+'order/ajaxGetPro?isAjax=1&uid='+uid+'&proId='+$(this).attr('proid'), function(data2){
								obj.val(data2.pro_code);
								obj.parents('tr').find('.proid').val(data2.pro_id);
								var lproId = parseInt(obj.parents('tr').find('.proid').attr('lproId'));
								obj.parents('tr').find('td:eq(1)').html(data2.pro_name);
								obj.parents('tr').find('td:eq(3)').html(data2.pro_wl_code);
								if(lproId != data2.pro_id || obj.parents('tr').find('td:eq(4) .layui-input').val() != '')
									obj.parents('tr').find('td:eq(4) .layui-input').val(data2.pro_price);
								obj.parents('tr').find('td:eq(5)').html(data2.pro_count);
								$('.pro-ul').remove();
								changeMOney();
							}, 'json');
						}
					});
				}
				else
				{
					layer.msg('规格型号'+code+'不存在'); 
				}
			}, 'json');
		}
	});
	$('.cmoney').change(function(){
		var price = $(this).val();
		price = VERIFICATION.floatNum(price, 2);
		price = price < 0.01? 0.01: price;
		$(this).val(price);
		var count = parseInt($(this).parents('tr').find('.acount').val());
		$(this).parents('tr').find('.xj').html(VERIFICATION.floatNum(price*count, 2));
		changeMOney();
	});

	$('.acount').change(function(){
		var num = $(this).parents('tr').attr('num');
		var count = parseInt($(this).val());
		var has = $('[name="pro['+num+'][has]"]').val();
		var kc =  parseInt($('[num="'+num+'"] td:eq(5)').html());
		kc = kc < 0 ? 0: kc;
		count = isNaN(count) || count < 1? 1: count;
		has = count > has ? has: count;
		has = kc > count? count: kc;
		$('[name="pro['+num+'][opp_count]"]').val(count);
		$('[name="pro['+num+'][has]"]').val(has);
		$('[name="pro['+num+'][no]"]').val(count-has);
		var price = parseInt($(this).parents('tr').find('.cmoney').val());
		$(this).parents('tr').find('.xj').html(VERIFICATION.floatNum(price*count, 2));
		changeMOney();
	});

	$('.hcount').change(function(){
		var num = $(this).parents('tr').attr('num');
		var has = parseInt($(this).val());
		var count = $('[name="pro['+num+'][opp_count]"]').val();
		var kc =  parseInt($('[num="'+num+'"] td:eq(5)').html());
		kc = kc < 0 ? 0: kc;
		has = isNaN(has) || has < 1? 0: has;
		has = kc > has? has: kc;
		count = count < has ? has: count;
		$('[name="pro['+num+'][opp_count]"]').val(count);
		$('[name="pro['+num+'][has]"]').val(has);
		$('[name="pro['+num+'][no]"]').val(count-has);
		changeMOney();
	});
}


function changeMOney()
{
	var moneyHas = 0.00;
	var money = 0.00;
	$('[num]').each(function(){
		var num = $(this).attr('num');
		var price = $('[name="pro['+num+'][opp_price]"]').val();
		if(price > 0)
		{
			price = VERIFICATION.floatNum(price, 2);
			var has = $('[name="pro['+num+'][has]"]').val();
			if(has > 0) moneyHas += has*price;
			var all = $('[name="pro['+num+'][opp_count]"]').val();
			if(all > 0) money += all*price;
		}
	});
	money = VERIFICATION.floatNum(money, 2);
	moneyHas = VERIFICATION.floatNum(moneyHas, 2);
	$('.allmoney').html(money);
	$('#planF td:eq(1) b').html(money);
	$('#planF td:eq(2) b').html(moneyHas);
	$('#planF td:eq(3) b').html(VERIFICATION.floatNum(money-moneyHas, 2));

}

function checkPlanForm()
{
	var flag = true;
	$('[num]').each(function(){
		var num = $(this).attr('num');
		var code = $.trim($('[name="pro['+num+'][opp_pro_code]"]').val());
		var proId = $('[name="pro['+num+'][opp_pro_id]"]').val();
		if(proId < 1 && code != '')
		{
			layer.msg('规格型号'+code+'不存在，请重新添加或者删除');
			flag = false;
			return false;
		}

		if(proId > 0 && $.trim($('[name="pro['+num+'][opp_price]"]').val()) == '')
		{
			layer.msg('规格型号'+code+'， 请填写单价');
			flag = false;
			return false;
		}

		if(proId > 0 && $.trim($('[name="pro['+num+'][opp_count]"]').val()) == '')
		{
			layer.msg('规格型号'+code+'， 请填写购买数量');
			flag = false;
			return false;
		}
		

	});
	if(!flag) return false;
}