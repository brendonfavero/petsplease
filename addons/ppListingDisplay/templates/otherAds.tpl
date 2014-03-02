<style>

	.sellerAd {
		float:left; 
		padding-right:25px;
		width:30%;
	}
		
	.sellerAd img {
		max-width:175px; 
		max-height:125px; 
		min-height:105px;
	}
		
	.sellerAd a {
		font-size:14px; 
		font-weight:bold;
	}
		
	.sellerAd span {
		font-size:12px;
		font-weight:bold;
		color:#4174a6;
	}
	
	.sellerAd.last {
		position:absolute;
		right: 0;
		padding-right:0px;
	}
</style>

{if $listings}
	<p style="font-weight:bold; font-size:16px">Sellers Other Listings:
	<div style="display:inline-block; width:100%">
	{foreach from=$listings item=listing key=i}
		<div  class="sellerAd {if $i eq 2}last{/if}">				
			<img src="{$listing.thumb_url}"/>
			<br/>				
			<a href="?a=2&b={$listing.id}">{$listing.title|urldecode}</a>
			<br/>
			<span>{$listing.price}</span>
		</div>
	{/foreach}
	</div>
{/if}
