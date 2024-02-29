<?

// Función que crea un archivo ".js" que contendrá una función 
// cambiamuni para ejecutar en el onChange del select a la hora 
// de seleccionar una provincia


function creamuni ($usua) {

$nomb = md5 ("muni".$usua);

// Abrimos el archivo temporal 
$arch = fopen ("/var/datos/tmp/.$nomb.tmp","w");

set_time_limit (60); // Se incrementa el tamaño de ejecucion

$regi = sql ("SELECT nomb,codimuni,codiprov FROM muni ORDER BY codiprov,nomb");

fputs ($arch,"function cambiamuni (select1,select2) {\n");
fputs ($arch," var i=select1.selectedIndex;\n");
fputs ($arch," var tamano;\n\n");
fputs ($arch," switch (select1.options[i].value) {\n\n");

$i = 1; 
$codiprov = 0; 

if (is_array($regi)) {
  while ($dato = each ($regi)) {
    $dato = $dato[value];

    if ($codiprov!=$dato[codiprov]) {
      // Una nueva provincia
      $codiprov = $dato[codiprov];
      if ($codiprov!=1){
        $cade = "  tamano = ".($i-1).";\n  break;\n\n";
        fputs ($arch,$cade);
      }
      fputs ($arch," case \"$codiprov\":\n");
      $i = 1; 
    }

    fputs ($arch,"  select2.options[$i] = new Option (\"$dato[nomb]\",\"$dato[codimuni]\");\n");
    $i++; 
  }

  $cade = "  tamano = ".($i-1).";\n  break;\n\n";
  fputs ($arch,$cade);

}

fputs ($arch," default:\n  tamano = 0;\n  break;\n\n");
fputs ($arch," }\n");
fputs ($arch," var tamactual = select2.length;\n\n");
fputs ($arch," if (tamactual > tamano) {\n");
fputs ($arch,"   var i=0;\n\n");
fputs ($arch,"   i = tamano;\n\n");
fputs ($arch,"   while (i<tamactual) {\n");
fputs ($arch,"     select2.options[tamano+1] = null;\n");
fputs ($arch,"     i++;\n");
fputs ($arch,"   }\n }\n return false;\n} // Fin de la función ");

fclose ($arch);
$resu = system ("cmp /var/datos/tmp/.$nomb.tmp " . cheqroot("comun/muni.js"));

if ($resu) { 
  // si hubo cambios en el archivo se cambia el script muni.js
  rename ("/var/datos/tmp/.$nomb.tmp", cheqroot("comun/muni/muni.js"));
}
else {
  // Se borra el archivo temporal
  unlink ("/var/datos/tmp/.$nomb.tmp");
}

} // Fin de la función creamuni
?>
