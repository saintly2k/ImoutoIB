


//captcha code
document.addEventListener("DOMContentLoaded", function(event) { 
  if (captcha_required = true) {
    if (document.getElementById("captcha")) {
    //load JS version of captcha.
    const captcha_image = document.querySelector("#captcha");
    const captcha_field = document.querySelector("#captcha-field");
    document.getElementById("load-captcha").onclick = function() {
      if (document.querySelector("details.js-captcha").open == false) {
        captcha.src = captcha.getAttribute('js-src') + '?' + Date.now();
        captcha_field.value = '';
        captcha_field.focus();
      } else {
        captcha.src = '';
        captcha_field.value = '';
      }
    }
      //refresh
    captcha_image.onclick = function() {
      captcha.src = install_location + '/captcha.php?' + Date.now();
      captcha_field.value = '';
      captcha_field.focus();
    }
    captcha_field.onclick = function() {
      if (captcha.src == location.href || captcha.src == '') { //if empty, yes this is weird it goes to href when emptied out by js, but '' if never changed before.
        document.querySelector("details.js-captcha").open = true;
        captcha.src = install_location + '/captcha.php?' + Date.now();
        captcha_field.value = '';
        captcha_field.focus();
      }
    }
    captcha_field.onfocus = function() { //if tabbing through fields
      if (captcha.src == location.href || captcha.src == '') { //if empty, yes this is weird it goes to href when emptied out by js, but '' if never changed before.
        document.querySelector("details.js-captcha").open = true;
        captcha.src = install_location + '/captcha.php?' + Date.now();
        captcha_field.value = '';
        captcha_field.focus();
      }
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

//generate and save an insecure post deletion password
document.addEventListener("DOMContentLoaded", function(event) {
  if (document.getElementById("post_password")) { //only when post-form is on
    if (localStorage.post_password != null) {
      document.getElementById("post_password").value = localStorage.post_password;
      let passwords = document.querySelectorAll("[type='password']");
       for (const password of passwords) { 
        password.value = localStorage.post_password;
       }
    } else {
      localStorage.post_password = Math.random().toString(22).substr(2, 10); //generate
      document.getElementById("post_password").value = localStorage.post_password;
    }
  }
});

//post quoting
document.addEventListener("DOMContentLoaded", function(event) { 
      //cite number + text if selected
      function cite(id) {
          const textArea = document.getElementById('body');
          if (!textArea) {
              return false;
          }
          document.getElementById('post-form').scrollIntoView();
          textArea.value += `\n>>${id}\n`;
          if (localStorage.getItem("text-selection")) {
            var selection = localStorage.getItem("text-selection");
          } else {
            var selection = window.getSelection().toString();
          }
          textArea.value = textArea.value.replace(/^\n/, ''); //cleanup if post begins with newline 
          if (selection != '') {
              textArea.value += `>${selection.split("\n").join("\n>")}\n`;
              textArea.value = textArea.value.replace('> ', '>'); //cleanup sometimes gets a space before the quote
              textArea.value = textArea.value.replace('\n>\n', '\n'); //cleanup if it ends with \n>\n then remove cuz it does that if u doubleclick to select on edge
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
            localStorage.removeItem("text-selection");
          }
      }

      // Get all posts
      const posts = document.querySelectorAll("[num]");
      for (const post of posts) { 
        post.addEventListener("click", (event) => {
          if (document.querySelector('body.thread')) {
            event.preventDefault();
          } else {
            localStorage.setItem("text-selection", window.getSelection().toString());
          }
          cite(post.getAttribute('num'));
        });
      }
});



document.addEventListener("DOMContentLoaded", function(event) {
  // Highlight
  function hl(element) {
      let current = document.getElementsByClassName('highlighted');
      for (let i = 0; i<current.length; i++) {
        current[i].classList.remove('highlighted');
      }
      let hlthis = document.querySelectorAll(`[id="${element}"]`);
      for (let i = 0; i<hlthis.length; i++) {
        hlthis[i].classList.toggle('highlighted');
      }
  }
  if (location.hash.substr(1) != '') {
      const hash = location.hash.substr(1); //remove #
      let regex = new RegExp('[0-9]+');
      if (regex.test(hash) == true) { //if #123
        hl(hash);
      }
  }

  // Get all anchor posts
  const highlights = document.querySelectorAll(".post-number a.anchor");
  for (const highlight of highlights) { 
    highlight.addEventListener("click", (event) => {
      hl(highlight.getAttribute('name'));
    });
  }

  // Get all quotelink posts
  const hlquotelinks = document.querySelectorAll(".quotelink");
  for (const hlquotelink of hlquotelinks) { 
    let number = hlquotelink.textContent.substr(2);
    hlquotelink.addEventListener("click", (event) => {
      hl(number);
    });
    hlquotelink.addEventListener("mouseover", (event) => {
      //highlight type 2 (to not mess with click highlights)
      let hlthis = document.querySelectorAll(`[id="${number}"]`);
      for (let i = 0; i<hlthis.length; i++) {
        hlthis[i].classList.add('mouse-highlight');
      }
    });
    hlquotelink.addEventListener("mouseout", (event) => {
      let hlthis = document.querySelectorAll(`[id="${number}"]`);
      for (let i = 0; i<hlthis.length; i++) {
        hlthis[i].classList.remove('mouse-highlight');
      }
    });
  }

});

//expand images
document.addEventListener("DOMContentLoaded", function(event) { 
  if (document.querySelector('body.index') || document.querySelector("body.thread")) { //only on index/thread

    function imageSwitcher(id) {
    //toggle dnone
    let switchthis = document.querySelectorAll(`[img-id="${id}"]`);
      for (let i = 0; i<switchthis.length; i++) {
        switchthis[i].classList.toggle('dnone');
      }
    }

    const images = document.querySelectorAll(".post-image[data-file='image']");
    for (const image of images) { 
      let thumb = image.querySelector('a img.thumb');
      let expanded = image.querySelector('a img.expand'); //target images inside the image here and not the div
      thumb.addEventListener("click", (event) => {
        event.preventDefault();
        imageSwitcher(thumb.getAttribute('img-id'));
        image.querySelector('a img.expand').src = image.querySelector('a img.expand').getAttribute('img-src'); //set src first click
      });
      expanded.addEventListener("click", (event) => {
        event.preventDefault();
        imageSwitcher(expanded.getAttribute('img-id'));
      });
    }
    //done? <- for images at least, need audio+video players too with a cute [close] button next to it.
  }
});

//expand videos (Todo audio)
document.addEventListener("DOMContentLoaded", function(event) { 
  if (document.querySelector('body.index') || document.querySelector("body.thread")) { //only on index/thread
    function videoSwitcher(id) {
    let switchthis = document.querySelectorAll(`[vid-id="${id}"]`);
      for (let i = 0; i<switchthis.length; i++) {
        switchthis[i].classList.toggle('dnone');
      }
    }

    const videos = document.querySelectorAll(".post-image[data-file='video']");
    for (const video of videos) { 
      let thumb = video.querySelector('a img.thumb');
      let expand = video.querySelector('video');
      let vidid = expand.getAttribute('vid-id');
      let fileinfo = video.parentNode.querySelector('div.file-info');
      let expandsrc = expand.getAttribute('vid-src');
      let expandtype = expand.getAttribute('vid-type');
      let closebutton = `<span class="closevid" closevid-id="${vidid}">&nbsp;[<a closevid-id="${vidid}" href="#">Close</a>]</span>`;
      thumb.addEventListener("click", (event) => {
        expand.play();
        event.preventDefault();
        expand.innerHTML = `<source src-id="${vidid}" src="${expandsrc}" type="${expandtype}"/>`;
        videoSwitcher(vidid);
        fileinfo.insertAdjacentHTML('beforeend', closebutton);
        document.querySelector(`span.closevid a[closevid-id="${vidid}"]`).addEventListener("click", (event) => {
          event.preventDefault();
          expand.pause();
          let elem1 = document.querySelector(`span.closevid[closevid-id="${vidid}"]`);
          elem1.parentNode.removeChild(elem1);
          videoSwitcher(vidid);
        });
    });
    }
  }
});
