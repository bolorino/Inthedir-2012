<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>404 Page Not Found</title>
<style type="text/css">
body {
background: url("http://itdstatic.s3.amazonaws.com/assets/css/images/fabric.png") repeat scroll left top #fff;
margin:				40px;
font-family:		Lucida Grande, Verdana, Sans-serif;
font-size:			1em;
color:				#222;
}

#content  {
background-color: transparent;
padding: 20px 20px 12px 20px;
text-align: center;
}

h1 {
font-weight:		normal;
font-size:			3em;
color:				#990000;
margin:				0 0 4px 0;
}

p {
font-size: 1.2em;
line-height: 1.4em;
}
</style>
</head>
<body>
	<div id="content">
		<img src="http://itdstatic.s3.amazonaws.com/images/inthedir.png" alt="Inthedir logo" width="233" height="59" />
		
		<h1><?php echo $heading; ?></h1>
		<img src="http://itdstatic.s3.amazonaws.com/images/notfound.png" alt="Oh! Something went wrong" />
		<p>
		    <?php echo $message; ?>
		</p>
		<p>
			<a href="http://www.inthedir.com/es">Prueba en la página principal</a> <br />
			<a href="http://www.inthedir.com/">Try Home</a>
		</p>
	</div>
</body>
</html>