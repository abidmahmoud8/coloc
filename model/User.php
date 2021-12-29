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
      $user = $database->queryOne($selectSql);
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
      $user = $database->queryOne($selectSql);
      if($user) {
        return array("status" => "KO", "msg" => "Cette adresse email est déjà utilisé");
      } else {
        $executeSql = 'INSERT INTO users (last_name, first_name, email, password, phone, role) VALUES (?, ?, ?, ?, ?, ?)';
        $newUser = $database->executeSql($executeSql, [$first_name, $last_name, $email, $password, $phone, 'customer']);
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
  public function getUserInfo($showInfos) {
    try {
      $user = $_SESSION['user'];
      if($showInfos) {
        $database = new Database();
        $selectSql = 'SELECT * FROM users WHERE id_user = "'.$user["id_user"] .'"';
        $user = $database->queryOne($selectSql);
        if(!$user) {
          return array("status" => "KO", "msg" => "Utilisateur non trouvé");
        }
      }
      return $user;
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function getSingleUser($id_user) {
    try {
      $database = new Database();
      $selectSql = 'SELECT * FROM users WHERE id_user = "'.$id_user .'"';
      $user = $database->queryOne($selectSql);
      if(!$user) {
        return array("status" => "KO", "msg" => "Utilisateur non trouvé");
      }
      return $user;
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function updateUser(
    $id_user,
    $first_name,
    $last_name,
    $adress,
    $city,
    $country,
    $email,
    $role,
    $images,
    $phone,
    $password,
    $newPassword
  ) {
    try {
      $database = new Database();
      $sql = 'UPDATE users SET first_name="'.$first_name.'", last_name="'.$last_name.'", adress="'.$adress.'", city="'.$city.'", city="'.$city.'", country="'.$country.'", email="'.$email.'", role="'.$role.'", phone="'.$phone.'" WHERE id_user='.$id_user;
      $updatedCountrySql = $database->executeSql($sql, []);
      $deleteImagesSql = 'DELETE FROM images WHERE query="id_user:' . $id_user .'"';
      $database->executeSql($deleteImagesSql, []);
      foreach ($images as $image) {
        $insertImage = 'INSERT INTO images (path, query) VALUES (?, ?)';
        $newImage = $database->executeSql($insertImage, [$image, 'id_user:'.$id_user]);
      }
      return array("status" => "OK"); 
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
}