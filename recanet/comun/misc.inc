<?
  # Imprime un dato en un espacio de n caracteres. Si se pasa, trunca. Si se
  # queda corto, rellena con espacios por la izquierda o derecha, según se
  # indique
  function alin ($dato, $long, $car = '', $lado = 'izq') {
    $form = "%$car" . ($lado == 'izq'?'-':'') . $long . "s";
    if (count_chars ($dato) > $long)
      $dato = substr ($dato, 0, $long);
    return sprintf ($form, $dato);
  }


  # Filtra una lista, devolviendo sólo los campos dados en una lista aparte
  function filtra ($list, $camp) {
    $foo = array ();
    foreach ($camp as $c) {
      $foo[$c] = $list[$c];
    }
    return $foo;
  }
  # La funcion refedocu nos permite obtener un array con los datos relacionados de una tabla
  # obteniendo así la referancia asociada a un registro.
  # $tablename será la tabla origen
  # $clavobje será el valor de la clave del registro en concreto separados los campos por comas
  # $splits será un array de scripts para cada uno de los campos para los que queremos tomar el dato.
  # Para formar cada uno de los elementos de splits debemos seguir las reglas de script siguientes:
  #      Caso 1:
  #          nombrecampo (se obtiene el dato de este campo en la tabla origen)
  #      Caso 2:
  #             (nombrecampo1[,nombrecampo2[,...]]:nombretablarelacinada[campotablarelac1[,campotablarelac1[,...]]])
  #
  # Ejemplo: Para
  #                          $tablename = 'carg'
  #                          $clavobje = '4,2003,29,197'
  #                          $splits = array('(codiconc:conctrib[abre])',
  #                                          'ejer',
  #                                          'numedocu')
  #          Obtendremos:
  #          El campo abre de la tabla conctrib
  #             para el cual su clave coincide con el campo codiconc de la tabla carg para la cual su clave coincide con '4,2003,29,197'
  #          El dato del campo ejer en la tabla carg para la cual su clave coincide con '4,2003,29,197'
  #          El dato del campo numedocu en la tabla carg para la cual su clave coincide con '4,2003,29,197'
  function refedocu($tablename, $clavobje, $splits){
    if (is_array($splits) && count($splits) > 0 && $tablename != '' && $clavobje != ''){
        require_once("comun/sql.fnc");
        $pkey = pg_primaryKey($tablename, ',');
        if ($pkey == '')
            return false;

        $where = combfields($pkey, $clavobje);
        if (!$where)
          return false;
        $result = array();
        for ($i = 0; $i < count($splits); $i++){
          $element = $splits[$i];
          $element = trim($element);
          if (substr($element, 0, 1) == '('){
              if (substr($element, strlen($element) - 1, 1) != ')')
                  return false;
              $element = substr($element, 1);
              $element = substr($element, 0, strlen($element) - 1);
              if (strpos($element, ':') <= 0)
                  return false;
              $elementfields = split(':', $element);
              if (count($elementfields) != 2)
                  return false;
              $codigo = $elementfields[0];
              $element = $elementfields[1];
              $resp = sql("SELECT " . $codigo . " FROM " . $tablename . " WHERE " . $where);
              if (!$resp)
                  return false;
              if (is_array($resp))
                  $resp = $resp[0];
              $codigo = $resp;
              if (strpos($element, '[') <= 1 || strpos($element, ']') <= 0)
                  return false;
              $auxtablename = substr($element, 0, strpos($element, '['));
              $resultfields = substr($element, strpos($element, '[') + 1, strpos($element, ']') - 1 - strpos($element, '['));
              $auxkeys = pg_primaryKey($auxtablename, ',');
              $auxwhere = combfields($auxkeys, $codigo);
              if (!$auxwhere)
                  return false;

              $resp = sql("SELECT " . $resultfields . " FROM " . $auxtablename . " WHERE " . $auxwhere);
              if (is_array($resp)){
                $resp = $resp[0];
                for ($j = 0; $j < count($resp); $j++){
                   if ($resp[$j] != NULL)
                        array_push($result, $resp[$j]);
                }
              }else{
                array_push($result, $resp);
              }
          }else{
            $resp = sql("SELECT " . $splits[$i] . " FROM " . $tablename . " WHERE " . $where);
            if (is_array($resp))
                $resp = $resp[0];
            array_push($result, $resp);
          }
        }
        return $result;
    }
    return false;
  }

  function combfields($pkey, $clavobje){
        $pkeyfields = split(',', $pkey);
        $clavobjefields = split(',', $clavobje);
        if (count($pkeyfields) != count($clavobjefields))
            return false;
        $where = '';
        $first = true;
        for ($i = 0; $i < count($pkeyfields); $i++){
            if (!$first){
              $where .= ' AND ';
            }
            $where .= $pkeyfields[$i] . "='" . $clavobjefields[$i] . "'";
            $first = false;
        }
        return $where;
  }
?>
