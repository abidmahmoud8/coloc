class Image {
  constructor() {
    this.images = [];
  };
  initImage() {
    var self = this;
    var imgUploads = document.getElementsByClassName('image-uploads');
    if(imgUploads && imgUploads.length) {
      for (var index = 0; index < imgUploads.length; index++) {
        var imgUpload = imgUploads[index];
        imgUpload.addEventListener('change', function(e) {
          var returnedData = uploadRequest(e);
          let imageContainer = 'images-container'
          if(e.target.attributes["data-container"] && e.target.attributes["data-container"]["value"]) {
            imageContainer = e.target.attributes["data-container"]["value"]
          }
          self.uploadImages(JSON.parse(returnedData), self, imageContainer);
        });
      }
    }
    self.deleteImages()
  }
  deleteImages() {
    var deleteImagesButton = document.getElementsByClassName('image-delete')
    if(deleteImagesButton && deleteImagesButton.length) {
      for (var index = 0; index < deleteImagesButton.length; index++) {
        var deleteImage = deleteImagesButton[index];
        deleteImage.addEventListener("click", function(e) {
          this.parentNode.parentNode.removeChild(this.parentNode);
        })
      }
    }
  }
  uploadImages(returnedData, self, imageContainer) {
    if(returnedData) {
      if(returnedData.errors && returnedData.errors.length) {
        alert(returnedData.errors.join('\n'));
      }
      if(returnedData.uploadedFiles && returnedData.uploadedFiles.length) {
        var imagesContainer = document.getElementById(imageContainer)
        for (var index = 0; index < returnedData.uploadedFiles.length; index++) {
          var imageContainer = document.createElement('div');
          var imageUrl = returnedData.uploadedFiles[index];
          imageContainer.classList.add('image-container');
          imageContainer.innerHTML = '<img src="' + imageUrl + '" alt=""/><span data-image="' + imageUrl +'" class="image-delete">X</span>';
          imagesContainer.append(imageContainer);
          self.images.push(imageUrl);
        }
      }
      self.deleteImages()
    } else {
      alert("Problème de connexion avec le serveur");
    }
  }
}