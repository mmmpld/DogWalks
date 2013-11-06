var UploadCount = 0;
var UploadMax = 10;

jQuery(document).ready(function($) {
  checkAdvancedUploadRequirements();
}); // end jquery ready

function checkAdvancedUploadRequirements() {
  if (window.FormData !== undefined) { // xhr.upload
    if (Modernizr.canvas) { // canvas
      if (Modernizr.draganddrop) { // dragdrop
        if(window.File && window.FileReader && window.FileList && window.Blob) { // more file jazz
          replaceDefaultUpload();
          listenDragDrop();
        }
      }
    }
  }
}
function replaceDefaultUpload() {
  // incase form validation redirected, get all images that were already uploaded
  var previouslyUploadedImagesArray = $.parseJSON(previouslyUploadedImages);
  var previouslyUploadedImagesHTML = '';
  if (previouslyUploadedImagesArray) {
    for (var i in previouslyUploadedImagesArray) {
      previouslyUploadedImagesHTML += '<li><input type="text" name="UploadedFiles[]" value="' + previouslyUploadedImagesArray[i] + '" class="text completed" readonly><div id="progress' + UploadCount + '"></div></li>';
      UploadCount++;
    }
  }
  var uploadHTML = ''
  + '<label class="left" for="WalkSuggestForm_suggested_Walk-Image">Walk Image</label>'
  + '<span>Drag images to the box or select an image (max 10)</span>'
  + '<ol id="UploadedFiles">'
  + previouslyUploadedImagesHTML
  + '</ol>'
  + '<ul id="UploadedErrors"></ul>'
  + '<div class="middleColumn ">'
  //+ '  <canvas id="ImageThumb" width="100" height="100"></canvas><button id="UploadButton">Select Image</button>'
  + '  <div id="ImageDrop"></div><button id="UploadButton">Select Image</button>'
  + '<input type="file" name="" class="file" id="UploadHidden" style="display:none;" multiple >'
  + '</div>';
  $('#Walk-Image').html(uploadHTML).addClass('js');
  $('#UploadButton').click(function(event){
    event.preventDefault();
    $('#UploadHidden').click();
  });
  $('#UploadHidden').change(function() {
    addImageUploads(this.files);
  });
}

function listenDragDrop() {
  var dropbox = document.getElementById("ImageDrop");
  dropbox.addEventListener("dragenter", dragenter, false);
  dropbox.addEventListener("dragleave", dragleave, false);
  dropbox.addEventListener("dragover", dragover, false);
  dropbox.addEventListener("drop", drop, false);
  function dragenter(e) {
    e.stopPropagation();
    e.preventDefault();
    $(this).addClass('drop');
  }
  function dragleave(e) {
    e.stopPropagation();
    e.preventDefault();
    $(this).removeClass('drop');
  }
  function dragover(e) {
    e.stopPropagation();
    e.preventDefault();
  }
  function drop(e) {
    e.stopPropagation();
    e.preventDefault();
    var dt = e.dataTransfer;
    var files = dt.files;
    handleDropFiles(files);
    $(this).removeClass('drop');
  }
  function handleDropFiles(files) {
    addImageUploads(files);
  }
}

function uploadError(text, style) {
  var errorHTML = ''
  + '<a class="uploadErrorDismiss" title="dismiss error"><span>dismiss error</span></a>'
  + '<li class="uploadError ' + style + '">'
  + text
  + '</li>';
  var $errorRef = $(errorHTML).appendTo('#UploadedErrors');
  if (style == 'warning') {
    setTimeout(function() {
      $errorRef.removeClass("warning");
    }, 2000);
    // setTimeout(function() {
      // errorRef.slideUp();
    // }, 5000);
  }
  $('.uploadErrorDismiss').click(function(event){
    event.preventDefault();
    $(this).next().slideUp();
    $(this).remove();
  });
}

function addImageUploads(imageFiles) {
  var i = 0;
  var length = imageFiles.length;
  function processDelay() {
    if (imageFiles[i]) {
      if (UploadCount < UploadMax) { // check here to prevent stacking errors
        processImage(imageFiles[i]);
      } else {
        uploadError('Max uploads of ' + UploadMax + ' reached', 'warning');
        return;
      }
    } else {
      console.log('Something went wrong');
    }
    setTimeout(function () {
      i++;
      if (i < length) {
        processDelay();
      }
    }, 2000);
  }
  processDelay();
}
function processImage(imageFile) {
  var acceptedFiletypes = ["image/jpeg", "image/gif", "image/png"];
  if (UploadCount < UploadMax) {
    if (acceptedFiletypes.indexOf(imageFile.type) > -1) {
      if (imageFile.size <= MaxUploadBytes) {
        UploadCount++;
        var UploadedFileHTML = '<li><input type="text" name="UploadedFiles[]" value="' + imageFile.name + '" class="text" readonly><div id="progress' + UploadCount + '"></div></li>';
        $('#UploadedFiles').append(UploadedFileHTML);
        prepThumb(imageFile);
        if (!Lat && !Lng) {
          $.fileExif(imageFile, updateLatLngFromExif);
        }
        UploadFile(imageFile, UploadCount);
      } else {
        uploadError(imageFile.name + ' is too big to upload. Please resize and try again', 'warning');
      }
    } else {
      uploadError(imageFile.name + ' cannot be uploaded. Only .jpg .gif .png are accepted', 'warning');
    }
  } else {
    uploadError('Max uploads of ' + UploadMax + ' reached', 'warning');
  }
}
function prepThumb(imageFile) {
  var canvas = document.getElementById("ImageThumb");
  var img = document.createElement("img");
  var reader = new FileReader();
  reader.onload = function(e) {
    img.src = e.target.result;
    setTimeout(function() {
      //drawThumb(img, canvas); //TODO
    }, 100);
  }
  reader.readAsDataURL(imageFile);
}
function drawThumb(img, canvas) {
  var ctx = canvas.getContext("2d");
  var MAX_WIDTH = 100;
  var MAX_HEIGHT = 100;
  var width = img.width;
  var height = img.height;
  if (width > height) {
    if (width > MAX_WIDTH) {
      height *= MAX_WIDTH / width;
      width = MAX_WIDTH;
    }
  } else {
    if (height > MAX_HEIGHT) {
      width *= MAX_HEIGHT / height;
      height = MAX_HEIGHT;
    }
  }
  canvas.width = width;
  canvas.height = height;
  try {
    ctx.drawImage(img, 0, 0, width, height);
  } catch(err) {
    console.log(err);
    console.trace();
  }
  //var dataurl = canvas.toDataURL("image/png");
}

// upload JPEG files
function UploadFile(file, count) {
  var xhr = new XMLHttpRequest();
  if (xhr.upload) {
    // create progress bar
    var o = document.getElementById("progress" + UploadCount);
    var progress = o.appendChild(document.createElement("p"));
    progress.appendChild(document.createTextNode("upload " + file.name));
    // progress bar
    xhr.upload.addEventListener("progress", function(e) {
      var pc = parseInt(100 - (e.loaded / e.total * 100));
      progress.style.backgroundPosition = pc + "% 0";
    }, false);
    // file received/failed
    xhr.onreadystatechange = function(e) {
      if (xhr.readyState == 4) {
        var c = count-1;
        var $progressInput = $("#UploadedFiles li:eq(" + c + ") input");
        if (xhr.status == 200) { // success
          progress.className = "success";
          $progressInput.addClass("success");
          setTimeout(function() {
            $progressInput.addClass("completed");
            $progressInput.removeClass("success");
          }, 1000);
        } else { // failure
          //console.log(xhr.status);
          progress.className = "failure";
          $progressInput.addClass("failure didnotcomplete");
        }
      }
    };
    // start upload
    var FormName = document.getElementById("WalkSuggestForm_suggested");
    var SecurityID = document.getElementById("WalkSuggestForm_suggested_SecurityID");
    xhr.open("POST", FormName.action, true);
    xhr.setRequestHeader("X_FILENAME", file.name);
    // xhr.send(file);
    console.log("sending file form");
    formData = new FormData(FormName);
    formData.append("SecurityID", $(SecurityID).val());
    formData.append("UploadedFiles[]", file, file.name);
    xhr.send(formData);
  } else if (!xhr.upload) {
    console.log('xhr upload not available');
  } else {
    console.log('image upload failed');
  }
}

function dataURItoBlob(dataURI) {
  var binary = atob(dataURI.split(',')[1]);
  var array = [];
  for(var i = 0; i < binary.length; i++) {
    array.push(binary.charCodeAt(i));
  }
  return new Blob([new Uint8Array(array)], {type: 'image/jpeg'});
}