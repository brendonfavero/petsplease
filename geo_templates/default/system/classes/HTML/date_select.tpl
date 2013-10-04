{* 6.0.7-3-gce41f93 *}
<span style="white-space: nowrap;">

{$labels.month} <select {if $isEnd}id="endtime_month"{/if} name="{$names.month}" class="field">
{section name=month start=1 loop=13}
	<option value="{$smarty.section.month.index}"{if $smarty.section.month.index == $values.month} selected="selected"{/if}>{$smarty.section.month.index}</option>
{/section}
</select>

{$labels.day} <select {if $isEnd}id="endtime_day"{/if} name="{$names.day}" class="field">
{section name=day start=1 loop=32}
	<option value="{$smarty.section.day.index}"{if $smarty.section.day.index == $values.day} selected="selected"{/if}>{$smarty.section.day.index}</option>
{/section}
</select>

{$labels.year} <select {if $isEnd}id="endtime_year"{/if} name="{$names.year}" class="field">
{* $years is the current year plus the next two *} 
{foreach from=$years item=year}
	<option value="{$year}"{if $year == $values.year} selected="selected"{/if}>{$year}</option>
{/foreach}
</select>


{* separate the date and time with a bunch of non-breaking spaces *}
{section name=spacer start=0 loop=11}&nbsp;{/section}


{$labels.hour} <select {if $isEnd}id="endtime_hour"{/if} name="{$names.hour}" class="field">
{section name=hour start=0 loop=24}
	<option value="{$smarty.section.hour.index}"{if $smarty.section.hour.index == $values.hour} selected="selected"{/if}>{$smarty.section.hour.index|string_format:"%02d"}</option>
{/section}
</select>

{$labels.minute} <select {if $isEnd}id="endtime_minute"{/if} name="{$names.minute}" class="field">
{section name=minute start=0 loop=60}
	<option value="{$smarty.section.minute.index}"{if $smarty.section.minute.index == $values.minute} selected="selected"{/if}>{$smarty.section.minute.index}</option>
{/section}
</select>

</span>