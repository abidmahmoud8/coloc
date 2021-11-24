class User {
  constructor() {
    this.request = new Request();
  };
  register() {
    try {
      var lastNameInput = document.querySelector('#register input[name="lastname"]')
      var firstNameInput = document.querySelector('#register input[name="firstname"]')
      var emailInput = document.querySelector('#register input[name="register-email"]')
      var passwordInput = document.querySelector('#register input[name="register-password"]')
      var phoneInput = document.querySelector('#register input[name="phone"]')
      var lastName = lastNameInput.value
      var firstName = firstNameInput.value
      var email = emailInput.value
      var password = passwordInput.value
      var phone = phoneInput.value
      var hasErrors = false;
      var validateLastName = this.request.nameValidator(lastName, 'Nom')
      if(!validateLastName.status) {
        hasErrors = true
        var lastNameError = document.getElementById('lastname-error')
        lastNameError.innerHTML = "<span>" + validateLastName.errors.join('<br/>') + "</span>"
      }
      var validateFirstName = this.request.nameValidator(firstName, 'Prénom')
      if(!validateFirstName.status) {
        hasErrors = true
        var firstNameError = document.getElementById('firstname-error')
        firstNameError.innerHTML = "<span>" + validateFirstName.errors.join('<br/>') + "</span>"
      }
      var validatePassword = this.request.passwordValidator(password)
      if(!validatePassword.status) {
        hasErrors = true
      }
      var validateEmail = this.request.emailValidator(email)
      if(!validateEmail.status) {
        hasErrors = true;
        var emailError = document.getElementById('register-email-error');
        emailError.innerHTML = "<span>" + validateEmail.errors.join('<br/>') + "</span>";
      }
      var validatePhone = this.request.phoneValidator(phone)
      if(!validatePhone.status) {
        hasErrors = true;
        var phoneError = document.getElementById('phone-error');
        phoneError.innerHTML = "<span>" + validatePhone.errors.join('<br/>') + "</span>";
      }
      var validatePassword = this.request.passwordValidator(password)
      if(!validatePassword.status) {
        hasErrors = true
        var passwordError = document.getElementById('register-password-error');
        passwordError.innerHTML = "<span>" + validatePassword.errors.join('<br/>') + "</span>";
      }
      if(!hasErrors) {
        let user = this.request.postRequest('connexion', "param=register&first_name=" + firstName + "&last_name=" + lastName + "&email=" + email + "&password=" + password + "&phone=" + phone);
        if(JSON.parse(user).status === "OK") {
          window.location.href = "/"
        } else if(JSON.parse(user).status === "KO"){
          var alert = document.getElementById("connexion-alert")
          alert.innerHTML = JSON.parse(user).msg
        }
      }
    } catch (error) {
      console.log({error});
      var alert = document.getElementById("connexion-alert")
      alert.innerHTML = "Problème de connexion avec le serveur"
    }
  }
  login() {
    try {
      var emailInput = document.querySelector('#connect input[name="email"]')
      var passwordInput = document.querySelector('#connect input[name="password"]')
      var email = emailInput.value
      var password = passwordInput.value
      var hasErrors = false;
      var validateEmail = this.request.emailValidator(email)
      if(!validateEmail.status) {
        hasErrors = true;
        var emailError = document.getElementById('email-error')
        emailError.innerHTML = "<span>" + validateEmail.errors.join('<br/>') + "</span>"
      }
      var validatePassword = this.request.passwordValidator(password)
      if(!validatePassword.status) {
        hasErrors = true
        var passwordError = document.getElementById('password-error')
        passwordError.innerHTML = "<span>" + validatePassword.errors.join('<br/>') + "</span>"
      }
      if(!hasErrors) {
        let user = this.request.postRequest('connexion', "param=login&email=" + email + "&password=" + password);
        if(JSON.parse(user).status === "OK") {
          window.location.href = "/"
        } else if(JSON.parse(user).status === "KO"){
          var alert = document.getElementById("connexion-alert")
          alert.innerHTML = JSON.parse(user).msg
        }
      }
    } catch (error) {
      var alert = document.getElementById("connexion-alert")
      alert.innerHTML = "Problème de connexion avec le serveur"
    }
  }
  initConnexion() {
    var self = this;
    let loginForm = document.querySelector('#connect > form');
    if(loginForm) {
      loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        self.login()
      });
    }
    let registerForm = document.querySelector('#register > form');
    if(registerForm) {
      registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        self.register()
      });
    }
    let inputs = document.querySelectorAll('input')
    if(inputs && inputs.length) {
      inputs.forEach(input => {
        input.addEventListener('keyup', function(e) {
          var alert = document.getElementById("connexion-alert")
          alert.innerHTML = ""
          input.nextElementSibling.innerHTML = "";
        });
      });
    }
  }
}