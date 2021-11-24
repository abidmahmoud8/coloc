const user = new User()
const admin = new Admin()
const product = new Product()
document.addEventListener("DOMContentLoaded", function(event) { 
  user.initConnexion();
  admin.initAdmin();
  product.initProduct();
  if(document.getElementById("opened")) {
    document.getElementById("opened").click();
  }
});