<?
# SE MUESTRA LA PANTALLA DE CRITERIOS DE SELECCIÓN #######################

if ($opci=='Eliminar')
{
if (sql ("delete from oipl where codioipl=$codioipl;
      delete from tercobje where codiobje='$codioipl' and tipoobje='OIPL';"))
    {
     $ELIMINAR =" Se ha borrado correctamente el registro";
     $ELIMINADO=1;
    }
else 
   {
     $ELIMINAR =" El registro no se ha podido borrar correctamente";
     $ELIMINADO=0;
   }

if ($ELIMINADO) echo "<script> alert (\"$ELIMINAR\");</script>";
else echo  "<meta http-equiv=\"REFRESH\" content=\"0; URL=oipl.php?opci=Buscar&codioipl=$codioipl\">";

}
if ($opci=='Limpiar' || $opci=='Eliminar')
{
 global $HTTP_POST_VARS,$HTTP_GET_VARS;
 if ($HTTP_POST_VARS)
   foreach ($HTTP_POST_VARS as $post => $v)
     $$post='';
 if ($HTTP_GET_VARS)
   foreach ($HTTP_GET_VARS  as $get => $v)
     $$get='';
}
opci ("Crear:Limpiar");
echo " <center> ";
mostrartodo ();
?>
</center>
