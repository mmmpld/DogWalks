<article>
  <h1>$Title</h1>
  <% include Breadcrumbs %>
  <div class="content">$Content</div>
</article>

<% if Children %>
  <% if ListChildWalkAreas %>
    <div class="noprint">
      <h2>Areas</h2>
      <ul>
      	<% loop ListChildWalkAreas %>
    			<li><a href="$Link">$MenuTitle</a></li>
      	<% end_loop %>
    	</ul>
  	</div>
	<% end_if %>
	<% if ListWalkPages %>
    <% require javascript('ssrating/javascript/jquery.rateit.min.js') %>
    <h2>Walks</h2>
    <table class="walkAreaTable">
      <thead>
        <tr>
          <th class="walkAreaTableTitle invisibleCell"></th>
          <th class="invisibleCell"></th>
          <th><span class="icon-Off-Leash" title="Off-leash area"></span><span class="icon-alt">Off-Leash</span></th>
          <th><span class="icon-Bins" title="Rubbish bins provided"></span><span class="icon-alt">Bins</span></th>
          <th><span class="icon-Toilets" title="Toilets available"></span><span class="icon-alt">Toilets</span></th>
          <th><span class="icon-Fenced" title="Fenced area"></span><span class="icon-alt">Fenced</span></th>
          <th><span class="icon-Cost" title="Associated cost"></span><span class="icon-alt">Costs</span></th>
          <th><span class="icon-Tap-Water" title="Tap water available"></span><span class="icon-alt">Tap Water</span></th>
          <th><span class="icon-Pushchair" title="Pushchair Accessible"></span><span class="icon-alt">Pushchair Accessible</span></th>
          <th><span class="icon-Wheelchair" title="Wheelchair Accessible"></span><span class="icon-alt">Wheelchair Accessible</span></th>
          <th><span class="icon-Playground" title="Wheelchair Accessible"></span><span class="icon-alt">Playground Available</span></th>
        </tr>
      </thead>
      <tbody>
      	<% loop ListWalkPages %>
      		<tr>
      		  <td class="walkAreaTableTitle"><a href="$Link">$Title</a></td>
      		  <td><div class="rateit" data-rateit-value="{$getRateValue($ID)}" data-rateit-ispreset="true" data-rateit-readonly="true"></div></td>
    		    <td><% if $Leash == 3 || $Leash == 4 %><span aria-hidden="true" data-icon="."></span><% end_if %></td>
            <td><% if $Bins == 2 %><span aria-hidden="true" data-icon="."></span><% end_if %></td>
            <td><% if $Toilets == 2 %><span aria-hidden="true" data-icon="."></span><% end_if %></td>
            <td><% if $Fenced == 2 %><span aria-hidden="true" data-icon="."></span><% end_if %></td>
            <td><% if $Cost != 0 %><span aria-hidden="true" data-icon="."></span><% end_if %></td>
            <td><% if $Water == 2 %><span aria-hidden="true" data-icon="."></span><% end_if %></td>
            <td><% if $Pram == 2 %><span aria-hidden="true" data-icon="."></span><% end_if %></td>
            <td><% if $Wheelchair == 2 %><span aria-hidden="true" data-icon="."></span><% end_if %></td>
            <td><% if $Playground == 2 %><span aria-hidden="true" data-icon="."></span><% end_if %></td>
      		</tr>
      	<% end_loop %>
    	</tbody>
    </table>
	<% end_if %>
<% end_if %>

$Form