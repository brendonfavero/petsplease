{if $listings}
	<p style="font-weight:bold; font-size:16px">Sellers Other Ads:
	<div style="display:inline-block">
	{foreach $listings as $listing}
		<div style="float:left; padding-right:50px" class="sellerAd" width="33%">				
			<img style="max-width:175px; max-height:125px; min-height:105px" src="{$listing.thumb_url}"/>
			<br/>				
			<a style="font-size:14px; font-weight:bold" href="?a=2&b={$listing.id}">{$listing.title|urldecode}</a>
			<br/>
			<span style="font-size:12px;font-weight:bold;color:#4174a6">{$listing.price}</span>
		</div>
	{/foreach}
	</div>
{/if}
