{* 6.0.7-3-gce41f93 *}
<div class="listing_extra_item">
	<label>
		{$messages.500095} {$help_link}
	</label>

	<div class="listing_extra_cost">
		<select name="c[new_pictures]" class="field">
			{foreach from=$img_dropdown item="price" key="i"}
				<option value="{$i}"{if $i eq $current} selected="selected"{/if}>
					{$i} {if $i == 1}{$messages.500437}{else}{$messages.500438}{/if} - {$price}
				</option>
			{/foreach}
		</select>
	</div>
	<div class="clr"></div>
</div>
