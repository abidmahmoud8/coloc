<?php
require_once 'model/User.php';
require_once 'model/Location.php';
require_once 'model/Admin.php';
class HomeController {
  public function handleRequest($page, $connected) {
    $param = isset($_GET['param']) ? $_GET['param'] : NULL;
    try {
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
}