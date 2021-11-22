class Admin {
  constructor() {
    this.request = new Request();
  };
  initAdmin() {
    let deleteBtns = document.querySelectorAll('#users > table > tbody > tr > td > button.btn-danger')
    for (let i = 0; i < deleteBtns.length; i++){
      deleteBtns[i].addEventListener('click', function(e) {
        console.log(e.target.attributes["data-user"]["value"])
      });
    }
    let editBtns = document.querySelectorAll('#users > table > tbody > tr > td > button.btn-success')
    for (let i = 0; i < editBtns.length; i++){
      editBtns[i].addEventListener('click', function(e) {
        openModal('modal-' + e.target.attributes["data-user"]["value"])
      });
    }
    let imgUploads = document.querySelectorAll('#add-product > .form-container > #image-uploads')
    for (let i = 0; i < imgUploads.length; i++){
      imgUploads[i].addEventListener('change', function(e) {
        let returnedData = uploadRequest(e)
        console.log(returnedData);
      });
    }
  }
}