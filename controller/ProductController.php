<?php
require_once 'model/User.php';
require_once 'model/Product.php';
require_once 'model/Admin.php';
class ProductController {
  public function handleRequest() {
    $productModel = new Product;
    $param = isset($_GET['param']) ? $_GET['param'] : NULL;
    try {
      if($param === 'save-product') {
        $user = new User();
        $user =  $user->getUserInfo(false);
        if(isset($_GET['id_product'])) {
          $this->saveProduct(
            $_GET['id_product'],
            $user['id_user'],
            $_GET['title'],
            $_GET['description'],
            $_GET['type'],
            $_GET['phone'],
            $_GET['price'],
            $_GET['show_profile'],
            $_GET['id_country'],
            $_GET['id_state'],
            $_GET['id_city'],
            $_GET['zip'],
            $_GET['images'],
          );
        } else {
          $this->saveProduct(
            null,
            $user['id_user'],
            $_GET['title'],
            $_GET['description'],
            $_GET['type'],
            $_GET['phone'],
            $_GET['price'],
            $_GET['show_profile'],
            $_GET['id_country'],
            $_GET['id_state'],
            $_GET['id_city'],
            $_GET['zip'],
            $_GET['images'],
          );
        }
      }
      if($param === 'get-products') {
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
        }
        if(isset($_GET['id_state'])) {
          $id_state = $_GET['id_state'];
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
        $products = $this->getProducts(
         $id_country, 
         $id_state,
         $id_city,
         $zip,
         $type,
         $prices,
         $limit,
         $skip
        );
        die(json_encode($products));
      }
      if($param === 'get-product') {
        $product = $productModel->getProduct($_GET['id_product']);
        die($product);
      }
    } catch ( Exception $e ) {
      return array("status" => "KO", "msg" => $e->getMessage());
    }
  }
  public function getProducts($id_country, $id_state, $id_city, $zip, $type, $prices, $limit, $skip) {
    try {
      $productModel = new Product;
      $params = [];
      if($id_country) {
        array_push($params, 'id_country = ' . $id_country);
      }
      if($id_state) {
        array_push($params, 'id_state = ' . $id_state);
      }
      if($id_city) {
        array_push($params, 'id_city = ' . $id_city);
      }
      if($zip) {
        array_push($params, 'zip = ' . $zip);
      }
      if($type) {
        array_push($params, 'type = ' . '"'.$type.'"');
      }
      if($prices) {
        array_push($params, 'price BETWEEN ' . str_replace(',', ' AND ', $prices));
      }
      if(count($params)) {
        $params = implode(' And ', $params);
      } else {
        $params = "";
      }
      if($limit) {
        $params .= " LIMIT " . $limit;
      }
      if($skip) {
        $params .= " OFFSET " . $skip;
      }
      $products = $productModel->getProductsList($params);
      return $products;
    } catch ( Exception $e ) {
      throw new Exception( "Problème de connexion avec le serveur");
    }
  }
  public function saveProduct(
    $id_product,
    $id_user,
    $title,
    $description,
    $type,
    $phone,
    $price,
    $show_profile,
    $id_country,
    $id_state,
    $id_city,
    $zip,
    $images
  ) {
    try {
      $productModel = new Product;
      if($images) {
        $images = explode("**", $images);
      } else {
        $images = [];
      }
      if($id_product) {
        $updatedProduct = $productModel->updateProduct(
          $id_user,
          $title,
          $description,
          $type,
          $phone,
          $price,
          $show_profile,
          $id_country,
          $id_state,
          $id_city,
          $zip,
          $images,
          $id_product
        );
        die(json_encode($updatedProduct));
      } else {
        $newProduct = $productModel->insertProduct(
          $id_user,
          $title,
          $description,
          $type,
          $phone,
          $price,
          $show_profile,
          $id_country,
          $id_state,
          $id_city,
          $zip,
          $images,
        );
        die(json_encode($newProduct));
      }
    } catch ( Exception $e ) {
      throw new Exception( "Problème de connexion avec le serveur");
    }
  }
}