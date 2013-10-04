{* 7.1beta1-1053-g114099e *}
{foreach $region_trees as $region_order => $levels}
	{if $region_order < 2}{continue}{/if}
	{foreach $levels as $region}
		{$region.name}{if !$region@last} &gt;{/if}
	{/foreach}
	{if !$levels@last}<br />{/if}
{/foreach}