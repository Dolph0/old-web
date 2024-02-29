  <script>
  // _conc_ es el número del concepto
  // _comb_ es el combo que se quiere rellenar con los periodos asociados a ese
  // concepto
  function cambperi (conc, comb) {
    comb.length = 0;

    switch (conc) {
<?

  # Para cada concepto, imprimimos todas las líneas de periodo necesarias
  $query = "SELECT codiconc, liqu FROM conctrib ORDER BY codiconc";
  $resu = sql ($query);
  while ($dato = each ($resu)) {
    $dato = $dato[value];

    print "      case '$dato[codiconc]':\n";
    print "        comb.options[0] = new Option ('', '');\n";

    $query = "SELECT abre, nomb FROM liquperi WHERE grup = '$dato[liqu]'";
    $resu2 = sql ($query);
    if ($resu2) {
      $cont  = 1;
      while ($dato2 = each ($resu2)) {
        $dato2 = $dato2[value];
        print "        comb.options[$cont] = new Option ('$dato2[nomb]', '$dato2[abre]');\n";
        $cont++;
      }
    }
    print "        break;\n\n";
  }

  ?>
      default:
        comb.options[0] = new Option ('', '');
        <?
/*          $query = "SELECT abre, nomb FROM liquperi";
          $resu = sql ($query);
          $cont = 1;
          while ($dato = each ($resu)) {
            $dato = $dato[value];
            print "        comb.options[$cont] = new Option ('$dato[nomb]', '$dato[abre]');\n";
            $cont++;
          }*/
        ?>
    }
  }
  </script>
