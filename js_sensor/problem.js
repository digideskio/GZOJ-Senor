// PAGE PROBLEM
var ont_flag=0;
var tmp_html='';
var nowtype=0;
var code_just_now='';
var isstatuspage=0;
function problem_init()
{
	
	//
	$("h2").css({"border-bottom":"0px","padding-bottom":"0px","margin-top":"10px"}).after("<div style='padding:0;margin:0;width:90px;height:2px;background-color:#ff7373;'></div>");
	
	//box-shadow:0 0 10px rgba(0,0,0,0.3);
	tmp_html=$("#problem-show-board").html();
	if(iscontest)
	{
		//比赛
		rf_problem_page_contest();
		rf_func.push(rf_problem_page_contest);
	}else{
		rf_problem_page();
		rf_func.push(rf_problem_page);
	}
	
	window.onscroll = function(){
		//
		
		if(window.scrollY>40&&!ont_flag){var o=$("#submitlayer")[0];
		$(o).stop(true,false).css({"box-shadow":"0 0 10px rgba(0,0,0,0.3)","position":"fixed","top":"60px","opacity":""}).animate({"right":"-180px"},100,"swing");ont_flag=1;}
		else if(window.scrollY<=40&&ont_flag){var o=$("#submitlayer")[0];
		$(o).stop(true,false).css({"box-shadow":"","position":"absolute","top":"0px","right":""});ont_flag=0;}
	};
	$("#submitlayer").hover(function()
	{
		if(ont_flag)
			$(this).stop(true,false).animate({"right":"-110px"},300);
	},function()
	{
		if(ont_flag)
			$(this).stop(true,false).animate({"right":"-180px"},300);
	});
}

function rf_problem_page()
{
	$("#solution_button").fadeOut(200,"swing");
	$.getJSON("ajax_problem_status.php",{id:id},function(data)
	{
		if(data["no"]==0)
		{
			$("#solution_button").html(data["html"]).css({display:"inline-block",opacity:"0",filter:"alpha(opacity=0)"}).stop(true,false).animate({"opacity":"1"},500,"swing").css("filter","alpha(opacity=100)");
			$("#view_tot_stat").html(data["html2"]).css({"display":"inline","opacity":"0"}).animate({"opacity":"1"},500,"swing");
			$("#submitlayer").fadeIn(500);
			if(data["privilage"]==1) $("#btn-editproblem-layer").html(data['editgetkey']).fadeIn(500);
		}
		else if(data["no"]==10001)
		{
			$("#view_tot_stat").fadeOut(500);
			$("#submitlayer").fadeOut(500);
			$("#btn-editproblem-layer").fadeOut(500);
			backToProblem();
		}
	});
}

function rf_problem_page_contest()
{
	$("#solution_button").fadeOut(200,"swing");
	$("#talk-problem").css("display","none");
	$("#view_tot_stat").html('<a href="contest.php?cid='+cid+'">返回赛题列表</a>').css({"display":"inline","opacity":"0"}).animate({"opacity":"1"},500,"swing");
	$.getJSON("ajax_problem_status.php",{id:id},function(data)
	{
		if(data["no"]==0)
		{
			//$("#solution_button").html(data["html"]).css({display:"inline-block",opacity:"0",filter:"alpha(opacity=0)"}).stop(true,false).animate({"opacity":"1"},500,"swing").css("filter","alpha(opacity=100)");
			//$("#view_tot_stat").html(data["html2"]).css({"display":"inline","opacity":"0"}).animate({"opacity":"1"},500,"swing");
			$("#submitlayer").fadeIn(500);
			if(data["privilage"]==1) $("#btn-editproblem-layer").html(data['editgetkey']).fadeIn(500);
		}
		else if(data["no"]==10001)
		{
			//$("#view_tot_stat").fadeOut(500);
			$("#submitlayer").fadeOut(500);
			$("#btn-editproblem-layer").fadeOut(500);
			backToProblem();
		}
	});

}

var btn_sb='<a class="button button-def" style="padding:0;width:80px;text-align:center;" onclick="submitProblem();">提交</a>',btn_bk='<a onclick="if(nowtype==1)backToProblem();else submitProblem();" style="padding:0;width:80px;text-align:center;" class="button">返回</a>';
var chk_statid=0;
var stat_sid=-1;
function submitProblem()
{
	if(nowtype==1) return;
	nowtype=1;
	$("#sbutton_layer").fadeOut(300,"swing",function(){$(this).html(btn_bk).fadeIn(200,"swing");});
	var langmask=['C','C++','Pascal'];
	$("#problem-show-board").fadeOut(300,"swing",function(){$(this).html('<h2 style="display:inline">提交题目</h2><center><div>>>Language: <div class="select enabled" id="code_lang" name="lang" data-value="'+optlang+'" tabindex="0"><div class="c">'+langmask[optlang]+'</div><ul class="popform" style="display:none;opacity:1;"><li data-value="0">C</li><li data-value="1">C++</li><li data-value="2">Pascal</li></ul><span class="downarrow"></span></div>&nbsp;&nbsp;&nbsp;&nbsp;<a onclick="submitProblem2();" class="button button-def">提交</a>&nbsp;&nbsp;&nbsp;&nbsp;'+btn_bk+'</div><Br><textarea class="textbox" style="width:67%;height:300px" id="code_text"></textarea></center><br>').fadeIn(200,"swing");setTimeout(function(){UI_Init();$("#code_text").val(code_just_now);$('#code_lang').children('ul').children('li').click(function(){optlang=parseInt($(this).attr('data-value'));});},50);});
}

function backToProblem()
{
	if(nowtype==0) return;
	clearInterval(chk_statid);
	nowtype=0;
	code_just_now=$("#code_text").val();
	$("#sbutton_layer").fadeOut(300,"swing",function(){$(this).html(btn_sb).fadeIn(200,"swing");});
	$("#problem-show-board").fadeOut(300,"swing",function(){$(this).html(tmp_html).fadeIn(200,"swing");});
}
function submitProblem2()
{
	if(nowtype==2) return;
	nowtype=2;
	var code=$("#code_text").val(),lang=parseInt($("#code_lang").attr("data-value"));
	code_just_now=code;
	//id:id, source:source, language:0 C,  1 C++,  2 Pascal
	if(iscontest) var post_data={cid:cid,pid:pid,source:code,language:lang};
	else var post_data={id:id,source:code,language:lang};
	try{console.debug(post_data);}catch(e){}
	$("#problem-show-board").fadeOut(300,"swing",function(){$(this).html('<h2 style="border-bottom:#ff7373 2px solid;">查看状态</h2><span style="top:40px;right:280px;position:absolute;">'+btn_bk+'</span><Br><div class="" style="width:77%;min-height:300px;text-align:left;color:green;margin-left:auto;margin-right:auto;" id="stat_text">提交POST_DATA中……</div><div style="position:absolute;bottom:10px;right:20px;"><Br></div><br>').fadeIn(200,"swing");_submit_post(post_data);});
}
function _submit_post(data)
{
	if(nowtype==3) return;
	nowtype=3;
	$.post("submit.php",data,function(data){if(data["no"]==0){stat_sid=data["sid"];$("#stat_text").html("已提交，ID："+stat_sid);chk_statid=1;setTimeout(_chkstat,100);}else{$("#stat_text").html('<font color="red">'+data["err"])+'</font>';}},"json");
	
}
var ScoreColor=[[244,26,25],[244,27,25],[244,29,25],[244,31,25],[245,34,25],[245,36,25],[245,39,25],[245,42,25],[246,45,25],[246,48,25],[247,52,26],[248,55,25],[248,58,25],[249,62,26],[249,66,26],[250,70,25],[250,73,26],[251,77,25],[252,82,26],[252,84,25],[252,89,26],[252,93,25],[252,97,26],[252,102,25],[252,106,26],[252,111,25],[252,115,25],[253,119,25],[253,124,26],[253,128,26],[252,133,26],[252,138,25],[252,142,26],[253,146,26],[253,151,25],[252,156,26],[253,161,26],[252,165,26],[252,169,27],[252,174,26],[252,178,26],[253,182,27],[252,187,26],[252,190,26],[252,195,27],[252,199,26],[252,203,27],[252,207,27],[253,210,26],[252,214,27],[252,218,27],[252,221,27],[252,225,27],[252,228,27],[252,232,27],[253,234,27],[252,237,27],[253,239,27],[252,242,27],[252,245,28],[252,246,28],[252,249,28],[253,251,28],[252,252,28],[252,254,27],[252,255,28],[249,255,28],[246,255,29],[242,255,29],[237,255,29],[232,255,29],[228,255,29],[222,255,30],[216,255,30],[210,255,30],[203,255,30],[197,255,31],[191,253,31],[183,251,32],[176,249,31],[169,246,32],[162,243,32],[154,240,33],[147,237,33],[140,234,34],[133,231,34],[126,228,34],[119,225,34],[111,222,34],[104,218,35],[98,215,36],[92,212,36],[85,209,36],[79,207,36],[75,205,36],[69,202,37],[64,200,37],[59,198,37],[55,195,37],[50,195,38],[48,192,37]]; //0-100都有~

function _chkstat(sid)
{
	if(sid!=stat_id) return;
	judge_result=new Array("提交中","提交重测","编译中","运行中","答案正确","Presentation Error","答案错误","运行时间超出限制","内存超出限制","输出超出限制","运行时错误","编译错误","未知错误 #1","未知错误 #2");
	rcolor=new Array("grey","grey","orange","orange","green","red","red","red","red","red","red","brown","red","red");
	//ecolor=new Array("green","orange","orange","orange","red","red");
	//jr=new Array("Accepted","Wrong Arguments","Source not exist","Destination not exist","Wrong Answer","Presentation Error");
	$.getJSON("ajax_solution_status.php",{rand:Math.random(),solution_id:(typeof(sid)==='undefined')?stat_sid:sid},function(data){
		/*
			#define MATCHER_ACCEPT              0
			#define MATCHER_WRONG_ARGS          1
			#define MATCHER_NOT_EXIST_SRC       2
			#define MATCHER_NOT_EXIST_DEST      3
			#define MATCHER_WRONG_ANWSER        4
			#define MATCHER_PRESENT_ERROR       5
		*/
		if(data['no']===0)
		{
			//成功获取状态
			try{console.debug(data);}catch(e){}
			var totr=data['result'];
			var mem=data['memory'];
			var time=data['time'];
			var cei=data['ceinfo'];
			var rei=data['reinfo'];
			var prps='';
			var score_color='';
			if(!iscontest)
			{try{
				var score_int=parseFloat(data['score']);
				score_color="rgb("+ScoreColor[score_int][0]+","+ScoreColor[score_int][1]+","+ScoreColor[score_int][2]+")";
				score_color='color:'+score_color+';';
			}catch(e){}
			//background-color:rgba('+(255-ScoreColor[score_int][0])+','+(255-ScoreColor[score_int][1])+','+(255-ScoreColor[score_int][2])+',0.1);display:inline-block;width:30px;text-align:center;border-radius:2px';
			}
			var thtml='已提交，ID：'+((typeof(sid)==='undefined')?stat_sid:sid)+'，分数：<span style="'+score_color+'">'+data['score']+'</span>，状态：<font color="'+rcolor[totr]+'">'+judge_result[totr]+'</font><br>评测时间：'+data['judgetime']+'，';
			//比赛：暂时设为13
			/*if(totr==13&&iscontest)
			{
				thtml+='您的程序已经评测完毕，请您继续参加比赛吧 :D<br>';
				$("#stat_text").html(thtml);
				return;
			}*/
			if(1||totr>=4)		//其实这就是评测结束
			{
				thtml+='最大内存使用：'+mem+'KB, 最长时间占用：'+time+'ms<br>';
				if(totr==11)
				{
					thtml+="编译错误，具体信息：<br>";
				}
				if(cei!='')thtml+="<pre style='background-color:rgba(255,0,0,0.2);padding:20px'>"+cei+"</pre>";
				if(totr==10)
				{
					thtml+="运行时错误，具体信息：<br>";
				}
				if(rei!='')thtml+="<pre style='background-color:rgba(255,255,0,0.2);padding:20px'>"+rei+"</pre>";
				thtml+='<br><br>';
				var splstr=data['judgeinfo'].split(';');
				//console.debug(splstr);
				thtml+='<table style="border:0;width:100%">';
				for(var i=0;i<splstr.length;i++)
				{
					var thspl=splstr[i].split(',');
					//console.debug(thspl);
					var para=new Array();
					for(j in thspl)
					{
						para.push(parseInt(thspl[j]));
					}
					if(isNaN(para[0])) break;
					var tcolor=rcolor[para[1]];
					thtml+='<tr style="color:'+tcolor+'"><td>评测数据</td><td>#'+para[0]+'&nbsp;&nbsp;</td><td>'+judge_result[para[1]]+'</td><td>内存:</td><td style=text-align:right>'+para[2]+' KB&nbsp;&nbsp;</td><td>用户态时间:</td><td style=text-align:right>'+para[3]+' ms&nbsp;&nbsp;</td><td>内核态时间:</td><td style=text-align:right>'+para[4]+' ms</td></tr>';
				}
				thtml+='</table>';
			}
			if(totr<4)
			{
				//尚未评测完成
				thtml+='尚未评测完成，正在获取最新状态中……<br>';
				if(nowtype==3)setTimeout(function(){_chkstat((typeof(sid)==='undefined')?stat_sid:sid);},200);
			}
			else
				if(!isstatuspage)
				{
					if(!iscontest)rf_problem_page();
					else rf_problem_page_contest();
				}
			$("#stat_text").html(thtml);
		}
		else
		{
			$("#stat_text").html('<font color=red>'+data["err"]+'</font>');
		}
	})
}