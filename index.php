<?php
$page = isset($_GET['page']) ? $_GET['page'] : NULL;
require_once 'controller/UserController.php';
require_once 'controller/AdminController.php';
require_once 'config/Upload.php';
try {
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  $upload = new Upload;
  $userController = new UserController;
  $adminController = new AdminController;
  $connected = $userController->isAuthenticated();
  if (!isset($page) || !$page) {
    $page = "index";
    include 'view/layouts/default.phtml';
  } else {
    switch ($page) {
      case "products":
        include 'view/layouts/default.phtml';
        break;
      case "contact":
        include 'view/layouts/default.phtml';
        break;
      case "connexion":
        $controller = $userController;
        $controller->handleRequest($page, $connected);
        break;
      case "admin":
        $isAdmin = true;
        $controller = $adminController;
        $controller->handleRequest($page, $isAdmin);
        break;
      case "profile":
        include 'view/layouts/profile.phtml';
        break;
      case "upload":
        $controller = $upload;
        $controller->handleRequest();
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