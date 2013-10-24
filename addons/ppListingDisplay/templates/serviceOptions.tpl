{foreach from=$services item=service key=i}
	<input type="checkbox" name="servicetype_check" value="{$service.value}" id="service_select_{$i}" {if $service.checked}checked="checked"{/if} />
	<label for="service_select_{$i}">{$service.value}</label><br>  
{/foreach}