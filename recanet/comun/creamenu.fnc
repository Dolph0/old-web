<?

function inicio() {
# Inserta todas las funciones javascript necesarias para
# que funcione correctamente el menú
echo ("
 <style>
a {font-family: verdana;arial; font-size: 9pt; font-style: normal; color: #666666; text-decoration: none}
.menuprin {font-family: Verdana; color: #FF6600; background-color: #FFD8B1}
.cabeform {  font-family: Verdana; font-size: 8pt; color: #FF6600; background-color: #FFD8B1; font-weight: bold }
 </style>
 
 
 <script LANGUAGE=\"JavaScript\">
   <!--

//  function doDocumentOnMouseOver() {
// Sí siempre paso  	alert (\"alguna vez paso por doDocumentOnMouseOver?\");
//    var eSrc = window.event.srcElement ;
//    if (eSrc.className == \"item\") {
//      window.event.srcElement.className = \"highlight\";
//    }
//  }

//  function doDocumentOnMouseOut() {
// Sí siempre paso   	alert (\"alguna vez paso por doDocumentOnMouseOut\");
//    var eSrc = window.event.srcElement ;
//    if (eSrc.className == \"highlight\") {
//      window.event.srcElement.className = \"item\";
//    }
//  }


var bV=parseInt(navigator.appVersion);
NS4=(document.layers) ? true : false;
IE4=((document.all)&&(bV>=4))?true:false;
ver4 = (NS4 || IE4) ? true : false;

function expandIt(){return}
function expandAll(){return}
//-->
</script>

<script LANGUAGE=\"JavaScript1.2\">
<!--
isExpanded = false;

function getIndex(el) {
	//alert (\"estoy dentro de getIndex\");
	ind = null;
	for (i=0; i<document.layers.length; i++) {
		whichEl = document.layers[i];
		if (whichEl.id == el) {
			ind = i;
			break;
		}
	}
	return ind;
}

// Esta función sólo se utiliza en NS4
function arrange() {
	nextY = document.layers[firstInd].pageY + document.layers[firstInd].document.height;
	for (i=firstInd+1; i<document.layers.length; i++) {
		whichEl = document.layers[i];
		if (whichEl.visibility != \"hide\") {
			whichEl.pageY = nextY;
			nextY += whichEl.document.height;
		}
	}
}

function initIt(){
	// Se utiliza para ocultar todos los hijos 
	if (NS4) {
		for (i=0; i<document.layers.length; i++) {
			whichEl = document.layers[i];
			if (whichEl.id.indexOf(\"Child\") != -1) whichEl.visibility = \"hide\";
		}
		arrange();
	}
	else {
		tempColl = document.all.tags(\"DIV\"); // Devuelve en un vector todas las tags correspondientes: curioso
		for (i=0; i<tempColl.length; i++) {
			if (tempColl(i).className == \"child\") tempColl(i).style.display = \"none\";
		}
	}
}

function expandIt(el) {
	//alert (\"estoy dentro de expandIt\");
	if (!ver4) return;
	if (IE4) {expandIE(el)} else {expandNS(el)}
}

// Expande el hijo del que se trate
function expandIE(el) { 
	//alert (\"estoy dentro de expandIE\");
	whichEl = eval(el + \"Child\");

        // Modified Tobias Ratschiller 01-01-99:
        // event.srcElement obviously only works when clicking directly
        // on the image. Changed that to use the images's ID instead (so
        // you've to provide a valid ID!).

	//whichIm = event.srcElement;
        whichIm = eval(el+\"Img\");

	if (whichEl.style.display == \"none\") {
		// Se muestra el bloque
		whichEl.style.display = \"block\";
		whichIm.src = \"images/menos.gif\";	
		whichIm.alt = \"Recoger opciones\";	
	}
	else {
		// Se oculta
		whichEl.style.display = \"none\";
		whichIm.src = \"images/mas.gif\";
		whichIm.alt = \"Desplegar opciones\";
	}
    window.event.cancelBubble = true ;
}

function expandNS(el) {
	whichEl = eval(\"document.\" + el + \"Child\");
	whichIm = eval(\"document.\" + el + \"Parent.document.images['imEx']\");
	if (whichEl.visibility == \"hide\") {
		whichEl.visibility = \"show\";
		whichIm.src = \"images/menos.gif\";
		whichIm.alt = \"Recoger opciones\";
	}
	else {
		whichEl.visibility = \"hide\";
		whichIm.src = \"images/mas.gif\";
		whichIm.alt = \"Desplegar opciones\";
	}
	arrange();
}

function showAll() {
// 	alert (\"estoy dentro de showall\");
	for (i=firstInd; i<document.layers.length; i++) {
		whichEl = document.layers[i];
		whichEl.visibility = \"show\";
	}
}

function expandAll(isBot) {
	newSrc = (isExpanded) ? \"images/mas.gif\" : \"images/menos.gif\";

	if (NS4) {
        // TR-02-01-99: Don't need that
        // document.images[\"imEx\"].src = newSrc;
		for (i=firstInd; i<document.layers.length; i++) {
			whichEl = document.layers[i];
			if (whichEl.id.indexOf(\"Parent\") != -1) {
				whichEl.document.images[\"imEx\"].src = newSrc;
			}
			if (whichEl.id.indexOf(\"Child\") != -1) {
				whichEl.visibility = (isExpanded) ? \"hide\" : \"show\";
			}
		}

		arrange();
		if (isBot && isExpanded) scrollTo(0,document.layers[firstInd].pageY);
	}
	else {
		alert (\"estoy dentro de expand all\"); 
		divColl = document.all.tags(\"DIV\");
		for (i=0; i<divColl.length; i++) {
			if (divColl(i).className == \"child\") {
				divColl(i).style.display = (isExpanded) ? \"none\" : \"block\";
			}
		}
		imColl = document.images.item(\"imEx\");
		for (i=0; i<imColl.length; i++) {
			imColl(i).src = newSrc;
		}
	}

	isExpanded = !isExpanded;
}

with (document) {
	// Simplemente se pone el estilo: es cual se puede poner a pelo
	write(\"<STYLE TYPE='text/css'>\");
	if (NS4)
        {
        write(\".parent {font-family: Verdana, Arial, Helvetica, sans-serif; color: #000000; text-decoration:none; position:absolute; visibility:hidden; color: black;}\");
        write(\".child {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt;color: #000000; position:absolute; visibility:hidden}\");
        write(\".item { font-family: Verdana, Arial, Helvetica, sans-serif; color: black; text-decoration:none; font-size: 8pt;}\");
        write(\".regular {font-family: Arial,Helvetica,sans-serif; position:absolute; visibility:hidden}\");
		write(\"A:link.nav {  font-family: Verdana, Arial, Helvetica, sans-serif; color: #000000}\");
		write(\"A:visited.nav {  font-family: Verdana, Arial, Helvetica, sans-serif; color: #000000}\");
		write(\"A:hover.nav {  font-family: Verdana, Arial, Helvetica, sans-serif; color: red;}\");
		write(\".nav {  font-family: Verdana, Arial, Helvetica, sans-serif; color: #000000}\");
        write(\"DIV { color:black; }\")
        }
	else
        {
        write(\".child {font-family: Verdana, Arial, Helvetica, sans-serif; color: #000000; text-decoration:none; width:auto; display:none}\");
        write(\".parent {font-family: Verdana, Arial, Helvetica, sans-serif; color: #000000; text-decoration:none; position:relative;}\");
        write(\".item { font-family: Verdana, Arial, Helvetica, sans-serif; color: black; text-decoration:none; font-size: 8pt;}\");
        write(\".highlight { color: red; font-size: 8pt;}\");
        write(\".heada { font: 12px/13px; Times}\");
		write(\"A:link.nav {  font-family: Verdana, Arial, Helvetica, sans-serif; color: #000000}\");
		write(\"A:visited.nav {  font-family: Verdana, Arial, Helvetica, sans-serif; color: #000000}\");
		write(\"A:hover.nav {  font-family: Verdana, Arial, Helvetica, sans-serif; color: red;}\");
		write(\".nav {  font-family: Verdana, Arial, Helvetica, sans-serif; color: #000000}\");
        write(\"DIV { color:black; }\")
	    }
	write(\"</STYLE>\");

}

onload = initIt;

//-->
</script>
</head>

<body class='menuprin' bgcolor=\"#ffd8b1\" topmargin=5 leftmargin=5>

"); 

}

# Inserta un padre (submenú)
function insertapadre ($ident,$titu,$nivel) {

  print "<!-- Nodo padre $ident en el nivel $nivel -->\n\n";

  print "<DIV ID=\"${ident}Parent\" CLASS=\"parent\">\n";
  print "<NOBR>";
  for ($i=0; $i<$nivel; $i++) {
    print "&nbsp;";
  }
  print "<A class=\"item\" HREF=\"\" onClick=\"expandIt('$ident'); return false;\"><IMG NAME=\"imEx\" SRC=\"images/mas.gif\" BORDER=\"0\" ALT=\"Desplegar opciones\" width=\"9\" height=\"9\" ID=\"${ident}Img\"><FONT class=\"item\"> $titu</FONT></a>\n";
  print "</NOBR>\n</DIV>\n";
}


function abremenu  ($identPadre) {
  print "\n\n<DIV ID=\"${identPadre}Child\" CLASS=\"child\">\n";
}

function cierramenu ($identPadre) {
  print "</DIV>\n<!-- Fin del menú $identPadre -->\n\n";
}

# Inserta un hijo
function insertahijo ($titulo,$enlace,$frame,$nivel) {

  print "<NOBR>\n";
  for ($i=0; $i < $nivel; $i++) {
    print "&nbsp;";
  }
  print "<A target=\"$frame\" href=\"$enlace\" class=\"item\"><IMG src=\"images/nodo.gif\" border=\"0\" alt=\"\" width=\"9\" height=\"9\"> $titulo</A>\n";
  print "</NOBR>\n";
  print "<BR>\n"; 
}


function fin ()
{
}

?>
