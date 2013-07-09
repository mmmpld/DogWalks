<% if LocalWalks %>
  <div id="LocalWalks"<% if ClassName = WalkPage %> class="noprint"<% end_if %>>
    <h2>
    <% if URLSegment = walks %>
      Walks Near You
    <% else_if URLSegment = near-you %>
      Walks Near Your Location
    <% else %>
      Walks Near $MenuTitle
    <% end_if %>
    </h2>
    <ul>
      <% loop LocalWalks %>
        <li><a href="$URL">$Name</a></li>
      <% end_loop %>
    </ul>
  </div>
<% end_if %>
<% if not LocalWalks %>
  <div id="LocalWalks" style="display:none;"></div>
  <script type="text/javascript" charset="utf-8"></script>
  <% require javascript('themes/dogwalks/javascript/min/walkLocal.min.js') %>
<% end_if %>