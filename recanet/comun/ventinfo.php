<?
# Esta página es una página de pega para poder enlazar fácilmente los códigos
# tributarios a las páginas que muestran los objetos.

# Recibe cinco (!) parámetros: el tipo de objeto (tipoobje), y los cuatro que
# se usan para indizar las tablas de información adicional de cargos
# (codiconc, codiayun, ejer, numedocu)
# Tambien puede aparecer (numeingr) para los ingresos a cuenta

include "comun/sql.fnc";
include "comun/dire.fnc";
include "comun/euro.fnc";
include "comun/fecha.fnc";
include "comun/cheqroot.fnc";

    switch ($tipoobje) {
      case 'OICV':
        $titu= "del vehículo";
        $fich= "infoobje.inc";
        break;
      case 'OIBIURBA':
      case 'OIBIRUST':
        $titu= "del inmueble";
        $fich= "infoobje.inc";
        break;
      case 'OTPP':
        $titu= "del objeto tributario";
        $fich= "infoobje.inc";
        break;
      case 'OIAE':
        $titu= "de la actividad";
        $fich= "infoobje.inc";
        break;
      case 'OIPL':
        $titu= "de la plusvalía";
        $fich= "infoobje.inc";
        break;
      case 'OICI':
        $titu= "de la construcción, instalación u obra";
        $fich= "infoobje.inc";
        break;
      case 'SANCTRAF':
        $titu= "de la denuncia";
        $fich= "infoobje.inc";
        break;
      case 'SANCOTRA':
        $titu= "de la denuncia";
        $fich= "infoobje.inc";
        break;
      case 'estacont':
        $titu= "del estado contable";
        $fich= "infoobje.inc";
        break;
      case 'nifx':
        $titu= "del sujeto";
        $fich= "infosuje.inc";
        break;
      case 'volu':
        $titu= "del estado voluntario";
        $fich= "infoestanoti.inc";
        break;
      case 'prov':
        $titu= "del estado de providencia apremio";
        $fich= "infoestanoti.inc";
        break;
      case 'susp':
        $titu= "del estado suspensión";
        $fich= "infosusp.inc";
        break;
      case 'carg':
        $titu= "del cargo";
        $fich= "infocarg.inc";
        break;
      case 'ingracue':
        $titu= "del ingreso a cuenta";
        $fich= "infoobje.inc";
        break;
    }
?>
<head>
  <title>Información <?print $titu?></title>
  <link rel="stylesheet" href="<? cheqroot("comun/estilo.css", TRUE) ?>">
</head>

<body onLoad="redimen()" >
<script language="javascript">
  <!-- // Parte común -->
  function redimen () {
    // Calculamos teniendo en cuenta que hay que dejar algo de espacio debajo
    // del botón, y que las dimensiones *incluyen* la barra de título.
    // La medida del alto del botón parece un poco escasa, debería bastar con
    // multiplicarla por 3
    var ancho = contenido.offsetWidth  + 2 * contenido.offsetLeft;
    var alto  = boton.offsetTop + 5 * boton.offsetHeight;
    // alert ('Redimensiono a ' + ancho + 'x' + alto);
    self.resizeTo (ancho, alto);
    // Línea original:
    // self.resizeTo (contenido.offsetWidth + 20, contenido.offsetHeight + 100);
  }
</script>

<table id="contenido">
  <tr><td>
    <table width="100%">
      <?
        // La variable $procllam indica el script que llama a infoobje.
        // Por ahora se usa si el tipoobje es OIBIRUST, para diferenciar cada uno de los casos
        // en que se muestra o no, la informacion de las parcelas
        $procllam = "ventinfo";
        include "comun/".$fich;
      ?>
    </table>
  </td></tr>

  <tr><td>&nbsp;</td></tr>
</table>

<div id=boton align=center>
  <input type=button value="Cerrar" onClick="window.close()">
</div>
</body>
