<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Forma Fitness | Admin</title>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
	<script>
			tinymce.init({selector:'.wys',
				menubar : false,
				toolbar: "undo redo | cut copy paste selectall  | bold italic blockquote | numlist bullist"});
	</script>
	<script>
	//Clock
		function startTime()
		{
		var today=new Date();
		var h=today.getHours();
		var m=today.getMinutes();
		var s=today.getSeconds();
		// add a zero in front of numbers<10
		m=checkTime(m);
		s=checkTime(s);
		document.getElementById('clock').innerHTML=h+":"+m+":"+s;
		t=setTimeout(function(){startTime()},500);
		}

		function checkTime(i)
		{
		if (i<10)
		  {
		  i="0" + i;
		  }
		return i;
		}
	</script>
	<link rel="stylesheet" href="core/css/core.css" />
	<link rel="stylesheet" href="core/css/jquery-ui.css" />
	
	
	<script>
	  $(function() {
		$( "#tabs, .sub_tabs" ).tabs();
	  });
	  </script>
</head>
<body onload="startTime()">

