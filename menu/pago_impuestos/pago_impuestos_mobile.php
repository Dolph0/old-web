1�) Se�ale el impuesto a pagar introduciendo la IDENTIFICACION, la REFERENCIA y el IMPORTE del pago. Encontrar� estos datos impresos en el documento que el Ayuntamiento ha enviado a su domicilio.
<br><br>
<form name="form" method="post">
  <table align="center" style="font-family: arial; font-size: 12px; color: black; font-weight: bold;">
				<tr>
          <td>
            <input type="text" size="20" maxlength="20" id="referencia" name="referencia" value="" placeholder='Referencia'>
            <br>
          </td>

				</tr>
				<tr>
				<td>
          <input type="text" size="20" maxlength="20" id="identificacion"  name="identificacion" value="" placeholder='Identificaci�n'>
          <br>
        </td>
				</tr>
				<tr>
				<td>
          <input type="text" size="20" maxlength="10" id="importe" name="importe" value="" placeholder='Importe Ej: 480,56'>&nbsp;�
          <br>
        </td>
				</tr>
				</table>
  </form>
<br>
2�) Pago <br>
a) Si desea pagar mediante tarjeta bancaria.
<br><br>

<script>
  function openTarjetas(){
    var importe = document.getElementById('importe').value;
    var identificacion = document.getElementById('identificacion').value;
    var referencia = document.getElementById('referencia').value;

    console.log('Importe: ' + importe);
    window.open("./pago_tarjeta_TPVBBVA.php?importe="+importe+"&identificacion="+identificacion+"&referencia="+referencia, "_self");
  }

  function openBBVA(){
    var importe = document.getElementById('importe').value;
    var identificacion = document.getElementById('identificacion').value;
    var referencia = document.getElementById('referencia').value;

    window.open("bbva/pedir_dni.php?comprobar_importe=SI&importe="+importe+'&identificacion='+identificacion+'&referencia='+referencia, "_self");
  }

  function openBancaMarch(){
    var importe = document.getElementById('importe').value;
    var identificacion = document.getElementById('identificacion').value;
    var referencia = document.getElementById('referencia').value;

    window.open("pago_tarjeta_TPVBBVA.php?importe="+importe+"&identificacion="+identificacion+"&referencia="+referencia, "_self");
  }

  function openCaixa(){
    var importe = document.getElementById('importe').value;
    var identificacion = document.getElementById('identificacion').value;
    var referencia = document.getElementById('referencia').value;

    window.open("pago_tarjeta_TPVBBVA.php?importe="+importe+"&identificacion="+identificacion+"&referencia="+referencia, "_self");
  }
</script>


 <div align='center'>

      <!-- TARJETA -->
      <span border=1 style=\"cursor: pointer;\" onclick="openTarjetas();">
        <img src="../../imagenes/entidades/cards.jpg" border="0" width='200px'>&nbsp;
      </span>



				<!-- BBVA -->
        <!--
        <br>
				<span style=\"cursor: pointer;\" onclick="openBBVA();"><img src="../../imagenes/entidades/bbva.jpg" border=\"0\"></span>
        -->

<!--
       <br>
-->

				<!-- LA BANCA MARCH -->
<!--
				<span style=\"cursor: pointer;\" onclick="form.action='bancamarch/envio.php'; form.submit();"><img src="../../imagenes/entidades/banca_march.jpg" border=\"0\"></span>
      <br>
-->
<br>

				<!-- LA CAIXA 
				<span style=\"cursor: pointer;\" onclick="form.action='lacaixa/envio.php'; form.submit();"><img src="../../imagenes/entidades/lacaixa.jpg" border=\"0\"></span> -->
</div>
b) Si lo desea tambi�n puede realizar el tr�mite o pago a trav�s de la banca electr�nica de su entidad bancaria si es entidad colaboradora del Ayuntamiento de Mog�n, para lo que ha de entrar a trav�s de la p�gina web de su banco.
<br><br>
Cualquiera que sea la modalidad de pago utilizada, podr� solicitar la Carta de Pago transcurridas al menos 24 horas desde la transacci�n, en las Oficinas de Recaudaci�n del Ayuntamiento de Mog�n:
<br><br>
<hr>
Avda. de la Constituci�n 14
35140 Mog�n
<br>
Telf.: 928 15 88 06
Fax.: 928 56 85 12
<hr>
Calle Tamar�n 4
35120 Arguinegu�n
<br>
Telf.: 928 56 85 66
Fax.: 928 73 50 04
<hr>
gestionmunicipal@gestmogan.com
