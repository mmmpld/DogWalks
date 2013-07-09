jQuery(document).ready(function($) {
  var localIntervalCount = 0;
  var localInterval = setInterval(localMarkerInterval, 100);
  function localMarkerInterval() {
    if (userPos.Lat && userPos.Lng && !localMarker) {
      jQuery.post("walks/near-you/LocalWalksJSON", function(data) {
        var LocalWalks = jQuery.parseJSON(data);
        if (LocalWalks && LocalWalks[0]) {
          var LocalWalksDIV = $('#LocalWalks');
          LocalWalksDIV.append('<h2>Walks Near Your Location</h2>');
          LocalWalksDIV.append('<ul></ul>');
          var LocalWalksUL = $('#LocalWalks ul');
          for (var i=0; i<LocalWalks.length; i++) {
            LocalWalksUL.append('<li><a href="'+LocalWalks[i].URL+'">'+LocalWalks[i].Name+'</a></li>');
          }
          LocalWalksDIV.slideDown();
        }
      });
      clearInterval(localInterval);
    } else if (localIntervalCount > 20) {
      clearInterval(localInterval);
    } else {
      ++localIntervalCount;
    }
  }
});