<?php
$page = isset($_GET['page']) ? $_GET['page'] : NULL;
require_once 'controller/UserController.php';
require_once 'controller/AdminController.php';
require_once 'controller/HomeController.php';
require_once 'controller/LocationController.php';
require_once 'controller/ProductController.php';
require_once 'config/Upload.php';
try {
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  $upload = new Upload;
  $userController = new UserController;
  $adminController = new AdminController;
  $homeController = new HomeController;
  $locationController = new LocationController;
  $productController = new ProductController;
  $connected = $userController->isAuthenticated();
  if (!isset($page) || !$page) {
    $page = "index";
    $countries = $locationController->getCountriesList();
    include 'view/layouts/default.phtml';
  } else {
    switch ($page) {
      case "products":
        $countries = $locationController->getCountriesList();
        $id_country = null;
        $id_state = null;
        $id_city = null;
        $zip = null;
        $type = null;
        $prices = null;
        $limit = 12;
        $skip = 0;
        if(isset($_GET['id_country'])) {
          $id_country = $_GET['id_country'];
          $states = $locationController->getStatesList($id_country);
        }
        if(isset($_GET['id_state'])) {
          $id_state = $_GET['id_state'];
          $cities = $locationController->getCitiesList($id_state);
        }
        if(isset($_GET['id_city'])) {
          $id_city = $_GET['id_city'];
        }
        if(isset($_GET['zip'])) {
          $zip = $_GET['zip'];
        }
        if(isset($_GET['type'])) {
          $type = $_GET['type'];
        }
        if(isset($_GET['prices'])) {
          $prices = $_GET['prices'];
        }
        if(isset($_GET['limit'])) {
          $limit = $_GET['limit'];
        }
        if(isset($_GET['skip'])) {
          $skip = $_GET['skip'];
        }
        $products = $productController->getProducts(
          $id_country,
          $id_state,
          $id_city,
          $zip,
          $type,
          $prices,
          $limit,
          $skip
        );
        include 'view/layouts/default.phtml';
        break;
      case "locations":
        $locationController->handleRequest();
        break;
      case "product":
        $productController->handleRequest();
        break;
      case "contact":
        include 'view/layouts/default.phtml';
        break;
      case "connexion":
        $userController->handleRequest($page, $connected);
        break;
      case "admin":
        $isAdmin = true;
        $adminController->handleRequest($page, $isAdmin);
        break;
      case "profile":
        include 'view/layouts/profile.phtml';
        break;
      case "upload":
        $upload->handleRequest();
        break;
      default:
        include 'view/layouts/default.phtml';
        break;
    }
  }
}
catch (Exception $e) {
  throw new Exception($e->getMessage());
}