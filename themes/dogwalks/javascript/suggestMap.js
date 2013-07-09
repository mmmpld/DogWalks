var $addressSelector
var $locationPrefilled
var $lat
var $lng

jQuery(document).ready(function($) {
  // cached selectors
  $addressSelector = $('#WalkSuggestForm_suggested_Address');
  $locationPrefilled = $('#LocationPrefilled');
  $lat = $('#WalkSuggestForm_suggested_Lat');
  $lng = $('#WalkSuggestForm_suggested_Lng');

  $('#WalkSuggestForm_suggested_Walk-Name').change(function() {
    Name = $(this).val();
  });
  $addressSelector.change(function() {
    requestGeocodeUpdate($(this).val(), updateFormGeocode);
    $locationPrefilled.text('(set from address)');
  });
  $('#map-canvas').height('400px');
}); // end jquery ready

function mapReady() {
  $('#map-canvas a').each(function(){
    this.tabIndex = -1;
  });
  if ($lat.val() != 0 && $lng.val() != 0) {
    Lat = $lat.val();
    Lng = $lng.val();
    setTimeout(function() {
      updateOrCreateMarker();
    }, 1000);
  }
  $('<button class="icon" id="AddressSearch" title="Mark on map">?</button>').appendTo('#Address .middleColumn');
  $('#AddressSearch').click(function(event){
    event.preventDefault();
    if ($addressSelector.val()) {
      requestGeocodeUpdate($addressSelector.val(), updateFormGeocode);
      $locationPrefilled.text('(set from address)');
    }
  });
}

var updateLatLngFromExif = function(exifObject) {
  if (exifObject.GPSLatitude && exifObject.GPSLongitude && exifObject.GPSLatitudeRef && exifObject.GPSLongitudeRef) {
    Lat = exifGPSToDecimal(exifObject.GPSLatitude[0], exifObject.GPSLatitude[1], exifObject.GPSLatitude[2], exifObject.GPSLatitudeRef);
    Lng = exifGPSToDecimal(exifObject.GPSLongitude[0], exifObject.GPSLongitude[1], exifObject.GPSLongitude[2], exifObject.GPSLongitudeRef);
    $lat.val(Math.round(Lat*10000000)/10000000);
    $lng.val(Math.round(Lng*10000000)/10000000);
    updateOrCreateMarker();
    addressFromGeocode(Lat, Lng, updateFormAddress);
    $locationPrefilled.text('(set from image location data)');
  }
}

function updateOrCreateMarker() {
  if (newWalkMarker) {
    var thisPos = new google.maps.LatLng(Lat, Lng);
    newWalkMarkerRef.setPosition(thisPos);
    setTimeout(function() {
      map.panTo(thisPos);
    }, 300);
  } else if (!newWalkMarker) {
    revealMap();
    newWalkMarker = [{'Lat' : Lat, 'Lng' : Lng, 'Name' : Name, 'centerOn' : true}];
    initMarker(newWalkMarker, markerOptionsNewWalk, newWalkMarkerSetRef);
  } else {
    console.log('failed to create or update new walk marker');
  }
}

function revealMap() {
  $('#Address').addClass('mapShowing');
  $('#Map').animate({height:'400px'}, 400);
}

function newWalkMarkerSetRef(marker) {
  newWalkMarkerRef = marker;
  setTimeout(function() {
    newWalkMarkerRef.infowindow.close();
  }, 4000);
}

function markerDoneDrag(dragPos) {
  addressFromGeocode(dragPos.Lat, dragPos.Lng, updateFormAddress);
  Lat = dragPos.Lat;
  Lng = dragPos.Lng;
  $lat.val(Math.round(Lat*10000000)/10000000);
  $lng.val(Math.round(Lng*10000000)/10000000);
  $locationPrefilled.text('(set from marker position)');
}

// request geocode if not already called within timeout period
var requestGeocodeUpdateTimer = 0;
function requestGeocodeUpdate(value, callbackToPass) {
  if (requestGeocodeUpdateTimer == 0) {
    geocodeFromAddress(value, callbackToPass);
    requestGeocodeUpdateTimer = 200;
    setTimeout(function() {
      requestGeocodeUpdateTimer = 0;
    }, requestGeocodeUpdateTimer);
  }
}
function updateFormGeocode(results) {
  Lat = results.Lat;
  Lng = results.Lng;
  $lat.val(Lat);
  $lng.val(Lng);
  updateOrCreateMarker();
}
function updateFormAddress(results) {
  Address = results[0].formatted_address;
  $addressSelector.val(Address);
}