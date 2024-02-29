<script>
  //------------------------------------------------------------------
  // Esta función redefine los tipos de expediente en funcion del
  // origen seleccionado.
  // 
  // Entradas:
  //   origexpe => Origen del expediente.
  //   subtipo  => Combo que se quiere rellenar.
  //
  // Salidas:
  //   Nuevo select para los tipos de expediente.
  //------------------------------------------------------------------
  function cambiatipoexpe (origexpe, subtipo) {
    subtipo.length = 0;

    switch (origexpe.value) {
    <?
      # Posible origen de expediente
      $query = "SELECT codiorig
                FROM origexpe"; 

      $resu = sql ($query);
      while ($dato = each ($resu)) {
        $dato = $dato[value];

        print "case '$dato[codiorig]':\n";
        print "  subtipo.options[0] = new Option ('AHORA SELECCIONE UN TIPO DE EXPEDIENTE', '');\n";

        # Tipos de expediente, siempre añado los de la 'ENTIDAD COLABORADORA'
        $query = "SELECT codiexpe, nombexpe as nombre
                  FROM tipoexpe
                  WHERE origexpe = '$dato[codiorig]'
                     or origexpe = 'E'
                  ORDER BY nombexpe"; 

        $resu2 = sql ($query);
        if ($resu2) {
          $cont  = 1;
          while ($dato2 = each ($resu2)) {
            $dato2 = $dato2[value];
            print "subtipo.options[$cont] = new Option ('$dato2[nombre]', '$dato2[codiexpe]');\n";
            $cont++;
          }
        }
        print "break;\n\n";
      }
    ?>
      default:
        subtipo.options[0] = new Option ('SELECCIONE ORIGEN DE EXPEDIENTE...', '');
    <?
        # Por omision siempre muestro los tipos de expediente de la Entidad colaboradora
        $query = "SELECT codiexpe, nombexpe as nombre
                  FROM tipoexpe
                  WHERE origexpe = 'E'
                  ORDER BY nombexpe"; 

        $resu2 = sql ($query);
        if ($resu2) {
          $cont = 1;
          while ($dato2 = each ($resu2)) {
            $dato2 = $dato2[value];
            print "subtipo.options[$cont] = new Option ('$dato2[nombre]', '$dato2[codiexpe]');\n";
            $cont++;
          }
        }
    ?>
    }
  }
</script>
