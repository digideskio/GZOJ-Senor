// JavaScript Document

//Problemset

function problemset_init()
{
	rf_page(page);
	rf_func.push(function(){rf_page(page);});
}

function rf_page(p)
{
		page=p;
		$("#loading-problemset").css({'background':'url(./img_senor/loading.gif) no-repeat',"opacity":"1"});
		$(".switch_page_btn").removeClass("btn-warning").removeClass("btn-gray");
		for(var i=1;i<=totalpage;i++)
		{
			var o=$("#a-page"+i);
			if(i==p)
			{
				o.addClass("btn-warning");
			}
			else
			{
				o.addClass("btn-gray");
			}
		}
		$("#problemset_show").animate({opacity:"0"},50,"swing",
			function()
			{
				$.getJSON("ajax_problemset.php",{page:p,rand:Math.random()},function(data){_getPage_JSON_handler(data);});
			});
}

function _getPage_JSON_handler(data)
{
	if(data['no']==0)
	{
		$("#loading-problemset").css('opacity','0');
		$("#problemset_show").html(data["err"]).animate({opacity:"1"},300,"swing");
	}
	else
	{
		$("#loading-problemset").animate({'background':'url(./img_senor/no.png) no-repeat'},300,"linear");
		$("#problemset_show").html(data["err"]).animate({opacity:"1"},300,"swing");
	}
}

function find_problemset()
{
		$("#loading-problemset").css({'background':'url(./img_senor/loading.gif) no-repeat',"opacity":"1"});
		$('#page-switcher-layer').fadeOut(400);
		$("#problemset_show").animate({opacity:"0"},50,"swing",
			function()
			{
				$.getJSON("ajax_problemset.php",{"search":frmSearch.txtsearch.value,rand:Math.random()},function(data){_getPage_JSON_handler(data);});
			});
}