<script>
        function cambconc(mod, comb){
                 comb.length = 0;
                 <?
                   if ($codiambi == _GLOBAL_) {
                      $codiayun = $ayun;
                   }else{
                      $codiayun = $codiambi;
                   }

                 ?>
                  conceplabel.style.display = 'block';
                  conceplist.style.display = 'block';
                  perilabel.style.display = 'block';
                  perilist.style.display = 'block';

                  switch (mod) {
                        case '1':
                             comb.options[0] = new Option ('', '');
                             <?
                               $query = "SELECT codiconc, nomb FROM conctrib WHERE codiayun = '$codiayun' AND (fechfina = '0001-01-01' OR fechfina > '" . date ("Y-m-d", time()) . "') AND (fechinic <> '0001-01-01' OR fechinic <= '" . date ("Y-m-d", time()) . "') ORDER BY orde";
                               $resu = sql($query);
                               if ($resu){
                                  $cont = 1;
                                  while($dato = each($resu)) {
                                    $dato = $dato[value];
                                    print "         comb.options[$cont] = new Option ('$dato[nomb]', '$dato[codiconc]');\n";
                                    $cont++;
                                  }
                               }
                             ?>
                             break;
                        case '2':
                             comb.options[0] = new Option ('', '');
                             <?
                               $query = "SELECT codiconc, nomb FROM conctrib WHERE codiayun = '$codiayun' AND (fechfina = '0001-01-01' OR fechfina > '" . date ("Y-m-d", time()) . "') AND (fechinic <> '0001-01-01' OR fechinic <= '" . date ("Y-m-d", time()) . "') AND liqu != 'N' ORDER BY orde";
                               $resu = sql($query);
                               if ($resu){
                                  $cont = 1;
                                  while($dato = each($resu)) {
                                    $dato = $dato[value];
                                    print "         comb.options[$cont] = new Option ('$dato[nomb]', '$dato[codiconc]');\n";
                                    $cont++;
                                  }
                               }
                             ?>
                             break;
                        default:
                          comb.options[0] = new Option ('', '');
                          conceplabel.style.display = 'none';
                          conceplist.style.display = 'none';
                          perilabel.style.display = 'none';
                          perilist.style.display = 'none';

                          break;
                 }
        }
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
    }
  }

</script>

