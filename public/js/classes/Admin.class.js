class Admin {
  constructor() {
    this.request = new Request();
  };
  initAdmin() {
    let self = this
    let deleteBtns = document.querySelectorAll('table > tbody > tr > td > button.btn-danger')
    for (let i = 0; i < deleteBtns.length; i++) {
      deleteBtns[i].addEventListener('click', function(e) {
        if(e.target.attributes["data-info"]) {
          let itemInfo = JSON.parse(e.target.attributes["data-info"]["value"])
          if(confirm("êtes-vous sûr de vouloir supprimer ce " + itemInfo.label)) {
            var db = itemInfo.db;
            var id = itemInfo.id;
            var value = itemInfo.value;
            let deletedItem = self.request.deleteRequest('admin', "param=delete&db=" + db + "&id=" + id + "&value=" + value);
            if(JSON.parse(deletedItem).status === "OK") {
              this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);
            } else if(JSON.parse(deletedItem).status === "KO") {
              alert("Problème de connexion avec le serveur");
            }
          }
        }
      });
    }
    let editBtns = document.querySelectorAll('table > tbody > tr > td > button.btn-success')
    for (let i = 0; i < editBtns.length; i++) {
      editBtns[i].addEventListener('click', function(e) {
        openModal('modal-' + e.target.attributes["data-modal"]["value"])
      });
    }
  }
}