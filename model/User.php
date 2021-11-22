<?php
require_once 'config/Database.php';

class User {
  public function __construct() {
  }
  // Démarrage de la session utilisateur.
  public function getUserByMail($email, $passwd) {
    try {
      $database = new Database();
      $selectSql = 'SELECT * FROM users WHERE email = "'.$email .'"';
      $user = $database->queryOne($selectSql, []);
      if($user) {
        $hashedPassword = $user["password"];
        if($this->checkPassword($passwd, $hashedPassword)) {
          return array("status" => "OK", "user" => $user);
        } else {
          return array("status" => "KO", "msg" => 'Mot de passe incorrect');
        }
      } else {
        return array("status" => "KO", "msg" => 'cet email n\'est associé à aucun compte');
      }
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  } 
  public function createNewUser($first_name, $last_name, $email, $password, $phone) {
    try {
      $database = new Database();
      $password = $this->hashPassword($password);
      $selectSql = 'SELECT * FROM users WHERE email = "'.$email .'"';
      $user = $database->queryOne($selectSql, []);
      if($user) {
        return array("status" => "KO", "msg" => "Cette adresse email est déjà utilisé");
      } else {
        $insertSql = 'INSERT INTO users (last_name, first_name, email, password, phone, role) VALUES (?, ?, ?, ?, ?, ?)';
        $newUser = $database->executeSql($insertSql, [$first_name, $last_name, $email, $password, $phone, 'customer']);
        if($newUser) {
          return array("status" => "OK"); 
        } else {
          return array("status" => "KO", "msg" => "Problème avec la connexion avec le serveur");
        }
      }
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function hashPassword($password) {
    $salt = '$2y$11$' . substr(bin2hex(openssl_random_pseudo_bytes(32)), 0, 22);
    return crypt($password, $salt);
  }
  public function checkPassword($password, $hashedPassword) {
    return crypt($password, $hashedPassword) === $hashedPassword;
  }
  public function getAllUsers() {
    try {
      $database = new Database();
      $selectSql = 'SELECT * FROM users';
      $users = $database->query($selectSql, []);
      return $users;
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
}