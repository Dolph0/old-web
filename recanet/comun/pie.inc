<?
  if (isset($codiambi)){
     $pieayun = $codiambi;
  }elseif (isset($ayun)){
     $pieayun = $ayun;
  }elseif(isset($codiayun)){
     $pieayun = $codiayun;
  }
  if (isset($pieayun)){
     include_once "comun/sql.fnc";
     include_once "clas/config.inc";
     $pieconfig = &config::getInstance();
     $escudo = $pieconfig->getValue('logotipo.escudo', $pieayun);
     $logotipo = $pieconfig->getValue('logotipo.recanet');
     if ($pieayun != _GLOBAL_){
        $resp = sql("SELECT trat, nomb FROM ayun where codiayun = $pieayun");
        if ($resp){
           $resp = $resp[0];
           $pietexto = $resp[trat] . " Ayuntamiento de " . $resp[nomb];
        }
     }else{
          $resp = sql("SELECT nomb FROM entigest");
          if ($resp)
             $pietexto = $resp;
     }
?>
<br>
<HR WIDTH="100%" SIZE="1">
<Table width=100%>
 <tr>

  <td align=left><? if ($escudo) { ?><img src='<? cheqroot($escudo, TRUE) ?>' width=30> <?}?></td>
  <td align=center><FONT SIZE="4"><I><B><? print $pietexto ?></B></I></FONT></td>
  <td align=right><? if ($logotipo) { ?><img src='<? cheqroot($logotipo, TRUE) ?>' width=70> <?}?></td>
 </tr>
</table>
<?
  }
?>
