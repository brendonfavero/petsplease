{* 6.0.7-203-g14ac3a5 *}
<div style="display: inline-block;">
	<select name="{$name}[country]" id="{$name}_country_ddl" class="field">
		<option value=""></option>
		{foreach $countries as $id => $c}
			<option value="{$c.abbreviation}"{if $c.selected} selected="selected"{/if}>{$c.name}</option>
		{/foreach}
	</select>
	<script type="text/javascript">
	//<![CDATA[
	
		Event.observe("{$name}_country_ddl", "change", function() {
		
			//remove the state selector (if it exists)
			if($('{$name}_state_remove_me')) {
				$('{$name}_state_remove_me').remove();
			}
			
			//now make an AJAX call with the selected country to get its states and show their DDL
			
			if($('{$name}_country_ddl').value.length == 0) {
				//empty selection -- nothing else to do here
				return false;
			}
			
			
			new Ajax.Request('{if $in_admin}../{/if}AJAX.php?controller=RegionSelect&action=getChildStatesForBilling', {
				method: 'post',
				parameters: {
					country: $('{$name}_country_ddl').value,
					name: '{$name}',
				},
				onSuccess: function(ret) {
					if(ret.responseText.length > 0) {
						$('billing_state_wrapper').insert(ret.responseText);
					}
				}
			});
		});
		
	
	//]]>
	</script>
</div>