{* 6.0.7-3-gce41f93 *}
{* This is meant to be a temporary location for this file, there is a high chance
	that the location and maybe even filename, may change in a future update. *}
<ul>
	{foreach from=$choices item=choice}
		<li class="{cycle values='row_odd,row_even'}">{$choice.label}{if $choice.extra}<span class="informal">{$choice.extra}</span>{/if}</li>
	{/foreach} 
</ul>