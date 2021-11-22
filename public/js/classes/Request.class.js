 
class Request {
  constructor() {
    this.url = window.location.protocol + "//" + window.location.host;
  };
  getRequest(page, params) {
    let xmlHttp = new XMLHttpRequest();
    var urlToSend = this.url + "?page=" + page + "&" + params
    xmlHttp.open( "GET", urlToSend, false ); // false for synchronous request
    xmlHttp.send( null );
    return xmlHttp.responseText; 
  }
  postRequest(page, params) {
    let xmlHttp = new XMLHttpRequest();
    var urlToSend = this.url + "?page=" + page + "&" + params
    xmlHttp.open( "POST", urlToSend, false ); // false for synchronous request
    xmlHttp.send( null );
    return xmlHttp.responseText; 
  }
  uploadRequest(e) {
    
  }
  emailValidator(email) {
    let errors = [];
    var reg = /^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/
    if(!email) {
      errors.push("Le champ email est requis")
    }
    if(!reg.test(email)) {
      errors.push("Le format de votre adresse email n'est pas validé")
    }
    if(!errors.length) {
      return {status: true}
    } else {
      return {status: false, errors: errors}
    }
  }
  passwordValidator(password) {
    let errors = [];
    if(!password) {
      errors.push("Le champ mot de passe est requis")
    }
    if(password.length < 8) {
      errors.push("Votre mot de passe est très courte")
    }
    if(!errors.length) {
      return {status: true}
    } else {
      return {status: false, errors: errors}
    }
  }
  nameValidator(name, label) {
    let errors = [];
    var reg = /^[a-z ,.'-]+$/ig
    if(!name) {
      errors.push("Le champ " + label + " est requis")
    }
    if(typeof name !== "string" ) {
      errors.push("Le champ " + label + " doit être une chaine de charactère")
    }
    if(!reg.test(name)) {
      errors.push("Le format de votre "+ label + " n'est pas validé")
    }
    if(!errors.length) {
      return {status: true}
    } else {
      return {status: false, errors: errors}
    }
  }
  phoneValidator(phone) {
    let errors = [];
    if(!phone) {
      errors.push("Le champ numéro de téléphone est requis")
    }
    if(isNaN(phone)) {
      errors.push("Le champ numéro de téléphone doit être seulement des chiffres")
    }
    if(!errors.length) {
      return {status: true}
    } else {
      return {status: false, errors: errors}
    }
  }
}