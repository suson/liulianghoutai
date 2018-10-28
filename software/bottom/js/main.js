$(function(){
	g_obj 		= $("#ul_play");
	g_length 	= g_obj.children().length;
	if (g_length > 1)
	{
		g_timer = setInterval(animate, g_ltimer);
		g_obj.mouseover(stop);
		g_obj.mouseout(start);
	}
});
var g_obj = {};
var g_length = 0;
var g_loop	 = 1;
var g_timer  = 0;
var g_ltimer = 3000;
function animate()
{
	g_obj.children().hide().eq(g_loop % g_length).fadeIn("slow");
	g_loop++;
}
function stop()
{
	clearInterval(g_timer);
	g_timer = 0;
}
function start()
{
	g_timer = setInterval(animate, g_ltimer);
}
