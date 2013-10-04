{* 7.1beta3-57-g679b064 *}
<ul class="info">
	{foreach $leveled_fields as $levels}
		{foreach $levels as $level}
			<li class="label">{$level.level_info.label}</li>
			<li class="value">{$level.name}</li>
		{/foreach}
	{/foreach}
</ul>