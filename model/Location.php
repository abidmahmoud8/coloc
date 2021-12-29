<?php
require_once 'config/Database.php';

class Location {
  public function __construct() {
  }
  public function getCountries() {
    try {
      $database = new Database();
      $selectSql = 'SELECT * FROM countries';
      $countries = $database->query($selectSql, []);
      return $countries;
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function getStates($id_country) {
    try {
      $database = new Database();
      $selectSql = 'SELECT states.name, states.id_state, states.id_country, countries.name as country_name 
      FROM states 
      LEFT JOIN countries ON countries.id_country = states.id_country';
      if($id_country) {
        $selectSql .= ' WHERE states.id_country = ' . $id_country;
      }
      $states = $database->query($selectSql, []);
      return $states;
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function getCities($id_state) {
    try {
      $database = new Database();
      $selectSql = 'SELECT cities.name, cities.id_city, cities.id_state, states.name as state_name, states.id_country, countries.name as country_name 
      FROM cities 
      LEFT JOIN states ON states.id_state = cities.id_state
      LEFT JOIN countries ON countries.id_country = states.id_country';
      if($id_state) {
        $selectSql .= ' WHERE cities.id_state = ' . $id_state;
      }
      $cities = $database->query($selectSql, []);
      return $cities;
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function getCountry($id_country) {
    try {
      $database = new Database();
      $selectSql = 'SELECT countries.*, group_concat(images.path separator "**") as images
      FROM countries 
      LEFT JOIN images ON images.query = "id_country:' . $id_country .'" 
      WHERE countries.id_country = ' . $id_country;
      $country = $database->queryOne($selectSql);
      if($country["images"]) {
        $country["images"] = explode("**", $country["images"]);
      } else {
        $country["images"] = [];
      }
      return $country;
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function getState($id_state) {
    try {
      $database = new Database();
      $selectSql = 'SELECT states.*, group_concat(images.path separator "**") as images
      FROM states 
      LEFT JOIN images ON images.query = "id_state:' . $id_state .'" 
      WHERE states.id_state = ' . $id_state;
      $state = $database->queryOne($selectSql);
      if($state["images"]) {
        $state["images"] = explode("**", $state["images"]);
      } else {
        $state["images"] = [];
      }
      return $state;
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function getCity($id_city) {
    try {
      $database = new Database();
      $selectSql = 'SELECT cities.*, group_concat(images.path separator "**") as images, states.id_country as id_country
      FROM cities 
      LEFT JOIN images ON images.query = "id_city:' . $id_city .'" 
      LEFT JOIN states ON states.id_state = cities.id_state
      WHERE cities.id_city = ' . $id_city;
      $city = $database->queryOne($selectSql);
      if($city["images"]) {
        $city["images"] = explode("**", $city["images"]);
      } else {
        $city["images"] = [];
      }
      return $city;
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function insertCountry($name, $iso_code, $currency, $images) {
    try {
      $database = new Database();
      $insertCountrySql = 'INSERT INTO countries (name, iso_code, currency) VALUES (?, ?, ?)';
      $newCountry = $database->executeSql($insertCountrySql, [$name, $iso_code, $currency]);
      if($newCountry) {
        foreach ($images as $image) {
          $insertImage = 'INSERT INTO images (path, query) VALUES (?, ?)';
          $newImage = $database->executeSql($insertImage, [$image, 'id_country:'.$newCountry]);
        }
        return array("status" => "OK"); 
      } else {
        return array("status" => "KO", "msg" => "Problème avec la connexion avec le serveur");
      }
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function updateCountry($name, $iso_code, $currency, $images, $id_country) {
    try {
      $database = new Database();
      $sql = 'UPDATE countries SET name="'.$name.'", iso_code="'.$iso_code.'", currency="'.$currency.'" WHERE id_country='.$id_country;
      $updatedCountrySql = $database->executeSql($sql, []);
      $deleteImagesSql = 'DELETE FROM images WHERE query="id_country:' . $id_country .'"';
      $database->executeSql($deleteImagesSql, []);
      foreach ($images as $image) {
        $insertImage = 'INSERT INTO images (path, query) VALUES (?, ?)';
        $newImage = $database->executeSql($insertImage, [$image, 'id_country:'.$id_country]);
      }
      return array("status" => "OK"); 
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function insertState($name, $id_country, $images) {
    try {
      $database = new Database();
      $insertStateSql = 'INSERT INTO states (name, id_country) VALUES (?, ?)';
      $newState = $database->executeSql($insertStateSql, [$name, $id_country]);
      if($newState) {
        foreach ($images as $image) {
          $insertImage = 'INSERT INTO images (path, query) VALUES (?, ?)';
          $newImage = $database->executeSql($insertImage, [$image, 'id_state:'.$newState]);
        }
        return array("status" => "OK"); 
      } else {
        return array("status" => "KO", "msg" => "Problème avec la connexion avec le serveur");
      }
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function updateState($name, $id_country, $images, $id_state) {
    try {
      $database = new Database();
      $sql = 'UPDATE states SET name="'.$name.'", id_country="'.$id_country.'" WHERE id_state='.$id_state;
      $updatedState = $database->executeSql($sql, []);
      $deleteImagesSql = 'DELETE FROM images WHERE query="id_state:' . $id_state .'"';
      $database->executeSql($deleteImagesSql, []);
      foreach ($images as $image) {
        $insertImage = 'INSERT INTO images (path, query) VALUES (?, ?)';
        $newImage = $database->executeSql($insertImage, [$image, 'id_state:'.$id_state]);
      }
      return array("status" => "OK"); 
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function insertCity($name, $id_state, $images) {
    try {
      $database = new Database();
      $insertCitySql = 'INSERT INTO cities (name, id_state) VALUES (?, ?)';
      $newState = $database->executeSql($insertCitySql, [$name, $id_state]);
      if($newState) {
        foreach ($images as $image) {
          $insertImage = 'INSERT INTO images (path, query) VALUES (?, ?)';
          $newImage = $database->executeSql($insertImage, [$image, 'id_state:'.$newState]);
        }
        return array("status" => "OK"); 
      } else {
        return array("status" => "KO", "msg" => "Problème avec la connexion avec le serveur");
      }
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function updateCity($name, $id_state, $images, $id_city) {
    try {
      $database = new Database();
      $sql = 'UPDATE cities SET name="'.$name.'", id_state="'.$id_state.'" WHERE id_city='.$id_city;
      $updatedCity = $database->executeSql($sql, []);
      $deleteImagesSql = 'DELETE FROM images WHERE query="id_city:' . $id_city .'"';
      $database->executeSql($deleteImagesSql, []);
      foreach ($images as $image) {
        $insertImage = 'INSERT INTO images (path, query) VALUES (?, ?)';
        $newImage = $database->executeSql($insertImage, [$image, 'id_city:'.$id_city]);
      }
      return array("status" => "OK"); 
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
}