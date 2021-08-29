


//captcha refresh code
document.addEventListener("DOMContentLoaded", function(event) { 
  if (captcha_required = true) {
    if (document.getElementById("captcha")) {
    var refreshButton = document.querySelector("#captcha");
      refreshButton.onclick = function() {
        document.querySelector("#captcha").src = install_location + '/includes/captcha.php?' + Date.now();
      }
    }

  }
});

//theme selector
document.addEventListener("DOMContentLoaded", function(event) {
    if (localStorage.theme == undefined) {
      localStorage.theme = default_theme;
    }
    if (document.getElementById("themes") != undefined) {
      document.getElementById("themes").onchange = function() {
        localStorage.theme = document.getElementById("themes").value;
        document.documentElement.setAttribute("data-stylesheet", document.getElementById("themes").value);
      }
      document.getElementById("themes").value = localStorage.theme;
    }
    document.documentElement.setAttribute("data-stylesheet", localStorage.theme);
});

document.addEventListener("DOMContentLoaded", function(event) {
  if (document.getElementById("upload")) {
    document.getElementById("upload").setAttribute("title", "Shift + Left Click to remove file.");
    document.getElementById("upload").addEventListener("click",
      function(event) {
        if (event.shiftKey) {
          document.getElementById("upload").value = '';
          event.preventDefault();
        }
      },
      false);
  }
});