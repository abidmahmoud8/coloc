<?php
class User {
  // Démarrage de la session utilisateur.
  public function __construct() {
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
  }
  public function getUserByMail($email, $passwd) {
    try {
      echo '<pre>';
      var_dump($email, $passwd);
      echo '</pre>';
      require_once 'config/db-connect.php';
      $sql = 'SELECT * FROM users WHERE email = "'.$email .'"';
      $req = $pdo->prepare($sql);
      $req->execute();
      $user = $req->fetch(PDO::FETCH_ASSOC);
      echo '<pre>';
      var_dump($email, $passwd);
      echo '</pre>';
      if($user) {
        $hashedPassword = $user["password"];
        var_dump(password_verify($passwd, $hashedPassword));
        if(password_verify($passwd, $hashedPassword)) {
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
      require_once 'config/db-connect.php';
      $password = password_hash($password, PASSWORD_DEFAULT);
      $sql1 = 'SELECT * FROM users WHERE email = "'.$email .'"';
      $req1 = $pdo->prepare($sql1);
      $req1->execute();
      $user = $req1->fetch(PDO::FETCH_ASSOC);
      if($user) {
        return array("status" => "KO", "msg" => "Cette adresse email est déjà utilisé");
      } else {
        $sql = 'INSERT INTO users (last_name, first_name, email, password, phone, role) VALUES (?, ?, ?, ?, ?, ?)';
        $query = $pdo->prepare($sql);
        $query->execute([$first_name, $last_name, $email, $password, $phone, 'customer']);
        if($pdo->lastInsertId()) {
          return array("status" => "OK"); 
        }
      }
    } catch ( Exception $e ) {
      var_dump($e);
    }
  }
  public function hashPassword($password) {
    $salt = '$2y$11$' . substr(bin2hex(openssl_random_pseudo_bytes(32)), 0, 22);
    return crypt($password, $salt);
  }
  public function checkPassword($password, $hashedPassword) {
    return crypt($password, $hashedPassword) == $hashedPassword;
  }

}
