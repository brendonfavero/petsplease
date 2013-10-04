{* 7.2.4-2-gf24202b *}
{if $regionsLabel!==false}
	<label class="field_label{if $required} required{/if}">{$regionsLabel}{if $required} *{/if}</label>
{/if}
<div class="region_selector_wrapper_{$fieldName_class}" style="display: inline-block;">
	
	{include file='Region/ajax_region_select.tpl'}
		
</div>
<div class="region_selector_placeholders_{$fieldName_class}{if $buildDown}_multiline{/if}"{if !$buildDown} style="display: inline-block;"{/if}>
	{foreach $fakeLevels as $fake}
		<div class="region_selector region_fake_{$fake.id}_{$fieldName_class}" style="display: none;">
			{if $fake.use_label}<label class="field_label region_label">{$fake.label}</label>{/if}<br />
			<select name="fake_region" class="field" disabled="disabled"><option value=""></option></select>
		</div>
		
		{* only show the fake box if its direct parent either doesn't exist or exists and has no value *}
		<script type="text/javascript">
			jQuery(document).ready(function () {
				var parentReal = jQuery('.region_level_{$fake.id - 1}_{$fieldName_class}');
				if (parentReal.length == 0 || parentReal.val() == "") {
					jQuery('.region_fake_{$fake.id}_{$fieldName_class}').show();
				}
			});
		</script>
		{if $buildDown}<br />{/if}
	{/foreach}
</div>