<?
function opci( $cade ) {
  // muestra en pantalla las opciones disponibles para seleccionar
  // el par�metro es una ristra con los valores de las opciones separados por :
  // aunque cualquier caracter de separaci�on es v�lido, incluso ninguno
  // por ejemplo: Guardar:Listar:Imprimir
  // pone caracter al comienzo para que la comparaci�n con la primera opci�n no devuelva cero 
  // Direcci�n del menu

  // Opciones disponibles,
  //  Volver : Atras : Crear : Eliminar : Modificar : Listar : ListConf : Limpiar :
  //  Liquidar : Deuda : Menu : Salir : Imprimir : ImprimeListado :
  //  ImprimeRecibo : ImprimeNotiIndi : NotiColec : SuspColec : EmiDocu
  //  CapIngr : ActuRepIngr : BajaContable :  Cerrar : PDF : Mapa : TextoPlano : AbrirExpediente :
  //  VerDocu : VerReso : Resolucion : ImprimeNotiApla : Notiapla : ImprimeDocu :
  //  Generarsoporte : Cambiaestado : GrabarDatos : AplicaIngreso : ImprimeRela :
  //  Parcela : DOC: XLS : Relaci�n : AcuseRecibo : EliMinarEnvios : NuevaFase : SicerImprimeNotiIndi
  //  Gestionar Fases : Gestionar Tareas : Detalle : NotiSicer

  global $urlxmenu; 
  // Estas variables se necesitan para pasarlas por parametros en la liquidacion
  global $conc, $codiobje, $tipoobje;

  // Estas variables se necesitan para pasarlas por parametros en docxls
  global $query, $cabe, $titu;

  // Hay que pasarle a Catastroweb en la opci�n Mapa, el usuario conectado a la sesi�n.
  global $sesi;

  $cade = ":" . $cade;
  $estaurlx = estaurlx();
?>
<div class=solopantalla>
<hr>
<input type='hidden' name='opci' value='<? print $cade ?>'>

<script>
  var comprobaciones = "function res () { ";
  var limpieza       = "";
</script>
  
<?
// Funciones javascript para la carga del informe de finca (ver opci�n 'Mapa')
print("<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/infocata.js") . "'></SCRIPT>\n\n");

  $cade_array = split(':',$cade);
  if (is_array($cade_array)){
  if ( array_search("Volver", $cade_array) ) {
    echo("<a href='$estaurlx.php' onClick='form.opci.value=\"Volver\"
                              document.form.submit();
                              return false;'>
          <img src='" . cheqroot("imag/opci_volver.gif") . "' border='0'
            align='middle' alt='Volver'>
          </a>\n\n");
  }

  if ( array_search("Atras", $cade_array) ) {
    echo("<a href='javascript:history.go(-1)'>
      <img src='" . cheqroot("imag/opci_volver.gif") . "' border='0' align='middle' alt='Atras'>
      </a>\n\n");
  }

  if ( array_search("Nuevo", $cade_array) )
    echo "<a href='$estaurlx.php' >
      <img src='" . cheqroot("imag/opci_nuevo.gif") . "' border='0' 
       align='middle' alt='Nuevo'>
      </a>\n\n";

  if ( array_search("Crear", $cade_array) )
    echo "<a href='$estaurlx.php' onClick = '
                               if (eval (comprobaciones + \" return true; } res ()\") == false) { return false; }
                               if ( cheq() ) {
                                     form.opci.value=\"Crear\";
                                     document.form.submit();
                                   }
                                   return false;'>
      <img src='" . cheqroot("imag/opci_insertar.gif") . "' border='0' 
       align='middle' alt='Insertar'>
      </a>\n\n";

  if ( array_search("Eliminar", $cade_array) ) {
    echo ("<a href='$estaurlx.php' onClick=' if (!regisele()) {
                                     alert(\"No se ha seleccionado nada a eliminar\");
                                     } else {
                                       if(confirm(\"�Est� seguro de que desea eliminar?\")) {
                                         form.opci.value=\"Eliminar\";
                                         form.submit();
                                       }
                                     }
                                     return false;'>
      <img src='" . cheqroot("imag/opci_eliminar.gif") . "' border='0' 
       align='middle' alt='Eliminar'>
      </a>\n\n");
  }

  if ( array_search("Modificar", $cade_array) ) {
    echo("<a href='$estaurlx.php' onClick='
                                 if (eval (comprobaciones + \" return true; } res ()\") == false) { return false; }
                                  if (cheq()) {
                                     form.opci.value=\"Modificar\";
                                     form.submit();
                                  }
                                  return false;'>
      <img src='" . cheqroot("imag/opci_actualizar.gif") . "' border='0' 
       align='middle' alt='Actualizar'>
      </a>\n\n");
  }


  if ( array_search("Listar", $cade_array) ) {
      /* Este icono no se muestra si solo puede cambiar la contrase�a */
      echo("<a href='$estaurlx.php' onClick='form.opci.value=\"Listar\";
                               try { if (antesEnvio() == false) { return false; } } catch (e) { }
                               //if (antesEnvio() == false) { return false; }
                                  window.document.form.submit();
                                  return false;'>
             <img src='" . cheqroot("imag/opci_buscar.gif") . "' border='0' 
              align='middle' alt='Buscar/Listar'>
             </a>\n\n");
  }

  // Listar con confirmaci�n : Si no se confirma, no se lista
  if ( array_search("ListConf", $cade_array) ) {
      echo("<a href='$estaurlx.php' onClick='if (listarOk()) {
                                      form.opci.value=\"ListConf\";
                                      window.document.form.submit();
                                      return false;
                                     }
                                     else return false;'>
             <img src='" . cheqroot("imag/opci_buscar.gif") . "' border='0' 
             align='middle' alt='Buscar/Listar'>
             </a>\n\n");
  }

  if ( array_search("Limpiar", $cade_array) ) {
      echo("<a href='$estaurlx.php' onClick='limp(); return false;'>
        <img src='" . cheqroot("imag/opci_limpiar.gif") . "' border='0' 
         align='middle' alt='Limpiar'>
        </a>\n\n");
  }

  if ( array_search("Liquidar", $cade_array) ) {
      echo("<a href=" . cheqroot("liqu/liqu.php?codiconc=".$conc."&codiobje=".$codiobje."&tipoobje=".$tipoobje) . ">
             <img src='" . cheqroot("imag/opci_liquidar.gif") . "' border='0' 
              align='middle' alt='Liquidaci�n'>
             </a>\n\n");
  }

  if ( array_search("Deuda", $cade_array) ) {
      echo("<a href='$estaurlx.php' onClick='if ( cheq() ) {
                                               form.opci.value=\"Deuda\";
                                               window.document.form.submit();
                                             }
                                             return false;'>
             <img src='" . cheqroot("imag/opci_calcdeud.gif") . "' border='0' 
              align='middle' alt='Calcular la deuda'>
             </a>\n\n");
  }





  if ( array_search("Menu", $cade_array) ) {
    $ulti = count($urlxmenu) - 1;
    if (strstr ($estaurlx,"menu")) $ulti--;
    print("<a href=\"" . cheqroot("menu/" . $urlxmenu[$ulti] . ".php") . "\">
      <img src='" . cheqroot("imag/menu.gif") . "' border='0' 
      align='middle' alt='Men�'>
      </a>\n\n");
  }

  if ( array_search("Salir", $cade_array) ) {
    print("<a href=\"". cheqroot("index.php") . "\">
      <img src='" . cheqroot("imag/salir.gif") . "' border='0' 
      align='middle' alt='Salir'>
      </a>\n\n");
  }

  if ( array_search("Imprimir", $cade_array) ) {
    print("<a href=\"#\">
      <img src='" . cheqroot("imag/opci_imprlist.gif") . "' border='0' 
      align='middle' alt='Imprimir'
      onClick='window.print(); return false;'>
      </a>\n\n");
  }

  if ( array_search("ImprimeListado", $cade_array) ) {
    print("<a href=\"\">
      <img src='" . cheqroot("imag/opci_imprlist.gif") . "' border='0'
      align='middle' alt='Imprimir listado'
      onClick='imprlist(); return false;'>
      </a>\n\n");
  }

  if ( array_search("ImprimeRecibo", $cade_array) ) {
    print("<a href=\"\">
      <img src='" . cheqroot("imag/opci_imprdocu.gif") . "' border='0' 
      align='middle' alt='Imprimir recibo'
      onClick='imprreci(); return false;'>
      </a>\n\n");
  }

  if ( array_search("SicerImprimeNotiIndi", $cade_array) ) {
    print("<a href=\"\">
      <img src='" . cheqroot("imag/opci_imprsicer.gif") . "' border='0' 
      align='middle' alt='Imprimir notificaci�n individual (SICER)'
      onClick='imprnotiindisicer(); return false;'>
      </a>\n\n");
  }

  if ( array_search("ImprimeNotiIndi", $cade_array) ) {
    print("<a href=\"\">
      <img src='" . cheqroot("imag/opci_imprdocu.gif") . "' border='0' 
      align='middle' alt='Imprimir notificaci�n individual'
      onClick='imprnotiindi(); return false;'>
      </a>\n\n");
  }

  if ( array_search("AcuseRecibo", $cade_array) ) {
    print("<a href=\"\">
      <img src='" . cheqroot("imag/opci_impracus.gif") . "' border='0' 
      align='middle' alt='Imprimir acuse de recibo'
      onClick='impracusreci(); return false;'>
      </a>\n\n");
  }

  if ( array_search("NotiColec", $cade_array) ) {
    print("<a href=\"\">
      <img src='" . cheqroot("imag/opci_notificar.gif") . "' border='0' 
      align='middle' alt='Registrar notificaci�n'
      onClick='noticole(); return false;'>
      </a>\n\n");
  }

  if ( array_search("SuspColec", $cade_array) ) {
    print("<a href=\"\">
      <img src='" . cheqroot("imag/opci_cambsusp.gif") . "' border='0'
      align='middle' alt='Suspensi�n colectiva'
      onClick='suspcole(); return false;'>
      </a>\n\n");
  }

  if ( array_search("EmiDocu", $cade_array) ) {
    echo("<a href='$estaurlx.php' onClick='form.opci.value=\"EmiDocu\"
                              document.form.submit();
                              return false;'>
          <img src='" . cheqroot("imag/opci_imprdocu.gif") . "' border='0'
            align='middle' alt='Emitir'>
          </a>\n\n");
  }

  if ( array_search("CapIngr", $cade_array) ) {
    echo("<a href='$estaurlx.php' onClick='form.opci.value=\"CapIngr\"
                              document.form.submit();
                              return false;'>
          <img src='" . cheqroot("imag/opci_procdato.gif") . "' border='0'
            align='middle' alt='Procesar ingresos'>
          </a>\n\n");
  }

  if ( array_search("ActuRep", $cade_array) ) {
    echo("<a href='$estaurlx.php' onClick='form.opci.value=\"ActuRep\"
                              document.form.submit();
                              return false;'>
          <img src='" . cheqroot("imag/opci_acturepo.gif") . "' border='0' 
            align='middle' alt='Actualizar repositorio'>
          </a>\n\n");
  }

  if ( array_search("BajaContable", $cade_array) ) {
    print("<a href=\"javascript:bajacont()\">
      <img src='" . cheqroot("imag/opci_bajacont.gif") . "' border='0'
      align='middle' alt='Baja contable'>
      </a>\n\n");
  }

  if ( array_search("Cerrar", $cade_array) ) {
   print("<a href=# onclick=\"hidemenuie5()\">
       <img src='" . cheqroot("imag/opci_cerrar.GIF") . "' border='0'
        align='middle'  alt='Cerrar'></a>\n\n");
  }

  if ( array_search("PDF", $cade_array) ) {
    // Inicialmente, los listados en PDF solo se usan en las notificaciones
   print("<a href=\"\" onClick=\"form.opci.value = 'Listar'; form.pdf.value = 1; enviform(form, '$estaurlx.php'); form.pdf.value = 0; return false\">
       <img src='" . cheqroot("imag/opci_pdf.gif") . "' border='0' 
        align='middle'  alt='Versi�n en PDF'></a>\n\n");
  }

  if ( array_search("Emipdf", $cade_array) ) {
   print("<a href=\"\" onClick=\"form.opci.value = 'Emipdf'; document.form.submit(); return false\">
       <img src='" . cheqroot("imag/opci_pdf.gif") . "' border='0'
        align='middle'  alt='Emisi�n en PDF'></a>\n\n");
  }

  if ( array_search("Mapa", $cade_array) ) {
    // Muestra el plano del inmueble en uso
    print ("<a href=''
             onclick='infocata(form.refecata.value,form.numecarg.value);
                      return false; '>
            <img src='" . cheqroot("imag/opci_infocata.gif") . "' border='0' 
              align='middle'  alt='Informe de finca/cargo'></a>\n\n");
  }

  if ( array_search("TextoPlano", $cade_array) ) {
    // Para abrir una ventana con texto sin formato
    print("<a href=\"\" onClick=\"form.opci.value = 'Texto'; enviform(form, '$estaurlx.php'); return false\">
       <img src='" . cheqroot("imag/opci_doctext.gif") . "' border='0'
        align='middle'  alt='Documento de texto'></a>\n\n");
  }
  if ( array_search("AbrirExpediente", $cade_array) ) {
    // Emite un informe sobre el proceso de apertura
    print("<a href=\"\" onClick='if(confirm(\"Va a comenzar el proceso de apertura de Expedientes. Acepte si est� conforme\")) {
                                    form.opci.value=\"AbrirExpediente\";
                                    enviform(form, \"$estaurlx.php\");
                                    }
                                   return false;'>
       <img src='" . cheqroot("imag/opci_procesar.gif") . "' border='0'
        align='middle'  alt='Ejecuci�n e informe de resultados'></a>\n\n");
  }

  if ( array_search("Resolucion", $cade_array) ) {
    echo("<a href=\"\" onClick='return false'>
           <img src='" . cheqroot("imag/opci_regireso.gif") . "' border='0'
            align='middle' alt='Resoluci�n'
            onClick='reso()'>
           </a>\n\n");
  }

  if ( array_search("ImprimeNotiApla", $cade_array) ) {
    print("<a href=\"$estaurlx.php\">
      <img src='" . cheqroot("imag/opci_imprdocu.gif") . "' border='0'
      align='middle' alt='Imprimir notificaci�n'
      onClick='imprnoti(); return false;'>
      </a>\n\n");
  }

  if ( array_search("Notiapla", $cade_array) ) {
    print("<a href=\"\">
      <img src='" . cheqroot("imag/opci_notificar.gif") . "' border='0'
      align='middle' alt='Notificaci�n'
      onClick='notiapla(); return false;'>
      </a>\n\n");
  }

  if ( array_search("VerDocu", $cade_array) ) {
    print("<a href=\"\" onClick=\"muestradocu(); return false\">
       <img src='" . cheqroot("imag/opci_verdocu.gif") . "' border='0'
        align='middle'  alt='Ver documentos'></a>\n\n");
  }

  if ( array_search("VerReso", $cade_array) ) {
    print("<a href=\"\" onClick=\"muestrareso(); return false\">
       <img src='" . cheqroot("imag/opci_verreso.gif") . "' border='0'
        align='middle'  alt='Ver resoluci�n'></a>\n\n");
  }

  if ( array_search("ImprimeDocu", $cade_array) ) {
    echo("<a href='' onClick=\"imprdocuentr(); 
                                         return false;\">
          <img src='" . cheqroot("imag/opci_imprdocu.gif") . "' border='0'
            align='middle' alt='Imprimir documentos entrega'>
          </a>\n\n");
  }

  if ( array_search("GenerarSoporte", $cade_array) ) {
    print("<a href=\"\" onClick=\"generasoport();
                                              return false\">
       <img src='" . cheqroot("imag/opci_genesopo.gif") . "' border='0' 
        align='middle'  alt='Generar soporte'></a>\n\n");
  }

  if ( array_search("NotiSicer", $cade_array) ) {
    print("<a href=\"\" onClick=\"generapdf();
                                              return false\">
       <img src='" . cheqroot("imag/opci_pdf.gif") . "' border='0'
        align='middle'  alt='Generar pdf'></a>\n\n");
  }
            
 
  if ( array_search("CambiaEstado", $cade_array) ) {
    print("<a href=\"\" onClick=\"cambiarestado(); 
                                              return false\">
       <img src='" . cheqroot("imag/opci_cambesta.gif") . "' border='0'
       align='middle'  alt='Cambiar estado'></a>\n\n");
  }

  if ( array_search("GrabarDatos", $cade_array) ) {
    print("<a href=\"\" onClick=\"grabardatos();
                                              return false\">
       <img src='" . cheqroot("imag/opci_regientr.gif") . "' border='0'
       align='middle'  alt='Grabar datos entrega'></a>\n\n");
  }

  if ( array_search("ProcInfo", $cade_array) ) {
    echo("<a href='$estaurlx.php' onClick='form.opci.value=\"ProcInfo\"
                              document.form.submit();
                              return false;'>
          <img src='" . cheqroot("imag/opci_procdato.gif") . "' border='0'
            align='middle' alt='Procesar informaci�n'>
          </a>\n\n");
  }

  if ( array_search("cargpadroibiurba", $cade_array) ) {
    echo("<a href='$estaurlx.php' 
                       onClick='if( form.contvarp.value > 0 ) {
                                  if(confirm(\"Existen registros con variaci�n al padr�n que a�n no se han incluido en alg�n archivo VARPAD, o que habi�ndose incluido en dicho archivo, no se ha recibido confirmaci�n de que han sido incorporados a la base de datos. �Desea continuar?\")) {
                                    form.opci.value=\"cargpadroibiurba\";
                                    form.submit();
                                  }
                                } else {
                                  form.opci.value=\"cargpadroibiurba\";
                                  form.submit();
                                }
                                return false;'>
          <img src='" . cheqroot("imag/opci_procdato.gif") . "' border='0'
            align='middle' alt='Procesar informaci�n'>
          </a>\n\n");
  }

  if ( array_search("cargpadroibirust", $cade_array) ) {
    echo("<a href='$estaurlx.php' 
                       onClick='form.opci.value=\"cargpadroibirust\";
                                form.submit();
                                return false;'>
          <img src='" . cheqroot("imag/opci_procdato.gif") . "' border='0'
            align='middle' alt='Procesar informaci�n'>
          </a>\n\n");
  }

  if ( array_search("cargpadroicv", $cade_array) ) {
    echo("<a href='$estaurlx.php' 
                       onClick='form.opci.value=\"cargpadroicv\";
                                form.submit();
                                return false;'>
          <img src='" . cheqroot("imag/opci_procdato.gif") . "' border='0'
            align='middle' alt='Procesar informaci�n'>
          </a>\n\n");
  }

  if ( array_search("ImprimeRela", $cade_array) ) {
    echo("<a href='' onClick=\"imprdocurela(); 
                                         return false;\">
          <img src='" . cheqroot("imag/opci_imprrela.gif") . "' border='0'
            align='middle' alt='Imprimir relaci�n entrega'>
          </a>\n\n");
  }

  if ( array_search("AplicaIngreso", $cade_array) ) {
    echo("<a href='$estaurlx.php' onClick=' if (cheqcamp()) {
                                       if(confirm(\"�Est� seguro de que desea aplicar el ingreso?\")) {
                                         form.opci.value=\"ApliIngr\";
                                         form.submit();
                                       }
                                     }
                                     return false;'>
          <img src='" . cheqroot("imag/opci_apliingr.gif") . "' border='0'
            align='middle' alt='Aplicar ingreso'>
          </a>\n\n");
  }

  if ( array_search("Tramitar", $cade_array) ) {
    echo("<a href='' onClick=\"tramitar();
                                         return false;\">
          <img src='" . cheqroot("imag/opci_tramitar.gif") . "' border='0'
            align='middle' alt='Tramitar'>
          </a>\n\n");
  }

  if ( array_search("ImprimeOrden", $cade_array) ) {
    echo("<a href='' onClick='doculeva();
                              return false;'>
          <img src='" . cheqroot("imag/opci_improrden.gif") . "' border='0'
           align='middle' alt='Imprimir orden'>
          </a>\n\n");
  }

  if ( array_search("Parcela", $cade_array) ) {
    echo("<a href='$estaurlx.php' onClick='creaparc();
                                                     return false;'>
          <img src='" . cheqroot("imag/opci_creaparc.gif") . "' border='0'
           align='middle' alt='Crear parcela'>
          </a>\n\n");
  }

  if ( array_search("DOC", $cade_array) ) {
    print("
           <a href=\"". cheqroot("comun/docxls.php?query=$query&cabe=$cabe&titu=$titu&tipo=0")."\"
              onClick='form.opci.value=\"DOC\";
                       try { if (antesEnvio() == false) { return false; } } catch (e) { }
                       window.document.form.submit();
                       return false;'>
           <img src='" . cheqroot("imag/opci_listword.gif") . "' border='0' align='middle'  
                alt='Listado en Word'></a>\n\n");
  }

  if ( array_search("XLS", $cade_array) ) {
    print("
           <a href='$estaurlx.php'
              onClick='form.opci.value=\"XLS\";
                       try { if (antesEnvio() == false) { return false; } } catch (e) { }
                       window.document.form.submit();
                       return false;'>
           <img src='" . cheqroot("imag/opci_listexcel.gif") . "' border='0' align='middle'  
                alt='Listado en Excel'></a>\n\n");
  }

  if ( array_search("Relacion", $cade_array) ) {
    print("<a href=\"\" onClick=\"relacion();
                                              return false\">
       <img src='" . cheqroot("imag/opci_relacionar.gif") . "' border='0'
        align='middle'  alt='Relaci�n'></a>\n\n");
  }

  if ( array_search("EliMinarEnvios", $cade_array)) {
    print("<a href=\"\">
      <img src='" . cheqroot("imag/opci_detaenvi.gif") . "' border='0'
      align='middle' alt='Detalle envios'
      onClick='eliminarenvios(); return false;'>
      </a>\n\n");
  }
  }
  print("<hr></div>");
}
?>
