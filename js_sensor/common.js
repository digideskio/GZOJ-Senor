//江苏省赣榆高级中学 在线评测系统 ‘现代’皮肤 by 张森：zhs490770@github   (lyxss.tk)
var imgIndex=1;
var ajaxUserInfo='';
var is_somemenu_on=0;
var rf_func=new Array();
var bt_func=new Array();
var is_stt_show=0;
var is_user_changing=0;

function common_init()
{
	$("body").append('<div id="footer" style="height:20px;"></div>');
	$.ajaxSetup({async:true});
	$("body").append('<div id="rib-blur" tabindex="111110" style=""></div>');
	UI_Init();
	try
	{
		document.getElementsByTagName("title")[0].innerHTML=document.getElementsByTagName("title")[0].innerHTML;
	}
	catch(e)
	{
		$("#login_drp").click(function(e){location.href="./loginpage.php";});
		if(!/Sensoriealert=1/.test(document.cookie))
		{
			alert('不要使用IE9以下的浏览器，谢谢你的合作~OIer应该更高端~');
			document.cookie="Sensoriealert=1; expires=Tue, 11 Mar 2036 13:12:31 GMT";
		}
	}
	$('body').append('<div id="back-to-top" style="display:none"></div>');
	window.onscroll=function(){
		if((document.documentElement.scrollTop>=130||document.body.scrollTop>=130)&&!is_stt_show)
		{
			is_stt_show=1;
			$("#back-to-top").css('display','block');
		}
		if((document.documentElement.scrollTop<130&&document.body.scrollTop<130)&&is_stt_show)
		{
			is_stt_show=0;
			$('#back-to-top').css('display','none');
		}
	}
	window.onunload=function(){
		document.cookie='SensorOpacity='+$('#nav').css('opacity')+'set';
	}
	var readopp=0.4;
	if(/SensorOpacity/.test(document.cookie))
	{
		readopp=parseFloat(document.cookie.match(/SensorOpacity=(.+?)set/)[1]);
	}
	$('#nav').css({"opacity":readopp});
	$('#back-to-top').click(function(){$('html,body').animate({scrollTop:0},300,"swing");for(i in bt_func)bt_func[i]();});
	$('#container').hover(function(){$('#nav').mouseout();});
}

function UI_Init()
{
	/*******************  hover  ***********************/
	//$("#nav").stop(true,false).animate({opacity:"0.4"},500).css({filter:"alpha(Opacity=40)"});
	$(".nav-li[isxssui_hover!='true']").attr("isxssui_hover","true").hover(_nav_li_mi,_nav_li_mo).click(function(){$(this).children('a').click();});
	$(".nav-li2[isxssui_hover!='true']").attr("isxssui_hover","true").hover(_nav_li2_mi,_nav_li2_mo);
	$("#nav[isxssui_hover!='true']").attr("isxssui_hover","true").hover(_nav_mi,_nav_mo);
	$(".btn-trans[isxssui_hover!='true']").attr("isxssui_hover","true").hover(_btn_trans_mi,_btn_trans_mo);
	$(".dropdown-menu-i[isxssui_hover!='true']:not(.grey)").attr("isxssui_hover","true").hover(_dropdown_menu_i_mi,_dropdown_menu_i_mo);
	$(".select[isxssui_select!='true']").attr("isxssui_select","true").bind("focus",_select_focus).bind("blur",_select_blur).children("ul").children("li").each(function(){$(this).bind("click",function(){$(this).parent().parent().attr("data-value",$(this).attr("data-value")).children('.c').html($(this).html());$("#rib-blur").focus();$("textarea").focus();});});
	$('#rib-blur').focus();
	//.dropdown-menu-i
	/**************************************************/
	
	$(".nav-li.drp").children("a").click(_nav_li_a_click).end().children(".dropdown-menu").attr("x-data-stat","0");
	$("body").click(_body_click);
	$("#login_drp").click(function(){if($(this).parent().children(".dropdown-menu").attr("x-data-stat")=="1")return;$('#loginusr,#loginpwd').val('');$("#login-dropdown-menu").css({height:"150px"}).children("#login-err-text").css("display","none").end().children('#loading-login').css("display","none");refresh_verifycode();$('#loginusr').focus();});
	$(".dropdown-menu").click(function(e){
		//e.preventDefault();
		e.stopPropagation();
	});
	$(".dropdown-menu-i").click(function(){
		$(this).parent().parent().children("a").click();
	});
	$("#nav").click(function(e){
		e.stopPropagation();
		$(".dropdown-menu[x-data-stat='1']").parent().children("a").click();
		if(fstt) fstt();
	});
}

function index_init()
{
	for(i=1;i<=imgCnt;i++) $("#preloader").append('<img src="../splash/'+i+'.jpg" />');
	var controller=["FadeIn", "FlyIn", "FlyOut"];
	var conIndex = 0;
    setInterval(function(){
		if(is_user_changing)
		{
			is_user_changing=0;
			return;
		}
    	imgIndex++;
    	$("#splash").ImageSwitch({
				Type:controller[conIndex], 
				NewImage:"splash/"+imgIndex+".jpg"
		});
		if(imgIndex>=imgCnt) imgIndex=0;
		conIndex++;
		if(conIndex >= controller.length) conIndex=0;
    }, 5000);
	$("#btn-prev").click(function()
	{
		//if($('#splash').css('opacity')<1) return;
		is_user_changing=1;
		imgIndex--;
		if(imgIndex<=0) imgIndex=imgCnt;
		$("#splash").ImageSwitch({
				Type:controller[conIndex], 
				NewImage:"splash/"+imgIndex+".jpg"
		});
		conIndex++;
		if(conIndex >= controller.length) conIndex=0;
	});
	$("#btn-next").click(function()
	{
		//if($('#splash').css('opacity')<1) return;
		is_user_changing=1;
		imgIndex++;
		$("#splash").ImageSwitch({
				Type:controller[conIndex], 
				NewImage:"splash/"+imgIndex+".jpg"
		});
		if(imgIndex>=imgCnt) imgIndex=0;
		conIndex++;
		if(conIndex >= controller.length) conIndex=0;
	});
	rf_news_list();
	bt_func.push(news_back_to_list);
}
/*声明回调函数*/
function _nav_mi(){$(this).stop(true,false).animate({opacity:"1"},500).css({filter:"alpha(Opacity=100)"})}function _nav_mo(){if(parseFloat($(this).css('opacity'))<0.5)return;if(is_somemenu_on)return;$(this).stop(true,false).animate({opacity:"0.4"},500).css({filter:"alpha(Opacity=40)"})}function _nav_li_mi(){$(this).stop(true,false).animate({"background-color":"#dddddd","color":"#000000"},200);$(this).children().stop(true,false).animate({"color":"#000000"},200)}function _nav_li_mo(){if(is_somemenu_on);if($(this).children(".dropdown-menu").css("display")!='block'){$(this).stop(true,false).animate({"background-color":"#000000","color":"#ccc"},200);$(this).children("a").animate({"color":"#ccc"},200);}
//就是这个消失……应该重写
if(0)
if($(this).children(".dropdown-menu").css("display")!="none")$(this).children(".dropdown-menu").animate({height:"0px"},300,"swing",function(){$(this).css("display","none");});
}
function _nav_li_b(){
	//$(this).children("a").click();
	}
function _nav_li2_mi(){$(this).stop(true,false).animate({"background-color":"#dddddd","color":"#000000"},200);$(this).children().stop(true,false).animate({"color":"#000000"},200)}function _nav_li2_mo(){$(this).stop(true,false).animate({"background-color":"#FCF","color":"#000000"},200);$(this).children().stop(true,false).animate({"color":"#000000"},200)}function _nav_li_a_click(e){
	e.preventDefault();
	e.stopPropagation();
	if($(this).parent().children(".dropdown-menu").attr("x-data-stat")!="1"){$(this).parent().children(".dropdown-menu").css("height","auto").fadeIn(300,"linear",function(){$(this).parent().children(".dropdown-menu").attr("x-data-stat","1");});is_somemenu_on=1;}
else{is_somemenu_on=0;$(this).parent().children(".dropdown-menu").animate({height:"0px"},300,"swing",function(){$(this).css("display","none");$(this).stop(true,false).parent().animate({"background-color":"#000000","color":"#ccc"},200);$(this).parent().children("a").animate({"color":"#ccc"},200);$(this).parent().children(".dropdown-menu").attr("x-data-stat","0");});}
}
function _body_click(){
	//remove all menus
	$(".dropdown-menu[x-data-stat='1']").parent().children("a").click();$("#nav").mouseleave();
}
function _btn_trans_mi(){$(this).stop(true,false).animate({opacity:"1"},500).css({filter:"alpha(Opacity=100)"});}function _btn_trans_mo(){$(this).stop(true,false).animate({opacity:"0.4"},500).css({filter:"alpha(Opacity=40)"})}

function _dropdown_menu_i_mi(){
	$(this).stop(true,false).animate({"background-color":"#e2effa"},300);
}
function _dropdown_menu_i_mo(){
	$(this).stop(true,false).animate({"background-color":"inherit"},300);
}
function _select_focus()
{
	$(this).children("ul").stop(true,false).fadeIn(200);
}
function _select_blur()
{
	$(this).children("ul").stop(true,false).fadeOut(200);
}
/*=========*/
function pswkey(e)
{
	var keynum
	var keychar
	var numcheck
	if(window.event) // IE
	{
		keynum = e.keyCode
	}
	else if(e.which) // Netscape/Firefox/Opera
	{
		keynum = e.which
	}
	return keynum==13;
}
function showloginerr(txt,isgreen)
{
	if(typeof(isgreen)!=='undefined'){if(isgreen==2)$("#login-err-text").css("color","orange");else $("#login-err-text").css("color","green");}else $("#login-err-text").css("color","red");
	$("#login-dropdown-menu").animate({height:"180px"},200,"swing",function(){});
	$("#login-err-text").html(txt).fadeIn(200);
}
var can_refresh=0;
function submitlogin()
{
	var username=$("#loginusr").val();
	var password=$("#loginpwd").val();
	if(username==''||password=='')
	{
		showloginerr('用户名或密码不能为空');
		return false;
	}
	$("#loading-login").css("display","block");
	$.ajax({async:true,url:"login.php",dataType:"json",type:"POST",data:{user_id:username,password:password,utype:"normal",vcode:$("#loginvrc").val()},timeout:10000,error: function(){showloginerr("发生未知错误")},success:function(data){
		if(data["no"]==0)	//登陆成功
		{
			showloginerr("登陆成功",1);
			if(can_refresh===0)
				setTimeout(function(){successLogin(0);},100);
			else
			{
				window.top.location.href='./';
			}
		}
		else if(data["no"]==1)
		{
			showloginerr(data["err"]);
			refresh_verifycode();
		}
		else if(data["no"]==2)
		{
			showloginerr(data["err"],2);
			$("#loginvrc").val("");
			refresh_verifycode();
		}
	}});
}

function successLogin(s)
{
	//刷新user-nav-bar
	if(s==0)
	{
		refresh_others();
		$("#user-nav-bar").fadeOut(300,"swing",function(){successLogin(1);});
		$.get("ajax_ojheader.php",""+Math.random(),function(data){ajaxUserInfo=data;});
	}
	else if(s==1)
	{
		$("#user-nav-bar").html(ajaxUserInfo).fadeIn(200,"swing");
		UI_Init();
		$("#nav").stop(true,false).animate({opacity:"0.4"},500).css({filter:"alpha(Opacity=40)"});		//bug fix:修复了登陆后导航栏不变为透明的问题
	}
}

function exitLogin()
{
	$.ajax({async:true,url:"logout.php",dataType:"json",type:"GET",timeout:10000,error: function(){alert("退出时ajax发生未处理异常，恳请您能够反馈给我")},success:function(data){
		if(data["no"]==0)
		{
			refresh_others();
			$("#user-nav-bar").fadeOut(300,"linear",function(){
			$.get("ajax_ojheader.php",""+Math.random(),function(data){ajaxUserInfo=data;$("#user-nav-bar").html(ajaxUserInfo).fadeIn(200);UI_Init();$("#nav").stop(true,false).animate({opacity:"0.4"},500).css({filter:"alpha(Opacity=40)"});});
		});
		}
		else
		{
			alert('退出登陆时发生错误 '+data["no"]+": "+data["err"]);
		}
	}});
}

function refresh_verifycode(){$("#vcode-layer").html('<img src="../vcode.php?'+Math.random()+'" id="verifycode-img" alt="验证码图形" />');$("#loginvrc,#vcode").val("").focus();}

function refresh_others()
{
	for(func in rf_func)
	{
		rf_func[func]();
	}
}
var this_nid=0;
function rf_news_list()
{
	$("#news-title-show").animate({"opacity":"0"},300,"swing");
	$.getJSON("ajax_getnews.php","type=list&start="+this_nid,
		function(data)
		{
			$("#newserr").html("");
			if(data['no']!=0)
			{
				//有错误
				if(data['no']==5) this_nid-=10;
				$("#news-title-show").animate({"opacity":"1"},100,"swing");
				$("#newserr").html(data['err']);
			}
			else
			{
				var thtml='<table width="100%" border="0">';
				var news_array=data['data'];
				for(i in news_array)
				{
					var nid=news_array[i]['id'];
					var ntt=news_array[i]['title'];
					var ntm=news_array[i]['time'];
					thtml+='<tr><td width="81%" style="font-size:14px"><a href="javascript:" onclick="show_news('+nid+')">'+ntt+'</a></td><td width="19%" style="font-size:12px">'+ntm+'</td></tr>';
				}
				thtml+='</table>';
				$("#news-title-show").html(thtml).animate({"opacity":"1"},300,"swing");
			}
		}
	);
}

function show_prev_news()
{
	if(this_nid<=0) return;
	this_nid-=10;
	rf_news_list();
}

function show_next_news()
{
	this_nid+=10;
	rf_news_list();
}
var lasttop=0;
function show_news(id)
{
	lasttop=document.body.scrollTop+document.documentElement.scrollTop;
	$.getJSON("ajax_getnews.php","type=show&id="+id,
		function(data)
		{
			$("#newserr").html("");
			if(data['no']!=0)
			{
				$("#newserr").html(data['err']);
			}
			else
			{
				$("#newsshowboard-layer").css({"display":"block","height":(screen.availHeight-240)+"px"});
				$("#newsshowboard").html(data['content']);
				$('#news-author').html('<a href="userinfo.php?user='+data['user_id']+'" target="_blank">'+data['user_id']+'</a>');
				$("#newsshowboard").find("img").each(function(){if($(this).width()>980){ $(this).click(function(){window.open($(this).attr('src'),'_target');});$(this).height($(this).height()*980/$(this).width());$(this).css("cursor",'pointer');$(this).width(980);}});
				$("#newstitle").html(data['title']);
				$("#newsshowboard-l2").scrollTop(0);
				$("#newsshowboard-l2").perfectScrollbar({wheelSpeed:40});
				setTimeout(function(){$("#newsshowboard-l2").perfectScrollbar('update');},300);
				//window.scrollTo(0,$("#newsshowboard-layer").offset().top-60);
				$("html,body").animate({scrollTop:$("#newsshowboard-layer").offset().top-60},200,"swing");
				//time
			}
		}
	);
}

function news_back_to_list()
{
	//window.scrollTo(0,0);
	//$("html,body").animate({scrollTop:0},200,"linear");
	$("#newsshowboard-layer").fadeOut(300,"swing");
	if(document.body.scrollTop+document.documentElement.scrollTop==lasttop) $('html,body').stop(true,false).animate({scrollTop:0},300,"swing");
	else $('html,body').stop(true,false).animate({scrollTop:lasttop},300,"swing");
}

//$(function(){$("#newsshowboard").perfectScrollbar();});


(function(a) {
    a.fn.typewriter = function(callback) {			//modified by zhs;小森森 
        this.each(function() {
            var d = a(this),
            c = d.html(),
            b = 0;
            d.html("");
            var e = setInterval(function() {
                var f = c.substr(b, 1);
                if (f == "<") {
                    b = c.indexOf(">", b) + 1
                } else if(f=="&") {
					b = c.indexOf(";",b)+1;
				} else {
                    b++
                }
                d.html(c.substring(0, b) + (b & 1 ? "_": ""));
                if (b >= c.length) {
					callback();
                    clearInterval(e)
                }
            },
            105)
        });
        return this
    }
})(jQuery);
