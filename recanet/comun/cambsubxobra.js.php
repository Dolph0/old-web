<script>
  //------------------------------------------------------------------
  // Esta función redefine los subtipos de obra en función de la obra
  // elegida.
  // 
  // Entradas:
  //   tipo    => Identificador del tipo de obra seleccionado.
  //   subtipo => Combo que se quiere rellenar.
  //
  // Salidas:
  //   Nuevo select para los subtipos de obras.
  //------------------------------------------------------------------
  function cambiasubobra (tipo, subtipo) {
    subtipo.length = 0;

    switch (tipo.value) {
    <?
      # Para cada tipo imprimos los subtipos
      $query = "SELECT codiobra
                FROM tipoobra
                ORDER BY codiobra"; 

      $resu = sql ($query);
      while ($dato = each ($resu)) {
        $dato = $dato[value];

        print "case '$dato[codiobra]':\n";
        print "  subtipo.options[0] = new Option ('AHORA SELECCIONE UN SUBTIPO DE OBRA', '');\n";

        # Subtipos
        $query = "SELECT codisubxobra, '(' || abre || ') ' || nomb as nombre
                  FROM subxobra
                  WHERE codiobra = '$dato[codiobra]'
                  ORDER BY abre"; 

        $resu2 = sql ($query);
        if ($resu2) {
          $cont  = 1;
          while ($dato2 = each ($resu2)) {
            $dato2 = $dato2[value];
            print "subtipo.options[$cont] = new Option ('$dato2[nombre]', '$dato2[codisubxobra]');\n";
            $cont++;
          }
        }
        print "break;\n\n";
      }
    ?>
      default:
        subtipo.options[0] = new Option ('SELECCIONE UN TIPO DE OBRA...', '');
    }
  }
</script>
