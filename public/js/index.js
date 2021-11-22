const user = new User()
const admin = new Admin()
document.addEventListener("DOMContentLoaded", function(event) { 
  user.initConnexion()
  admin.initAdmin()
  if(document.getElementById("opened")) {
    document.getElementById("opened").click();
  }
});