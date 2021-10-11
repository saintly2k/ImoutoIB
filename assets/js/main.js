


//captcha refresh code
document.addEventListener("DOMContentLoaded", function(event) { 
  if (captcha_required = true) {
    if (document.getElementById("captcha")) {
    var refreshButton = document.querySelector("#captcha");
      refreshButton.onclick = function() {
        document.querySelector("#captcha").src = install_location + '/captcha.php?' + Date.now();
        document.querySelector("#captcha-field").value = '';
      }
    }

  }
});

//theme selector
if (board_type != 'txt') {
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
} else {
  document.addEventListener("DOMContentLoaded", function(event) {
      if (localStorage.text_theme == undefined) {
        localStorage.text_theme = default_theme; //look header.html for it changing
      }
      if (document.getElementById("themes") != undefined) {
        document.getElementById("themes").onchange = function() {
          localStorage.text_theme = document.getElementById("themes").value;
          document.documentElement.setAttribute("data-stylesheet", document.getElementById("themes").value);
        }
        document.getElementById("themes").value = localStorage.text_theme;
      }
      document.documentElement.setAttribute("data-stylesheet", localStorage.text_theme);
  });
}



//shift-click on upload to remove file
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

//post quoting
document.addEventListener("DOMContentLoaded", function(event) { 
  if (document.querySelector('body.thread')) { //Only allow post-quoting if thread is open.
      //cite number + text if selected
      function cite(id) {
          const textArea = document.getElementById('body');
          if (!textArea) {
              return false;
          }  
              document.getElementById('post-form').scrollIntoView();
              textArea.value += `>>${id}\n`;
              const selection = window.getSelection().toString();
          if (selection) {
              textArea.value += `>${selection.split("\n").join("\n>")}\n`;
          }
              textArea.focus();
      }

      //call a cite if # is numeric (avoids #top #bottom) that will only run on inital page load
      if (location.hash.substr(1) != '') {
          var hash = location.hash.substr(1); //remove # and convert to number
          const regex = new RegExp('q[0-9]+');
          if (regex.test(hash) == true) { //if #q123
            var hash = hash.substr(1); //remove q
            cite(hash);
          }
      }

      // Get all posts
      const posts = document.querySelectorAll("[num]");
      for (const post of posts) { 
        post.addEventListener("click", (event) => {
          event.preventDefault();
          cite(post.getAttribute('num'));
        });
      }
  };
});