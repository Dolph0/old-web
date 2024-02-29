<?php
  //--------------------------------------------------
  // Adaptandonos al register_global OFF
  //--------------------------------------------------
  // Emular register_globals on
  if (!ini_get('register_globals')) {
      // $superglobales = array($_SERVER, $_ENV, $_FILES, $_COOKIE, $_POST, $_GET);

      $superglobales = array($_POST, $_GET);

      if (isset($_SESSION)) {
          array_unshift($superglobales, $_SESSION);
      }
      foreach ($superglobales as $superglobal) {
          extract($superglobal, EXTR_SKIP);
      }
  }
