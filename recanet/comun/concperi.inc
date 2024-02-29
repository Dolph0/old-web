<?
# Genera el campo de conceptos segun los campos en la base de datos. 
# es necesario este fragmento 
  if ($conctrib) $_conctrib_=$conctrib;
  $_concepto_= sql ("SELECT codiconc, liqu, nomb from conctrib where codiayun = '$ayun' ORDER BY  orde");
  $_script_="\n<script>\n function concepto ()\n  {\n switch (document.form.conctrib.value)\n   {";
  $_option_=" <select class='inputform' name='conctrib' size='1' onchange='concepto()'> <option></option>";
   while ($_conc_ = each ($_concepto_))
   {
      $_conc_ = $_conc_[value];
      $_option_.= "\n<option value = '$_conc_[codiconc]'";
      if ($_conctrib_ == $_conc_[codiconc]) $_option_.= " selected";
      $_option_.= "> $_conc_[nomb] </option>";
      $_script_.= "\n     case '$_conc_[codiconc]': periodo('liquperi','$_conc_[liqu]');break;";
   }
  $_script_.="\n }\n }\n</script>";
   echo"\n</select>";
   echo "
   <table border=0>
    <tr><td> Concepto </td><td> Período </td></tr>
    <tr><td> $_option_ $_script_ </td>
     <td>";
      mueslist ("liquperi", "SELECT \"abre\", \"nomb\" FROM liquperi WHERE abre='$liquperi'", "$liquperi");
   echo"<SCRIPT LANGUAGE='JavaScript' SRC='" . cheqroot("comun/periodo.js") . "'></script></td></tr>
   </table>
   ";
?>
