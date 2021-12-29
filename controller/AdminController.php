<?php
require_once 'model/User.php';
require_once 'model/Location.php';
require_once 'model/Admin.php';
require_once 'model/Product.php';
class AdminController {
  public function handleRequest($page, $isAdmin) {
    $param = isset($_GET['param']) ? $_GET['param'] : NULL;
    try {
      // Vérifier si l'utilisateur et authentifié.
      $user = $_SESSION['user'];
      if($isAdmin) {
        $location = new Location;
        $productModel = new Product;
        if (!$param) {
          $param = "dashboard";
        } elseif($param === "users") {
          $users = $this->getUsersList();
        } elseif($param === "localisations") {
          $countries = $location->getCountries();
          $states = $location->getStates(null);
          $cities = $location->getCities(null);
        } elseif($param === "save-product") {
          $countries = $location->getCountries();
          if(isset($_GET['id_product'])) {
            $product = $productModel->getProduct($_GET['id_product']);
            if(!!$product["id_country"]) {
              $states = $location->getStates($product['id_country']);
              if(!!$product["id_city"]) {
                $cities = $location->getCities($product['id_state']);
              }
            }
          }
        } elseif($param === "products") {
          $products = $productModel->getProducts(null);
          
        } elseif($param === "save-user") {
          $user =  $this->getUser($_GET["id_user"]);
        }
        elseif($param === "country") {
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
  public function getUser($id_user) {
    try {
      $user = new User;
      $singleUser = $user->getSingleUser($id_user);
      return $singleUser;
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