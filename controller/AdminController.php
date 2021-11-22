<?php
require_once 'model/User.php';
class AdminController {
  public function handleRequest($page, $isAdmin) {
    $param = isset($_GET['param']) ? $_GET['param'] : NULL;
    try {
      // Vérifier si l'utilisateur et authentifié.
      if($isAdmin) {
        if (!$param) {
          $param = "dashboard";
        }  elseif($param === "users") {
          $users = $this->getUsersList();
        }
        include 'view/layouts/admin.phtml';
      } else {
      }
    } catch ( Exception $e ) {
      throw new Exception($e->getMessage());
    }
  }
  public function getUsersList() {
    try {
      $user = new User;
      $users = $user->getAllUsers();
      return $users;
    } catch ( Exception $e ) {
      throw new Exception( "Utilisateur non trouvé");
    }
  }
}