{strip}
	{foreach from=$groups item=values key=key}
		<div style="width: 100%">
			<p class="field_value_sub_header">{$key}</p>
			<ul class="extra_multi clearfix">
				{foreach from=$values item=value key=i}
					<li {if $value@last}class="last"{/if}>{$value}</li>
				{/foreach}
			</ul>
		</div>
	{/foreach}
{/strip}