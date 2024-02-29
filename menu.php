<?php
// Recoger el parámetro que indica el idioma seleccionado
if ($_GET["idioma"]!="") $idioma=$_GET["idioma"]; else $idioma="esp";

// Cargar el fichero de idiomas
include "idioma/idioma_".$idioma.".php";

$estiloentorno="";
$entorno = getenv('ENTORNO');
if( $entorno && $entorno ='desarrollo'){
    $estiloentorno = "<link rel=\"stylesheet\" href=\"css/estilos.dev.css\" type=\"text/css\">";
}

print("
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
<link rel=\"stylesheet\" href=\"css/estilos.css\" type=\"text/css\">
$estiloentorno
</head>

<body bgcolor=\"#FFFFFF\" text=\"#000000\">
<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" height=\"100%\">
  <tr>
    <td class=\"td_izdo_menu\">
      <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">
        <tr>
          <td class=\"td_linea_menu\" align=\"right\"><img src=\"imagenes/trans.gif\" width=\"1\" height=\"1\"></td>
        </tr>
        <tr>
          <td align=\"left\">
            <img src=\"imagenes/ic_infooutline.png\" align=\"absmiddle\" hspace=\"5\" vspace=\"7\">
            <a href=\"menu/calendario.php?idioma=".$idioma."\" target=\"contenido\" class=\"txt_menu\">".$IDI_CALE."</a>
         </td>
        </tr>
        <tr>
          <td class=\"td_linea_menu\" align=\"right\"><img src=\"imagenes/trans.gif\" width=\"1\" height=\"1\"></td>
        </tr>
        <tr>
          <td align=\"left\">
            <img src=\"imagenes/ic_formatlist.png\" align=\"absmiddle\" hspace=\"5\" vspace=\"7\">
            <a href=\"menu/info_fiscal/ordenanzas.php?idioma=".$idioma."\" target=\"contenido\" class=\"txt_menu\">".$IDI_INFO."</a>
          </td>
        </tr>
        <tr>
          <td class=\"td_linea_menu\" align=\"right\"><img src=\"imagenes/trans.gif\" width=\"1\" height=\"1\"></td>
        </tr>
        <tr>
          <td align=\"left\">
            <img src=\"imagenes/ic_zona_azul.png\" align=\"absmiddle\" hspace=\"5\" vspace=\"7\">
            <a href=\"menu/pago_impuestos/zona_azul.php?idioma=".$idioma."\" target=\"contenido\" class=\"txt_menu\">
            $IDI_ZONA
            </a>
          </td>
        </tr>
        <tr>
          <td class=\"td_linea_menu\" align=\"right\"><img src=\"imagenes/trans.gif\" width=\"1\" height=\"1\"></td>
        </tr>
        <tr>
          <td align=\"left\">
            <img src=\"imagenes/ic_payment.png\" align=\"absmiddle\" hspace=\"5\" vspace=\"7\">
            <a href=\"menu/pago_impuestos/pago_impuestos.php?idioma=".$idioma."\" target=\"contenido\" class=\"txt_menu\">
            $IDI_PAGO
            </a>
          </td>
        </tr>
        <tr>
          <td class=\"td_linea_menu\" align=\"right\"><img src=\"imagenes/trans.gif\" width=\"1\" height=\"1\"></td>
        </tr>
        <tr>
          <td align=\"left\">
            <img src=\"imagenes/ic_edit.png\" align=\"absmiddle\" hspace=\"5\" vspace=\"7\">
            <a href=\"menu/tramites/tramites.php?idioma=".$idioma."\" target=\"contenido\" class=\"txt_menu\">$IDI_TRAM</a>
          </td>
        </tr>
        <tr>
          <td class=\"td_linea_menu\" align=\"right\"><img src=\"imagenes/trans.gif\" width=\"1\" height=\"1\"></td>
        </tr>
        <tr>
          <td nowrap align=\"left\">
            <img src=\"imagenes/ic_keyboard.png\" align=\"absmiddle\" hspace=\"5\" vspace=\"7\">
            <a href=\"menu/plusvalia.php?idioma=".$idioma."\" target=\"contenido\" class=\"txt_menu\">".$IDI_SIMU."</a>
          </td>
        </tr>
        <tr>
          <td class=\"td_linea_menu\" align=\"right\"><img src=\"imagenes/trans.gif\" width=\"1\" height=\"1\"></td>
        </tr>
        <tr>
          <td align=\"left\">
            <img src=\"imagenes/ic_messages.png\" align=\"absmiddle\" hspace=\"5\" vspace=\"7\">
            <a href=\"menu/tablon_anuncios/convocatoria.php?idioma=".$idioma."\" target=\"contenido\" class=\"txt_menu\">$IDI_TABL</a>
          </td>
        </tr>
        <tr>
          <td class=\"td_linea_menu\" align=\"right\"><img src=\"imagenes/trans.gif\" width=\"1\" height=\"1\"></td>
        </tr>
        <tr>
          <td align=\"left\">
            <img src=\"imagenes/ic_account.png\" align=\"absmiddle\" hspace=\"5\" vspace=\"7\">
            <a href=\"menu/perfil_anunciantes/anunciantes.php?idioma=".$idioma."\" target=\"contenido\" class=\"txt_menu\">".$IDI_CAB_PERF."</a>
          </td>
        </tr>
        <tr>
          <td class=\"td_linea_menu\" align=\"right\"><img src=\"imagenes/trans.gif\" width=\"1\" height=\"1\"></td>
        </tr>
        <tr>
          <td align=\"left\">
            <img src=\"imagenes/ic_contact.png\" align=\"absmiddle\" hspace=\"5\" vspace=\"7\">
            <a href=\"menu/contacto_consultas.php\" target=\"contenido\" class=\"txt_menu\">".$IDI_CONT.$IDI_CONS."</a>
          </td>
        </tr>
        <tr>
          <td class=\"td_linea_menu\" align=\"right\"><img src=\"imagenes/trans.gif\" width=\"1\" height=\"1\"></td>
        </tr>
        <tr>
          <td align=\"left\">
            <img src=\"imagenes/ic_map.png\" align=\"absmiddle\" hspace=\"5\" vspace=\"7\">
            <a href=\"callejero.php?idioma=".$idioma."\" target=\"contenido\" class=\"txt_menu\">".$IDI_CALL."</a>
          </td>
        </tr>
        <tr>
          <td class=\"td_linea_menu\" align=\"right\"><img src=\"imagenes/trans.gif\" width=\"1\" height=\"1\"></td>
        </tr>
        <tr>
          <td nowrap align=\"left\">
            <img src=\"imagenes/ic_business.png\" align=\"absmiddle\" hspace=\"5\" vspace=\"7\">
            <a href=\"menu/la_empresa.php?idioma=".$idioma."\" target=\"contenido\" class=\"txt_menu\">".$IDI_EMPR."</a>
          </td>
        </tr>
        <tr>
          <td class=\"td_linea_menu\" align=\"right\"><img src=\"imagenes/trans.gif\" width=\"1\" height=\"1\"></td>
        </tr>
        <tr>
          <td nowrap align=\"left\">
            <img src=\"imagenes/ic_transparencia.png\" align=\"absmiddle\" hspace=\"5\" vspace=\"7\">
            <a href=\"https://transparencia.mogan.es/transparencia-institucional-organizativa-y-personal/entidades-participadas-por-el-ayuntamiento/#gestmogan\" target=\"contenido\" class=\"txt_menu\">".$IDI_TRANS."</a>
          </td>
        </tr>
        <tr>
          <td class=\"td_linea_menu\" align=\"right\"><img src=\"imagenes/trans.gif\" width=\"1\" height=\"1\"></td>
        </tr>
      </table>

     </td>
    <td width=\"8\" class=\"td_dcho_menu\">&nbsp;</td>
   </tr>

   <tr>
     <td align=\"center\" valign=\"top\" class=\"td_izdo_menu\">
       <a href=\"menu/politica_privacidad.php?idioma=".$idioma."\" target=\"contenido\" class=\"txt_chico\">".$IDI_POL."</a>
     </td>
     <td width=\"8\" class=\"td_dcho_menu\">&nbsp;</td>
   </tr>

</table>
</body>
</html>
");