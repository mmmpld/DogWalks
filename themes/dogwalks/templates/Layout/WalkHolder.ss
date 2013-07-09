<article>
    <h1>$Title</h1>
    <% include Breadcrumbs %>

    <div class="content">$Content</div>
</article>

<% if Children %>
  <% if ListChildWalkAreas %>
    <h2>Areas</h2>
    <ul>
      <% loop ListChildWalkAreas %>
        <li><a href="$Link">$MenuTitle</a></li>
      <% end_loop %>
    </ul>
  <% end_if %>
<% end_if %>
<% include WalkLocal %>

$Form