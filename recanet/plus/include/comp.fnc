<?
function complementario ($estado='M')
{
 global
  $_clastran,
  $_clasdere,
  $_precescr,
  $_obse;


 if ($estado == 'M')
{
?>
<table cellpadding=2 cellspacing=1 border=0 width=100%>
  <tr>
    <td colspan=4 class=izqform>Datos complementarios</td>
  </tr> 
  <tr>
    <td class=izqform width=20%>Mercado</td>
    <td colspan=3 class=derform>
      <table>
       <tr><td>Precio declarado</td></tr>
       <tr><td><? cajatexto ('_precescr', $_precescr, 'euro') ?></td></tr>
      </table>
    </td> 
  </tr>
  <tr>
    <td class=izqform width=20%>Observaciones</td>
    <td colspan=3  class=derform><?areatexto ('_obse', $_obse)?> 
    </td>
  </tr>
</table>
<?
 }
}



?>

