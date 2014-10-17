document.cancelFullScreen = document.webkitExitFullscreen || document.mozCancelFullScreen || document.exitFullscreen;

var elem = document.querySelector(document.webkitExitFullscreen ? "#container" : "#container");

document.addEventListener('keydown', function(e) {
  switch (e.keyCode) {
    case 13: // ENTER. ESC should also take you out of fullscreen by default.
      e.preventDefault();
      document.cancelFullScreen(); // explicitly go out of fs.
      $('#container').isotope('reloadItems')
                .isotope({ sortBy: 'original-order' })
                .isotope('option', { sortBy: 'symbol' }).isotope('reLayout');

      break;
    case 70: // f
      enterFullscreen();
      $('#container').isotope('reloadItems')
                .isotope({ sortBy: 'original-order' })
                .isotope('option', { sortBy: 'symbol' }).isotope('reLayout');
      break;
  }
}, false);

function toggleFS(el) {
  if (el.webkitEnterFullScreen) {
    el.webkitEnterFullScreen();
  } else {
    if (el.mozRequestFullScreen) {
      el.mozRequestFullScreen();
    } else {
      el.requestFullscreen();
    }
  }

  el.ondblclick = exitFullscreen;
}

function onFullScreenEnter() {
  console.log("Entered fullscreen!");
  elem.onwebkitfullscreenchange = onFullScreenExit;
  elem.onmozfullscreenchange = onFullScreenExit;
};

// Called whenever the browser exits fullscreen.
function onFullScreenExit() {
  console.log("Exited fullscreen!");
};

// Note: FF nightly needs about:config full-screen-api.enabled set to true.
function enterFullscreen() {
  console.log("enterFullscreen()");
  elem.onwebkitfullscreenchange = onFullScreenEnter;
  elem.onmozfullscreenchange = onFullScreenEnter;
  elem.onfullscreenchange = onFullScreenEnter;
  if (elem.webkitRequestFullscreen) {
    elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
  } else {
    if (elem.mozRequestFullScreen) {
      elem.mozRequestFullScreen();
    } else {
      elem.requestFullscreen();
    }
  }
  document.getElementById('enter-exit-fs').onclick = exitFullscreen;
}

function exitFullscreen() {
  console.log("exitFullscreen()");
  document.cancelFullScreen();
  document.getElementById('enter-exit-fs').onclick = enterFullscreen;
}