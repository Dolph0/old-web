<HTML>
<HEAD>
<TITLE>Formulario de la dieta mensual para viajes profesionales</TITLE>
<SCRIPT LANGUAGE="JavaScript" >
//función controladora
function Check(form)
{
    var arr=Array();
    var error="";
    with(form)
        arr=[[dat,"Fecha"],
        [frn,"Nombre"],
        [nam,"Apellido"]]
        for (i=0;i<arr.length;i++)
        if (arr[i][0].value=="")
            error=error+" -"+arr[i][1]+"\n";
        if (error!="")
            alert("Faltan los siguientes datos:\n"+error);
        else
            form.submit();
}

</SCRIPT>
</HEAD>
<BODY>
<!-- creando el formulario del documento
de la dieta -->
<FORM NAME="<?php print $_GET["doc"] ?>" ACTION="conversion.php?doc=<?php print $_GET["doc"] ?>" METHOD="POST">
<TABLE>
<TR><TD ALIGN="center"><BIG><B><?php print strtoupper($_GET["desc"]) ?></B></BIG>
</TD>
</TR>
</TABLE>
<BR><BR>
<TABLE WIDTH>
<TR><TD><B>Fecha:</B></TD>
<TD><INPUT NAME="dat" SIZE=12 MAXLENGHT=12></TD>
</TR>
<TR><TD><B>Nombre:</B></TD>
<TD><INPUT NAME="frn" SIZE=20 MAXLENGTH=20></TD>
</TR>
<TR><TD><B>Apellido:</B></TD>
<TD><INPUT NAME="nam" SIZE=30 MAXLENGTH=30></TD>
</TR>
<TR><TD>Nº del contrato:</TD>
<TD><INPUT NAME="agr" SIZE=20 MAXLENGHT=20></TD>
</TR>
<TR><TD>Viajes en el mes de:</TD>
<TD><SELECT NAME="mon">
<OPTION>enero
<OPTION>febrero
<OPTION>marzo
<OPTION>abril
<OPTION>mayo
<OPTION>junio
<OPTION>julio
<OPTION>agosto
<OPTION>septiembre
<OPTION>octubre
<OPTION>noviembre
<OPTION>diciembre
</SELECT></TD>
</TR>
<TR><TD>Matrícula:</TD>
<TD><INPUT NAME="reg" SIZE=15 MAXLENGTH=15></TD>
</TR>
<TR><TD>Cilindrada:</TD>
<TD><INPUT NAME="cap" SIZE=12 MAXLENGTH=12></TD>
</TR>
<TR><TD>Importe mensual:</TD>
<TD><INPUT NAME="lim" SIZE=15 MAXLENGTH=15></TD>
</TR>
<TR><TD>Cuota de la dieta:</TD>
<TD><INPUT NAME="rat" SIZE=15 MAXLENGTH=15></TD>
</TR>
<TR><TD><I>Faltas:</I></TD>
<TD>&nbsp;</TD>
</TR>
<TR><TD>Vacaciones:</TD>
<TD><INPUT NAME="res" SIZE=10 MAXLENGTH=10></TD>
</TR>
<TR><TD>Baja laboral:</TD>
<TD><INPUT NAME="sic" SIZE=10 MAXLENGTH=10></TD>
</TR>
<TR><TD>Viaje profesional:</TD>
<TD><INPUT NAME="bus" SIZE=10 MAXLENGTH=10></TD>
</TR>
</TABLE>
<INPUT TYPE="HIDDEN" NAME="sum_m">
<INPUT TYPE="HIDDEN" NAME="day">
<INPUT TYPE="HIDDEN" NAME="sum_l">
<INPUT TYPE="HIDDEN" NAME="sum_d">
<INPUT TYPE="HIDDEN" NAME="pay">
<BR>
<HR>
<INPUT TYPE="button" value="Generando el documento" onClick="Check(<?php print $_GET["doc"] ?>)">
</FORM> </BODY> </HTML>
