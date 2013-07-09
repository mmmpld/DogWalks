<article>
  <div id="WalkPageHeader">
    <h1>$Title</h1><% include WalkIcons %>
  </div>
  <div class="clear"><!-- --></div>
  <% include Breadcrumbs %>
  $FormSuccess
  <% include WalkGallery %>
  <div id="WalkInfoSidebar">
    <% include WalkInfo %>
    <div id="WalkRating" class="noprint">$getSSRating</div>
  </div>
  <h2>Description</h2>
  <% if $Leash = 1 %>
    <div id="NoDogsWarning" class="icon-No-Dogs" title="Dogs not allowed"></div>
  <% end_if %>
  <% if $Content %>
    <div class="content">$Content</div>
  <% else %>
    <div id="WalkContentless" class="noprint">This walk has no description, perhaps <a href="{$link}edit/">you could add one</a>?</div>
  <% end_if %>
  <% if $Author %><div id="Author" class="noprint">Walk suggested by $Author</div><% end_if %>
  <div id="LastEdited" class="noprint">Last updated $LastEdited.Long</div>
</article>
<% if $Lat!=0 && $Lng!=0 %>
  <% include WalkMap %>
  <% include WalkLocal %>
<% end_if %>
<% include WalkComments %>

<a href="{$link}edit/" class="noprint">Improve this page</a>

$Form