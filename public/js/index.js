const user = new User()
const admin = new Admin()
const product = new Product()
const home = new Home()
const image = new Image()
document.addEventListener("DOMContentLoaded", function(event) { 
  user.initConnexion();
  admin.initAdmin();
  product.initProduct();
  home.initHome()
  image.initImage()
});