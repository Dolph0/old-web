<?
// Datos del sujeto:
  $query = "SELECT tercdato.nomb as tercdatonomb,
                   tercdato.dire as tercdatodire,
                   tercdato.coditerc as tercdatocoditerc,
                   tercdato.loca as tercdatoloca,
                   tercdato.codipost as tercdatocodipost,
                   tercdato.codipais as tercdatocodipais,
                   tercdato.aparcorr as tercdatoaparcorr,
                   tercdato.tel1 as tercdatotel1,
                   tercdato.tel2 as tercdatotel2,                   
                   tercdato.nifx as tercdatonifx,                   
                   tercdato.pers as tercdatopers,                   
                   tercdato.direcorr as tercdatodirecorr,                   
                   muni.nomb as muninomb,
                   prov.nomb as provnomb
            FROM ((tercdato 
                   LEFT JOIN muni 
                   ON tercdato.codimuni = muni.codimuni)
                  LEFT JOIN prov 
                  ON muni.codiprov = prov.codiprov)
            WHERE  tercdato.nifx = '$nifx'";
    
  $suje = sql ($query);
  
  if (is_array ($suje)) {
    $suje = each ($suje);
    $suje = $suje [value];
  }

// Datos de la direccion del sujeto
  if ($suje[tercdatodire] == 'n')
    $dire = "SELECT * FROM direnoes WHERE coditerc='$suje[tercdatocoditerc]'";
  else 
    $dire = "SELECT direestr.nomb as direestrnomb,
                    direestr.nume as direestrnume,
                    direestr.letr as direestrletr,
                    direestr.esca as direestresca,
                    direestr.plan as direestrplan,
                    direestr.puer as direestrpuer,
                    direestr.letr as direestrletr,
                    direestr.siglviax as direestrsiglviax,
                    siglvias.siglviax as siglviassiglviax
             FROM direestr LEFT JOIN siglvias
                          ON direestr.siglviax = siglvias.siglviax
             WHERE direestr.coditerc = '$suje[tercdatocoditerc]'";

  $dire = sql ($dire);
    
  if (is_array ($dire)) {
    $dire = each ($dire);
    $dire = $dire[value];
  }

// Inicializaciones
  switch ($suje[tercdatopers]) {
    case 'F':
      $suje[tercdatopers] = 'FÍSICA';
      break;
    case 'J':
      $suje[tercdatopers] = 'JURÍDICA';
      break;
    case 'E':
      $suje[tercdatopers] = 'ENTIDAD';
      break;
    default:
      $suje[tercdatopers] = '';
  }

   ?>
   <tr>
     <td colspan='2' class='izqform'>INFORMACIÓN DEL SUJETO PASIVO</td>
   </tr>
   
   <tr>
     <td class='izqform'>NIF</td>
     <td class='derform'><? print "$suje[tercdatonifx]"; ?></td>
   </tr>

   <tr>
     <td class='izqform'>Nombre</td>
     <td class='derform'><? print "$suje[tercdatonomb]"; ?></td>
   </tr>
<?
   if ($suje[tercdatopers]) {
?>
     <tr>
       <td class='izqform'>Personalidad</td>
       <td class='derform'><? print "$suje[tercdatopers]"; ?></td>
     </tr> 
<? 
  }
?>  
  <tr>
    <td class='izqform'>Domicilio fiscal</td>
    <td class='derform'>
<? 
  if ($suje[tercdatodire] == 'n'){ 
    print $dire[nomb];
  } else {
    $dire = mostrarDireccion ($dire[siglviassiglviax],
                              $dire[direestrnomb],
                              $dire[direestrnume],
                              $dire[direestrplan],
                              $dire[direestrletr],
                              $dire[direestresca],
                              $dire[direestrpuer]);

    print $dire.'<br>'.mostrarDireExtra ($suje[tercdatoaparcorr],
                                         $suje[tercdatoloca],
                                         $suje[tercdatocodipost],
                                         $suje[muninomb],
                                         $suje[provnomb],
                                         $suje[tercdatocodipais]);
  }
?>
   </td>
  </tr>
   
  <tr>
    <td class='izqform'>Teléfonos</td>
    <td class='derform'><? print $suje[tercdatotel1].' &nbsp;&nbsp;'.$suje[tercdatotel2]; ?></td>
  </tr>

  <tr>
    <td class='izqform'>e-mail</td>
    <td class='derform'><? print "$suje[tercdatodirecorr]"; ?></td>
  </tr>
<?
  // Datos del expediente de apremio del sujeto
  $expe = sql ("SELECT * FROM expeapre WHERE nifx='$nifx'");
  if (is_array ($expe)) {
    $expe = each ($expe);
    $expe = $expe[value];
    
    // Inicializaciones
    switch ($expe[accimodi]) {
      case 'A':
        $expe[accimodi] = 'APERTURA';
        break;
      case 'R':
        $expe[accimodi] = 'REAPERTURA';
        break;
      case 'S':
        $expe[accimodi] = 'ADICIÓN DE DOCUMENTOS';
        break;
      case 'N':
        $expe[accimodi] = 'CORRECCIÓN NIF';
        break;
      case 'C':
        $expe[accimodi] = 'CIERRE';
        break;
      case 'E':
        $expe[accimodi] = 'CAMBIO DE ESTADO';
        break;
      case 'T':
        $expe[accimodi] = 'TRASPASO';
        break;
      default:
        $expe[accimodi] = '';
    }
?>
    <tr>
      <td colspan='2' class='izqform'>INFORMACIÓN DEL EXPEDIENTE DE APREMIO</td>
    </tr>

    <tr>
      <td class='izqform'>Nº de Expediente</td>
      <td class='derform'><? print $expe[numeexpe].' / '.$expe[tipoexpe]; ?></td>
    </tr>

    <tr>
      <td class='izqform'>Fecha apertura</td>
      <td class='derform'><? print mostrarfecha($expe[fechaper]); ?></td>
    </tr>

    <tr>
      <td class='izqform'>Estado actual</td>
      <td class='derform'><? print sql ("SELECT nomb FROM estaexpeapre where codiestaexpe='$expe[estaexpe]'").' ('.mostrarfecha($expe[fechestaexpe]).')'; ?></td>
    </tr>

    <tr>
      <td class='izqform'>Última acción</td>
      <td class='derform'><? print $expe[accimodi].' ('.mostrarfecha($expe[fechmodi]).')'; ?></td>
    </tr>
<?
    if ($expe[fechcier] <> '0001-01-01') {
?>
      <tr>
        <td class='izqform'>Fecha cierre</td>
        <td class='derform'><? print mostrarfecha($expe[fechcier]); ?></td>
      </tr>
<?
    }
?>
    <tr>
      <td class='izqform'>Observaciones</td>
      <td class='derform'><? print $expe[obse]; ?></td>
    </tr>
<?
  }
?>