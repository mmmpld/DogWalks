<% require javascript('themes/dogwalks/javascript/min/googlemaps.min.js') %>
<div id="Map">
  <script type="text/javascript" charset="utf-8">
    jQuery(document).ready(function($) {
      <% if $Lat && $Lng %>
        walkMarker = [{'Lat' : $Lat, 'Lng' : $Lng, 'Name' : '$MenuTitle', 'centerOn' : true}];
        localMarker = jQuery.parseJSON('$LocalWalksJSON');
      <% end_if %>

      var userIntervalCount = 0;
      var userInterval = setInterval(userMarkerInterval, 100);
      function userMarkerInterval() {
        if (userPos.Lat && userPos.Lng) {
          var userCenter = (!isDefined(walkMarker) || !walkMarker[0].centerOn) ? true : false ;
          userMarker = [{'Lat' : userPos.Lat, 'Lng' : userPos.Lng, 'Name' : 'Your Location', 'centerOn' : userCenter}];
          clearInterval(userInterval);
          if (!localMarker) { // now that we have a base location request local walks via ajax
            jQuery.post("walks/near-you/LocalWalksJSON", function(data) {
              localMarker = jQuery.parseJSON(data);
            });
          }
        } else if (userIntervalCount > 20) {
          clearInterval(userInterval);
        } else {
          ++userIntervalCount;
        }
      }

      $('#map-canvas').height('400px');
      $('#map-static').addClass('hidden');
    });
  </script>
  <h2>Map</h2>
  <noscript><span aria-hidden="true" data-icon="W"></span>Please enable JavaScript to view dynamic location maps.</noscript>
  <div id="map-canvas" class="noprint"></div>
  <% if $Lat != 0 && $Lng != 0 %>
    <div id="map-static"><img src="$StaticMapURL"></div>
  <% end_if %>
  <% if ClassName = WalkPage %>
    <% include Directions %>
  <% end_if %>
</div>