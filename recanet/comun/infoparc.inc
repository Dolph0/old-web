<?
  // Este fichero muestra un listado con datos de las parcelas asociadas 
  // al cargo seleccionado
  $query = "SELECT *
            FROM cargoibirustparc
            WHERE codiayun = '$codiayun' 
              AND codiconc = '$codiconc' 
              AND ejer = '$ejer'     
              AND numedocu = '$numedocu'";
                   
  $dato = sql ($query);
  if (is_array ($dato)) {
?>
    <tr><td colspan='4'>
      <table width='100%'>
        <tr>
          <td class='izqform' colspan='7'>Referencia catastral</td>
          <td class='izqform' colspan='3'><? print $datoparc[contparc]; ?> parcelas</td>
        </tr>
        
        <tr>
          <td class='izqform'>Dele</td>
          <td class='izqform'>Muni</td>
          <td class='izqform'>Sect</td>          
          <td class='izqform'>Poli</td>          
          <td class='izqform'>Parc</td>                    
          <td class='izqform'>Cons</td>                              
          <td class='izqform'>CC</td>                    
          <td class='izqform'>Paraje</td>
          <td class='izqform'>Superficie</td>
          <td class='izqform'>Valor catastral</td>
        </tr>
<?
        while ($parc = each ($dato)) {
          $parc = $parc[value];
          print "<tr>";
          print "<td class='derform'>$parc[codidele]</td>";          
          print "<td class='derform'>$parc[codimuni]</td>";          
          print "<td class='derform'>$parc[sect]</td>";          
          print "<td class='derform'>$parc[poli]</td>";          
          print "<td class='derform'>$parc[parc]</td>";          
          print "<td class='derform'>$parc[cons]</td>";          
          print "<td class='derform'>$parc[caracont]</td>";
          print "<td class='derform'>$parc[para]</td>";
          print "<td class='derform' align='right'>".supeboni ($parc[supe])."</td>";
          print "<td class='derform' align='right'>".impoboni ($parc[valocata])."</td>";
          print "</tr>";
        }
?>
      </table>
    </td></tr>
<?
  }
?>
