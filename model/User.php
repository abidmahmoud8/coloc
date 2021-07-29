<?php
class User {
  // Démarrage de la session utilisateur.
  public function __construct() {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
  }
  // Cripté la mot de passe.
  public function hashPassword($password) {
    $salt = '$2y$11$' . substr(bin2hex(openssl_random_pseudo_bytes(32)), 0, 22);
    return crypt($password, $salt);
  }
  public function checkPassword($password, $hashedPassword) {
    return crypt($password, $hashedPassword) == $hashedPassword;
  }
}
