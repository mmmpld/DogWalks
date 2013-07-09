<script type="text/javascript" charset="utf-8">
  var DirectionsLat = {$Lat};
  var DirectionsLng = {$Lng};
  jQuery(document).ready(function($) {
    $('#Directions').slideDown();
    $('#DirectionsSearch button').click(function(event){
      event.preventDefault();
      if ($('#DirectionsAddress').val()) {
        clearDirectionsErrors();
        $('#Directions h2').html('Directions');
        calcRoute();
      } else {
        addDirectionsErrors('Please add an address to start from');
      }
    });
  });

  function clearDirectionsErrors() {
    $('#DirectionsErrors').html('');
  }
  function addDirectionsErrors(error) {
    console.log($errors);
    $('#DirectionsErrors').html('<div>'+error+'</div>');
  }
</script>
<div id="Directions">
  <div id="DirectionsErrors" class="noprint"></div>
  <form id="DirectionsSearch" class="noprint">
    <input id="DirectionsAddress" type="text" value="{$UserAddress}"><button><span class="icon-directions" title="Get Directions"></span><span class="icon-alt">Go</span></button>
  </form>
  <h2></h2>
  <div id="DirectionsPanel"></div>
</div>