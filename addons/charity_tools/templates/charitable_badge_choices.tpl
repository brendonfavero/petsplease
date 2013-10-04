{* 7.2.4-13-gbf3c85e *}

<div class="listing_extra_item">
	{if !$allFree}<div class="listing_extra_cost price">{$price}</div>{/if}
	
	<input type="hidden" name="c[charitable_badge_toggle]" value="0" />
	<input id="charitable_badge_main_toggle" onclick='if(this.checked) jQuery(".charitable_badge_option").prop("disabled",false); else jQuery(".charitable_badge_option").prop("disabled",true);' type="checkbox" name="c[charitable_badge_toggle]" value="1" {if $toggle}checked="checked"{/if} />
	<label for="charitable_badge_main_toggle">{$toggleLabel}</label>

	{if $error}<br /><span class="error_message">{$error}</span>{/if}
</div>

<div class="listing_extra_item_child">
	<ul id="charitable_badges">
		{foreach $badges as $id => $b}
			<li>
				<input type="radio" class="charitable_badge_option" id="charitable_badge{$id}" name="c[charitable_badge_choice]" onchange="if(this.checked)jQuery('#charitable_badge_main_toggle').checked=true;" value="{$id}" {if $choice == $id}checked="checked"{/if} />
				<label for="charitable_badge{$id}"><img src="{$b.image}" alt="" /></label>
			</li>
		{/foreach}
	</ul>
	<div class="clr"></div>
</div>

<script type="text/javascript">
	if(jQuery('#charitable_badge_main_toggle').checked) {
		jQuery(".charitable_badge_option").prop('disabled',false);
	} else {
		jQuery(".charitable_badge_option").prop('disabled',true);
	}
</script>