<?php

class Upload {
  public function handleRequest() {
    try {
      var_dump($_FILES);
      $files = $_FILES;
      foreach ($files as $file) {
        $target_dir = "assets/";
        $target_file = $target_dir . basename($file["name"]);
        move_uploaded_file($file["tmp_name"], $target_file);
      }
      die(json_encode(array("status" => "OK", "user" => 'test')));
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function checkFile($file, $target_file) {
    $errors = [];
    $check = getimagesize($file["tmp_name"]);
    if(!$check) {
      array_push($errors, "Le fichier n'est pas une image");
    }
    if (file_exists($target_file)) {
      array_push($errors, "Désolé, le fichier existe déjà.");
    }
    if ($_FILES["fileToUpload"]["size"] > 500000) {
      array_push($errors, "Désolé, votre fichier est trop volumineux.");
    }
    return $check;
  }
}