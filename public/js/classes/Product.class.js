class Product {
  constructor() {
    this.images = [];
    this.request = new Request();
  };
  initProduct() {
    var self = this;
    var addProductButton = document.querySelector('#save-product #add-product-button');
    if(addProductButton) {
      addProductButton.addEventListener('click', function(e) {
        self.saveProduct(null)
      });
    }
    var editProductButton = document.querySelector('#save-product #edit-product-button');
    if(editProductButton) {
      editProductButton.addEventListener('click', function(e) {
        self.saveProduct(e.target.attributes["data-product"]["value"]);
      });
    }
    var btnFilter = document.getElementById('btn-filter')
    if(btnFilter) {
      btnFilter.addEventListener('click', function(e) {
        self.filterProducts();
      });
    }
    self.priceRange()
  }
  priceRange() {
    var parent = document.querySelector('.range-slider');
    if (!parent) {
      return;
    }
    var rangeInputs = parent.querySelectorAll('input[type="range"]');
    var prices = parent.querySelectorAll('input[type="number"]');
    rangeInputs.forEach((element) => {
      element.addEventListener('input', function() {
        var slide1 = parseFloat(rangeInputs[0].value);
        var slide2 = parseFloat(rangeInputs[1].value);
        if (slide1 > slide2) {
          [slide1, slide2] = [slide2, slide1];
        }
        prices[0].value = slide1;
        prices[1].value = slide2;
      })
    });
    prices.forEach((element) => {
      element.addEventListener('input', function() {
        var price1 = parseFloat(prices[0].value);
        var price2 = parseFloat(prices[1].value);
        if (price1 > price2) {
          let price = price1;
          prices[0].value = price2;
          prices[1].value = price;
        }
        rangeInputs[0].value = price1;
        rangeInputs[1].value = price2;
      })
    })
  }
  saveProduct(id_product) {
    var titleInput = document.querySelector('#save-product input[name="title"]')
    var descriptionInput = document.querySelector('#save-product textarea[name="description"]')
    var phoneInput = document.querySelector('#save-product input[name="phone"]')
    var priceInput = document.querySelector('#save-product input[name="price"]')
    var idCountrySelect = document.querySelector('#save-product select[name="country"]')
    var idStateSelect = document.querySelector('#save-product select[name="state"]')
    var idCitySelect = document.querySelector('#save-product select[name="city"]')
    var showProfileInput = document.querySelector('#save-product input[name="show-profile"]')
    var typeInput = document.querySelector('#save-product input[name="type"]:checked')
    var zipInput = document.querySelector('#save-product input[name="zip"]')
    let allSelect = document.querySelectorAll("#save-product select");
    var title = titleInput.value
    var description = descriptionInput.value
    var phone = phoneInput.value;
    var price = priceInput.value;
    var idCountry = idCountrySelect.value;
    var idState = idStateSelect.value;
    var idCity = idCitySelect.value;
    var showProfile = showProfileInput.checked;
    var zip = zipInput.value;
    var type = typeInput.value;
    var hasErrors = false;
    if(!title) {
      hasErrors = true;
      var titleError = document.getElementById('title-error');
      titleError.innerHTML = "<span> Ce champ est obligatoire </span>";
      titleInput.classList.add("invalid");
    }
    if(!phone) {
      hasErrors = true;
      var phoneError = document.getElementById('phone-error');
      phoneError.innerHTML = "<span> Ce champ est obligatoire </span>";
      phoneInput.classList.add("invalid");
    }
    var locationAlert = document.getElementById("location-alert");
    if(!idCountry && !zip) {
      hasErrors = true;
      locationAlert.innerHTML = "Selectionner la localisation ou le code postale de votre annonce";
      locationAlert.classList.add('p-2');
    }
    allSelect.forEach(function(select) {
      select.addEventListener("change", function() {
        locationAlert.innerHTML = "";
        locationAlert.classList.remove('p-2');
      });
    });
    zipInput.addEventListener("keyup", function() {
      locationAlert.innerHTML = "";
      locationAlert.classList.remove('p-2');
    })
    var imagesList = document.querySelectorAll('#save-product #images-container .image-container img')
    var images = [];
    for (var index = 0; index < imagesList.length; index++) {
      var image = imagesList[index];
      images.push(image.getAttribute("src"));
    }
    if(showProfile) {
      showProfile = 1
    } else {
      showProfile = 0
    }
    var imagesStr = images.join("**")
    console.log({type});
    if(!hasErrors) {
      var loader = document.getElementById("modal-loader")
      loader.style.display = "flex";
      var savedProduct = null
      if(id_product) {
        savedProduct = this.request.postRequest('product', 'param=save-product&id_product=' + id_product +'&title=' + title + '&description=' + description + '&phone=' + phone + '&price=' + price + '&id_state=' + idState + '&id_country=' + idCountry + '&id_state=' + idState + '&id_city=' + idCity + '&zip=' + zip + '&type=' + type + '&show_profile=' + showProfile + '&images=' + imagesStr);
      } else {
        savedProduct = this.request.postRequest('product', 'param=save-product&title=' + title + '&description=' + description + '&phone=' + phone + '&price=' + price + '&id_state=' + idState + '&id_country=' + idCountry + '&id_state=' + idState + '&id_city=' + idCity + '&zip=' + zip + '&type=' + type + '&show_profile=' + showProfile + '&images=' + imagesStr);
      }
      if(JSON.parse(savedProduct).status === "KO"){
        alert(JSON.parse(savedProduct).msg)
      }
      window.location.href = "/?page=admin&param=products"
    }
  }
  filterProducts() {
    var countrySelector = document.getElementById("country-selector");
    var stateSelector = document.getElementById("state-selector");
    var citySelector = document.getElementById("city-selector");
    var typeInput = document.querySelector('#sidebar-filter #sidebar-filter-container input[name="type"]:checked');
    var zipInput = document.querySelector('#sidebar-filter #sidebar-filter-container input[name="zip"]');
    var priceMinInput = document.querySelector('#sidebar-filter #sidebar-filter-container input[name="price-min"]');
    var priceMaxInput = document.querySelector('#sidebar-filter #sidebar-filter-container input[name="price-max"]');
    var id_country = countrySelector.value;
    var id_state = stateSelector.value;
    var id_city = citySelector.value;
    var zip = zipInput.value;
    var type = null;
    if(typeInput) {
      var type = typeInput.value;
    }
    var priceMin = priceMinInput.value;
    var priceMax = priceMaxInput.value;
    var min = priceMinInput.getAttribute("min");
    var max = priceMaxInput.getAttribute("max");
    var params = [];
    if(id_country) {
      params.push("id_country=" + id_country);
    }
    if(id_state) {
      params.push("id_state=" + id_state);
    }
    if(id_city) {
      params.push("id_city=" + id_city);
    }
    if(zip) {
      params.push("zip=" + zip);
    }
    if(type) {
      params.push("type=" + type);
    }
    if((priceMin != min) || (priceMax != max)) {
      params.push("prices=" + priceMin + "," + priceMax);
    }
    if(params.join("&") !== window.location.search.replace("?page=products&", "")) {
      let products = this.request.getRequest('product', 'param=get-products&' + params.join("&"));
      
    }
  }
}