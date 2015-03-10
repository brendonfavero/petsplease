{* 7.1.3-12-g5230dd6 *}
<!DOCTYPE HTML>
<html>
	<head>
		<title>Geodesic Solutions Software Admin</title>
		{if $charset}<me{* 
				A spacer to get eclipse to stop 
				trying to open the file with "unknown charset
				of {$charset}"  *}ta http-equiv="Content-Type" content="text/html; charset={$charset}" />{/if}
		
		{header_html}
		
		<
<script type="text/javascript" src="/geo_templates/default/external/js/gjmain.js" ></script>

<script type="text/javascript" src="/geo_templates/default/external/js/main.js" ></script>
<script type="text/javascript" src="/geo_templates/default/external/js/plugins/utility.js" ></script>
script type="text/javascript" src="/geo_templates/default/external/js/plugins/lightbox.js" ></script>
<script type="text/javascript" src="/geo_templates/default/external/js/plugins/simpleCarousel.js" ></script>

		<script type="text/javascript">
			jQuery(document).ready(function () {
				gjUtil.inAdmin = true;
				gjUtil.ready();
			});
			//Wait for entire page to be done for this stuff to load
			jQuery(window).load(gjUtil.load);
			
			//Load the 'old' prototype-based stuff
			Event.observe(window, 'load', geoUtil.init);
		</script>
	</head>
	<body{if $body_tag_html} $body_tag_html{/if}>
		<!-- div container for tooltips, style needs to be inline -->
		<div id="tiplayer" style="visibility:hidden;position:absolute;z-index:100000;top:-100;"></div>
		{$developer_mode}
		<div class="bodyWrapper">
			<div id="header">
				<div id="header-inside">
					<div id="logo" class="geoCoreLogo">
						<h1>
							<a href="index.php?page=home">
								{$product_typeDisplay}{if $is_beta} BETA{/if}
								<br /><span>{if $license_only}{$license_only|capitalize}{else}Auctions / Classifieds{/if} Management Software</span>
							</a>
						</h1>
					</div>
					<p id="geodesic">Logged in as: {$admin_username}{if $admin_userid != 1} (#{$admin_userid}){/if}<span>Software by <a href="http://geodesicsolutions.com">Geodesic Solutions, LLC</a></span></p>
					<!-- <ul class="topAdminTabs leftTabs">
					</ul> -->
					<ul class="topAdminTabs">
						<li><a href="index.php?page=home">Admin Home</a></li>
						<li><a href="{$classifieds_url}" onclick="window.open(this.href); return false;">My Site</a></li>
						<li><a href="http://geodesicsolutions.com/support/geocore-wiki/" onclick="window.open(this.href); return false;">Manual</a></li>
						<li><a href="index.php?page=site_map">Admin Map</a></li>
						<li><a href="index.php?page=quick_find" class="lightUpLink">Quick Find</a></li>
						<li><a href="javascript:void(0)" onclick="logout(this);">Logout</a></li>
					</ul>
				</div>
			</div>
		
			{if !$hide_side_menu}
				<div class="side_menu" id="sideMenu">
					{include file="side_menu/index"}
				</div>
			{/if}
			<div class="body_html" id="bodyHtml"{if $hide_side_menu} style="left: 0px;"{/if}>
				<div id="footerContainer">
					<div id="footer-top">
						<p>{$product_typeDisplay} DB Ver. {$product_version}
							 [ <a href="http://geodesicsolutions.com/changelog/" onclick="window.open(this.href); return false;">Changelog</a> ] </p>
					</div>
					<div id="footer">
						<div id="footer-inside">
							<p>Copyright &copy;2001-2013 <a href="http://geodesicsolutions.com">Geodesic Solutions, LLC</a></p>
						</div>
					</div>
				</div>

				{if !$hide_title}
					<div style='clear:both'></div>
					<div class='topIcons'>
						<div class="breadcrumb_title">
							<div>{$breadcrumb_title}</div>
						</div>
						<div class="page_image">
							<img src="{$image}" alt="icon" />
							<div style='clear:both'></div>
							<a class="page_help_button" href="http://geodesicsolutions.com/support/geocore-wiki/doku.php/id,admin_menu;{$wiki_uri}" onclick="window.open(this.href); return false;">
								<img src="admin_images/page_help.gif" title="Help for this page" alt="Help for this page" />
							</a>
						</div>
					</div>
								
				{/if}
				
				{include file='lease_payment_due.tpl'}
				
				{if !$hide_notifications}
					<div id="notifications-box">
						{include file="notifications"}
					</div>
				{/if}
				{body_html}
			</div>
		</div>
	</body>
</html>
