{strip}
	{foreach from=$groups item=group key=i}
		<h4>{$group.value}</h4>
		<div class="clearfix" style="width:100%">
			<ul class="extra_multiselect" data-groupvalue="{$group.value}">
				{foreach from=$group.values item=option key=j}
					<li>
						<input type="checkbox" name="{$listingfield}_check" value="{$option.value}" id="{$listingfield}_select_{$i}_{$j}" {if $option.checked}checked="checked"{/if} />
						<label for="{$listingfield}_select_{$i}_{$j}">{$option.value}</label><br>
					</li>  
				{/foreach}
			</ul>
		</div>
	{/foreach}
{/strip}
