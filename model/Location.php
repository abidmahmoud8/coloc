<?php
require_once 'config/Database.php';

class Location {
  public function __construct() {
  }
  public function getAllCountries() {
    try {
      $database = new Database();
      $selectSql = 'SELECT * FROM countries';
      $countries = $database->query($selectSql, []);
      return $countries;
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function getAllStates() {
    try {
      $database = new Database();
      $selectSql = 'SELECT states.name, states.id_state, states.id_country, countries.name as country_name 
      FROM states 
      LEFT JOIN countries ON countries.id_country = states.id_country';
      $states = $database->query($selectSql, []);
      return $states;
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function getAllCities() {
    try {
      $database = new Database();
      $selectSql = 'SELECT cities.name, cities.id_city, cities.id_state, states.name as state_name, states.id_country, countries.name as country_name 
      FROM cities 
      LEFT JOIN states ON states.id_state = cities.id_state
      LEFT JOIN countries ON countries.id_country = states.id_country';
      $cities = $database->query($selectSql, []);
      return $cities;
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
}