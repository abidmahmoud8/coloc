<?php
require_once 'config/Database.php';

class Admin {
  public function __construct() {
  }
  public function deleteItem($db, $id, $value) {
    try {
      $database = new Database();
      $deleteItemSql = 'DELETE FROM ' . $db . ' WHERE '. $id .'=' .$value;
      $deleteImagesSql = 'DELETE FROM images WHERE query="' . $id . ':' . $value .'"';
      $database->executeSql($deleteItemSql, []);
      $database->executeSql($deleteImagesSql, []);
      return array("status" => "OK"); 
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
}