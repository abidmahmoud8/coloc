class User {
  constructor() {
    this.request = new Request();
  };
  register() {
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
      console.log(user);
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
        if(JSON.parse(user).status) {
          
        };

      }
    } catch (error) {
      console.log(error);
    }
  }
  initConnexion() {
    var user = new User();
    let loginForm = document.querySelector('#connect > form');
    loginForm.addEventListener('submit', function(e) {
      e.preventDefault();
      user.login()
    });
    let registerForm = document.querySelector('#register > form');
    registerForm.addEventListener('submit', function(e) {
      e.preventDefault();
      user.register()
    });
    let inputs = document.querySelectorAll('input')
    inputs.forEach(input => {
      input.addEventListener('keyup', function(e) {
        var alert = document.getElementById("connexion-alert")
        alert.innerHTML = ""
        input.nextElementSibling.innerHTML = "";
      });
    });
  }
}