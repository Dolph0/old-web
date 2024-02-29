<?
  // Formatea una dirección para mostrarla, y la devuelve
  function mostrarDireccion ($sigl, $nomb, $nume, $plan, $letr, $esca, $puer, $kilo = '') {
    $dire = ($sigl?"$sigl ":'');
    $dire .= "$nomb" . ($kilo?" Km. $kilo":'') . ($nume?" $nume":'') . ($letr?" $letr":'');
    if ($plan or $esca or $puer) {
      $dire .= ',';
    }
    # Varias convenciones
    if ($esca == 'T' and $plan == 'OD' and $puer == 'OS') {
      $dire .= ' (TODOS)';
    } elseif ($esca == 'S' and $plan == 'OL' and $puer == 'AR') {
      $dire .= ' (SOLAR)';
    } elseif ($esca == 'S' and $plan == 'UE' and $puer == 'LO') {
      $dire .= ' (SUELO)';
    } else {
      $dire .= ($esca?" escalera $esca":'') . ($plan?" planta $plan":'') .
               ($puer?" puerta $puer":'');
    }

    return $dire;
  }


  // Devuelve los datos adicionales de una dirección ya formateados
  function mostrarDireExtra ($aparcorr, $loca, $codipost, $muni, $prov, $pais) {
    if ($aparcorr or $loca or $codipost or $muni or $prov or $pais){
       $query = "SELECT nomb FROM pais WHERE codipais = '$pais'";
       $resp = sql($query);
       if ($resp != '')
          $pais = $resp;
      return ($aparcorr?" APDO. CORREOS $aparcorr":"") .
             (($loca)?"<br>$loca":"") .
             (($codipost or $muni)?"<br>$codipost $muni":"") .
             (($prov or $pais)?"<br>$prov ($pais)":"");
    }
  }
?>
