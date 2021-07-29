<?php
class userController {
  // Création de la session utilisateur.
  public function createUserSession($user_id, $first_name, $last_name, $email, $roles)  {
    $_SESSION['user'] = [
      'UserId'    => $user_id,
      'FirstName' => $first_name,
      'LastName'  => $last_name,
      'Email'     => $email,
      'Roles'     => $roles
    ];
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
