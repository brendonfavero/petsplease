{* c7c54e8 *}
<ul class="button_list">
{foreach from=$methods item=fancy_name key=internal_name}
	<li><input type="button" class="button" onclick="getOptionsForMethod('{$internal_name}');" value="{$fancy_name}" /></li>
{/foreach}
</ul>