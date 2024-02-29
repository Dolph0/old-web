<?php
// Recoger el parámetro que indica el idioma seleccionado
if ($_GET["idioma"]!="") $idioma=$_GET["idioma"]; else $idioma="esp";

print("
	<html>
	<head>
	<title>Web Recaudación Mogán</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	</head>
	<frameset rows=\"95,*\" cols=\"*\" border=\"0\" framespacing=\"0\" frameborder=\"NO\"> 
	  <frame name=\"arriba\" src=\"arriba.php?idioma=".$idioma."\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"NO\" noresize name=\"superior\">
	  <frame src=\"index_contenido.php?idioma=".$idioma."\">
	</frameset>
	<noframes>
	<body bgcolor='#FFFFFF' text='#000000'>
	</body>
	</noframes> 
	</html>
");