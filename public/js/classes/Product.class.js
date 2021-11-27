class Product {
  constructor() {
    this.images = [];
  };
  initProduct() {
    var self = this;
    var addProductButton = document.querySelector('#add-product #add-product-button');
    if(addProductButton) {
      addProductButton.addEventListener('click', function(e) {
        self.addProduct()
      });
    }
  }
  addProduct() {
    var titleInput = document.querySelector('#add-product input[name="title"]')
    var descriptionInput = document.querySelector('#add-product textarea[name="description"]')
    var phoneInput = document.querySelector('#add-product input[name="phone"]')
    var priceInput = document.querySelector('#add-product input[name="price"]')
    var title = titleInput.value
    var description = descriptionInput.value
    var phone = phoneInput.value
    var price = priceInput.value
    var hasErrors = false;
    if(!title) {
      hasErrors = true
      var titleError = document.getElementById('title-error')
      titleError.innerHTML = "<span> Ce champ est obligatoire </span>"
      titleInput.classList.add("invalid")
    }
  }
}