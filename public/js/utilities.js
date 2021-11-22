function openTab(evt, tab) {
  var tabs = document.getElementsByClassName("tabcontent");
  for (var i = 0; i < tabs.length; i++) {
    tabs[i].style.display = "none";
  }
  var tablinks = document.getElementsByClassName("tablinks");
  for (var i = 0; i < tablinks.length; i++) {
    tablinks[i].classList.remove("active");
  }
  if(tab){
    document.getElementById(tab).style.display = "block";
    evt.target.classList.add("active");
  }
}
function toggleSidebar() {
  var sidebar = document.getElementById("sidebar")
  sidebar.classList.toggle("opened-sidebar");
  var toggleBtn = document.getElementById("toggle-sidebar")
  toggleBtn.classList.toggle("opened-btn");
  var openBtn = document.getElementById("open-sidebar")
  openBtn.classList.toggle("d-none");
  var closeBtn = document.getElementById("close-sidebar")
  closeBtn.classList.toggle("d-none");
}
function openModal(idModal) {
  var modal = document.getElementById(idModal);
  modal.style.display = "block";
  window.onclick = function(event) {
    console.log();
    if (event.target == modal || (event.target.classList && event.target.classList.contains("close"))) {
      modal.style.display = "none";
    }
  }
}
function uploadRequest(e) {
  var url = window.location.protocol + "//" + window.location.host;
  var urlToSend = url + "?page=upload";
  var xmlHttp = new XMLHttpRequest();
  var formData = new FormData();
  var files = e.target.files;
  for (let index = 0; index < files.length; index++) {
    const file = files[index];
    formData.append("file-" + index, file);
  }
  xmlHttp.open( "POST", urlToSend, false ); // false for synchronous request
  // xmlHttp.setRequestHeader("Content-type", "multipart/form-data");
  xmlHttp.send( formData );
  return xmlHttp.responseText; 
}