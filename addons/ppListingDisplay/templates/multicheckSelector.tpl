{strip}
	<ul class="extra_multiselect">
		{foreach from=$options item=option key=i}
			<li>
				<input type="checkbox" name="{$listingfield}_check" value="{$option.value}" id="{$listingfield}_select_{$i}" {if $option.checked}checked="checked"{/if} />
				<label for="{$listingfield}_select_{$i}">{$option.value}</label><br>
			</li>  
		{/foreach}
	</ul>
{/strip}
