{* 6.0.7-3-gce41f93 *}
{foreach from=$durations item=d}
<input type="hidden" id="classified_length" name="b[classified_length]" value="{$d.numerical_length}"/>{$d.display_length}
{/foreach}