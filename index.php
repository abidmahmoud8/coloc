<?php
$page = isset($_GET['page']) ? $_GET['page'] : NULL;
require_once 'controller/UserController.php';
try {
  if (!isset($page) || !$page) {
    $var = "index";
    include 'view/layouts/default.phtml';
  } else {
    $var = $page;
    switch ($page) {
      case "products":
        include 'view/layouts/default.phtml';
        break;
      case "contact":
        include 'view/layouts/default.phtml';
        break;
      case "connexion":
        $controller = new UserController();
        $controller->handleRequest();
        break;
      case "admin":
        include 'view/layouts/admin.phtml';
        break;
      case "profile":
        include 'view/layouts/profile.phtml';
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