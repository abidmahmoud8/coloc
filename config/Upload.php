<?php

class Upload {
  public function handleRequest() {
    try {
      $files = $_FILES;
      $errors = [];
      $uploadedFiles = [];
      foreach ($files as $key => $file) {
        $imgType = strtolower(pathinfo(basename($file["name"]), PATHINFO_EXTENSION));
        $targetFile = "assets/" . htmlspecialchars($key) . '.' . $imgType;
        $checkFile = $this->checkFile($file, $targetFile);
        if($checkFile["check"]) {
          move_uploaded_file($file["tmp_name"], $targetFile);
          array_push($uploadedFiles, $targetFile);
        } else {
          foreach ($checkFile["errors"] as $error) {
            array_push($errors, $error);
          }
        }
      }
      die(json_encode(array("uploadedFiles" => $uploadedFiles, "errors" => $errors)));
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function checkFile($file, $targetFile) {
    $errors = [];
    $check = getimagesize($file["tmp_name"]);
    if(!$check) {
      array_push($errors, "Le fichier ". $file["name"] ." n'est pas une image");
    }
    if (file_exists($targetFile)) {
      array_push($errors, "Le fichier ". $file["name"] ." existe déjà.");
    }
    if ($file["size"] > 500000) {
      array_push($errors, "Votre fichier ". $file["name"] ." est trop volumineux.");
    }
    $imgType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    if($imgType != "jpg" && $imgType != "png" && $imgType != "jpeg" && $imgType != "gif" ) {
      array_push($errors, "Votre fichier ". $file["name"] ."de type '" .$imgType. "' n\est pas autorisé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.");
    }
    if($errors && count($errors) && count($errors) > 0) {
      return array("check" => false, "errors" => $errors);
    } else {
      return array("check" => true);
    }
  }
}