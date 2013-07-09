<script type="text/javascript" charset="utf-8">
  jQuery(document).ready(function($) {
    var jQunavailable = $('tr.unavailable');
    var jQtoggleButton = $('#toggleUnavailable');
    var jQtitle = jQtoggleButton.attr("title");

    if(jQunavailable.length > 0) {
      jQtoggleButton.html('show extra details');
      jQtoggleButton.click(function(){
        if (jQtoggleButton.hasClass('open')) {
          jQtoggleButton.html('show extra details');
          //jQtoggleButton.attr('title',"show extra details");
        } else {
          jQtoggleButton.html('hide extra details');
          //jQtoggleButton.attr('title',"hide extra details");
        }
        jQunavailable.fadeToggle();
        jQtoggleButton.toggleClass('open');
      });
    }
  });
</script>

<h2>Details</h2>
<% if $WalkInfo %>
  <table id="WalkInfoTable">
    <tbody>
      <% if $Cost != 0 %>
        <tr class="info cost">
          <td><span class="icon-Cost" title="Cost"></span><span class="icon-alt">Cost</span></td><td>\${$Cost} <% if $CostDescription %>$CostDescription<% else %>Charge applies<% end_if %></td>
        </tr>
      <% end_if %>
      <% loop $WalkInfo %>
        <tr class="<% if $Value != 0 && $Value != 1 %>available<% else %>unavailable<% end_if %>">
          <td><span class="icon-{$Class}" title="{$Title}"></span><span class="icon-alt">$Name</span></td><td>$Text</td>
        </tr>
      <% end_loop %>
    </tbody>
    <tbody>
      <% if $Address %>
        <tr class="info">
          <td><span class="icon-map" title="Address"><span class="icon-alt">Address</span></span></td><td class="address" title="$Address">$Address</td>
        </tr>
      <% end_if %>
      <% if $Lat != 0 && $Lng != 0 %>
        <tr class="info">
          <td><span class="icon-location-2" title="GPS"></span><span class="icon-alt">GPS</span></td><td>$Lat,$Lng</td>
        </tr>
        <tr class="info">
          <td><span class="icon-qrcode" title="QR Code"></span><span class="icon-alt">QR Code</span></td><td><% include QR %></td>
        </tr>
      <% end_if %>
    </tbody>
    <tbody class="noprint">
      <tr><td colspan="2" id="toggleUnavailable"></td></tr>
    </tbody>
  </table>
<% end_if %>