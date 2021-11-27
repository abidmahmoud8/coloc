<?php
require_once 'model/User.php';
require_once 'model/Location.php';
require_once 'model/Admin.php';
class AdminController {
  public function handleRequest($page, $isAdmin) {
    $param = isset($_GET['param']) ? $_GET['param'] : NULL;
    try {
      // Vérifier si l'utilisateur et authentifié.
      $user = $_SESSION['user'];
      if($isAdmin) {
        $location = new Location;
        if (!$param) {
          $param = "dashboard";
        } elseif($param === "users") {
          $users = $this->getUsersList();
        } elseif($param === "localisations") {
          $countries = $this->getCountriesList();
          $states = $this->getStatesList();
          $cities = $this->getCitiesList();
        } elseif($param === "add-product") {
          $countries = $this->getCountriesList();
        } elseif($param === "country") {
          if(isset($_GET['id_country'])) {
            $country = $location->getCountry($_GET['id_country']);
          }
        } elseif($param === "state") {
          if(isset($_GET['id_state'])) {
            $state = $location->getState($_GET['id_state']);
          }
          $countries = $location->getCountries();
        } elseif($param === "city") {
          $countries = $location->getCountries();
          if(isset($_GET['id_city'])) {
            $city = $location->getCity($_GET['id_city']);
            $states = $location->getStates($city['id_country']);
          }
        } elseif($param === "delete") {
          $this->deleteItem($_GET['db'], $_GET['id'], $_GET['value']);
        }
        include 'view/layouts/admin.phtml';
      }
    } catch ( Exception $e ) {
      throw new Exception($e->getMessage());
    }
  }
  public function getUsersList() {
    try {
      $user = new User;
      $users = $user->getAllUsers();
      return $users;
    } catch ( Exception $e ) {
      throw new Exception( "Problème de connexion avec le serveur" );
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
  public function getStatesList() {
    try {
      $location = new Location;
      $states = $location->getStates(null);
      return $states;
    } catch ( Exception $e ) {
      throw new Exception( "Problème de connexion avec le serveur" );
    }
  }
  public function getCitiesList() {
    try {
      $location = new Location;
      $cities = $location->getCities(null);
      return $cities;
    } catch ( Exception $e ) {
      throw new Exception( "Problème de connexion avec le serveur" );
    }
  }
  public function deleteItem($db, $id, $value) {
    try {
      $admin = new Admin;
      $deletedItem = $admin->deleteItem($db, $id, $value);
      die(json_encode($deletedItem));
    } catch ( Exception $e ) {
      throw new Exception( "Problème de connexion avec le serveur" );
    }
  }
}