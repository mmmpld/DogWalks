<div id="WalkIcons" class="noprint">
	<% if $Leash != 0 %>
    <% if $Leash == 1 %>
      <span class="icon-No-Dogs" title="Dogs not allowed"></span><span class="icon-alt">No Dogs</span>
    <% else_if $Leash == 2 %>
      <span class="icon-On-Leash" title="On-leash area"></span><span class="icon-alt">On-Leash</span>
    <% else_if $Leash == 3 %>
      <span class="icon-Off-Leash" title="Off-leash area"></span><span class="icon-alt">Off-Leash</span>
    <% else_if $Leash == 4 %>
      <span class="icon-On-Leash" title="On-leash area"></span><span class="icon-alt">On-Leash</span>
      <span class="icon-Off-Leash" title="Off-leash area"></span><span class="icon-alt">Off-Leash</span>
    <% end_if %>
	<% end_if %>
	<% if $Bins == 2 %>
    <span class="icon-Bins" title="Rubbish bins provided"></span><span class="icon-alt">Bins</span>
	<% end_if %>
	<% if $Toilets == 2 %>
    <span class="icon-Toilets" title="Toilets available"></span><span class="icon-alt">Toilets</span>
	<% end_if %>
	<% if $Fenced == 2 %>
    <span class="icon-Fenced" title="Fenced area"></span><span class="icon-alt">Fenced</span>
	<% end_if %>
	<% if $Cost != 0 %>
    <span class="icon-Cost" title="Associated cost"></span><span class="icon-alt">Costs</span>
	<% end_if %>
	<% if $Water == 2 || $Water == 3 %>
    <% if $Water == 2 %>
      <span class="icon-Tap-Water" title="Tap water available"></span><span class="icon-alt">Tap Water</span>
    <% else_if $Water == 3 %>
      <span class="icon-River-Water" title="River water available"></span><span class="icon-alt">River Water</span>
    <% end_if %>
	<% end_if %>
	<% if $Pram == 2 %>
    <span class="icon-Pushchair" title="Pushchair Accessible"></span><span class="icon-alt">Pushchair Accessible</span>
  <% end_if %>
  <% if $Wheelchair == 2 %>
    <span class="icon-Wheelchair" title="Wheelchair Accessible"></span><span class="icon-alt">Wheelchair Accessible</span>
  <% end_if %>
  <% if $Playground == 2 %>
    <span class="icon-Playground" title="Playground available"></span><span class="icon-alt">Playground Available</span>
  <% end_if %>
</div>