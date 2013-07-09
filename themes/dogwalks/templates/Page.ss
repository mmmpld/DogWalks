<!DOCTYPE html>
  <!--[if !IE]><!-->
  <html lang="$ContentLocale" class="no-js">
  <!--<![endif]-->
  <!--[if IE 6 ]><html lang="$ContentLocale" class="ie ie6 no-js"><![endif]-->
  <!--[if IE 7 ]><html lang="$ContentLocale" class="ie ie7 no-js"><![endif]-->
  <!--[if IE 8 ]><html lang="$ContentLocale" class="ie ie8 no-js"><![endif]-->
  <head>
  	<% base_tag %>
  	<title><% if $MetaTitle %>$MetaTitle<% else %>$Title<% end_if %> &raquo; $SiteConfig.Title</title>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
  	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  	$MetaTags(false)
  	<link rel="stylesheet" href="themes/dogwalks/css/dogwalks.css" type="text/css" media="all">
  	<link rel="stylesheet" href="themes/dogwalks/css/print.css" type="text/css" media="print">
    <script type="text/javascript" src="themes/dogwalks/javascript/min/modernizr.2.6.2.custom.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="themes/dogwalks/javascript/min/jquery-1.7.2.min.js"><'+'/script>')</script>
    <% require javascript('themes/dogwalks/javascript/min/script.min.js') %>
  	<% require javascript('themes/dogwalks/javascript/min/jquery.cookie.min.js') %>
    <% require javascript('//maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&region=NZ') %>
    <% require javascript('themes/dogwalks/javascript/min/userposition.min.js') %>
  	<link rel="shortcut icon" href="$ThemeDir/images/favicon.ico" />
    <link rel="apple-touch-icon" sizes="57x57" href="touch-icon-iphone-114.png" /><!-- Standard iPhone -->
    <link rel="apple-touch-icon" sizes="114x114" href="touch-icon-iphone-114.png" /><!-- Retina iPhone -->
    <link rel="apple-touch-icon" sizes="72x72" href="touch-icon-ipad-144.png" /><!-- Standard iPad -->
    <link rel="apple-touch-icon" sizes="144x144" href="touch-icon-ipad-144.png" /><!-- Retina iPad -->
  </head>
  <body class="$ClassName<% if not $Menu(2) %> no-sidebar<% end_if %>">
    <% include Header %>
    <div class="main" role="main">
    	<div class="inner typography">
    	  <% include SideBar %>
        <div class="content-container unit size3of4 lastUnit">
    		  $Layout
    	  </div>
    	</div>
    </div>
    <% include Footer %>
    <% include GoogleAnalytics %>
  </body>
</html>
