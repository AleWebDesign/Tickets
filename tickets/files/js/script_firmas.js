var url = window.location.href;
var arr = url.split("/");

var tiemposcambian = tiemposcambian || {};

tiemposcambian.GuardandoPNGs = (function() {
  var mousePressed = false;
  var lastX, lastY;
  var ctx;

  function init() {
    // init canvas
    var canvas1 = document.getElementById('canvas1');
    var canvas2 = document.getElementById('canvas2');
    ctx1 = canvas1.getContext('2d');
    ctx2 = canvas2.getContext('2d');
    resetCanvas1();
    resetCanvas2();

    // button events
    document.getElementById("submit").onmouseup = sendToServer;
    document.getElementById('bt-clear1').onmouseup = resetCanvas1;
    document.getElementById('bt-clear2').onmouseup = resetCanvas2;

    canvas1.onmousedown = function(e) {
      draw1(e.layerX, e.layerY);
      mousePressed = true;
    };

    canvas1.onmousemove = function(e) {
      if (mousePressed) {
        draw1(e.layerX, e.layerY);
      }
    };

    canvas1.onmouseup = function(e) {
      mousePressed = false;
    };
    
    canvas1.onmouseleave = function(e) {
      mousePressed = false;
    };
    
    canvas2.onmousedown = function(e) {
      draw2(e.layerX, e.layerY);
      mousePressed = true;
    };

    canvas2.onmousemove = function(e) {
      if (mousePressed) {
        draw2(e.layerX, e.layerY);
      }
    };

    canvas2.onmouseup = function(e) {
      mousePressed = false;
    };
    
    canvas2.onmouseleave = function(e) {
      mousePressed = false;
    };
  }

  function draw1(x, y) {
    if (mousePressed) {
      ctx1.beginPath();
      ctx1.strokeStyle = document.getElementById('color').value;
      ctx1.lineWidth = 1;
      ctx1.lineJoin = 'round';
      ctx1.moveTo(lastX, lastY);
      ctx1.lineTo(x, y);
      ctx1.closePath();
      ctx1.stroke();
    }
    lastX = x; lastY = y;
  }
  
  function draw2(x, y) {
    if (mousePressed) {
      ctx2.beginPath();
      ctx2.strokeStyle = document.getElementById('color').value;
      ctx2.lineWidth = 1;
      ctx2.lineJoin = 'round';
      ctx2.moveTo(lastX, lastY);
      ctx2.lineTo(x, y);
      ctx2.closePath();
      ctx2.stroke();
    }
    lastX = x; lastY = y;
  }

  function sendToServer() {
    var firma1 = canvas1.toDataURL('image/png');
    var firma2 = canvas2.toDataURL('image/png');
    var reca = $('#reca').val();
    var salon = $('#salon').val();

    $.ajax({
      type: "POST",
      url: arr[0] + "//atc.apuestasdemurcia.es/tickets/guardar_firma_recaudacion",
      data: { 
         img1: firma1,
         img2: firma2,
         reca: reca,
         salon: salon
      }
      }).done(function(o) {
      	window.location.href = arr[0] + "//atc.apuestasdemurcia.es/tickets/recaudacion_finalizada/"+reca;
    });
  }
  
  function resetCanvas1() {
    // just repaint canvas white
    ctx1.fillStyle = '#EEEEEE';
    ctx1.fillRect(0, 0, canvas1.width, canvas1.height);
  }
  
  function resetCanvas2() {
    // just repaint canvas white
    ctx2.fillStyle = '#EEEEEE';
    ctx2.fillRect(0, 0, canvas2.width, canvas2.height);
  }

  return {
    'init': init
  };
});

function startup() {
  var el1 = document.getElementById('canvas1');
  el1.addEventListener("touchstart", handleStart1, false);
  el1.addEventListener("touchend", handleEnd1, false);
  el1.addEventListener("touchcancel", handleCancel, false);
  el1.addEventListener("touchleave", handleEnd1, false);
  el1.addEventListener("touchmove", handleMove1, false);

  var el2 = document.getElementById('canvas2');
  el2.addEventListener("touchstart", handleStart2, false);
  el2.addEventListener("touchend", handleEnd2, false);
  el2.addEventListener("touchcancel", handleCancel, false);
  el2.addEventListener("touchleave", handleEnd2, false);
  el2.addEventListener("touchmove", handleMove2, false);
}
var ongoingTouches = new Array;

function handleStart1(evt) {
    var el = document.getElementById('canvas1');
    var ctx = el.getContext("2d");
    var touches = evt.changedTouches;
    var offset = findPos(el);      
   
    for (var i = 0; i < touches.length; i++) {
      if(touches[i].clientX-offset.x >0 && touches[i].clientX-offset.x < parseFloat(el.width) && touches[i].clientY-offset.y >0 && touches[i].clientY-offset.y < parseFloat(el.height)){
          evt.preventDefault();
          ongoingTouches.push(copyTouch(touches[i]));
          var color = colorForTouch(touches[i]);
          ctx.beginPath();
          ctx.fillStyle = color;
          ctx.fill();
      }
    }
}

function handleMove1(evt) {
    var el = document.getElementById('canvas1');
    var ctx = el.getContext("2d");
    var touches = evt.changedTouches;
    var offset = findPos(el);

    for (var i = 0; i < touches.length; i++) {
        if(touches[i].clientX-offset.x >0 && touches[i].clientX-offset.x < parseFloat(el.width) && touches[i].clientY-offset.y >0 && touches[i].clientY-offset.y < parseFloat(el.height)){
            evt.preventDefault();
            var color = colorForTouch(touches[i]);
            var idx = ongoingTouchIndexById(touches[i].identifier);
    
        if (idx >= 0) {
            ctx.beginPath();
            ctx.moveTo(ongoingTouches[idx].clientX-offset.x, ongoingTouches[idx].clientY-offset.y);
            ctx.lineTo(touches[i].clientX-offset.x, touches[i].clientY-offset.y);
            ctx.lineWidth = 2;
            ctx.strokeStyle = color;
            ctx.stroke();
            ongoingTouches.splice(idx, 1, copyTouch(touches[i]));
        }
      }
    }
}

function handleEnd1(evt) {
    var el = document.getElementById('canvas1');
    var ctx = el.getContext("2d");
    var touches = evt.changedTouches;
    var offset = findPos(el);
        
    for (var i = 0; i < touches.length; i++) {
      if(touches[i].clientX-offset.x >0 && touches[i].clientX-offset.x < parseFloat(el.width) && touches[i].clientY-offset.y >0 && touches[i].clientY-offset.y < parseFloat(el.height)){
          evt.preventDefault();
          var color = colorForTouch(touches[i]);
          var idx = ongoingTouchIndexById(touches[i].identifier);
        
        if (idx >= 0) {
          ctx.lineWidth = 4;
          ctx.fillStyle = color;
          ctx.beginPath();
          ctx.moveTo(ongoingTouches[idx].clientX-offset.x, ongoingTouches[idx].clientY-offset.y);
          ctx.lineTo(touches[i].clientX-offset.x, touches[i].clientY-offset.y);
          ongoingTouches.splice(i, 1);
        }
      }
    }
}

function handleStart2(evt) {
    var el = document.getElementById('canvas2');
    var ctx = el.getContext("2d");
    var touches = evt.changedTouches;
    var offset = findPos(el);      
   
    for (var i = 0; i < touches.length; i++) {
      if(touches[i].clientX-offset.x >0 && touches[i].clientX-offset.x < parseFloat(el.width) && touches[i].clientY-offset.y >0 && touches[i].clientY-offset.y < parseFloat(el.height)){
          evt.preventDefault();
          ongoingTouches.push(copyTouch(touches[i]));
          var color = colorForTouch(touches[i]);
          ctx.beginPath();
          ctx.fillStyle = color;
          ctx.fill();
      }
    }
}

function handleMove2(evt) {
    var el = document.getElementById('canvas2');
    var ctx = el.getContext("2d");
    var touches = evt.changedTouches;
    var offset = findPos(el);

    for (var i = 0; i < touches.length; i++) {
        if(touches[i].clientX-offset.x >0 && touches[i].clientX-offset.x < parseFloat(el.width) && touches[i].clientY-offset.y >0 && touches[i].clientY-offset.y < parseFloat(el.height)){
            evt.preventDefault();
            var color = colorForTouch(touches[i]);
            var idx = ongoingTouchIndexById(touches[i].identifier);
    
        if (idx >= 0) {
            ctx.beginPath();
            ctx.moveTo(ongoingTouches[idx].clientX-offset.x, ongoingTouches[idx].clientY-offset.y);
            ctx.lineTo(touches[i].clientX-offset.x, touches[i].clientY-offset.y);
            ctx.lineWidth = 2;
            ctx.strokeStyle = color;
            ctx.stroke();
            ongoingTouches.splice(idx, 1, copyTouch(touches[i]));
        }
      }
    }
}

function handleEnd2(evt) {
    var el = document.getElementById('canvas2');
    var ctx = el.getContext("2d");
    var touches = evt.changedTouches;
    var offset = findPos(el);
        
    for (var i = 0; i < touches.length; i++) {
      if(touches[i].clientX-offset.x >0 && touches[i].clientX-offset.x < parseFloat(el.width) && touches[i].clientY-offset.y >0 && touches[i].clientY-offset.y < parseFloat(el.height)){
          evt.preventDefault();
          var color = colorForTouch(touches[i]);
          var idx = ongoingTouchIndexById(touches[i].identifier);
        
        if (idx >= 0) {
          ctx.lineWidth = 4;
          ctx.fillStyle = color;
          ctx.beginPath();
          ctx.moveTo(ongoingTouches[idx].clientX-offset.x, ongoingTouches[idx].clientY-offset.y);
          ctx.lineTo(touches[i].clientX-offset.x, touches[i].clientY-offset.y);
          ongoingTouches.splice(i, 1);
        }
      }
    }
}

function handleCancel(evt) {
  evt.preventDefault();
    var touches = evt.changedTouches;
  
    for (var i = 0; i < touches.length; i++) {
      ongoingTouches.splice(i, 1);
    }
}

function colorForTouch(touch) {
  var r = touch.identifier % 16;
    var g = Math.floor(touch.identifier / 3) % 16;
    var b = Math.floor(touch.identifier / 7) % 16;
    r = r.toString(16);
    g = g.toString(16);
    b = b.toString(16);
    var color = "#" + r + g + b;
    return color;
}

function copyTouch(touch) {
  return {identifier: touch.identifier,clientX: touch.clientX,clientY: touch.clientY};
}

function ongoingTouchIndexById(idToFind) {
  for (var i = 0; i < ongoingTouches.length; i++) {
      var id = ongoingTouches[i].identifier;
    
      if (id == idToFind) {
          return i;
      }
    }
    return -1;
}
 
function findPos (obj) {
    var curleft = 0,
        curtop = 0;

    if (obj.offsetParent) {
        do {
            curleft += obj.offsetLeft;
            curtop += obj.offsetTop;
        } while (obj = obj.offsetParent);

        return { x: curleft-document.body.scrollLeft, y: curtop-document.body.scrollTop };
    }
}

window.onload = function() {
  new tiemposcambian.GuardandoPNGs().init();
  startup();
};
