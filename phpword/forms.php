<?php
function get_form()
{
    $FormArr = array("F003"=>"Dieta mensual para viajes profesionales",);
   print "<BIG>FORMULARIO:</BIG><BR><BR>";
   print "<TABLE BORDER>";
   foreach($FormArr as $Symbol=>$Description)
      print "<TR> <TD><A HREF=\"forms.php?doc=$Symbol&desc=$Description\">".$Description."</A></TD></TR>";
   print "</TABLE>";
}

if (Empty($_GET["doc"]))
    get_form();
else
    include("{$_GET['doc']}.php");
?>