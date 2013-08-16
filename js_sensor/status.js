// JavaScript Document
function status_init()
{
	lasttop=$('#disp-board').offset().top+100;
	bt_func.push(function(){nowtype=0;$('#stat-l2').fadeOut(300);if(document.body.scrollTop+document.documentElement.scrollTop==lasttop) $('html,body').stop(true,false).animate({scrollTop:0},300,"swing");else $('html,body').stop(true,false).animate({scrollTop:lasttop},300,"swing");});
	isstatuspage=1;
}

var iscontest=0;
var lasttop=0;
var stat_id=0;
function view_solution_status(id)
{
	lasttop=document.body.scrollTop+document.documentElement.scrollTop;
	$('#stat-l2').fadeIn(400);
	$('#stat_text').html('等待请求');
	nowtype=3;
	stat_id=id;
	_chkstat(id);
	$("html,body").animate({"scrollTop":$('#stat-l2').offset().top-100},400,"swing");
}

function showmysrc(id,lang)
{
	stat_id=id;
	nowtype=0;

	lasttop=document.body.scrollTop+document.documentElement.scrollTop;
	$('#stat-l2').fadeIn(400);
	$('#stat_text').html('等待请求');
	$.getJSON("ajax_get_src.php",{id:id},
		function(data)
		{
			if(data['no']==0)
			{
				$('#stat_text').html('<pre><code style="background-color:rgba(160,160,160,0.3);border-radius:2px;border:1px solid rgba(140,140,140,0.3)" class="'+lang+'">'+data['src'].replace(/[<]/ig,'&lt;').replace(/[>]/ig,'&gt;')+'</code></pre>');
				setTimeout(_brush,40);
			}
			else
			{
				$('#stat_text').html(data['err']);
			}
		}
	);
	$("html,body").animate({"scrollTop":$('#stat-l2').offset().top-100},400,"swing");

}

function _brush()
{
	hljs.tabReplace = '    ';
	hljs.initHighlightingOnLoad();
	$('pre code').each(function(i, e) {hljs.highlightBlock(e)});
}
