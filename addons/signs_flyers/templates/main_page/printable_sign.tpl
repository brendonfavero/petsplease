<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
				{if $price}<li class="contact">Price: {$price}</li>{/if}
				{if $phone_1}<li class="contact">Call: {$phone_1}</li>{/if}
				{if $address}<li class="contact">{$address}</li>{/if}
				{if $city or $state or $zip}	
					<li class="contact">{$city}{if $city and $state},{/if} {$state} {$zip}</li>
				{/if}		
				{if $contact}<li class="contact">Ask for: {$contact}</li>{/if}
			</ul>
			
			<div style="float: right;">{$classifieds_url|cat:"?a=2&b="|cat:$classified_id|qr_code:150}</div>

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
