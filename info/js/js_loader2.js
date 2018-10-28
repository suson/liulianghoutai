$(document).ready(
	function ()
	{
		$.ajaxSetup({async: false});
		$.getScript("js/info2.js");
		$.ajaxSetup({async: true});		
		
		WebApp.WebMain();
	}
);