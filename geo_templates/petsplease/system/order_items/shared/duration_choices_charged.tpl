{* 6.0.7-3-gce41f93 *}
{foreach from=$durations item=d}
<option value="{$d.numerical_length}" {if $d.selected}selected="selected"{/if}>{$d.display_length} - {$d.display_amount}</option>
{/foreach}