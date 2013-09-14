// //江苏省赣榆高级中学 在线评测系统 ‘现代’皮肤 by 张森：zhs490770@github   (lyxss.tk)

function registerpage_init()
{
	$('.textbox').focusout(_textbox_focusout);
	$('.select').children('ul').children('li').click(function(){$(this).parent().parent().parent().children('input').val($(this).attr('data-value'));});
}

function submitRegister()
{
	$("#loading-register").stop(true,false).fadeOut(300,"linear");
	$("#regstate").html("...").fadeOut(100);
	if(!_reg_check_all())
	{
		return false;
	}
	$("#loading-register").stop(true,false).fadeIn(300,"linear");
	$.post("register.php",$("#frmReg").serialize(),_postCallback,"json");
}

function submitSettings()
{
	$("#loading-register").stop(true,false).fadeOut(300,"linear");
	$("#regstate").html("...").fadeOut(100);
	//if(!_reg_check_all())
	//{
	//	//return false;
	//}
	$("#loading-register").stop(true,false).fadeIn(300,"linear");
	$.post("settings.php?POSTSENSORDATA=1&_ran="+Math.random()*Math.random(),$("#frmSet").serialize(),_postSettingsCallback,"json");
}

/*****************************************************************


             以下为副函数。 - 连云小森森 2013年7月17日23:42:36


 *****************************************************************/

function _postCallback(data)
{
	var d=data["data"];
	if(data["no"]==-1)
	{
		$("#loading-register").css({"trasition":"background-image 0.3s linear"}).css($("#loading-register").attr("addcsst"),$("#loading-register").attr("addcssn")); 
		$("#regstate").stop(true,false).fadeIn(100).css('color','red').html('mysql_error: '+data["err"]);
	}
	else if(data["no"]==0)	//注册成功
	{
		$("#loading-register").css({"trasition":"background-image 0.3s linear"}).css($("#loading-register").attr("addcsst"),$("#loading-register").attr("addcssv"));
		$("#regstate").stop(true,false).fadeIn(100).css('color','green').html(data["err"]+"，3秒后跳转到<a href='./'>首页</a>~");
		setTimeout(function(){window.location.href='./';},3000);
	}
	else
	{
		$("#loading-register").css({"trasition":"background-image 0.3s linear"}).css($("#loading-register").attr("addcsst"),$("#loading-register").attr("addcssn")); 
		var errcnt=parseInt(data["no"]);
		for(var i=0;i<errcnt;i++)
		{
			if(d[i]["obj"]=='vcode')
			{
				refresh_verifycode();
			}
			$('#'+d[i]["obj"]).removeClass('textbox-red').removeClass('textbox-green').addClass("textbox-red");
			$('#'+d[i]["obj"]+'-notice').css("display","none");
			$('#'+d[i]["obj"]+'-alert').html(d[i]["str"]).stop(true,false).css({"opacity":"0","display":"inline"}).animate({"opacity":"1"},300,"swing").css("filter","alpha(opacity:100)");
		}
	}
}

function _postSettingsCallback(data)
{
	var d=data["data"];
	if(data["no"]==-1)
	{
		$("#loading-register").css({"trasition":"background-image 0.3s linear"}).css($("#loading-register").attr("addcsst"),$("#loading-register").attr("addcssn")); 
		$("#regstate").stop(true,false).fadeIn(100).css('color','red').html('mysql_error: '+data["err"]);
	}
	else if(data["no"]==0)	//设定成功
	{
		$("#loading-register").css({"trasition":"background-image 0.3s linear"}).css($("#loading-register").attr("addcsst"),$("#loading-register").attr("addcssv"));
		$("#regstate").stop(true,false).css('display','none').fadeIn(100).css('color','green').html(data["err"]+"");
		$('.showerr:not(.disabled)').each(function(){_show_ok(this)});
		$('input[type=password]').val('');
	}
	else
	{
		$("#loading-register").css({"trasition":"background-image 0.3s linear"}).css($("#loading-register").attr("addcsst"),$("#loading-register").attr("addcssn")); 
		var errcnt=parseInt(data["no"]);
		for(var i=0;i<errcnt;i++)
		{
			$('#'+d[i]["obj"]).removeClass('textbox-red').removeClass('textbox-green').addClass("textbox-red");
			$('#'+d[i]["obj"]+'-notice').css("display","none");
			$('#'+d[i]["obj"]+'-alert').html(d[i]["str"]).stop(true,false).css({"opacity":"0","display":"inline"}).animate({"opacity":"1"},300,"swing").css("filter","alpha(opacity:100)");
		}
	}
}

function _show_alert(o,t)
{
	$(o).removeClass('textbox-red').removeClass('textbox-green').addClass("textbox-red");
	$('#'+o.id+'-notice').css("display","none");
	$('#'+o.id+'-alert').html(t).stop(true,false).css({"opacity":"0","display":"inline"}).animate({"opacity":"1"},300,"swing").css("filter","alpha(opacity:100)");
}
function _show_ok(o)
{
	if(typeof o =='undefined') o=this;
	$(o).removeClass('textbox-red').removeClass('textbox-green').addClass("textbox-green");
	//$('#'+o.id+'-alert').stop(true,false).fadeOut(300,"swing")
	$('#'+o.id+'-alert').stop(true,false).animate({"opacity":"0"},300,"swing",function(){$(this).css("display","none")}).css("filter","alpha(opacity:0)");
}
function _textbox_focusout()
{
	switch(this.id)
	{
	case 'user-id':
	{
		//用户名3-16位，可以包含字母，数字，汉字，下划线
		var flag=0,sz='';
		$.ajax({async:true,url:"checkuser.php",data:{user_id:this.value},dataType:"json",type:"POST",success:function(data){if(data["no"]==0){_show_ok($('#user-id')[0]);}else{flag=1;sz=data["err"];_show_alert($('#user-id')[0],sz);}}});
		break;
	}
	case 'regpwd':
	{
		if(typeof $('#orgpwd').val()!=='undefined' && $('#orgpwd').val()==='') break;
		if(/^.{6,16}$/.test($(this).val()))
		{
			_show_ok(this);
		}
		else
		{
			_show_alert(this,"密码必须为6-16位字符");
		}
		break;
	}
	case 'regpwd2':
	{
		if(typeof $('#orgpwd').val()!=='undefined' && $('#orgpwd').val()==='') break;
		if($(this).val()===$('#regpwd').val()&&/^.{6,16}$/.test($(this).val()))
		{
			_show_ok(this);
		}
		else
		{
			_show_alert(this,"本密码与上面的密码不一致");
		}
		break;
	}
	case 'school':
	{
		//if(/^\w+$/.test($(this).val()))
		{
			_show_ok(this);
		}
		break;
	}
	case 'regemail':
	{
		if(/^.+?@.+?[.].+$/.test($(this).val())||$(this).val()==='')
		{
			_show_ok(this);
		}
		else
		{
			_show_alert(this,"电子邮箱地址格式错误");
		}
		break;
	}
	case 'vcode':
	{
		if(/^[abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890]{4}$/.test($(this).val()))
		{
			_show_ok(this);
		}
		else
		{
			_show_alert(this,"验证码格式错误");
		}
		break;
	}
	default:
	{
		break;
	}
	}
	return true;
}

function _reg_check_all()
{
	var flag=true;
	//user-id
	//no
	//regpwd
	if(/^.{6,16}$/.test($('#regpwd').val()))
	{
		_show_ok($('#regpwd')[0]);
	}
	else
	{
		_show_alert($('#regpwd')[0],"密码必须为6-16位字符");
		flag=false;
	}
	//regpwd2
	if($('#regpwd2').val()===$('#regpwd').val()&&/^.{6,16}$/.test($('#regpwd2').val()))
	{
		_show_ok($('#regpwd2')[0]);
	}
	else
	{
		_show_alert($('#regpwd2')[0],"本密码与上面的密码不一致");
		flag=false;
	}
	//school
	//if(/^\w+$/.test($('#school').val()))
	{
		_show_ok($('#school')[0]);
	}
	//regemail
	if(/^.+?@.+?[.].+$/.test($('#regemail').val())||$('#regemail').val()==='')
	{
		_show_ok($('#regemail')[0]);
	}
	else
	{
		_show_alert($('#regemail')[0],"电子邮箱地址格式错误");
		flag=false;
	}
	//vcode
	if(/^[abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890]{4}$/.test($('#vcode').val()))
	{
		_show_ok($('#vcode')[0]);
	}
	else
	{
		_show_alert($('#vcode')[0],"验证码格式错误");
		flag=false;
	}
	return flag;
}
