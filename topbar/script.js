  document.addEventListener("DOMContentLoaded", function () {
    console.log("Document loaded");
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "/topbar/topbar.html", true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4) {
        if (xhr.status == 200) {
          console.log("Topbar content loaded successfully");
          document.getElementById("topbarContainer").innerHTML = xhr.responseText;
        } else {
          console.error("Error loading topbar content. Status: " + xhr.status);
        }
      }
    };
    xhr.send(); 
  });
  