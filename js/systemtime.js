
var Intervall = null;

	function dataprocess()
	{
		if( AjaxRequest.readyState == 4 && AjaxRequest.status == 200 )
		{
			document.getElementById("time").innerHTML = AjaxRequest.responseText;	
		}
	}
	
	function get_time()
	{
		AjaxRequest = new XMLHttpRequest();
		AjaxRequest.onreadystatechange = dataprocess;
		AjaxRequest.open("GET","index.php", true );
		AjaxRequest.send(null);
	}
	
	function start_interval ()
	{
		if( ! Intervall)
		{
			Intervall = setInterval(get_time, 500);
		}
	}
	
	function stop_interval() 
	{
		if( Intervall)
		{
			clearInterval(Intervall);
			Intervall = null;
		}
	}
	