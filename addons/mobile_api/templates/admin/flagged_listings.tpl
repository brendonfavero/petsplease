{* 7.3beta5-48-g093a60a *}
{$adminMessages}

<p class="page_note">As required by Apple's App Store Review Guidelines, app users can "flag" listings as containing inappropriate or objectionable content. 
The most recently flagged listings are shown here for you to review. Click on the listing ID number for full listing details -- from there you can decide whether to take administrative action.</p> 

<fieldset>
	<legend>Flagged Listings</legend>
	<div>
		<table>
			<tr>
				<th class="center">Listing ID</th>
				<th class="center">Number of times listing has been flagged</th>
				<th class="center">Time of most recent flag</th>
			</tr>			
			{foreach $flags as $listing}
				<tr class='{cycle values="row_color1,row_color2"}'>
					<td class="center"><a href="index.php?mc=users&page=users_view_ad&b={$listing.listing_id}">{$listing.listing_id}</td>
					<td class="center">{$listing.numFlags}</td>
					<td class="center">{$listing.lastFlagTime}</td>
				</tr>
			{/foreach}
		</table>
	</div>
</fieldset>