<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Justificante de Pago de Tributos</title>
    <LINK REL=STYLESHEET TYPE="text/css" HREF="../css/estilos_lacajadecanarias.css">
</head>
<body topmargin=1>
<img src="logojus.gif">
<center>
 <table width=100% cellspacing=0 cellpadding=8 border=1>
  <tr>
   <td  colspan=4 class="CabeceraFrame" align="center" >Justificante de Pago de Tributos Ayuntamiento de Mógan</td>
  </tr> 
 </table>
 <br>
 <table width=100% cellspacing=0 cellpadding=4 border=1>
  <tr>
   <td align="left" class="CabeceraTabla5" width="28%"><b>Oficina</b></td>
   <td  colspan=3 align="left" class="datosjustificante" width="*"><?echo $_POST[oficina]?></td>
   <td align="left" class="CabeceraTabla5" width="32%"><b>Terminal</b></td>
   <td  align="left" class="datosjustificante" colspan=2><?echo $_POST[terminal]?></td>  
  </tr>    
  <tr>
   <td align="left" class="CabeceraTabla5" width="28%"><b>N&uacute;mero de Tarjeta</b></td>
   <td  colspan=3 align="left" class="datosjustificante"><?echo $_POST[pan]?></td>	   
   <td align="left" class="CabeceraTabla5" width="32%" colspan=2><b>Fecha de Caducidad</b></td>
   <td  align="left" class="datosjustificante" width="13%"><?echo $_POST[fechaCaducidad]?></td>
  </tr>    
  <tr>
   <td align="left" class="CabeceraTabla5" width="28%"><b>Entidad Emisora</b></td>
   <td  colspan=6 align="left" class="datosjustificante"><?echo $_POST[codigoEntidad]?></td>
  </tr>	  
  <tr>
   <td class="CabeceraTabla5" align="left" width="28%"><b>Identificaci&oacute;n</b></td>
   <td  colspan=6 align="left" class="datosjustificante"><?echo $_POST[descripEmisor]?>&nbsp;&nbsp;<?echo $_POST[identificacion]?></td>
  </tr>
  <tr>	  	  
   <td class="CabeceraTabla5" align="left" width="28%"><b>Referencia</b></td>
   <td  colspan=3 align="left" class="datosjustificante"><?echo $_POST[referencia]?></td>	  
   <td align="left" class="CabeceraTabla5" width="32%" colspan=2><b>Importe</b></td>
   <td align="left"  class="datosjustificante"><?echo $_POST[importe]?>&nbsp;&nbsp;<?echo $_POST[divisa]?></td>
  </tr>
  <tr>
   <td align="left" class="CabeceraTabla5" width="28%"><b>Operaci&oacute;n</b></td>
   <td  colspan=6 align="left" class="datosjustificante"><?echo $_POST[numOperacion]?></td>
  </tr>	  
 </table>
 </center>
 <center>  
 <table width=100%  border=0 cellspacing=0 cellpadding=0>
  <tr>
   <td class="Icono" colspan=3 align="left">
    <p class="justificante">Este comprobante es el justificante de pago a efectos liberatorios, frente a la Entidad emisora, unido al original del documento de pago remitido por esta:&nbsp;<?echo date("l dS of F Y h:i:s A")?></p>	  
   </td>
  </tr>     
  <tr>
   <td class="Icono" colspan=3 align="left">&nbsp;</td>
  </tr>		  
  <tr>		  		  
   <td class="Icono" colspan=3><p class="justificante">En caso de no poder imprimir el presente justificante, rogamos solicite en cualquier oficina de esta caja, certificaci&oacute;n del pago del recibo, aportando los datos de este.</p></td>
  </tr>		  		   	
  <tr>
   <td class="Icono" align="right" width=53%>&nbsp;<a href="javascript:history.go(-1);"><img src="volver.gif" border=0 alt="Volver a Pago Tributos"></a></TD>
   <td class="Icono" width=37%>&nbsp;</td>
   <td class="Icono" align="right" width=10%><a href="javaScript:print();"><img src="printr01.gif" border=0 alt="Imprimir Justificante" width=30 height=30></a></td>
  </tr>
 </table>	
 </center>
  

</body>
</html>
