class Home {
  constructor() {
    this.request = new Request();
  };
  initHome() {
    if(document.getElementById("opened")) {
      document.getElementById("opened").click();
    }
    var self = this;
    var searchAreaBtn = document.getElementById("bloc-search-area-btn")
    if(searchAreaBtn) {
      searchAreaBtn.addEventListener("click", function(e) {
        self.executeSearch()
      })
    }
    this.handleZoneSelector();
  }
  handleZoneSelector() {
    var self = this;
    var countrySelector = document.getElementById("country-selector")
    var stateSelector = document.getElementById("state-selector")
    var citySelector = document.getElementById("city-selector")
    if(countrySelector && stateSelector) {
      countrySelector.addEventListener("change", function(e) {
        stateSelector.disabled = true;
        stateSelector.innerHTML = '<option value="">Selectionner une Etat</option>'
        if(citySelector){
          citySelector.disabled = true;
          citySelector.innerHTML = '<option value="">Selectionner une Ville</option>'
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
        citySelector.innerHTML = '<option value="">Selectionner une Ville</option>'
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
    var addStateButton = document.getElementById("add-state-button")
    if(addStateButton) {
      addStateButton.addEventListener('click', function(e) {
        self.saveState();
      })
    }
    var editStateButton = document.getElementById("edit-state-button")
    if(editStateButton) {
      editStateButton.addEventListener('click', function(e) {
        self.saveState(e.target.attributes["data-state"]["value"]);
      })
    }
    var addCityButton = document.getElementById("add-city-button")
    if(addCityButton) {
      addCityButton.addEventListener('click', function(e) {
        self.saveCity();
      })
    }
    var editCityButton = document.getElementById("edit-city-button")
    if(editCityButton) {
      editCityButton.addEventListener('click', function(e) {
        self.saveCity(e.target.attributes["data-city"]["value"]);
      })
    }
  }
  executeSearch() {
    var countrySelector = document.getElementById("country-selector");
    var stateSelector = document.getElementById("state-selector");
    var citySelector = document.getElementById("city-selector");
    var country = countrySelector.value;
    var state = stateSelector.value;
    var city = citySelector.value;
    if(country && state && city ) {
      window.location.href = "?page=products&id_country=" + country + "&id_state=" + state +  "&id_city=" + city;
    } else {
      alert('selectionner une localisation')
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
  saveState(id_state) {
    var nameInput = document.querySelector('#save-state input[name="name"]')
    var idCountrySelect = document.querySelector('#save-state select[name="country"]')
    var name = nameInput.value
    var idCountry = idCountrySelect.value
    var hasErrors = false;
    if(!name) {
      hasErrors = true
      var nameError = document.getElementById('name-error')
      nameError.innerHTML = "<span> Ce champ est obligatoire </span>"
      nameInput.classList.add("invalid")
    }
    if(!idCountry) {
      hasErrors = true
      var idCountryError = document.getElementById('id_country-error')
      idCountryError.innerHTML =  "<span> Ce champ est obligatoire </span>"
      idCountrySelect.classList.add("invalid")
    }
    var imagesList = document.querySelectorAll('#save-state #images-container-state .image-container img')
    var images = [];
    for (var index = 0; index < imagesList.length; index++) {
      var image = imagesList[index];
      images.push(image.getAttribute("src"));
    }
    var imagesStr = images.join("**");
    if(!hasErrors) {
      var loader = document.getElementById("modal-loader")
      loader.style.display = "flex";
      var savedState = null
      if(id_state) {
        savedState = this.request.postRequest('locations', 'param=save-state&id_state=' + id_state +'&name=' + name + '&id_country=' + idCountry + '&images=' + imagesStr);
      } else {
        savedState = this.request.postRequest('locations', 'param=save-state&name=' + name + '&id_country=' + idCountry + '&images=' + imagesStr);
      }
      if(JSON.parse(savedState).status === "KO"){
        alert(JSON.parse(savedState).msg)
      }
      window.location.href = "/?page=admin&param=localisations"
    }
  }
  saveCity(id_city) {
    var nameInput = document.querySelector('#save-city input[name="name"]')
    var idCountrySelect = document.querySelector('#save-city select[name="country"]')
    var idStateSelect = document.querySelector('#save-city select[name="state"]')
    var name = nameInput.value
    var idCountry = idCountrySelect.value
    var idState = idStateSelect.value
    var hasErrors = false;
    if(!name) {
      hasErrors = true
      var nameError = document.getElementById('name-error')
      nameError.innerHTML = "<span> Ce champ est obligatoire </span>"
      nameInput.classList.add("invalid")
    }
    if(!idCountry) {
      hasErrors = true
      var idCountryError = document.getElementById('id_country-error')
      idCountryError.innerHTML = "<span>Selectionner une pay pour choisie l'etat</span>"
      idCountrySelect.classList.add("invalid")
    }
    if(!idState) {
      hasErrors = true
      var idStateError = document.getElementById('id_state-error')
      idStateError.innerHTML = "<span> Ce champ est obligatoire </span>"
      idStateSelect.classList.add("invalid")
    }
    var imagesList = document.querySelectorAll('#save-city #images-container-city .image-container img')
    var images = [];
    for (var index = 0; index < imagesList.length; index++) {
      var image = imagesList[index];
      images.push(image.getAttribute("src"));
    }
    var imagesStr = images.join("**")
    if(!hasErrors) {
      var loader = document.getElementById("modal-loader")
      loader.style.display = "flex";
      var savedCity = null
      if(id_city) {
        savedCity = this.request.postRequest('locations', 'param=save-city&id_city=' + id_city +'&name=' + name + '&id_state=' + idState + '&images=' + imagesStr);
      } else {
        savedCity = this.request.postRequest('locations', 'param=save-city&name=' + name + '&id_state=' + idState + '&images=' + imagesStr);
      }
      if(JSON.parse(savedCity).status === "KO"){
        alert(JSON.parse(savedCity).msg)
      }
      window.location.href = "/?page=admin&param=localisations"
    }
  }
}