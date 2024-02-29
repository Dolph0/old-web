1º) Señale el impuesto a pagar introduciendo la IDENTIFICACION, la REFERENCIA y el IMPORTE del pago. Encontrará estos datos impresos en el documento que el Ayuntamiento ha enviado a su domicilio.
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
          <input type="text" size="20" maxlength="20" id="identificacion"  name="identificacion" value="" placeholder='Identificación'>
          <br>
        </td>
				</tr>
				<tr>
				<td>
          <input type="text" size="20" maxlength="10" id="importe" name="importe" value="" placeholder='Importe Ej: 480,56'>&nbsp;€
          <br>
        </td>
				</tr>
				</table>
  </form>
<br>
2º) Pago <br>
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
b) Si lo desea también puede realizar el trámite o pago a través de la banca electrónica de su entidad bancaria si es entidad colaboradora del Ayuntamiento de Mogán, para lo que ha de entrar a través de la página web de su banco.
<br><br>
Cualquiera que sea la modalidad de pago utilizada, podrá solicitar la Carta de Pago transcurridas al menos 24 horas desde la transacción, en las Oficinas de Recaudación del Ayuntamiento de Mogán:
<br><br>
<hr>
Avda. de la Constitución 14
35140 Mogán
<br>
Telf.: 928 15 88 06
Fax.: 928 56 85 12
<hr>
Calle Tamarán 4
35120 Arguineguín
<br>
Telf.: 928 56 85 66
Fax.: 928 73 50 04
<hr>
gestionmunicipal@gestmogan.com
