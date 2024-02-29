<?php
/**
 * Calculo de la norma60 en su formato 521
 * 
 * @author jframirez
 * */
class formato521 {
        private $Provincia;
        private $Municipio;
        private $Iddocumento;
        private $Tributo;
        private $Ejercicio;
        private $Fechalimite;
        private $Importe;
        private $pesos;

        function formato521 ($provincia, $municipio, $iddocumento, $tributo, $ejercicio, $fechalimite, $importe){
                   $this->Provincia = $provincia;
                   $this->Municipio = $municipio;
                   $this->Iddocumento = $iddocumento;
                   $this->Tributo = $tributo;
                   $this->Ejercicio = $ejercicio;
                   $this->Fechalimite = $fechalimite;
                   $this->Importe = $importe;
                   $this->pesos = array(2,3,4,5,6);
        }

        function SetProvincia($provincia){
                 if ($provincia != '')
                    $this->Provincia = $provincia;
        }

        function GetProvincia(){
                 return $this->Provincia;
        }

        function SetMunicipio($municipio){
                 if ($municipio != '')
                    $this->Municipio = $municipio;
        }

        function GetMunicipio(){
                 return $this->Municipio;
        }

        function SetIddocumento($iddocumento){
                 if ($iddocumento != '')
                    $this->Iddocumento = $iddocumento;
        }

        function GetIddocumento(){
                 return $this->Iddocumento;
        }

        function SetTributo($tributo){
                 if ($tributo != '')
                    $this->Tributo = $tributo;
        }

        function GetTributo(){
                 return $this->Tributo;
        }

        function SetEjercicio($ejercicio){
                 if ($ejercicio != null)
                    $this->Ejercicio = $ejercicio;
        }

        function GetEjercicio(){
                 return $this->Ejercicio;
        }

        function SetFechalimite($fechalimite){
                 if ($fechalimite != '')
                    $this->Fechalimite = $fechalimite;
        }

        function GetFechalimite(){
                 return $this->Fechalimite;
        }

        function SetFechainicio($fechainicio){
                 if ($fechainicio != '')
                    $this->Fechainicio = $fechainicio;
        }

        function GetFechainicio(){
                 return $this->Fechainicio;
        }

        function SetImporte($importe){
                 if ($importe > 0)
                    $this->Importe = $importe;
        }

        function GetImporte(){
                 return $this->Importe;
        }

        function generaBarCodeText(){
                $res .= "90";
                $res .= "521";
                $val = $this->generaEmisora();
                if (!$val){
//                   echo ("No se pudo generar la emisora");
                   return false;
                }
                $res .= $val;
                $val = $this->generaReferencia();
                if (!$val){
//                   echo ("No se pudo generar la Referencia");
                   return false;
                }
                $res .= $val;
                $val = $this->generaIdentificacion();
                if (!$val){
//                   echo ("No se pudo generar la Identificacion");
                   return false;
                }
                $res .= $val;
                $val = $this->GetImporte();
                $len = strlen($val);
                for ($i = 0; $i < 8 - $len; $i++){
                    $val = "0" . $val;
                }
                $res .= $val;
                $res .= "0";
                return $res;
        }

        function generaEmisora(){
                $codine = $this->GetProvincia() . $this->GetMunicipio();
                if (strlen($codine) < 5 || !is_numeric($codine))
                   return false;

                //Calculo del digito de control
                $decmillar = intval(substr($codine, 0, 1));
                $unidadmil = intval(substr($codine, 1, 1));
                $centenas = intval(substr($codine, 2, 1));
                $decenas = intval(substr($codine, 3, 1));
                $unidad = intval(substr($codine, 4, 1));

                $res = $this->pesos[4] * $decmillar + $this->pesos[3] * $unidadmil +
                       $this->pesos[2] * $centenas + $this->pesos[1] * $decenas +
                       $this->pesos[0] * $unidad;
                $res = $res % 11;
                if ($res == 10)
                   $res = 0;

                return $codine . $res;
        }

        function generaReferencia(){
                $emisora = $this->generaEmisora();
                if (!$emisora)
                   return false;
                $emisora = intval($emisora);
                $numedocu = $this->GetIddocumento();
                $len = strlen($numedocu);
                for ($i = 0; $i < (10 - $len); $i++){
                    $numedocu = "0" . $numedocu;
                }
                $identificacion = $this->generaIdentificacion();
                if (!$identificacion)
                   return false;
                $res = ($emisora * 76) +
                       ($numedocu * 9) +
                       ((($identificacion + $this->GetImporte()) - 1) * 55);
                $res = $res / 97;
                if (!strpos($res, ".")){
                   $res = 99;
                } else {
                $res = substr($res, strpos($res, ".") + 1, 2);
                $res = 99 - intval($res);
                }
                if ($res == 0)
                   $res = 99;
                for ($i = 0; $i < 2 - strlen($res); $i++){
                    $res = "0" . $res;
                }

                return $numedocu . $res;
        }

        function generaIdentificacion(){
                $res = "1";
                $res .= $this->GetTributo();

                $val = $this->GetEjercicio();
                if (strlen($val) > 2)
                   $val = substr($val, strlen($val) - 2);
                $res .= $val;
                $val = $this->GetFechalimite();
                list( $year, $month, $day ) = preg_split("/[\/.-]/", $val );
                $res .= substr($year, strlen($year) - 1);
                $jd = $this->julianDay($day, $month, $year);
                $jdl = strlen($jd);
                for ($i = 0; $i < 3 - $jdl; $i++)
                    $jd = "0" . $jd;
                $res .= $jd;
                return $res;
        }

        function julianDay($day, $month, $year){
             $a = intval((14-$month)/12);
             $y = $year + 4800 - $a;
             $m = $month + intval(12*$a) - 3;

             //For a date in the Gregorian calendar:
             $JD = $day + intval((153*$m+2)/5) + $y*365 + intval($y/4) - intval($y/100) + intval($y/400) - 32045;

             return $JD - $this->firstJulianDay($year);
        }

        function firstJulianDay($year){
             $a = intval((13)/12);
             $y = $year + 4800 - $a;
             $m = 1 + intval(12*$a) - 3;

             //For a date in the Gregorian calendar:
             $JD = intval((153*$m+2)/5) + $y*365 + intval($y/4) - intval($y/100) + intval($y/400) - 32045;

             return $JD;
        }


  }

?>
