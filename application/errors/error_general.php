<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Error</title>
<style type="text/css">

body {
background-color:	#fff;
margin:				40px;
font-family:		Lucida Grande, Verdana, Sans-serif;
font-size:			1em;
color:				#000;
}

#content  {
background-color:	#fff;
padding:			20px 20px 12px 20px;
text-align: center;
}

h1 {
font-weight:		normal;
font-size:			3em;
color:				#990000;
margin:				0 0 4px 0;
}
</style>
</head>
<body>
	<div id="content">
		<h1><?php echo $heading; ?></h1>
		<img src="/images/notfound.png" alt="Oh! Something went wrong" />
		<?php echo $message; ?>
		
		<p>
			<a href="http://www.inthedir.com/"><?php echo _('Try to go Home');?></a> 
		</p>
	</div>
</body>
</html>