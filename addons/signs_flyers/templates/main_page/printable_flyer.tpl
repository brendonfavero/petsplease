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
	<div class="print_shell">
		<!-- START HEADER -->
		<div id="header">
			<img src="{external file='images/logo.jpg'}" alt="" />
			
		</div>
		<!-- END HEADER -->
		
		<div class="clr"></div>
		
		<ul id="breadcrumb">
			<li class="element highlight">Featured at: www.YourDomain.com</li>
                        <li class="element">Search for Listing ID: {$classified_id}</li>
		</ul>
		
		<h1 class="listing_title">
			{$title}
		</h1>
		
		<div id="print_photo_column">
			{$image}
		</div>
		
		<div id="print_listing_info_column">
			<ul class="info">
				<li class="label price">Price: {$price}</li>
				<li class="label">{$address}</li>
				<li class="label">{$city}, {$state} {$zip}</li>
				<li class="label">Call: {$phone_1}</li>
				<li class="label">Ask for: {$contact}</li>	
			</ul>
			
			<div style="float: right;">{$classifieds_url|cat:"?a=2&b="|cat:$classified_id|qr_code:125}</div>
			
			<div class="clr"><br /></div>
		</div>

		<div id="print_listing_info_column">
			<ul class="info">
                                {if $optional_field_1}
				<li class="label">{$optional_field_1}</li>
                                {/if}
                                {if $optional_field_2}
				<li class="label">{$optional_field_2}</li>
                                {/if}
                                {if $optional_field_3}
				<li class="label">{$optional_field_3}</li>
                                {/if}
                                {if $optional_field_4}
				<li class="label">{$optional_field_4}</li>
                                {/if}
                                {if $optional_field_5}
				<li class="label">{$optional_field_5}</li>
                                {/if}
                                {if $optional_field_6}
				<li class="label">{$optional_field_6}</li>
                                {/if}
                                {if $optional_field_7}
				<li class="label">{$optional_field_7}</li>
                                {/if}
                                {if $optional_field_8}
				<li class="label">{$optional_field_8}</li>
                                {/if}
                                {if $optional_field_9}
				<li class="label">{$optional_field_9}</li>
                                {/if}
                                {if $optional_field_10}
				<li class="label">{$optional_field_10}</li>
                                {/if}
                                 {if $optional_field_11}
				<li class="label">{$optional_field_11}</li>
                                {/if}
                                {if $optional_field_12}
				<li class="label">{$optional_field_12}</li>
                                {/if}
                                {if $optional_field_13}
				<li class="label">{$optional_field_13}</li>
                                {/if}
                                {if $optional_field_14}
				<li class="label">{$optional_field_14}</li>
                                {/if}
                                {if $optional_field_15}
				<li class="label">{$optional_field_15}</li>
                                {/if}
                                {if $optional_field_16}
				<li class="label">{$optional_field_16}</li>
                                {/if}
                                {if $optional_field_17}
				<li class="label">{$optional_field_17}</li>
                                {/if}
                                {if $optional_field_18}
				<li class="label">{$optional_field_18}</li>
                                {/if}
                                {if $optional_field_19}
				<li class="label">{$optional_field_19}</li>
                                {/if}
                                {if $optional_field_20}
				<li class="label">{$optional_field_20}</li>
                                {/if}
			</ul>
			<div class="clr"><br /></div>
		</div>
		<div class="clr"><br /></div>		

		<div id="print_description">
			<h1 class="print_title">Description</h1>
			
			<div class="box_pad main_text">
				{$description}
			</div>
		</div>

		<!-- START LEFT TABS -->
		
		<div class="print_half_column_left">
			<h1 class="print_title left">Please Take One</h1>
			
			<ul id="print_optional_fields">
			<li class="rows left"><strong>{$title}</strong> :: {$phone_1} <br>
                        www.YourDomain.com :: Listing ID: {$classified_id}</li>
			<li class="rows left"><strong>{$title}</strong> :: {$phone_1} <br>
                        www.YourDomain.com :: Listing ID: {$classified_id}</li>
			<li class="rows left"><strong>{$title}</strong> :: {$phone_1} <br>
                        www.YourDomain.com :: Listing ID: {$classified_id}</li>
			<li class="rows left"><strong>{$title}</strong> :: {$phone_1} <br>
                        www.YourDomain.com :: Listing ID: {$classified_id}</li>
			<li class="rows left"><strong>{$title}</strong> :: {$phone_1} <br>
                        www.YourDomain.com :: Listing ID: {$classified_id}</li>
			<li class="rows left"><strong>{$title}</strong> :: {$phone_1} <br>
                        www.YourDomain.com :: Listing ID: {$classified_id}</li>
			<li class="rows left"><strong>{$title}</strong> :: {$phone_1} <br>
                        www.YourDomain.com :: Listing ID: {$classified_id}</li>
			<li class="rows left"><strong>{$title}</strong> :: {$phone_1} <br>
                        www.YourDomain.com :: Listing ID: {$classified_id}</li>
			<li class="rows left"><strong>{$title}</strong> :: {$phone_1} <br>
                        www.YourDomain.com :: Listing ID: {$classified_id}</li>
			</ul>

		</div>
		<!-- END LEFT TABS -->
		
		<!-- START RIGHT TABS -->
		
		<div class="print_half_column_right">
			<h1 class="print_title right">Please Take One</h1>
			
			<ul id="print_optional_fields">
			<li class="rows right"><strong>{$title}</strong> :: {$phone_1} <br>
                        www.YourDomain.com :: Listing ID: {$classified_id}</li>
			<li class="rows right"><strong>{$title}</strong> :: {$phone_1} <br>
                        www.YourDomain.com :: Listing ID: {$classified_id}</li>
			<li class="rows right"><strong>{$title}</strong> :: {$phone_1} <br>
                        www.YourDomain.com :: Listing ID: {$classified_id}</li>
			<li class="rows right"><strong>{$title}</strong> :: {$phone_1} <br>
                        www.YourDomain.com :: Listing ID: {$classified_id}</li>
			<li class="rows right"><strong>{$title}</strong> :: {$phone_1} <br>
                        www.YourDomain.com :: Listing ID: {$classified_id}</li>
			<li class="rows right"><strong>{$title}</strong> :: {$phone_1} <br>
                        www.YourDomain.com :: Listing ID: {$classified_id}</li>
			<li class="rows right"><strong>{$title}</strong> :: {$phone_1} <br>
                        www.YourDomain.com :: Listing ID: {$classified_id}</li>
			<li class="rows right"><strong>{$title}</strong> :: {$phone_1} <br>
                        www.YourDomain.com :: Listing ID: {$classified_id}</li>
			<li class="rows right"><strong>{$title}</strong> :: {$phone_1} <br>
                        www.YourDomain.com :: Listing ID: {$classified_id}</li>

			</ul>

		</div>
		
		<!-- END LEFT TABS -->
		
		<div class="clr"></div>
	</div>
</body>
</html>
