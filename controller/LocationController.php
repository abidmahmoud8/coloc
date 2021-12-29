<?php
require_once 'model/User.php';
require_once 'model/Location.php';
require_once 'model/Admin.php';
class LocationController {
  public function handleRequest() {
    $location = new Location;
    $param = isset($_GET['param']) ? $_GET['param'] : NULL;
    try {
      if($param === 'states') {
        $id_country = $_GET["id_country"];
        $states = $location->getStates($id_country);
        die(json_encode($states));
      } elseif($param === 'cities') {
        $id_state = $_GET["id_state"];
        $cities = $location->getCities($id_state);
        die(json_encode($cities));
      } elseif($param === 'save-country') {
        if(isset($_GET['id_country'])) {
          $this->saveCountry($_GET['id_country'], $_GET['name'], $_GET['iso_code'], $_GET['currency'], $_GET['images']);
        } else {
          $this->saveCountry(null, $_GET['name'], $_GET['iso_code'], $_GET['currency'], $_GET['images']);
        }
      } elseif($param === 'save-state') {
        if(isset($_GET['id_state'])) {
          $this->saveState($_GET['id_state'], $_GET['name'], $_GET['id_country'], $_GET['images']);
        } else {
          $this->saveState(null, $_GET['name'], $_GET['id_country'], $_GET['images']);
        }
      } elseif($param === 'save-city') {
        if(isset($_GET['id_city'])) {
          $this->saveCity($_GET['id_city'], $_GET['name'], $_GET['id_state'], $_GET['images']);
        } else {
          $this->saveCity(null, $_GET['name'], $_GET['id_state'], $_GET['images']);
        }
      }
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function getCountriesList() {
    try {
      $location = new Location;
      $countries = $location->getCountries();
      return $countries;
    } catch ( Exception $e ) {
      throw new Exception( "Problème de connexion avec le serveur" );
    }
  }
  public function getStatesList($id_country) {
    try {
      $location = new Location;
      $states = $location->getStates($id_country);
      return $states;
    } catch ( Exception $e ) {
      throw new Exception( "Problème de connexion avec le serveur" );
    }
  }
  public function getCitiesList($id_state) {
    try {
      $location = new Location;
      $cities = $location->getCities($id_state);
      return $cities;
    } catch ( Exception $e ) {
      throw new Exception( "Problème de connexion avec le serveur" );
    }
  }
  public function saveCountry($id_country, $name, $iso_code, $currency, $images) {
    try {
      $location = new Location;
      $images = explode("**", $images);
      if($id_country) {
        $updatedCountry = $location->updateCountry($name, $iso_code, $currency, $images, $id_country);
        die(json_encode($updatedCountry));
      } else {
        $newCountry = $location->insertCountry($name, $iso_code, $currency, $images);
        die(json_encode($newCountry));
      }
    } catch ( Exception $e ) {
      throw new Exception( "Problème de connexion avec le serveur");
    }
  }
  public function saveState($id_state, $name, $id_country, $images) {
    try {
      $location = new Location;
      $images = explode("**", $images);
      if($id_state) {
        $updatedState = $location->updateState($name, $id_country, $images, $id_state);
        die(json_encode($updatedState));
      } else {
        $newState = $location->insertState($name, $id_country, $images);
        die(json_encode($newState));
      }
    } catch ( Exception $e ) {
      throw new Exception( "Problème de connexion avec le serveur");
    }
  }
  public function saveCity($id_city, $name, $id_state, $images) {
    try {
      $location = new Location;
      $images = explode("**", $images);
      if($id_city) {
        $updatedCity = $location->updateCity($name, $id_state, $images, $id_city);
        die(json_encode($updatedCity));
      } else {
        $newCity = $location->insertCity($name, $id_state, $images);
        die(json_encode($newCity));
      }
    } catch ( Exception $e ) {
      throw new Exception( "Problème de connexion avec le serveur");
    }
  }
}