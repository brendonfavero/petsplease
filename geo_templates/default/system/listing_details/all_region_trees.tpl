{* 7.1beta1-1053-g114099e *}
{foreach $region_trees as $levels}
	{foreach $levels as $region}
		{$region.name}{if !$region@last} &gt;{/if}
	{/foreach}
	{if !$levels@last}<br />{/if}
{/foreach}