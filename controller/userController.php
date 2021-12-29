<?php
require_once 'model/User.php';

class UserController {
  // Création de la session utilisateur.
  public function createUserSession($id_user, $first_name, $last_name, $email, $role, $phone)  {
    $_SESSION['user'] = [
      'id_user'    => $id_user,
      'first_name' => $first_name,
      'last_name'  => $last_name,
      'email'     => $email,
      'phone'     => $phone,
      'role'      => $role,
    ];
  }
  public function handleRequest($page, $connected) {
    $param = isset($_GET['param']) ? $_GET['param'] : NULL;
    try {
      // Vérifier si l'utilisateur et authentifié.
      if(!$connected) {
        if (!$param) {
          include 'view/layouts/default.phtml';
        } elseif ( $param === 'login' ) {
          $this->login($_GET['email'], $_GET['password']);
        } elseif ( $param === 'register' ) {
          $this->register($_GET['first_name'], $_GET['last_name'], $_GET['email'], $_GET['password'], $_GET['phone']);
        }
      } else {
        if ( ($param !== 'deconnect') && ($param !== 'connected-user') ) {
          header('Location: index.php');
        } elseif ( $param === 'deconnect' ) {
          $this->deconnect();
        } elseif ( $param === 'connected-user' ) {
          if(isset($_GET['show-infos'])) {
            $this->getUser(true);
          } else {
            $this->getUser(false);
          }
        }
      }
    } catch ( Exception $e ) {
      throw new Exception($e->getMessage());
    }
  }
  public function getUser($showInfos) {
    try {
      $user = new User;
      $connectdUser = $user->getUserInfo($showInfos);
      die(json_encode($connectdUser));
    } catch ( Exception $e ) {
      throw new Exception("Utilisateur non trouvé");
    }
  }
  public function deconnect() {
    try {
      if(isset($_SESSION['user'])) {
        session_unset();
        session_destroy();
      }
      if(isset($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
      } else {
        header('Location: /');
      }
    } catch ( Exception $e ) {
      throw new Exception( "Problème de deconnexion");
    }
  }
  public function login($email, $password) {
    try {
      $user = new User;
      $connectdUser = $user->getUserByMail($email, $password);
      if($connectdUser["status"] === "OK") {
        $this->createUserSession($connectdUser["user"]["id_user"], $connectdUser["user"]["first_name"], $connectdUser["user"]["last_name"], $connectdUser["user"]["email"], $connectdUser["user"]["role"], $connectdUser["user"]["phone"]);
      }
      die(json_encode($connectdUser));
    } catch ( Exception $e ) {
      throw new Exception( "Utilisateur non trouvé");
    }
  }
  public function register($first_name, $last_name, $email, $password, $phone) {
    try {
      $user = new User;
      $newUser = $user->createNewUser($first_name, $last_name, $email, $password, $phone);
      if($newUser["status"] === "OK") {
        $connectdUser = $user->getUserByMail($email, $password);
        if($connectdUser["status"] === "OK") {
          $this->createUserSession($connectdUser["user"]["id_user"], $connectdUser["user"]["first_name"], $connectdUser["user"]["last_name"], $connectdUser["user"]["email"], $connectdUser["user"]["role"], $connectdUser["user"]["phone"]);
        }
      }
      die(json_encode($newUser));
    } catch ( PDOException $e ) {
      throw new Exception( "Utilisateur non enregistré");
    }
  }
  public function isAuthenticated() {
    if (array_key_exists('user', $_SESSION)) {
      if (!empty($_SESSION['user'])) {
        return true;
      }
    }
    return false;
  }
}