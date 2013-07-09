<% if $Lat!=0 && $Lng!=0 %>
  <% require javascript('themes/dogwalks/javascript/min/qrcode.min.js') %>
  <script type="text/javascript" charset="utf-8">
    jQuery(document).ready(function($) {
      // var qrcode = new QRCode(document.getElementById("mapQR"), {
        // text: "https://maps.google.co.nz/?q=loc:$Lat,$Lng",
        // width: 100,
        // height: 100,
        // colorDark : "#000000",
        // colorLight : "#ffffff",
        // correctLevel : QRCode.CorrectLevel.M // L<M<Q<H
      // });
      var qrOptions = {
        text: "https://maps.google.co.nz/?q=loc:$Lat,$Lng",
        width: 75,
        height: 75,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.M // L<M<Q<H
      }
      //var qrcode = new QRCode(document.getElementById("mapQR"), qrOptions);
      $('#mapQRButton').click(function(){
        var qrcode = new QRCode(document.getElementById("mapQR"), qrOptions);
        this.remove();
      });
    });
  </script>
  <span id="mapQRButton">View QR Code</span>
  <div id="mapQR"></div>
<% end_if %>