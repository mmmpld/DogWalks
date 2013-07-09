<script type="text/javascript" charset="utf-8">
  jQuery(document).ready(function($) {
    if (!$('#LocationSearchInput').val()) {
     fillUserLocation();
    }
  });
</script>
<noscript><span aria-hidden="true" data-icon="W"></span>This website requires JavaScript for the best experience. Please enable JavaScript.</noscript>
<div id="LocationSearch" class="noprint">
  <% if URLSegment = home %>
    <h1>search for walks near your address</h1>
  <% else %>
    <h2>Search for walks near your address</h2>
  <% end_if %>
  <form action="walks/near-you/locations" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" id="LocationSearchLat" name="Lat" value=""><input type="hidden" id="LocationSearchLng" name="Lng" value=""><input type="hidden" id="LocationSearchAddressDefault" name="AddressDefault" value="">
    <input type="search" id="LocationSearchInput" name="Address" value=""><button>Search</button>
  </form>
</div>