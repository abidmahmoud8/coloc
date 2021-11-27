class Home {
  constructor() {
    this.request = new Request();
  };
  initHome() {
    if(document.getElementById("opened")) {
      document.getElementById("opened").click();
    }
    this.handleZoneSelector()
  }
  handleZoneSelector() {
    var self = this;
    var countrySelector = document.getElementById("country-selector")
    var stateSelector = document.getElementById("state-selector")
    var citySelector = document.getElementById("city-selector")
    if(countrySelector && stateSelector) {
      countrySelector.addEventListener("change", function(e) {
        stateSelector.disabled = true;
        stateSelector.innerHTML = '<option>Selectionner une Etat</option>'
        if(citySelector){
          citySelector.disabled = true;
          citySelector.innerHTML = '<option>Selectionner une Ville</option>'
        }
        let states = self.request.getRequest('locations', "param=states&id_country=" + e.target.value);
        if(states && JSON.parse(states).length) {
          states = JSON.parse(states);
          stateSelector.disabled = false;
          for (let index = 0; index < states.length; index++) {
            const state = states[index];
            stateSelector.innerHTML = stateSelector.innerHTML + '<option value = "' +  state.id_state + '">' + state.name + '</option>';
          }
        } else {
          stateSelector.disabled = true;
        }
      })
    }
    if(countrySelector && stateSelector && citySelector) {
      stateSelector.addEventListener("change", function(e) {
        citySelector.disabled = true;
        citySelector.innerHTML = '<option>Selectionner une Ville</option>'
        let cities = self.request.getRequest('locations', "param=cities&id_state=" + e.target.value);
        if(cities && JSON.parse(cities).length) {
          cities = JSON.parse(cities);
          citySelector.disabled = false;
          for (let index = 0; index < cities.length; index++) {
            const city = cities[index];
            citySelector.innerHTML = citySelector.innerHTML + '<option value = "' +  city.id_city + '">' + city.name + '</option>';
          }
        } else {
          citySelector.disabled = true;
        }
      })
    }
    var addCountryButton = document.getElementById("add-country-button")
    if(addCountryButton) {
      addCountryButton.addEventListener('click', function(e) {
        self.saveCountry();
      })
    }
    var editCountryButton = document.getElementById("edit-country-button")
    if(editCountryButton) {
      editCountryButton.addEventListener('click', function(e) {
        self.saveCountry(e.target.attributes["data-country"]["value"]);
      })
    }
  }
  saveCountry(id_country) {
    var nameInput = document.querySelector('#save-country input[name="name"]')
    var isoCodeInput = document.querySelector('#save-country input[name="iso_code"]')
    var currencyInput = document.querySelector('#save-country input[name="currency"]')
    var name = nameInput.value
    var isoCode = isoCodeInput.value
    var currency = currencyInput.value
    var hasErrors = false;
    if(!name) {
      hasErrors = true
      var nameError = document.getElementById('name-error')
      nameError.innerHTML = "<span> Ce champ est obligatoire </span>"
      nameInput.classList.add("invalid")
    }
    if(!isoCode) {
      hasErrors = true
      var isoCodeError = document.getElementById('iso_code-error')
      isoCodeError.innerHTML =  "<span> Ce champ est obligatoire </span>"
      isoCodeInput.classList.add("invalid")
    }
    if(!currency) {
      hasErrors = true
      var currencyError = document.getElementById('currency-error')
      currencyError.innerHTML =  "<span> Ce champ est obligatoire </span>"
      currencyInput.classList.add("invalid")
    }
    var imagesList = document.querySelectorAll('#save-country #images-container-country .image-container img')
    var images = [];
    for (var index = 0; index < imagesList.length; index++) {
      var image = imagesList[index];
      images.push(image.getAttribute("src"));
    }
    var imagesStr = images.join("**")
    if(!hasErrors) {
      var loader = document.getElementById("modal-loader")
      loader.style.display = "flex";
      var savedCountry = null
      if(id_country) {
        savedCountry = this.request.postRequest('locations', 'param=save-country&id_country=' + id_country +'&name=' + name + '&iso_code=' + isoCode + '&currency=' + currency + '&images=' + imagesStr);
      } else {
        savedCountry = this.request.postRequest('locations', 'param=save-country&name=' + name + '&iso_code=' + isoCode + '&currency=' + currency + '&images=' + imagesStr);
      }
      if(JSON.parse(savedCountry).status === "KO"){
        alert(JSON.parse(savedCountry).msg)
      }
      window.location.href = "/?page=admin&param=localisations"
    }
  }
}