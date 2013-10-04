{* 7.1.3-12-g5230dd6 *}
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="robots" content="NONE" />
	<title>
		{if $on_license_page}
			License Details
		{else}
			Admin Login
		{/if}
	</title>
	<link rel="stylesheet" href="css/login.css" type="text/css" />
	<script type="text/javascript" src="../js/jquery.min.js"></script>
	<script type='text/javascript' src='../js/prototype.js'></script>
	<script type="text/javascript" src="../{external file='js/main.js' forceDefault=1}"></script>
	<script type="text/javascript" src="../{external file='js/gjmain.js' forceDefault=1}"></script>
	<script type="text/javascript" src="../{external file='js/plugins/simpleCarousel.js' forceDefault=1}"></script>
	<script type="text/javascript" src="../{external file='js/plugins/lightbox.js' forceDefault=1}"></script>
	<script type="text/javascript" src="../{external file='js/plugins/utility.js' forceDefault=1}"></script>
	
	{literal}
	<style type="text/css">
		#login_sub {
			{/literal}
			{* in-line bg image required, since image URL will be different
				depending on the product, so can't load in static CSS file *}
			
			background-image: url({$login_logo});
			{literal}
		}
	</style>
	<script type="text/javascript">
		//<![CDATA[

		var initLogin = function () {
			/* check for a cookie */
			if (document.cookie == "") {
				/* if a cookie is not found - alert user -
				 change cookieexists field value to false */
				alert("COOKIES need to be enabled!");
				
				/* If the user has Cookies disabled an alert will let him know 
				  that cookies need to be enabled to log on.*/ 
				
				$('cookieexists').value = "false"  
			} else {
				/* this sets the value to true and nothing else will happen,
				   the user will be able to log on*/
				$('cookieexists').value = "true"
			}
			//if the admin user field exists, focus on it.
			focusAdminUser('admin_username');
			//or focus on license field if that exists
			focusAdminUser('license_key_field');

			//move whole login box to middle of page
			geoEffect.moveToMiddle('outerBox');
			$('outerBox').show();
		}

		/* Set a cookie to be sure that one exists.
		   Note that this is outside the function*/
		document.cookie = 'killme' + escape('nothing')
		
		var focusAdminUser = function (id_name) {
			if ($(id_name)) {
				$(id_name).focus();
			}
		}
		//run initLogin() when page loads.
		Event.observe(window, 'load', initLogin);
		{/literal}
		//]]>
	</script>
</head>
<body>
	<div id="outerBox">
		<form action="index.php" method="post" id="login_form">
			{if $error}<div class="login_error">{$error}</div>{/if}
			{if $license_error}<div class="login_error">{$license_error}</div>{/if}
			{if $cookie_error}<div class="login_error">{$cookie_error}</div>{/if}
			<div id="login_box">
				<div id="login_sub">
					<div id="login_left">
						<div id="login_left_list">{$version}</div>
						<ul>
							<li><a href="http://geodesicsolutions.com/wiki/" onclick="window.open(this.href); return false;">User Manual</a></li>
							<li><a href="http://geodesicsolutions.com/geo_user_forum/index.php" onclick="window.open(this.href); return false;">User Forum</a></li>
							<li><a href="https://geodesicsolutions.com/geo_store/customers" onclick="window.open(this.href); return false;">Client Area</a></li>
							<li><a href="http://geodesicsolutions.com/resources.html" onclick="window.open(this.href); return false;">Resources</a></li>
						</ul>
					</div>
					<div id="login_right">
						<h1 id="login_product_name">{$product_name}</h1>
						<h2 id="login_software_type">{$software_type}</h2>
						<div id="login_form_fields">
							{if $on_license_page}
								{$username_field}{$password_field}
								<div id="login_username_block">{$license_label}<br />{$license_field}</div>
							{else}
								<div id="login_username_block">{$username_label}<br />{$username_field}</div>
					
								<div id="login_password_block">{$password_label}<br />{$password_field}</div>
							{/if}
						</div>
						{if $on_license_page && $must_agree}
							{$must_agree}
						{/if}
						<div id="submit_button">
							<input type="hidden" id="cookieexists" name="cookieexists" value="false" />
							<input type="submit" value="{$submit}" class="theButton" />
						</div>
						
						{if $on_license_page}
							<div id="license_tip">
								Tip: Your License Key can be retrieved by logging into your <a class="login_link" href="https://geodesicsolutions.com/geo_store/customers" onclick="window.open(this.href); return false;">Client Area</a>
							</div>
							
							<div id="license_help">
								<strong>Key not working?</strong> Copy/Paste the data located in the box below and send in an email to <a class="login_link" href="mailto:sales@geodesicsolutions.com">sales@geodesicsolutions.com</a>
							</div>
							
							<div id="license_data">
								Install Domain: {$install_domain_name}<br />
								Install Folder: {$install_folder}
							</div>
						{/if}
						<div id="login_copyright">Copyright 2001-2013. <a class="login_link" href="http://geodesicsolutions.com" onclick="window.open(this.href); return false;">Geodesic Solutions, LLC.</a><br />All Rights Reserved.</div>
					</div>
					<div id="login_bottom"></div>
				</div>
			</div>
		</form>
	</div>
</body>
</html>
