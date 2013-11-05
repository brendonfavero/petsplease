{strip}
	<ul class="extra_multi">
		{foreach from=$values item=value key=i}
			<li {if $value@last}class="last"{/if}>{$value}</li>
		{/foreach}
	</ul>
{/strip}