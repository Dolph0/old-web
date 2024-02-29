<script>
  //------------------------------------------------------------------
  // Esta función redefine los subtipos de conceptos en función del 
  // tipo elegido.
  // 
  // Entradas:
  //   coditipo => Código del tipo de concepto.
  //   subtipo  => Combo que se quiere rellenar.
  //
  // Salidas:
  //   Nuevo select para los subtipos.
  //------------------------------------------------------------------
  function cambiasubxconc (coditipo, subtipo) {
    subtipo.length = 0;

    switch (coditipo) {
    <?
      # Para cada tipo imprimos los subtipos
      $query = "SELECT codiconc
                FROM conctrib
                ORDER BY codiconc"; 

      $resu = sql ($query);
      while ($dato = each ($resu)) {
        $dato = $dato[value];
        
        print "case '$dato[codiconc]':\n";
        print "  subtipo.options[0] = new Option ('AHORA SELECCIONE UN SUBTIPO', '');\n";

        # Subtipos
        $query = "SELECT codisubc, '(' || abre || ') ' || nomb as nombre
                  FROM subxconc
                  WHERE codiconc = '$dato[codiconc]'
                  ORDER BY abre"; 

        $resu2 = sql ($query);
        if ($resu2) {
          $cont  = 1;
          while ($dato2 = each ($resu2)) {
            $dato2 = $dato2[value];
            print "subtipo.options[$cont] = new Option ('$dato2[nombre]', '$dato2[codisubc]');\n";
            $cont++;
          }
        }
        print "break;\n\n";
      }
    ?>
      default:
        subtipo.options[0] = new Option ('SELECCIONE UN TIPO...', '');
    }
  }
</script>
