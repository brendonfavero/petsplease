<!DOCTYPE HTML>
<html>
<head>
	<title>{$title}</title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="Keywords" content="KEYWORDS GO HERE" />
	<meta name="Description" content="DESCRIPTION GOES HERE" />
	
	{header_html}
	
	<link href="{external file='css/signs_flyers.css'}" rel="stylesheet" type="text/css" />
</head>
<body class="print_body">
	<div class="print_shell_sign">
		<!-- START HEADER -->
		<div class="for_sale">
			FOR SALE
		</div>

		<!-- END HEADER -->
		
		<div class="clr"></div>
		
		<ul id="breadcrumb">
			<li class="element highlight">Featured at: www.YourDomain.com</li>
                        <li class="element">Search for Listing ID: {$classified_id}</li>
		</ul>
		
		<h1 class="listing_title_sign">
			{$title}
		</h1>
		
		<div id="print_photo_column">
			{$image}
		</div>
		
		<div id="print_listing_info_column">
			<ul class="info">
				<li class="contact">Price: {$price}<br>
                                Call: {$phone_1}<br>
				Ask for: {$contact}</li>
			</ul>

			<div class="clr"><br /></div>
		</div>
		<div id="print_listing_info_column">
                <h1 class="print_title left">Description</h1>
                <div class="box_pad main_text">{$description}</div>
		</div>
		<div class="clr"><br /></div>		
	</div>
</body>
</html>
