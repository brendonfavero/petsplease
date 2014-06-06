<!DOCTYPE html>
<html>
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<script>
		var _prum = [['id', '538400a6abe53dbb68ac21e4'],
		             ['mark', 'firstbyte', (new Date()).getTime()]];
		(function() {
		    var s = document.getElementsByTagName('script')[0]
		      , p = document.createElement('script');
		    p.async = 'async';
		    p.src = '//rum-static.pingdom.net/prum.min.js';
		    s.parentNode.insertBefore(p, s);
		})();
	</script>
    
	<meta name="google-site-verification" content="Z5pMM5JDfpcOkzKn-LCEM9f3KqjCdAcnZnMrF--r61w" />
	<link href="{external file='css/theme_styles.css'}" rel="stylesheet" type="text/css" />

	<link href='http://fonts.googleapis.com/css?family=BenchNine:400,700' rel='stylesheet' type='text/css'>
	<link href="{external file='css/master.css'}" rel="stylesheet" type="text/css" />
	<link href="{external file='css/home.css'}" rel="stylesheet" type="text/css" />
	{header_html}
	{if $alt_css}
		{* Allow main template to add an alternate CSS file to show *}
		<link href="{external file=$alt_css}" rel="stylesheet" type="text/css" />
	{/if}
	
	<!--  This loads the RSS feed  -->
	<link rel="alternate" type="application/rss+xml" title="Newest Listing Feed" href="rss_listings.php" />
	
	
	
	<script type="text/javascript" src="{external file='js/petsplease-extensions.js'}"></script>

	{* Load the theme_styles.css files last, so it can over-write any page/module 
		specific CSS files if desired. *}	

	{addon addon='ppAds' tag='header'}

	{* HTML5 compatibility for browsers before IE 9. *}
	<!--[if lt IE 9]>
		<script type="text/javascript" src="js/html5shiv.js"></script>
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<![endif]-->
</head>
<body>
