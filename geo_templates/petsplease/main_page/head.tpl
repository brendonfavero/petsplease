<!DOCTYPE html>
<html>
<head>
	<title>{module tag='module_title'}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<meta name="Keywords" content="KEYWORDS GO HERE" />
	<meta name="Description" content="DESCRIPTION GOES HERE" />
	
	{header_html}
	{if $alt_css}
		{* Allow main template to add an alternate CSS file to show *}
		<link href="{external file=$alt_css}" rel="stylesheet" type="text/css" />
	{/if}
	
	<!--  This loads the RSS feed  -->
	<link rel="alternate" type="application/rss+xml" title="Newest Listing Feed" href="rss_listings.php" />
	
	{* Load the theme_styles.css files last, so it can over-write any page/module 
		specific CSS files if desired. *}
	<link href="{external file='css/theme_styles.css'}" rel="stylesheet" type="text/css" />
	<link href="{external file='css/primary_theme_styles.css'}" rel="stylesheet" type="text/css" />
	<link href="{external file='css/secondary_theme_styles.css'}" rel="stylesheet" type="text/css" />
	
	<link href='http://fonts.googleapis.com/css?family=BenchNine' rel='stylesheet' type='text/css'>
	<link href="{external file='css/master.css'}" rel="stylesheet" type="text/css" />
	<link href="{external file='css/home.css'}" rel="stylesheet" type="text/css" />

	{* HTML5 compatibility for browsers before IE 9. *}
	<!--[if lt IE 9]>
		<script type="text/javascript" src="js/html5shiv.js"></script>
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<![endif]-->
</head>
<body>
