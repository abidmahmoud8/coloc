<?php
require_once 'model/User.php';

class UserController {
  public function initContent() {
  }

  // Création de la session utilisateur.
  public function createUserSession($user_id, $first_name, $last_name, $email, $role, $phone)  {
    $_SESSION['user'] = [
      'UserId'    => $user_id,
      'FirstName' => $first_name,
      'LastName'  => $last_name,
      'Email'     => $email,
      'Phone'     => $phone,
      'Role'      => $role,
    ];
  }
  public function handleRequest() {
    $param = isset($_GET['param']) ? $_GET['param'] : NULL;
    try {
      $var = "connexion";
      if (!$param) {
        include 'view/layouts/default.phtml';
      } elseif ( $param == 'login' ) {
        $this->login($_GET['email'], $_GET['password']);
      } elseif ( $param == 'register' ) {
        $this->register($_GET['first_name'], $_GET['last_name'], $_GET['email'], $_GET['password'], $_GET['phone']);
      }
    } catch ( Exception $e ) {
      throw new Exception($e->getMessage());
    }
  }
  public function login($email, $password) {
    try {
      $user = new User;
      $connectdUser = $user->getUserByMail($email, $password);
      die(json_encode($connectdUser));
    } catch ( Exception $e ) {
      throw new Exception( "Utilisateur non trouvé");
    }
  }
  public function register($first_name, $last_name, $email, $password, $phone) {
    try {
      $user = new User;
      $connectdUser = $user->createNewUser($first_name, $last_name, $email, $password, $phone);
      return $connectdUser;
    } catch ( Exception $e ) {

    }
  }
  // Vérifier si l'utilisateur et authentifié.
  public function isAuthenticated() {
    if (array_key_exists('user', $_SESSION)) {
      if (!empty($_SESSION['user'])) {
        return true;
      }
    }
    return false;
  }
}
