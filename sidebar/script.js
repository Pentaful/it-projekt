function openNav() {
    document.getElementById("main-sidebar").getElementsByClassName("sidebar")[0].style.width = "13%";
  }
  
  function closeNav() {
    document.getElementById("main-sidebar").getElementsByClassName("sidebar")[0].style.width = "8%";
  }
  
  document.addEventListener("DOMContentLoaded", function () {
    console.log("Dokument geladen");
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "/sidebar/sidebar.html", true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4) {
        // 200: OK
        if (xhr.status == 200) {
          console.log("Sidebar geladen");
          document.getElementById("sidebarContainer").innerHTML = xhr.responseText;
        } else { //Alles andere: Fehler
          console.error("Fehler beim Laden der Sidebar. Status: " + xhr.status);
        }
      }
    };
    xhr.send(); 
  });
  