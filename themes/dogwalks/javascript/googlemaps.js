var map
var walkMarker
var userMarker
var localMarker

var directionsDisplay
var directionsService

jQuery(document).ready(function($) {

  google.maps.visualRefresh = true;

  function initialize() {


    var initialCenter = new google.maps.LatLng((userPos.Lat) ? userPos.Lat : -46.651, (userPos.Lng) ? userPos.Lng : 168.409);
    var mapOptions = {
      center: initialCenter,
      zoom: 11,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);

    /*
     * Directions Init
     */
    directionsService = new google.maps.DirectionsService();
    directionsDisplay = new google.maps.DirectionsRenderer({
      suppressMarkers: true,
      map: map,
      panel: document.getElementById('DirectionsPanel')
    });

    /*
     * once map is created start adding markers
     */
    google.maps.event.addListenerOnce(map, 'idle', function(){
      if (typeof mapReady !== 'undefined') {
        mapReady();
      }
      var walkIntervalCount = 0;
      var walkInterval = setInterval(walkMarkerInterval, 100);
      function walkMarkerInterval() {
        if (walkMarker) {
          initMarker(walkMarker, markerOptionsWalk);
          clearInterval(walkInterval);
        } else if (walkIntervalCount > 20) {
          clearInterval(walkInterval);
        } else {
          ++walkIntervalCount;
        }
      }
      var userIntervalCount = 0;
      var userInterval = setInterval(userMarkerInterval, 100);
      function userMarkerInterval() {
        if (userMarker) {
          initMarker(userMarker, markerOptionsUser);
          clearInterval(userInterval);
        } else if (userIntervalCount > 20) {
          clearInterval(userInterval);
        } else {
          ++userIntervalCount;
        }
      }
      setTimeout(function() {
        var localIntervalCount = 0;
        var localInterval = setInterval(localMarkerInterval, 100);
        function localMarkerInterval() {
          if (localMarker) {
            initMarker(localMarker, markerOptionsLocal);
            clearInterval(localInterval);
          } else if (localIntervalCount > 20) {
            clearInterval(localInterval);
          } else {
            ++localIntervalCount;
          }
        }
      }, 1000);
    });

  } // end initialize

  var markerOptionsUser = {
      draggable:false,
      icon:new google.maps.MarkerImage("themes/dogwalks/images/map/marker_user.png"),
      zIndex:800
    }
  var markerOptionsWalk = {
      draggable:false,
      animation:google.maps.Animation.DROP,
      icon:new google.maps.MarkerImage("themes/dogwalks/images/map/marker.png"),
      zIndex:900
    }
  var markerOptionsLocal = {
      draggable:false,
      animation:google.maps.Animation.DROP,
      icon:new google.maps.MarkerImage("themes/dogwalks/images/map/marker_local.png")
    }

  google.maps.event.addDomListener(window, 'load', initialize);

}); // end document ready

/*
 * name is the marker label. (string)
 * centerOn can be set to true to center map at the new marker position. (true|false)
 */
function initMarker(marker, pin, callback) {
  if (isDefined(marker)) {
    var length = marker.length,
    element = null;
    for (var i = 0; i < length; i++) {
      element = marker[i];
      var lat = element.Lat;
      var lng = element.Lng;
      var name = element.Name;
      var url = element.URL;
      var centerOn = element.centerOn;
      name = typeof name !== 'undefined' ? name : 'Walk Location';
      centerOn = typeof centerOn !== 'undefined' ? centerOn : false;
      placeMarker(lat, lng, name, url, centerOn, pin, callback);
    }
  }
}

function isDefined(variable) {
  if (typeof variable !== 'undefined') {
    return true;
  } else {
    return false;
  }
}

function placeMarker(lat, lng, name, url, centerOn, pin, callback) {
  var thisPos = new google.maps.LatLng(lat,lng);
  pin.map = map;
  pin.position = thisPos;
  pin.title = name;
  pin.url = url;
  var marker = new google.maps.Marker(pin);

  if (url) {
    google.maps.event.addListener(marker, 'click', function() {
      window.location.href = this.url;
    });
  } else if (centerOn) {
    var contentString = '<h5 class="mapInfoHeader">' + name + '</h5>';
    marker.infowindow = new google.maps.InfoWindow({content: contentString});
    google.maps.event.addListener(marker, 'click', function() {
      marker.infowindow.open(map,marker);
    });
    setTimeout(function() {
      marker.infowindow.open(map,marker);
    }, 1200);
  }

  // Center Map
  if (centerOn) {
    setTimeout(function() {
      map.panTo(thisPos);
    }, 300);
  }

  // Draggable Markers
  if (pin.draggable = true) { // draggable set in marker options
    // Add dragging event listeners. // TODO update user position?
    // google.maps.event.addListener(marker, 'dragstart', function() {});
    // google.maps.event.addListener(marker, 'drag', function() {});
    google.maps.event.addListener(marker, 'dragend', function() {
      var newPos = marker.getPosition();
      var dragPos = {'Lat' : newPos.lat(), 'Lng' : newPos.lng()};
      if (typeof markerDoneDrag !== 'undefined') {
        markerDoneDrag(dragPos);
      }
    });
  }

  if (callback) {
    return callback(marker);
  } else {
    return true;
  }
}

function calcRoute() {
  //var start = document.getElementById('DirectionsAddress').value;
  // var end = document.getElementById('end').value;
  //var start = new google.maps.LatLng((userPos.Lat) ? userPos.Lat : -46.651, (userPos.Lng) ? userPos.Lng : 168.409);
  var start = document.getElementById('DirectionsAddress').value;
  var end = new google.maps.LatLng(DirectionsLat, DirectionsLng);
  var request = {
    origin: start,
    destination: end,
    travelMode: google.maps.TravelMode.DRIVING
  };
  directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
      directionsDisplay.setDirections(response);
    } else {
      $('#DirectionsErrors').html('<div>Could not locate address, please be more specific</div>');
    }
  });
}
