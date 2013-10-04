{* 6.0.7-203-g14ac3a5 *}
<div id="{$name}_state_remove_me" style="display: inline-block;">
	{if $states === false}
		<input type="text" name="{$name}[state]" class="field" />
	{else}
		<select name="{$name}[state]" id="{$name}_state_ddl" class="field">
			<option value=""></option>
			{foreach $states as $id => $s}
				<option value="{$s.abbreviation}"{if $s.selected} selected="selected"{/if}>{$s.name}</option>
			{/foreach}
		</select>
	{/if}
</div>