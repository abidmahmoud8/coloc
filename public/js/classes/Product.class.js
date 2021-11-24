class Product {
  constructor() {
    this.images = [];
  };
  initProduct() {
    var self = this;
    var imgUploads = document.querySelector('#add-product > .form-container > #image-uploads');
    if(imgUploads) {
      imgUploads.addEventListener('change', function(e) {
        var returnedData = uploadRequest(e);
        self.uploadImages(JSON.parse(returnedData), self);
      });
      var addProductButton = document.querySelector('#add-product #add-product-button');
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
  uploadImages(returnedData, self) {
    if(returnedData) {
      if(returnedData.errors && returnedData.errors.length) {
        alert(returnedData.errors.join('\n'));
      }
      if(returnedData.uploadedFiles && returnedData.uploadedFiles.length) {
        var imagesContainer = document.getElementById("images-container")
        for (var index = 0; index < returnedData.uploadedFiles.length; index++) {
          var imageContainer = document.createElement('div');
          var imageUrl = returnedData.uploadedFiles[index];
          imageContainer.classList.add('image-container');
          imageContainer.innerHTML = '<img src="' + imageUrl + '" alt=""/><span class="image-delete">X</span>';
          imagesContainer.append(imageContainer);
          self.images.push(imageUrl);
        }
      }
    } else {
      alert("Problème de connexion avec le serveur");
    }
  }
}