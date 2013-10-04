{* 7.0.1-45-g8242a29 *}

{if !$skipUl}<ul id="breadcrumb" class="geographic_navigation_breadcrumb">{/if}
	<li class="element highlight">{$msgs.currentRegion}</li>
	{if $msgs.allRegions}<li class="element"><a href="{$base_url}region=0">{$msgs.allRegions}</a></li>{/if}
	
	{foreach from=$breadcrumb item=region name=regionTree}
		<li class="element{if $smarty.foreach.regionTree.last} active{/if}{if $region.onlyRegionOnLevel} onlyRegionOnLevel{/if}">
			{if not $smarty.foreach.regionTree.last}<a href="{$region.link}">{/if}
				{$region.label}
			{if not $smarty.foreach.regionTree.last}</a>{/if}
		</li>
	{/foreach}
{if !$skipUl}</ul>
<div class="clear"></div>{/if}