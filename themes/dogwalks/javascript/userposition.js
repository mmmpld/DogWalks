var userPos = {};

var interval
var intervalCount = 0;
var intervalGPS
var intervalCountGPS = 0;
function fillUserLocation() {
  interval = setInterval(fillUserLocationInterval, 100);
  intervalGPS = setInterval(fillUserLocationGPSInterval, 100);
}
function fillUserLocationInterval() {
  if (typeof userPos.Address !== 'undefined') {
    $('#LocationSearchInput').val(userPos.Address);
    $('#LocationSearchAddressDefault').val(userPos.Address);
    clearInterval(interval);
  } else if (intervalCount > 20) {
    clearInterval(interval);
  } else {
    ++intervalCount;
  }
}
function fillUserLocationGPSInterval() {
  if (typeof userPos.Lat !== 'undefined' && typeof userPos.Lng !== 'undefined') {
    $('#LocationSearchLat').val(userPos.Lat);
    $('#LocationSearchLng').val(userPos.Lng);
    clearInterval(intervalGPS);
  } else if (intervalCountGPS > 20) {
    clearInterval(intervalGPS);
  } else {
    ++intervalCountGPS;
  }
}

jQuery(document).ready(function($) {

  /*
   * set userPos
   */
  userPos['Lat'] = $.cookie('Lat');
  userPos['Lng'] = $.cookie('Lng');
  userPos['Address'] = $.cookie('Address');
  if (userPos.Lat && userPos.Lng) {
    //console.log('userPos.Lat/Lng set from cookies');
  } else if (userPos.Address) {
    //console.log('userPos.Lat/Lng trying from address');
    geocodeFromAddress(userPos.Address, saveCookieLatLng);
  } else {
    //console.log('userPos.Lat/Lng/Address trying geo location');
    geolocate();
  }
  if (userPos.Address) {
    //console.log('userPos.Address already set');
  } else if (userPos.Lat && userPos.Lng) {
    //console.log('userPos.Address trying reverse geo location');
    addressFromGeocode(userPos.Lat, userPos.Lng, makeUserPosAddressString);
  } else {
    //console.log('userPos.Address not set');
  }

  function saveCookieLatLng(result) {
    userPos.Lat = result.Lat;
    userPos.Lng = result.Lng;
    $.cookie('Lat', userPos.Lat, { expires: 90, path: '/' });
    $.cookie('Lng', userPos.Lng, { expires: 90, path: '/' });
  }

  function geolocate() {
    // Try HTML5 geolocation
    if(navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        userPos['Lat'] = position.coords.latitude;
        userPos['Lng'] = position.coords.longitude;
        $.cookie('Lat', userPos.Lat, { expires: 90, path: '/' });
        $.cookie('Lng', userPos.Lng, { expires: 90, path: '/' });
        addressFromGeocode(userPos.Lat, userPos.Lng, setUserAddress);
      }, function() {
        handleNoGeolocation(true);
      });
    } else {
      // Browser doesn't support Geolocation
      handleNoGeolocation(false);
    }
  }
  function handleNoGeolocation(errorFlag) {
    if (errorFlag) {
      var content = 'Error: The Geolocation service failed.';
    } else {
      var content = 'Error: Your browser doesn\'t support geolocation.';
    }
    //console.log(content);
  }




}); // end jquery ready

function setUserAddress(address) {
  userPos.Address = makeUserPosAddressString(address);
  $.cookie('Address', userPos.Address, { expires: 90, path: '/' });
}

/*
 * cuts a reverse geocode result down to city/town range
 */
function makeUserPosAddressString(addressResult) {
  var includeTypes = new Array("administrative_area_level_1","sublocality","locality");
  address:
  for (var y=0; y<addressResult.length; y++) {
    components:
    for (var i=0; i<addressResult[y].address_components.length; i++) {
      types:
      for (var x=0; x<addressResult[y].address_components[i].types.length; x++) {
        if ($.inArray(addressResult[y].address_components[i].types[x], includeTypes) > -1) {
          //userPos.Address = addressResult[y].address_components[i].short_name;
          //$.cookie('Address', userPos.Address, { expires: 90, path: '/' });
          //break address;
          return addressResult[y].address_components[i].short_name;
        }
      }
    }
  }
}

/*
 * get street address from latlng (reverse geocode)
 */
function addressFromGeocode(lat, lng, callback) {
  var latlng = new google.maps.LatLng(lat, lng);
  var geocoder = new google.maps.Geocoder();
  geocoder.geocode({'latLng': latlng}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      if (results) {
        callback(results);
      } else {
        console.log('No results found');
      }
    } else {
      console.log('Geocoder failed due to: ' + status);
    }
  });
}

/*
 * get latlng from street address
 */
function geocodeFromAddress(address, callback) {
  var geocoder = new google.maps.Geocoder();
  geocoder.geocode( { 'address': address, 'region': 'NZ'}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      var r = results[0].geometry.location;
      var result = new Array();
      result.Lat = r.lat();
      result.Lng = r.lng();
      return callback(result);
    } else {
      console.log('Geocode for ' + address + ' was not successful for the following reason: ' + status);
    }
  });
}

/*
 * Convert gps data from image exif to decimal format
 */
function exifGPSToDecimal(deg, min, sec, hem) {
  var d = deg + ((min/60) + (sec/3600));
  return (hem=='S' || hem=='W') ? -d : d;
}
