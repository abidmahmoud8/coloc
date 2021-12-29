<?php
require_once 'config/Database.php';

class Product {
  public function __construct() {
  }
  // id_product id_user title description phone price show_profile_image id_country id_state id_city zip status
  public function insertProduct(
    $id_user,
    $title,
    $description,
    $type,
    $phone,
    $price,
    $show_profile,
    $id_country,
    $id_state,
    $id_city,
    $zip,
    $images
  ) {
    try {
      $database = new Database();
      $insertProductSql = 'INSERT INTO products (id_user, title, description, type, phone, price, show_profile, id_country, id_state, id_city, zip, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
      $product = $database->executeSql($insertProductSql, [
        $id_user,
        $title,
        $description,
        $type,
        $phone,
        $price,
        intval($show_profile),
        $id_country,
        $id_state,
        $id_city,
        $zip,
        "wait"
      ]);
      if($product) {
        foreach ($images as $image) {
          $insertImage = 'INSERT INTO images (path, query) VALUES (?, ?)';
          $newImage = $database->executeSql($insertImage, [$image, 'id_product:'.$product]);
        }
        return array("status" => "OK"); 
      } else {
        return array("status" => "KO", "msg" => "Problème avec la connexion avec le serveur");
      }
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function updateProduct(
    $id_user,
    $title,
    $description,
    $type,
    $phone,
    $price,
    $show_profile,
    $id_country,
    $id_state,
    $id_city,
    $zip,
    $images,
    $id_product
  ) {
    try {
      $database = new Database();
      $sql = 'UPDATE products SET id_user="'.$id_user.'", title="'.$title.'", description="'.$description.'", type="'.$type.'", phone="'.$phone.'", price="'.$price.'", show_profile="'.intval($show_profile).'", id_country="'.$id_country.'", id_state="'.$id_state.'", id_city="'.$id_city.'", zip="'.$zip.'" WHERE id_product='.$id_product;
      $updatedProductSql = $database->executeSql($sql, []);
      $deleteImagesSql = 'DELETE FROM images WHERE query="id_product:' . $id_product .'"';
      $database->executeSql($deleteImagesSql, []);
      foreach ($images as $image) {
        $insertImage = 'INSERT INTO images (path, query) VALUES (?, ?)';
        $newImage = $database->executeSql($insertImage, [$image, 'id_product:'.$id_product]);
      }
      return array("status" => "OK"); 
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function getProduct ($id_product) {
    try {
      $database = new Database();
      $selectSql = 'SELECT products.*, group_concat(images.path separator "**") as images
      FROM products 
      LEFT JOIN images ON images.query = "id_product:' . $id_product .'" 
      WHERE products.id_product = ' . $id_product;
      $product = $database->queryOne($selectSql);
      if($product["images"]) {
        $product["images"] = explode("**", $product["images"]);
      } else {
        $product["images"] = [];
      }
      return $product;
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function getProducts ($id_user) {
    try {
      $database = new Database();
      $selectSql = 'SELECT * FROM products';
      if($id_user) {
        $selectSql .= ' WHERE id_user = ' . $id_user;
      }
      $products = $database->query($selectSql, []);
      return $products;
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function getProductsList($params) {
    try {
      $database = new Database();
      $selectSql = 'SELECT * FROM products';
      if($params) {
        $selectSql .= ' WHERE ' . $params;
      }
      $products = $database->query($selectSql, []);
      foreach ($products as &$product) {
        $imagesSql = 'SELECT path FROM images where query = "id_product:' . $product["id_product"] . '"';
        $images = $database->query($imagesSql, []);
        $product["images"] = $images;
      }
      return $products;
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
}