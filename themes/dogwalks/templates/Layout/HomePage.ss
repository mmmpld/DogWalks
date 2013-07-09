<div id="Location">
  <% include WalkNearSearch %>
  <% if ListChildWalkAreas(6) %>
    <div id="LocationAreas">
      <h2>or select an area below</h2>
      <ul>
        <% loop ListChildWalkAreas(6) %>
          <li><a href="$Link" class="button">$MenuTitle</a></li>
        <% end_loop %>
      </ul>
    </div>
  <% end_if %>
</div>
<% if Content %>
  <h3>$Title</h3>
  $Content
<% end_if %>
$Form