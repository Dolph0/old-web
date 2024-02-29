<?
echo "<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/wizard.js") . "'></SCRIPT>";
?>

<body onLoad="dc(0);">
    <div id="DIV_DESKTOP" style="background-image : url(<? cheqroot("imag/vacio.gif", TRUE) ?>);"> </div>
    <div id="loader">
      <table summary="Loader Layout" border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
        <tr>
          <td valign="middle" align="center">
            <table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%">
              <tr>
                <td valign="middle" align="center" width="147"><img src="<? cheqroot("imag/espera.gif", TRUE) ?>" align="center" border="0" hspace="0" vspace="0" height="63" width="147" alt="Recanet"></td>
                <td valign="middle">Por favor, espere hasta que la página se cargue totalmente.</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>
    <script language="JavaScript">dc(1);</script>

<?
flush();ob_flush();usleep(1);flush();ob_flush();

