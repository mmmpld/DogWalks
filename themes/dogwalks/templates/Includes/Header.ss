<header class="header" role="banner">
	<div class="inner">
		<a href="$BaseHref" class="brand" rel="home">
			<h1><img src="themes/dogwalks/images/logotrial/DogWalksLogo{$RandomNumber(0,20)}.png" alt="$SiteConfig.Title" width="550"></h1>
			<% if $SiteConfig.Tagline %>
			<p>$SiteConfig.Tagline</p>
			<% end_if %>
		</a>
		<% if $SearchForm %>
			<span class="search-dropdown-icon noprint">L</span>
			<div class="search-bar noprint">
				<%-- $SearchForm --%>
        <form id="SearchForm_SearchForm" action="/walks/auckland/west/te-henga-bethells-beach/SearchForm" method="get" enctype="application/x-www-form-urlencoded">
          <fieldset>
            <div id="Search" class="field text nolabel">
              <div class="middleColumn">
                <input type="search" name="Search" value="Search" class="text nolabel" id="SearchForm_SearchForm_Search" />
              </div>
            </div>
            <input type="submit" name="action_results" value="Go" class="action action" id="SearchForm_SearchForm_action_results" />
          </fieldset>
        </form>
			</div>
		<% end_if %>
		<% include Navigation %>
	</div>
</header>
