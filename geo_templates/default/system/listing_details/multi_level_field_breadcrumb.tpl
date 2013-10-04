{* 7.1beta3-57-g679b064 *}
{foreach $leveled_fields as $levels}
	{strip}
		{foreach $levels as $level}
			{$level.name}
			{if !$level@last} {if $bread_seperator}{$bread_seperator}{else}&gt;{/if} {/if}
		{/foreach}
	{/strip}
	{if !$levels@last}<br />{/if}
{/foreach}