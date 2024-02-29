<?php

function cheqroot($page, $print = FALSE){
          if (strpos($page, "../") > 0 || strpos($page, "/") == 1){
            if (!$print)
              return $page;
            print($page);
            return;
          }
          global $PHP_SELF;
          $dirs = explode("/",$PHP_SELF);
          $path = "";
          for ($i = 1; $i < sizeof($dirs)-1; $i++) {
                  $path .= "../"; 
          }
          global $myhttproot;
          global $myfileroot;
          if ($_SESSION['myhttproot'] && $_SESSION['myfileroot']){
            $file = $myfileroot . getFile($page);
            if (file_exists($file)) {
               if (!$print)
                  return $myhttproot . $page;
               print($myhttproot . $page);
               return;
            }
          }
          global $mainroot;
          if ( $_SESSION['mainroot'] ){
             if (!$print)
                return $path . $mainroot . $page;
             print($path . $mainroot . $page);
          }
          else{
        if (!$print)
            return ("../" . $page);
        print("../" . $page);
      }
}

function getFile($page){
         if (strpos($page, "?") > 0){
            return substr($page, 0, strpos($page, "?"));
         }
         return $page;
}

function getFullPath($relativefile){
         $paths = explode(":", get_include_path());
         foreach ($paths as $path => $value) {
               if (file_exists($value . "/" . $relativefile)){
                  return $value . "/" . $relativefile;
               }
         }
         return false;
}