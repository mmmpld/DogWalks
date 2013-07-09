<% if Pages %>
	<% loop Pages %>
		<% if Last %>$MenuTitle.XML<% else %><a href="$Link">$MenuTitle.XML</a> &raquo;<% end_if %>
	<% end_loop %>
<% end_if %>