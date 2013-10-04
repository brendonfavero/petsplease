{* 6.0.7-3-gce41f93 *}
{* Displays the regions for a listing, used in the listing_regions tag for this addon. *}
{if $regions}{strip}
	{foreach from=$regions item=thisRegion name=listingRegionLoop}
		{$thisRegion}{if !$smarty.foreach.listingRegionLoop.last} &gt; {/if}
	{/foreach}
{/strip}{/if}