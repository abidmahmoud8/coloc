<?php
require_once 'model/User.php';
require_once 'model/Location.php';
class AdminController {
  public function handleRequest($page, $isAdmin) {
    $param = isset($_GET['param']) ? $_GET['param'] : NULL;
    try {
      // Vérifier si l'utilisateur et authentifié.
      $user = $_SESSION['user'];
      if($isAdmin) {
        if (!$param) {
          $param = "dashboard";
        } elseif($param === "users") {
          $users = $this->getUsersList();
        } elseif($param === "localisations") {
          $countries = $this->getCountriesList();
          $states = $this->getStatesList();
          $cities = $this->getCitiesList();
        }
        include 'view/layouts/admin.phtml';
      } else {
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
      throw new Exception( "Problème de connexion avec le serveur");
    }
  }
  public function getCountriesList() {
    try {
      $location = new Location;
      $countries = $location->getAllCountries();
      return $countries;
    } catch ( Exception $e ) {
      throw new Exception( "Problème de connexion avec le serveur");
    }
  }
  public function getStatesList() {
    try {
      $location = new Location;
      $states = $location->getAllStates();
      return $states;
    } catch ( Exception $e ) {
      throw new Exception( "Problème de connexion avec le serveur");
    }
  }
  public function getCitiesList() {
    try {
      $location = new Location;
      $cities = $location->getAllCities();
      return $cities;
    } catch ( Exception $e ) {
      throw new Exception( "Problème de connexion avec le serveur");
    }
  }
}