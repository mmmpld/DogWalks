<% require javascript('themes/dogwalks/javascript/min/googlemaps.min.js') %>
<% require javascript('themes/dogwalks/javascript/min/jquery.exif.min.js') %>
<% require javascript('themes/dogwalks/javascript/min/walkUpload.min.js') %>
<% require javascript('themes/dogwalks/javascript/min/suggestMap.min.js') %>
<script type="text/javascript" charset="utf-8">
  var Lat
  var Lng
  var Name = 'New Walk';
  var Address
  var newWalkMarker
  var markerOptionsNewWalk = {
    draggable:true,
    animation:google.maps.Animation.DROP,
    icon:new google.maps.MarkerImage("themes/dogwalks/images/map/marker.png"),
    zIndex:901
  }
  var newWalkMarkerRef
  var MaxUploadMb = {$GetMaxUpload} || 2;
  var MaxUploadBytes = MaxUploadMb * 1024 * 1024;
  var previouslyUploadedImages = '{$PreviouslyUploadedImages}';

  jQuery(document).ready(function($) {
    prettyCurrency();
  });
</script>

<form $FormAttributes class="walkForm">
  <% if $Message %>
    <p id="{$FormName}_error" class="message $MessageType">$Message</p>
  <% else %>
    <p id="{$FormName}_error" class="message $MessageType" style="display: none"></p>
  <% end_if %>
  <fieldset id="SuggestForm_Description">
    <legend><h2>Description</h2></legend>
    <div id="Walk-Name" class="field text">
      <label class="left" for="{$FormName}_Walk-Name">Walk Name <span class="requiredText">(Required)</span></label>
      <strong class="requiredText">$Fields.dataFieldByName(Walk-Name).Message</strong>
      <div class="middleColumn">
        $Fields.dataFieldByName('Title')
      </div>
    </div>
    <div id="Walk-Description" class="field textarea">
      <label class="left" for="{$FormName}_Walk-Description">Walk Description</label>
      <span>Tell us about the walk</span>
      <div class="middleColumn">
        <textarea name="Content" class="textarea" id="WalkPageEditForm_EditForm_Content" rows="5" cols="20">$GetPlainContent</textarea>
      </div>
    </div>
    <div id="Walk-Image" class="field file">
      <label class="left" for="{$FormName}_Walk-Image">Walk Image</label>
      <% if $Fields.dataFieldByName(Walk-Image).Message %>
        <strong class="requiredText">$Fields.dataFieldByName(Walk-Image).Message</strong>
      <% else %>
        <span id="UploadDescription">Select an image to represent the walk (max filesize {$GetMaxUpload}MB)</span>
      <% end_if %>
      <div class="middleColumn ">
        <span id="DefaultUpload">$Fields.dataFieldByName('Walk-Image')</span>
      </div>
    </div>
  </fieldset>
  <fieldset id="SuggestForm_Location">
    <legend><h2>Location</h2></legend>
    <div id="Address" class="field text">
      <label class="left" for="{$FormName}_Address">Address</label>
      <span>For the entrance to the walk <span id="LocationPrefilled"></span></span>
      <div class="middleColumn">
        $Fields.dataFieldByName('Address')
      </div>
    </div>
    <div id="Map" class="field text">
      $Fields.dataFieldByName('Lat')
      $Fields.dataFieldByName('Lng')
      <noscript><span aria-hidden="true" data-icon="W"></span>Please enable JavaScript to view location maps.</noscript>
      <div id="map-canvas"></div>
    </div>
  </fieldset>
  <fieldset id="SuggestForm_Details">
    <legend><h2>Details</h2></legend>
    <div id="Leash" class="field text">
      <label class="left" for="{$FormName}_Leash">On-Leash or Off-Leash</label>
      <div class="middleColumn">
        $Fields.dataFieldByName('Leash')
      </div>
    </div>
    <div class="field text">
      <div class="middleColumn">
        <table class="layoutTable">
          <tbody>
            <tr>
              <td><label class="left" for="{$FormName}_Toilets">Toilets Available</label>$Fields.dataFieldByName('Toilets')</td>
              <td><label class="left" for="{$FormName}_Bins">Bins Provided</label>$Fields.dataFieldByName('Bins')</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="field text">
      <div class="middleColumn">
        <table class="layoutTable">
          <tbody>
            <tr>
              <td><label class="left" for="{$FormName}_Fenced">Fenced Area</label>$Fields.dataFieldByName('Fenced')</td>
              <td><label class="left" for="{$FormName}_Water">Water Available</label>$Fields.dataFieldByName('Water')</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="field text">
      <div class="middleColumn">
        <table class="layoutTable">
          <tbody>
            <tr>
              <td><label class="left" for="{$FormName}_Pram">Pushchair Accessible</label>$Fields.dataFieldByName('Pram')</td>
              <td><label class="left" for="{$FormName}_Wheelchair">Wheelchair Accessible</label>$Fields.dataFieldByName('Wheelchair')</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div id="Cost" class="field text">
      <div class="middleColumn">
        <table class="layoutTable">
          <tbody>
            <tr>
              <td><label class="left" for="{$FormName}_Cost">Cost</label>$Fields.dataFieldByName('Cost')</td>
              <td class="CostDescriptionTD"><label class="left" for="{$FormName}_Cost-Description">Cost Explanation</label>$Fields.dataFieldByName('Cost-Description')</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </fieldset>
  <fieldset id="SuggestForm_YourInfo">
    <legend><h2>Your Info</h2></legend>
    <div id="Name" class="field text">
      <label class="left" for="{$FormName}_Name">Name</label>
      <span>We will use this to credit you for the suggestion</span>
      <div class="middleColumn">
        $Fields.dataFieldByName('Name')
      </div>
    </div>
    <div id="Email" class="field email text">
      <label class="left" for="{$FormName}_Email">Email</label>
      <span>To let you know when the page is ready (we won't share your email)</span>
      <div class="middleColumn">
        $Fields.dataFieldByName('Email')
      </div>
    </div>
    <div id="Message" class="field textarea">
      <label class="left" for="{$FormName}_Message">Message</label>
      <span>Anything else you'd like to add?</span>
      <div class="middleColumn">
        $Fields.dataFieldByName('Message')
      </div>
    </div>
  </fieldset>

  $Fields.dataFieldByName(SecurityID)
  <% if $Actions %>
  <div class="Actions">
    <% loop $Actions %>$Field<% end_loop %>
  </div>
  <% end_if %>
</form>