{* 7.0.2-44-g394ea28 *}

{$admin_msgs}

<script type="text/javascript">
{literal}
var setClearType = function (type) {
	$('clearType').setValue(type);
}
{/literal}
</script>

<p class="page_note">
	<strong>License Type Info:</strong>  Information provided below is only meant as
	a brief summary, it is not a legal contract or binding in any way.  See the
	user agreement and/or license agreement for specific details.
</p>

<fieldset>
	<legend>License Info</legend>
	<div>
		<form action='index.php?mc=admin_tools_settings&page=admin_tools_license' method='post'>
			<input id="clearType" type="hidden" name="clearType" value="data" />
			{if $show_upgrade_pricing}
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">
						Upgrade Pricing
					</div>
					<div class="rightColumn">
						<a href="http://geodesicsolutions.com/software-services/61-software-support-update-service/262-software-upgrades.html" class="mini_button">View</a>	
					</div>
					<div class="clearColumn"></div>
				</div>
			{/if}
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					License Key
				</div>
				<div class="rightColumn">
					{$licenseKey} <input type="submit" class="mini_cancel" name="auto_save" value="Clear Key" onclick="setClearType('key'); return confirm('Are you sure you want to clear your license?');" />
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					License Key Expires
				</div>
				<div class="rightColumn">
					{$licenseExp}
					{if $leased}
						(Or until Lease is Canceled)
					{/if}
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Licensed Product
				</div>
				<div class="rightColumn">
					{$product_typeDisplay}
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					License Type Info
				</div>
				<div class="rightColumn">
					{if $demo}
						<strong>DEMO</strong>
					{elseif $trial}
						<strong>Trial License</strong>:<br />
						- All PHP files fully encoded using Ioncube<br />
						- License typically expires after 14 days<br />
						- Trial Notice added to top and bottom<br />
						- "Powered by Geodesic Solutions" not removable
					{elseif $leased}
						<strong>Leased License{if !$force_powered_by} (branding removed){/if}</strong>:<br />
						- All PHP files fully encoded using Ioncube<br />
						- Includes ALL paid addons created by Geodesic Solutions LLC that are normally sold separately<br />
						- Support and Software Updates included for duration the software is leased<br />
						- License expires only if/when lease is canceled or payments lapse<br />
						- "Powered by Geodesic Solutions" {if $force_powered_by}removable for additional monthly fee{else}is removable via setting{/if}
					{else}
						<strong>Purchased License</strong>:<br />
						- PHP Files fully "open source" and editable (except files relating to software licensing)<br />
						- Some Addons may cost extra, as noted on product details on geodesicsolutions.com.<br />
						- Includes 6 months of download access included (extension can be purchased after that time)<br />
						- Includes 3 months of Premium Support (extension can be purchased after that time)<br />
						- License never expires<br />
						- "Powered by Geodesic Solutions" is removable via setting
					{/if}
				</div>
				<div class="clearColumn"></div>
			</div>
			<br /><br />
			<div class="col_hdr">
				Local License Data
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Local data expires
				</div>
				<div class="rightColumn">
					{$localExpire}
					{if $leased}
						<br />
						(License key will automatically revalidate after this time)
					{/if}
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Product Type
				</div>
				<div class="rightColumn">
					{$product_typeDisplay}
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="center">
				<br /><br />
				<input type="submit" name="auto_save" value="Refresh License Data" class="mini_button" onclick="setClearType('data'); return true;" />
			</div>
		</form>
	</div>
</fieldset>
